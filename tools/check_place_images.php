<?php
$host='127.0.0.1';
$user='root';
$pass='';
$db='kuliner_review';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) { echo "CONNECT_ERR: " . $mysqli->connect_error . PHP_EOL; exit(1); }
$res = $mysqli->query("SELECT COUNT(*) AS total, SUM(CASE WHEN image IS NOT NULL THEN 1 ELSE 0 END) AS with_image FROM places");
$row = $res->fetch_assoc();
echo json_encode($row) . PHP_EOL;