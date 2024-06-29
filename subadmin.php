<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'subadmin') {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';

$sql_users = "SELECT * FROM users";
$result_users = mysqli_query($conn, $sql_users);

if (!$result_users) {
    echo "Error fetching users: " . mysqli_error($conn);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $sql_update = "UPDATE form_submissions SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}

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

$sql_submissions = "SELECT * FROM form_submissions";
$result_submissions = mysqli_query($conn, $sql_submissions);

if (!$result_submissions) {
    echo "Error fetching form submissions: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        h3 {
            color: #333;
            margin-top: 40px;
            margin-bottom: 20px;
        }

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

        select, button {
            padding: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        select {
            width: 100%;
        }

        button {
            background-color: #007bff;
            color: #fff;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-to-dashboard {
            display: block;
            width: 200px;
            margin: 20px auto;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 10px 0;
            text-align: center;
            text-decoration: none;
        }

        .back-to-dashboard:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Admin Dashboard</h2>

    <h3>List of Users</h3>
    <table>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Username</th>
            <th>Contact Number</th>
        </tr>
        <?php
        if (mysqli_num_rows($result_users) > 0) {
            while ($row = mysqli_fetch_assoc($result_users)) {
                echo "<tr>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['contact_number'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No users found</td></tr>";
        }
        ?>
    </table>

    <h3>Form Submissions</h3>
    <table>
        <tr>
            <th>Submission ID</th>
            <th>User ID</th>
            <th>Form Name</th>
            <th>Date Submitted</th>
            <th>Status</th>
            <th>Action</th>
            <th>Submitted Data</th>
        </tr>
        <?php
        if (mysqli_num_rows($result_submissions) > 0) {
            while ($row = mysqli_fetch_assoc($result_submissions)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>" . ($form_name[$row['form_id']] ?? 'Unknown') . "</td>";
                echo "<td>" . $row['date_submitted'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<select name='status'>";
                echo "<option value='Pending'" . ($row['status'] == 'Pending' ? ' selected' : '') . ">Pending</option>";
                echo "<option value='Approve'" . ($row['status'] == 'Approve' ? ' selected' : '') . ">Approve</option>";
                echo "<option value='Disapprove'" . ($row['status'] == 'Disapprove' ? ' selected' : '') . ">Disapprove</option>";
                echo "</select>";
                echo "<button type='submit'>Update</button>";
                echo "</form>";
                echo "</td>";
                echo "<td>";
                echo "<form method='post' action='view_submission.php'>";
                echo "<input type='hidden' name='submission_id' value='" . $row['id'] . "'>";
                echo "<button type='submit'>View Submission</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No form submissions found</td></tr>";
        }
        ?>
    </table>
    <a href="login.php" class="back-to-dashboard">Logout</a>
</body>
</html>