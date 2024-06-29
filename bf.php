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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_form'])) {
    $date_of_birth = $_POST['date_of_birth'];
    $place_of_birth = $_POST['place_of_birth'];
    $barangay_address = $_POST['barangay_address'];
    $gender = $_POST['gender'];
    $reason_for_request = $_POST['reason_for_request'];


    $sql = "INSERT INTO birth_fact (date_of_birth, place_of_birth, barangay_address, gender, reason_for_request) 
            VALUES ('$date_of_birth', '$place_of_birth', '$barangay_address', '$gender', '$reason_for_request')";

    if (mysqli_query($conn, $sql)) {
        $form_id = 1;
        $user_id = $_SESSION['user_id']; 
        $date_submitted = date('Y-m-d H:i:s');


        $insert_sql = "INSERT INTO form_submissions (user_id, form_id, date_submitted) 
                       VALUES ('$user_id', '$form_id', '$date_submitted')";
                       
        if (mysqli_query($conn, $insert_sql)) {
            $submission_id = mysqli_insert_id($conn); 


            $fields = [
                'date_of_birth' => $date_of_birth,
                'place_of_birth' => $place_of_birth,
                'barangay_address' => $barangay_address,
                'gender' => $gender,
                'reason_for_request' => $reason_for_request
            ];

            foreach ($fields as $field_name => $field_value) {
                $field_sql = "INSERT INTO form_data (form_id, submission_id, field_name, field_value) 
                              VALUES ('$form_id', '$submission_id', '$field_name', '$field_value')";
                mysqli_query($conn, $field_sql);
            }

            echo '<script>alert("Record inserted successfully");</script>';
        } else {
            echo "Error inserting record into form_submissions table: " . mysqli_error($conn);
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

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
    input[type="date"],
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
    <h2>Birth Fact</h2>
    <form method="post">
        <div class="input-group">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>
        </div>
        <div class="input-group">
            <label for="place_of_birth">Place of Birth:</label>
            <input type="text" id="place_of_birth" name="place_of_birth" required>
        </div>
        <div class="clear"></div>
        <div class="input-group">
            <label for="barangay_address">Barangay Address:</label>
            <input type="text" id="barangay_address" name="barangay_address" required>
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
            <label for="reason_for_request">Reason for Request:</label>
            <textarea id="reason_for_request" name="reason_for_request" rows="4" required></textarea>
        </div>
        <div>
            <button type="submit" name="submit_form">Submit</button>
        </div>
    </form>
</body>
</html>