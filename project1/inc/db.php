<?php 
// https://wikidocs.net/117002
// https://www.codingfactory.net/10075
// https://programmerdaddy.tistory.com/232

function db_get_pdo()
{
    $host = 'localhost';
    $port = '3306';
    $dbname = 'exmall'; // ✅ DB 이름은 'exmall'로 유지 (haru.sql을 exmall DB에 로드했다고 가정)
    $charset = 'utf8';
    
    // ⬇️ 🚨 이 두 값을 사용자님의 실제 접속 정보로 수정해야 합니다! 🚨
    $username = 'root'; // ✅ 사용자 이름
    $db_pw = "";        // ✅ 비밀번호 (설정했다면 여기에 입력하세요!)
    
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

    try {
        $pdo = new PDO($dsn, $username, $db_pw, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        // 연결 실패 시 오류 메시지를 던집니다.
        throw new Exception("DB 연결 실패: " . $e->getMessage());
    }
}


// 가져오기 (SELECT)
function db_select($query, $param = array()){
    $pdo = db_get_pdo();
    try {
        $st = $pdo->prepare($query);
        $st->execute($param);
        $result = $st->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        return $result ?: [];   // ← 빈값도 배열 보장
    } catch (PDOException $ex) {
        error_log("DB SELECT Error: " . $ex->getMessage() . " | Query: " . $query);
        return [];              // ← 오류 시 빈 배열 반환
    } finally {
        $pdo = null;
    }
}


function db_insert($query, $param = array())
{
    $pdo = db_get_pdo();
    try {
        $st = $pdo->prepare($query);
        $result = $st->execute($param);
        $last_id = $pdo->lastInsertId();
        $pdo = null;
        if ($result) {
            // lastInsertId가 0이면(AUTO_INCREMENT가 아니면) true를 반환하여 성공임을 알림
            return $last_id ?: true;
        } else {
            return false;
        }
    } catch (PDOException $ex) {
        error_log("DB INSERT Error: " . $ex->getMessage() . " | Query: " . $query);
        return false;
    } finally {
        $pdo = null;
    }
}

// 수정/삭제 (UPDATE/DELETE)
function db_update_delete($query, $param = array())
{
    $pdo = db_get_pdo();
    try {
        $st = $pdo->prepare($query);
        $result = $st->execute($param);
        $pdo = null;
        return $result; // 성공 시 true 반환
    } catch (PDOException $ex) {
        error_log("DB UPDATE/DELETE Error: " . $ex->getMessage() . " | Query: " . $query);
        return false;
    } finally {
        $pdo = null;
    }
}


?>