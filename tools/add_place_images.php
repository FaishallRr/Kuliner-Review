<?php
$host='127.0.0.1';
$user='root';
$pass='';
$db='kuliner_review';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) { echo "CONNECT_ERR: " . $mysqli->connect_error . PHP_EOL; exit(1); }
$res = $mysqli->query("SELECT id, slug FROM places");
if (! $res) { echo "QUERY_ERR: " . $mysqli->error . PHP_EOL; exit(1); }
$uploadDir = __DIR__ . '/../writable/uploads';
if (! is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
$sample = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAE0lEQVR42mP8z8BQz0AEYBxVSQAAAwAB/3nQpQAAAABJRU5ErkJggg==');
while ($row = $res->fetch_assoc()) {
    $slug = $row['slug'];
    $id = (int)$row['id'];
    $filename = $slug . '.png';
    $filepath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
    if (! file_exists($filepath)) {
        file_put_contents($filepath, $sample);
    }
    $stmt = $mysqli->prepare("UPDATE places SET image = ? WHERE id = ?");
    $stmt->bind_param('si', $filename, $id);
    $stmt->execute();
}
echo "UPDATED_IMAGES\n";
