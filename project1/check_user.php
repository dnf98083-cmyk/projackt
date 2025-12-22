<?php
require_once 'inc/db.php';
$id = 'dnf826';
$res = db_select("SELECT * FROM members WHERE member_id = ?", [$id]);
if (empty($res)) {
    echo "User not found";
} else {
    echo "User found: ";
    print_r($res);
}
?>