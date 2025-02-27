<?php
require 'db.php'; // Ensure database connection

if (!isset($_GET['userId'])) {
    die("Missing user ID");
}

$user_id_number = htmlspecialchars($_GET['userId']);

try {
    $stmt = $pdo->prepare("SELECT date, action, time FROM attendance WHERE user_id_number = :user_id_number ORDER BY date DESC");
    $stmt->bindParam(':user_id_number', $user_id_number);
    $stmt->execute();

    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$records) {
        die("No records found for this user.");
    }

    // Output CSV headers
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=attendance_report.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Date', 'Action', 'Time'));

    foreach ($records as $row) {
        fputcsv($output, $row);
    }

    fclose($output);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
