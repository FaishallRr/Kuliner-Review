<?php
$conn = new mysqli('localhost', 'root', '');
$conn->query('DROP DATABASE IF EXISTS kuliner_review');
$conn->query('CREATE DATABASE kuliner_review DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_general_ci');
echo "Database reset done!\n";