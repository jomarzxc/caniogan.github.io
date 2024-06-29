<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'subadmin')) {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';

if (!isset($_POST['submission_id'])) {
    echo "<p>Invalid request.</p>";
    exit();
}

$submission_id = $_POST['submission_id'];

$sql_submission = "SELECT * FROM form_submissions WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql_submission);
mysqli_stmt_bind_param($stmt, "i", $submission_id);
mysqli_stmt_execute($stmt);
$result_submission = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result_submission)) {
    $user_id = $row['user_id'];

    $sql_user = "SELECT name FROM users WHERE user_id = ?";
    $stmt_user = mysqli_prepare($conn, $sql_user);
    mysqli_stmt_bind_param($stmt_user, "i", $user_id);
    mysqli_stmt_execute($stmt_user);
    $result_user = mysqli_stmt_get_result($stmt_user);
    $user_row = mysqli_fetch_assoc($result_user);
    $user_name = $user_row['name'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Submission Details</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f2f2f2;
            }
            .container {
                width: 1200px;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                text-align: center;
            }
            h2, h3 {
                color: #333;
                margin-bottom: 30px;
            }
            p {
                margin: 5px 0;
                color: #666;
            }
            ul {
                list-style-type: none;
                padding: 0;
            }
            li {
                margin-bottom: 5px;
                color: #666;
            }
            .button {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                text-decoration: none;
            }
            .button:hover {
                background-color: #0056b3;
            }
            img {
                max-width: 100%;
                height: auto;
                border-radius: 4px;
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h2>Submission Details</h2>
        <p><strong>Submission ID:</strong> <?= htmlspecialchars($row['id']) ?></p>
        <p><strong>User ID:</strong> <?= htmlspecialchars($row['user_id']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($user_name) ?></p>
        <p><strong>Form ID:</strong> <?= htmlspecialchars($row['form_id']) ?></p>
        <p><strong>Date Submitted:</strong> <?= htmlspecialchars($row['date_submitted']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></p>
        <h3>Form Data</h3>
        <ul>
            <?php
            // Fetch and display form data for the given submission ID
            $sql_form_data = "SELECT field_name, field_value FROM form_data WHERE submission_id = ?";
            $stmt_form_data = mysqli_prepare($conn, $sql_form_data);
            mysqli_stmt_bind_param($stmt_form_data, "i", $submission_id);
            mysqli_stmt_execute($stmt_form_data);
            $result_form_data = mysqli_stmt_get_result($stmt_form_data);

            // Loop through form data and display each field
            while ($form_data_row = mysqli_fetch_assoc($result_form_data)) {
                $field_name = htmlspecialchars($form_data_row['field_name']);
                $field_value = htmlspecialchars($form_data_row['field_value']);
                
                // Output each form field and its corresponding value
                echo "<li><strong>$field_name:</strong> $field_value</li>";
            }
            ?>
        </ul>
        <br>
        <?php
        if ($_SESSION['role'] === 'admin') {
            echo "<a href='admin.php' class='button'>Back to Admin Dashboard</a>";
        } else {
            echo "<a href='subadmin.php' class='button'>Back to Subadmin Dashboard</a>"; 
        }
        ?>
    </div>
    </body>
    </html>
    <?php
} else {
    echo "<p>No submission found for the given ID.</p>";
}
?>
