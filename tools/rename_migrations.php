<?php
$host='127.0.0.1';
$user='root';
$pass='';
$db='kuliner_review';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo "CONNECT_ERR: " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}
$res = $mysqli->query("SHOW TABLES LIKE 'migrations'");
if (! $res) { echo "QUERY_ERR: " . $mysqli->error . PHP_EOL; exit(1); }
if ($res->num_rows === 0) { echo "NO_TABLE" . PHP_EOL; exit(0); }
$rename = $mysqli->query("RENAME TABLE migrations TO migrations_backup");
if (! $rename) { echo "RENAME_ERR: " . $mysqli->error . PHP_EOL; exit(1); }
echo "RENAMED" . PHP_EOL;