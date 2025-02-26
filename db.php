<?php
// filepath: /C:/Users/Hp/Documents/GitHub/tracker/db.php
$host = 'localhost';
$db   = 'attendance_tracker'; // your database name
$user = 'root';               // your database username
$pass = 'admin';              // your database password
$charset = 'utf8mb4';
$port = 3307;                 // your MySQL server port

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>