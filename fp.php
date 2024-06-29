<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['logout'])) {
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $assistance = $_POST['assistance'];

    $sql = "INSERT INTO fire_prevention (age, gender, assistance) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $age, $gender, $assistance);

    if (mysqli_stmt_execute($stmt)) {
        $form_id = 4; 
        $user_id = $_SESSION['user_id']; 
        $date_submitted = date('Y-m-d H:i:s'); 

        $insert_sql = "INSERT INTO form_submissions (user_id, form_id, date_submitted) VALUES (?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($stmt_insert, "iis", $user_id, $form_id, $date_submitted);

        if (mysqli_stmt_execute($stmt_insert)) {
            $submission_id = mysqli_insert_id($conn);

            $form_data = [
                'age' => $age,
                'gender' => $gender,
                'assistance' => $assistance
            ];

            $sql_form_data = "INSERT INTO form_data (form_id, field_name, field_value, submission_id) VALUES (?, ?, ?, ?)";
            $stmt_form_data = mysqli_prepare($conn, $sql_form_data);

            foreach ($form_data as $field_name => $field_value) {
                mysqli_stmt_bind_param($stmt_form_data, "issi", $form_id, $field_name, $field_value, $submission_id);
                mysqli_stmt_execute($stmt_form_data);
            }

            echo '<script>alert("Record inserted successfully");</script>';
        } else {
            echo "Error inserting record into form_submissions table: " . mysqli_error($conn);
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt_insert);
    mysqli_stmt_close($stmt_form_data);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caniogan</title>
    <link rel="icon" href="images/caniogan.jpg">
    <style>
body {
        font-family: palatino;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }
    .container {
        display: flex;
    }
    .sidebar {
        width: 250px;
        position: sticky;
        top: 0;
        height: 95vh;
        padding: 20px;
        background-color: white;
        color: black;
    }
    .sidebar ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    .sidebar a {
        display: block;
        font-weight: bold;
        color: black;
        text-decoration: none;
        padding: 10px;
        font-size: 16px;
        margin-bottom: 5px;
    }
    .sidebar a:hover {
        background-color: #ccc;
    }
    .card {
        background-color: #fff;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
        width: 70%;
        text-align: center;
        margin: 0 auto;
    }

    .card h2 {
        font-size: 50px;
        margin-bottom: 20px;
        margin-top: 100px;
    }

    form {
        background-color: #fff;
        padding: 10px;
        border-radius: 8px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    select {
        width: calc(100% - 12px);
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        float: left;
        text-align: center;
    }

    select {
        width: calc(100% - 22px);
    }

    textarea {
        width: calc(100% - 12px);
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        resize: vertical;
    }

    button[type="submit"] {
        padding: 10px 20px;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    .input-group {
        width: 50%;
        float: left;
    }

    .clear {
        clear: both;
    }
        img {
            width: 200px;
            height: 200px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
    <a href="dashboard.php"><img src="images/caniogan.jpg" alt=""></a>
        <ul>
            <li><a href="bf.php">Birth Fact</a></li>
            <li><a href="b.php">Blotter</a></li>
            <li><a href="es.php">Environment Support</a></li>
            <li><a href="fp.php">Fire Protection</a></li>
            <li><a href="ftjsa.php">First Time Job Seekers Assistance</a></li>
            <li><a href="hs.php">Health Service</a></li>
            <li><a href="nid.php">No Income Declaration</a></li>
            <li><a href="permit.php">Permit</a></li>
            <li><a href="r.php">Request</a></li>
            <li><a href="spid.php">Solo Parent ID</a></li>
            <li><a href="tor.php">Transfer of Residency</a></li>
            <li><a href="a.php">Activity</a></li>
        </ul>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <button type="submit" name="logout" class="logout-button">Logout</button>
        </form>
    </div>
    <div class="card">
        <h2>Disaster Response</h2>
        <form action="fp.php" method="post">
            <div class="input-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="input-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="clear"></div>
            <div style="margin-top: 20px;">
                <label for="assistance">Emergency Preparedness Needs</label>
                <textarea id="assistance" name="assistance" rows="4" required></textarea>
            </div>
            <div>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
