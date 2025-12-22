<?php
require_once __DIR__ . '/../inc/db.php';

$host = 'localhost';
$user = 'root';
$pass = '';
$name = 'exmall';
$tables = '*';

// DB 연결 정보 가져오기 (db.php에서 파싱하거나 직접 설정)
// 여기서는 XAMPP 기본값 가정

$link = mysqli_connect($host, $user, $pass, $name);
if (!$link) {
    die("DB 연결 실패: " . mysqli_connect_error());
}

mysqli_query($link, "SET NAMES 'utf8'");

// 테이블 목록 가져오기
if($tables == '*') {
    $tables = array();
    $result = mysqli_query($link, 'SHOW TABLES');
    while($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }
} else {
    $tables = is_array($tables) ? $tables : explode(',',$tables);
}

$return = '';

// 각 테이블 순회
foreach($tables as $table) {
    $result = mysqli_query($link, 'SELECT * FROM '.$table);
    $num_fields = mysqli_num_fields($result);
    
    $return .= 'DROP TABLE IF EXISTS '.$table.';';
    $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
    $return .= "\n\n".$row2[1].";\n\n";
    
    for ($i = 0; $i < $num_fields; $i++) {
        while($row = mysqli_fetch_row($result)) {
            $return .= 'INSERT INTO '.$table.' VALUES(';
            for($j=0; $j < $num_fields; $j++) {
                $row[$j] = addslashes($row[$j]);
                $row[$j] = str_replace("\n","\\n",$row[$j]);
                if (isset($row[$j])) { $return .= '"'.$row[$j].'"' ; } else { $return .= '""'; }
                if ($j < ($num_fields-1)) { $return .= ','; }
            }
            $return .= ");\n";
        }
    }
    $return .= "\n\n\n";
}

// 파일 저장
$fileName = 'latest_backup.sql';
// DB 폴더가 없으면 생성
if (!file_exists(__DIR__ . '/../DB')) {
    mkdir(__DIR__ . '/../DB', 0777, true);
}
$handle = fopen(__DIR__ . '/../DB/' . $fileName, 'w+');
fwrite($handle, $return);
fclose($handle);

echo "백업 완료! DB 폴더에 {$fileName} 파일이 생성되었습니다.\n";
echo "이 파일을 새 컴퓨터의 phpMyAdmin에서 가져오기(Import) 하시면 됩니다.";
?>
