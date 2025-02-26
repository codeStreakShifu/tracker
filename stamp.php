<?php
// filepath: /C:/Users/Hp/Documents/GitHub/tracker/stamp.php
header("Content-Type: application/json");
require_once 'db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt'); // Log errors to a file named error_log.txt

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['date'], $data['action'], $data['time'])) {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO attendance (date, action, time) VALUES (?, ?, ?)");
    $stmt->execute([$data['date'], $data['action'], $data['time']]);
    echo json_encode(["status" => "success", "message" => "Attendance recorded"]);
} catch (\PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Log the error message
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>