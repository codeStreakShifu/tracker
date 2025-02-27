<?php
// filepath: /C:/Users/Hp/Documents/GitHub/tracker/db.php

date_default_timezone_set('Asia/Manila');

$host = 'localhost';
$db   = 'attendance_tracker'; // your database name
$user = 'root';               // your database username
$pass = 'admin';              // your database password
$charset = 'utf8mb4';
$port = 3306;                 // your MySQL server port

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("SET time_zone = '+08:00'");
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>