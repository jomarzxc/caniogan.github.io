<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['logout'])) {

        session_destroy();
        header("Location: login.php");
        exit();
    } else {

        $oldAddress = isset($_POST['old_address']) ? $_POST['old_address'] : '';
        $newAddress = isset($_POST['new_address']) ? $_POST['new_address'] : '';
        $age = isset($_POST['age']) ? $_POST['age'] : '';
        $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
        $reasonForTransfer = isset($_POST['reason_for_transfer']) ? $_POST['reason_for_transfer'] : '';

        if (!empty($oldAddress) && !empty($newAddress) && !empty($age) && !empty($gender) && !empty($reasonForTransfer)) {
            $sql = "INSERT INTO transfer_requests (old_address, new_address, age, gender, reason_for_transfer) 
                    VALUES ('$oldAddress', '$newAddress', '$age', '$gender', '$reasonForTransfer')";

            if (mysqli_query($conn, $sql)) {
                $form_id = 11;
                $user_id = $_SESSION['user_id'];
                $date_submitted = date('Y-m-d H:i:s');
                $insert_sql = "INSERT INTO form_submissions (user_id, form_id, date_submitted) 
                               VALUES ('$user_id', '$form_id', '$date_submitted')";
                if (mysqli_query($conn, $insert_sql)) {
                    echo '<script>alert("Request submitted successfully");</script>';


                    $submission_id = mysqli_insert_id($conn);

                    $form_data = [
                        'old_address' => $oldAddress,
                        'new_address' => $newAddress,
                        'age' => $age,
                        'gender' => $gender,
                        'reason_for_transfer' => $reasonForTransfer
                    ];

                    $sql_form_data = "INSERT INTO form_data (form_id, field_name, field_value, submission_id) VALUES (?, ?, ?, ?)";
                    $stmt_form_data = mysqli_prepare($conn, $sql_form_data);

                    foreach ($form_data as $field_name => $field_value) {
                        mysqli_stmt_bind_param($stmt_form_data, "issi", $form_id, $field_name, $field_value, $submission_id);
                        mysqli_stmt_execute($stmt_form_data);
                    }

                    mysqli_stmt_close($stmt_form_data);
                } else {
                    echo "Error inserting record into form_submissions table: " . mysqli_error($conn);
                }
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "Please fill all the fields.";
        }

        mysqli_close($conn);
    }
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
            margin-top: 60px;
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
        <form action="tor.php" method="post">
            <button type="submit" name="logout" class="logout-button">Logout</button>
        </form>
    </div>
    <div class="card">
        <h2>TRANSFERING</h2>
        <form action="tor.php" method="post">
            <h2>TRANSFERING FORM</h2>
            <div class="input-group">
                <label for="old_address">Old Address:</label>
                <input type="text" id="old_address" name="old_address" placeholder="Sample St. Caniogan Pasig City" required>
            </div>
            <div class="input-group">
                <label for="new_address">New Address:</label>
                <input type="text" id="new_address" name="new_address" placeholder="Sample St. Caniogan Pasig City" required>
            </div>
            <div class="clear"></div>
            <div class="input-group">
                <label for="age">Age:</label>
                <input type="text" id="age" name="age" placeholder="Input your current age" required>
            </div>
            <div class="input-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="clear"></div>
            <div>
                <label for="reason_for_transfer">Reason for Transfer:</label>
                <textarea id="reason_for_transfer" name="reason_for_transfer" placeholder="Please dictate your reasons" rows="4" required></textarea>
            </div>
            <div>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
