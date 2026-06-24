<?php
// Simple smoke test: login, create place, create review
$base = 'http://localhost:8080';

function post($url, $data, $token = null) {
    $ch = curl_init($url);
    $payload = json_encode($data);
    $headers = [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload),
    ];
    if ($token) $headers[] = 'Authorization: Bearer ' . $token;
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $res = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return [$res, $info];
}

// 1. login
list($res, $info) = post($base . '/api/auth/login', ['email' => 'admin@udinus.ac.id', 'password' => 'admin123']);
echo "LOGIN HTTP: " . $info['http_code'] . PHP_EOL;
$json = json_decode($res, true);
if (empty($json['token'])) { echo "Login failed: " . $res . PHP_EOL; exit(1); }
$token = $json['token'];
echo "TOKEN: " . substr($token,0,10) . "..." . PHP_EOL;

// 2. create place
$place = [
    'name' => 'API Test Place ' . rand(1000,9999),
    'address' => 'Jl. Test API',
    'category_id' => 1,
    'description' => 'Dibuat oleh smoke test',
    'latitude' => -6.98,
    'longitude' => 110.41,
    'tags' => [1],
];
list($res2, $info2) = post($base . '/api/places', $place, $token);
echo "CREATE PLACE HTTP: " . $info2['http_code'] . PHP_EOL;
echo $res2 . PHP_EOL;
$j2 = json_decode($res2, true);
if (empty($j2['id'])) { echo "Create place failed" . PHP_EOL; exit(1); }
$placeId = $j2['id'];

// 3. create review
$review = ['rating' => 5, 'comment' => 'Smoke test review'];
list($res3, $info3) = post($base . '/api/places/' . $placeId . '/reviews', $review, $token);
echo "CREATE REVIEW HTTP: " . $info3['http_code'] . PHP_EOL;
echo $res3 . PHP_EOL;

echo "SMOKE TEST COMPLETE" . PHP_EOL;
