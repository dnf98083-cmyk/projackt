<?php
require_once 'inc/db.php';
$members = db_select("SELECT id, name, level FROM members");
foreach ($members as $m) {
    echo "ID: " . $m['id'] . " / Name: " . $m['name'] . " / Level: " . $m['level'] . "\n";
}
?>