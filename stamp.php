<?php
require 'db.php'; // Ensure this connects to your database
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get JSON input
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['user_id_number']) || !isset($data['action'])) {
            echo json_encode(["status" => "error", "message" => "Missing required fields"]);
            exit;
        }

        // Sanitize input
        $user_id_number = htmlspecialchars($data['user_id_number']);
        $action = htmlspecialchars($data['action']);
        $date = date('Y-m-d'); // Current date
        $time = date('H:i:s'); // Current time

        // Prepare SQL statement
        $sql = "INSERT INTO attendance (user_id_number, date, action, time) 
                VALUES (:user_id_number, :date, :action, :time)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id_number', $user_id_number);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':time', $time);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Attendance recorded"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database insertion failed"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
