<?php
// filepath: /C:/Users/Hp/Documents/GitHub/tracker/login.php
header("Content-Type: application/json");
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['idNumber'], $data['password'])) {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}

$idNumber = $data['idNumber'];
$password = $data['password'];

try {
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id_number = ?");
    $stmt->execute([$idNumber]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        echo json_encode(["status" => "success", "message" => "Login successful"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID number or password"]);
    }
} catch (\PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>