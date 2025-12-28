<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS distrik19");
    echo "Database distrik19 created successfully or already exists.";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
