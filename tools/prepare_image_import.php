<?php
// 안전 모드: DB에 접속하거나 실행하지 않습니다.
// 사용법: PHP CLI로 실행하여 'import_images.sql' 파일을 생성합니다.
// 기본값: 이미지는 복사하지 않음. 복사하려면 $DO_COPY = true로 변경하세요.

$SRC_ROOT = __DIR__ . '/../img/밀키트 사진 캡쳐본';
$DEST_ROOT = __DIR__ . '/../img/organized';
$SQL_FILE = __DIR__ . '/../import_images.sql';

$ALLOWED_EXT = ['png','jpg','jpeg','gif','webp'];

// 안전 옵션
$DO_COPY = true; // true로 하면 이미지들을 img/organized/<카테고리>/ 으로 복사합니다.
$OVERWRITE_FILES = true; // 복사 시 기존 파일 덮어쓰기 허용 여부

// 기본 DB 필드 값 (수동 변경 가능)
$DEFAULT_DELIV = 'N';
$DEFAULT_PRICE = 0;
$DEFAULT_COST = 0;
$DEFAULT_DISCOUNT = 0;

// --- 실행 ---
if (!is_dir($SRC_ROOT)) {
    echo "원본 이미지 폴더가 존재하지 않습니다: $SRC_ROOT\n";
    exit(1);
}

// 초기화
$sql_lines = [];
$counter = 1;

$dirs = scandir($SRC_ROOT);
foreach ($dirs as $d) {
    if ($d === '.' || $d === '..') continue;
    $catPath = $SRC_ROOT . '/' . $d;
    if (!is_dir($catPath)) continue;

    $category = $d; // 폴더명을 그대로 카테고리로 사용

    // 대상 폴더(복사 옵션 사용 시)
    $targetDir = $DEST_ROOT . '/' . $category;
    if ($DO_COPY && !is_dir($targetDir)) {
        if (!mkdir($targetDir, 0755, true)) {
            echo "대상 폴더 생성 실패: $targetDir\n";
            continue;
        }
    }

    $files = scandir($catPath);
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') continue;
        $full = $catPath . '/' . $f;
        if (!is_file($full)) continue;

        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (!in_array($ext, $ALLOWED_EXT)) continue;

        // 파일명에서 확장자 제거, 정리
        $name_raw = pathinfo($f, PATHINFO_FILENAME);
        $name = trim(preg_replace('/[_\-]+/', ' ', $name_raw));
        $name = preg_replace('/\s+/', ' ', $name);
        if ($name === '') $name = '무제';

        // content_code 생성 (MK-YYYYMMDD-XXXX)
        $code = sprintf('MK-%s-%04d', date('Ymd'), $counter);
        $counter++;

        // 이미지 경로: 복사하지 않으면 원본 경로 사용 (웹에서 접근 가능한 상대경로로 설정)
        if ($DO_COPY) {
            // 복사 시 파일명을 content_code 기반으로 통일하여 충돌과 검색을 쉽게 함
            $destFilename = $code . '.' . $ext;
            $destFile = $targetDir . '/' . $destFilename;
            $relPath = 'img/organized/' . $category . '/' . $destFilename;

            if (file_exists($destFile) && !$OVERWRITE_FILES) {
                // 덮어쓰기를 허용하지 않으면 타임스탬프를 붙임
                $destFilename = $code . '-' . time() . '.' . $ext;
                $destFile = $targetDir . '/' . $destFilename;
                $relPath = 'img/organized/' . $category . '/' . $destFilename;
            }

            if (!file_exists($destFile) || $OVERWRITE_FILES) {
                if (!copy($full, $destFile)) {
                    echo "복사 실패: $full -> $destFile\n";
                    continue;
                }
            }
        } else {
            // 원본 위치를 가리키는 상대 경로
            $relPath = 'img/밀키트 사진 캡쳐본/' . $category . '/' . $f;
        }

        // SQL 문자열 생성 (single-quote escape)
        $name_sql = str_replace("'", "\\'", $name);
        $img_sql = str_replace("'", "\\'", $relPath);
        $category_sql = str_replace("'", "\\'", $category);

        $sql = "INSERT INTO `contents` (`content_code`,`content_img`,`deliv_today`,`content_name`,`discount_rate`,`content_cost`,`content_price`,`category_large`) VALUES ('" .
               $code . "','" . $img_sql . "','" . $DEFAULT_DELIV . "','" . $name_sql . "'," .
               intval($DEFAULT_DISCOUNT) . "," . intval($DEFAULT_COST) . "," . intval($DEFAULT_PRICE) . ",'" . $category_sql . "');";

        $sql_lines[] = $sql;
    }
}

// 쓰기 전에 백업(있으면) - 기존 SQL 파일이 있으면 백업
if (file_exists($SQL_FILE)) {
    $bak = $SQL_FILE . '.bak.' . date('YmdHis');
    if (!copy($SQL_FILE, $bak)) {
        echo "기존 SQL 파일 백업 실패: $SQL_FILE -> $bak\n";
    } else {
        echo "기존 SQL 파일 백업: $bak\n";
    }
}

$content = "-- import_images.sql generated on " . date('c') . "\n";
$content .= "-- 안전 모드: 이 파일을 검토한 후 DB에 적용하세요.\n\n";
$content .= implode("\n", $sql_lines) . "\n";

if (file_put_contents($SQL_FILE, $content) === false) {
    echo "SQL 파일 생성 실패: $SQL_FILE\n";
    exit(1);
}

// 요약 출력
echo "완료: SQL 파일 생성됨 -> $SQL_FILE\n";
echo "생성된 INSERT 문 개수: " . count($sql_lines) . "\n";
echo "이미지 복사 옵션 (DO_COPY): " . ($DO_COPY ? 'ON' : 'OFF') . "\n";
echo "다음 단계:\n";
echo " - 'import_images.sql' 파일을 열어 내용 검토\n";
echo " - DB에 적용하려면 백업 후 MySQL 클라이언트(phpMyAdmin 또는 mysql CLI)에서 실행\n";
echo " - 원하면 제가 SQL 적용(직접 DB 연결) 전에 검토해드릴게요.\n";

exit(0);

?>
