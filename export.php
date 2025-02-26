<?php
// export.php
require_once 'db.php';

$stmt = $pdo->query("SELECT date, action, time, created_at FROM attendance ORDER BY created_at DESC");
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