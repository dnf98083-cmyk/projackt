<?php
require_once __DIR__ . '/../inc/db.php';

$SRC_ROOT = __DIR__ . '/../img/organized';
$DEST_ROOT = __DIR__ . '/../img/organized_ascii';
$ALLOWED_EXT = ['png','jpg','jpeg','gif','webp'];

// 카테고리 한글 -> ASCII 슬러그 매핑
$slug_map = [
    '한식' => 'korean',
    '양식' => 'western',
    '중식' => 'chinese',
    '일식' => 'japanese',
    '건강식' => 'health',
    '기타' => 'other'
];

if (!is_dir($SRC_ROOT)) {
    echo "원본 organized 폴더가 존재하지 않습니다: $SRC_ROOT\n";
    exit(1);
}

if (!is_dir($DEST_ROOT)) {
    if (!mkdir($DEST_ROOT, 0755, true)) {
        echo "대상 폴더 생성 실패: $DEST_ROOT\n";
        exit(1);
    }
}

$dirs = scandir($SRC_ROOT);
$pdo = db_get_pdo();
$total = 0;
$updated = 0;
$errors = 0;

foreach ($dirs as $d) {
    if ($d === '.' || $d === '..') continue;
    $catPath = $SRC_ROOT . '/' . $d;
    if (!is_dir($catPath)) continue;

    $korean_cat = $d;
    $slug = $slug_map[$korean_cat] ?? null;
    if (!$slug) {
        // 미지정 카테고리는 'other'로
        $slug = 'other';
    }

    $targetDir = $DEST_ROOT . '/' . $slug;
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $files = scandir($catPath);
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') continue;
        $full = $catPath . '/' . $f;
        if (!is_file($full)) continue;

        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (!in_array($ext, $ALLOWED_EXT)) continue;

        // MK- 파일만 처리 (안전)
        if (strpos($f, 'MK-') !== 0) continue;

        $newPathRel = 'img/organized_ascii/' . $slug . '/' . $f;
        $newFull = $targetDir . '/' . $f;

        // 이동(덮어쓰기)
        if (!@rename($full, $newFull)) {
            // fallback to copy+unlink
            if (!copy($full, $newFull)) {
                echo "파일 이동 실패: $full -> $newFull\n";
                $errors++;
                continue;
            } else {
                unlink($full);
            }
        }

        $total++;

        // DB 업데이트: content_img -> newPathRel (ASCII 경로), category_large -> korean category
        try {
            $stmt = $pdo->prepare("UPDATE contents SET content_img = ?, category_large = ? WHERE content_code = ?");
            $code = pathinfo($f, PATHINFO_FILENAME);
            $res = $stmt->execute([$newPathRel, $korean_cat, $code]);
            if ($res) $updated++; else $errors++;
        } catch (Exception $e) {
            echo "DB 업데이트 실패: " . $e->getMessage() . "\n";
            $errors++;
        }
    }
}

echo "총 처리 파일: $total\n";
echo "DB 업데이트 성공: $updated\n";
echo "오류: $errors\n";

?>