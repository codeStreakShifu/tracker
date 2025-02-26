<?php
// filepath: /C:/Users/Hp/Documents/GitHub/tracker/export.php
require_once 'db.php';

$userId = $_GET['userId']; // Get user ID from query parameter

$stmt = $pdo->prepare("SELECT date, action, time, created_at FROM attendance WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$records = $stmt->fetchAll();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=attendance.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Date', 'Action', 'Time', 'Recorded At']);

foreach ($records as $record) {
    fputcsv($output, $record);
}

fclose($output);
exit;
?>