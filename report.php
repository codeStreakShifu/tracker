<?php
require 'db.php'; // Ensure database connection

if (!isset($_GET['userId'])) {
    die("<h3 style='color: red; text-align: center;'>Error: Missing user ID</h3>");
}

$user_id_number = htmlspecialchars($_GET['userId']);

try {
    // Fetch attendance records
    $stmt = $pdo->prepare("SELECT date, action, time FROM attendance WHERE user_id_number = :user_id_number ORDER BY date, time ASC");
    $stmt->bindParam(':user_id_number', $user_id_number);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<h3 style='color: red; text-align: center;'>Database error: " . $e->getMessage() . "</h3>");
}

// Function to calculate duration in minutes
function calculateDuration($start, $end) {
    if (!$start || !$end) return 0; // If missing timestamps, return 0 minutes
    $startTime = strtotime($start);
    $endTime = strtotime($end);
    return round(($endTime - $startTime) / 60); // Convert seconds to minutes
}

// Initialize tracking variables
$totalBreakDuration = 0;
$totalLunchDuration = 0;
$breakStart = $lunchStart = null;

foreach ($records as $row) {
    if ($row['action'] == "startBreak") {
        $breakStart = $row['time'];
    } elseif ($row['action'] == "stopBreak" && $breakStart) {
        $totalBreakDuration += calculateDuration($breakStart, $row['time']);
        $breakStart = null; // Reset break start after adding duration
    } elseif ($row['action'] == "startLunch") {
        $lunchStart = $row['time'];
    } elseif ($row['action'] == "stopLunch" && $lunchStart) {
        $totalLunchDuration += calculateDuration($lunchStart, $row['time']);
        $lunchStart = null; // Reset lunch start after adding duration
    }
}

// Function to format duration with warning label
function formatDuration($duration, $limit) {
    if ($duration === 0) return "<span class='text-muted'>N/A</span>";
    if ($duration > $limit) {
        return "<span class='text-danger fw-bold'>$duration minutes (Exceeded Limit!)</span>";
    }
    return "<span class='text-success'>$duration minutes</span>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; 
                     box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); text-align: center; }
        table { margin-top: 20px; }
        .text-danger { font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">Attendance Report</h2>

    <?php if ($records): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['action']) ?></td>
                        <td><?= htmlspecialchars($row['time']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="mt-4">Break & Lunch Summary</h3>
        <table class="table table-bordered mt-2">
            <tr>
                <th>Total Break Duration</th>
                <td><?= formatDuration($totalBreakDuration, 30) ?></td>
            </tr>
            <tr>
                <th>Total Lunch Duration</th>
                <td><?= formatDuration($totalLunchDuration, 60) ?></td>
            </tr>
        </table>
    <?php else: ?>
        <h4 class="text-muted">No attendance records found.</h4>
    <?php endif; ?>

    <a href="tracker.html" class="btn btn-primary mt-3">Back to Tracker</a>
</div>

</body>
</html>
