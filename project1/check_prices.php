<?php
require_once "inc/db.php";

$sql = "SELECT content_code, content_name, content_price, category_large FROM contents LIMIT 20";
$results = db_select($sql);

foreach ($results as $row) {
    echo "Code: " . $row['content_code'] . ", Name: " . $row['content_name'] . ", Price: " . $row['content_price'] . ", Cat: " . $row['category_large'] . "\n";
}
?>