<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';


$sql_total_users = "SELECT COUNT(*) as total_users FROM users";
$result_total_users = mysqli_query($conn, $sql_total_users);
$row_total_users = mysqli_fetch_assoc($result_total_users);
$total_users = $row_total_users['total_users'];

$current_date = date('Y-m-d'); 
$sql_total_requests = "SELECT COUNT(*) as total_requests FROM form_submissions WHERE DATE(date_submitted) = '$current_date'";
$result_total_requests = mysqli_query($conn, $sql_total_requests);

if (!$result_total_requests) {
    echo "Error fetching total requests: " . mysqli_error($conn);
    exit();
}

$row_total_requests = mysqli_fetch_assoc($result_total_requests);
$total_requests = $row_total_requests['total_requests'];

$sql_users = "SELECT * FROM users";
$result_users = mysqli_query($conn, $sql_users);

if (!$result_users) {
    echo "Error fetching users: " . mysqli_error($conn);
    exit();
}

function backupDatabase() {
    $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $command = "mysqldump --user=root --host=localhost --databases caniogan_db --skip-column-statistics > $backupFile 2>&1";

    exec($command, $output, $return_var);

    if ($return_var === 0) {
        return "Backup completed successfully. File: <a href='$backupFile'>$backupFile</a>";
    } else {
        $errorMessage = "Error during backup process:<br>";
        foreach ($output as $line) {
            $errorMessage .= htmlspecialchars($line) . "<br>";
        }
        return $errorMessage;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'backup') {
        $backupMessage = backupDatabase();
        echo $backupMessage;
    }
}

$sql_total_users = "SELECT COUNT(*) as total_users FROM users";
$result_total_users = mysqli_query($conn, $sql_total_users);

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <h3>Statistics</h3>
    <canvas id="statsChart"></canvas>

    <h3>Database Management</h3>
    <form method="post">
        <input type="hidden" name="action" value="backup">
        <button type="submit">Backup Database</button>
        </form>

    <h3>List of Users</h3>
    <table>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>House No.</th>
            <th>Street</th>
            <th>Barangay</th>
            <th>City</th>
            <th>Postal Code</th>
            <th>Username</th>
            <th>Contact Number</th>
        </tr>
        <?php
        if (mysqli_num_rows($result_users) > 0) {
            while ($row = mysqli_fetch_assoc($result_users)) {
                echo "<tr>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['house_number'] . "</td>";
                echo "<td>" . $row['street'] . "</td>";
                echo "<td>" . $row['barangay'] . "</td>";
                echo "<td>" . $row['city'] . "</td>";
                echo "<td>" . $row['postal_code'] . "</td>";
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

    <a href="news-admin.php" class="back-to-dashboard">Create News</a>
    <a href="login.php" class="back-to-dashboard">Logout</a>
</div>

<script>
    const ctx = document.getElementById('statsChart').getContext('2d');
    const statsChart = new Chart(ctx, {
        type: 'bar',
        data: {
    labels: ['Total Users', 'Total Requests for Today'],
    datasets: [{
        label: 'Statistics',
        data: [<?php echo $total_users; ?>, <?php echo $total_requests; ?>],
        backgroundColor: ['#007bff', '#28a745'],
        borderColor: ['#0056b3', '#1e7e34'],
        borderWidth: 1
    }]
},
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>
