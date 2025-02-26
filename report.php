<?php
// filepath: /C:/Users/Hp/Documents/GitHub/tracker/report.php
require_once 'db.php';

$userId = $_GET['userId']; // Get user ID from query parameter

$stmt = $pdo->prepare("SELECT date, action, time, created_at FROM attendance WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$records = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Attendance Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f4f9;
      padding: 20px;
    }
    .report-container {
      max-width: 800px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }
    table {
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="report-container">
    <h2 class="mb-4">Attendance Report</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Date</th>
          <th>Action</th>
          <th>Time</th>
          <th>Recorded At</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($records): ?>
          <?php foreach ($records as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['date']) ?></td>
              <td><?= htmlspecialchars($row['action']) ?></td>
              <td><?= htmlspecialchars($row['time']) ?></td>
              <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center">No records found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <a href="tracker.html" class="btn btn-primary">Back to Tracker</a>
  </div>
</body>
</html>