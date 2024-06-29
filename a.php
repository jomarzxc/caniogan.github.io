<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT s.form_id, s.date_submitted, s.status FROM form_submissions s WHERE s.user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["error" => mysqli_error($conn)]);
    exit();
}

$activities = [];

$form_name = [
    1 => "Birth Fact",
    2 => "Blotter",
    3 => "Environmental Support",
    4 => "Fire Prevention",
    5 => "First Time Job Seekers Assistance",
    6 => "Health Service",
    7 => "No Income Declaration",
    8 => "Permit",
    9 => "Requests",
    10 => "Solo Parent",
    11 => "Transfer Request"
];

while ($row = mysqli_fetch_assoc($result)) {
    $activity = [
        "form_name" => isset($form_name[$row['form_id']]) ? $form_name[$row['form_id']] : "Unknown Form",
        "date_submitted" => $row['date_submitted'],
        "status" => $row['status']
    ];
    $activities[] = $activity;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activity</title>
    <style>
        /* Add your CSS styles here */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .back-to-dashboard {
            background-color: blue;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            cursor: pointer;
        }
        .back-to-dashboard:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>
    <h2>Activity</h2>
    <table id="activity-table">
        <thead>
            <tr>
                <th>Form Name</th>
                <th>Date Submitted</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activities as $activity): ?>
            <tr>
                <td><?php echo $activity['form_name']; ?></td>
                <td><?php echo $activity['date_submitted']; ?></td>
                <td><?php echo $activity['status']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button class="back-to-dashboard" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
</body>
</html>
