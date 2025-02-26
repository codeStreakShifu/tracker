<?php
// filepath: /C:/Users/Hp/Documents/GitHub/tracker/change_password.php
header("Content-Type: application/json");
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['idNumber'], $data['newPassword'])) {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}

$idNumber = $data['idNumber'];
$newPassword = password_hash($data['newPassword'], PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id_number = ?");
    $stmt->execute([$newPassword, $idNumber]);
    echo json_encode(["status" => "success", "message" => "Password changed successfully"]);
} catch (\PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>