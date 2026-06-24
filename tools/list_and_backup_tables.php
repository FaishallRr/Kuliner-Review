<?php
$host='127.0.0.1';
$user='root';
$pass='';
$db='kuliner_review';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) { echo "CONNECT_ERR: " . $mysqli->connect_error . PHP_EOL; exit(1); }
$res = $mysqli->query("SHOW TABLES");
$tables = [];
while ($row = $res->fetch_array()) { $tables[] = $row[0]; }
if (empty($tables)) { echo "NO_TABLES" . PHP_EOL; exit(0); }
echo json_encode($tables, JSON_PRETTY_PRINT) . PHP_EOL;
// Backup by renaming tables (prefix old_)
foreach ($tables as $t) {
    $new = 'old_' . $t;
    $mysqli->query("RENAME TABLE `$t` TO `$new`");
}
echo "RENAMED_ALL" . PHP_EOL;