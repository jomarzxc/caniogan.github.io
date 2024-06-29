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
    $typeOfRequest = $_POST['type_of_request'];
    $age = $_POST['age'];
    $guardian = $_POST['guardian'];

    $sql = "INSERT INTO requests (type_of_request, age, guardian) 
            VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sis", $typeOfRequest, $age, $guardian);

    if (mysqli_stmt_execute($stmt)) {
        $form_id = 9;
        $user_id = $_SESSION['user_id']; 
        $date_submitted = date('Y-m-d H:i:s');
        $insert_sql = "INSERT INTO form_submissions (user_id, form_id, date_submitted) 
                       VALUES (?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($stmt_insert, "iis", $user_id, $form_id, $date_submitted);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            $submission_id = mysqli_insert_id($conn);

            $form_data = [
                'type_of_request' => $typeOfRequest,
                'age' => $age,
                'guardian' => $guardian
            ];

            $sql_form_data = "INSERT INTO form_data (form_id, field_name, field_value, submission_id) VALUES (?, ?, ?, ?)";
            $stmt_form_data = mysqli_prepare($conn, $sql_form_data);

            foreach ($form_data as $field_name => $field_value) {
                mysqli_stmt_bind_param($stmt_form_data, "issi", $form_id, $field_name, $field_value, $submission_id);
                mysqli_stmt_execute($stmt_form_data);
            }

            echo '<script>alert("Request submitted successfully");</script>';
        } else {
            echo "Error inserting record into form_submissions table: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmt_insert);
        mysqli_stmt_close($stmt_form_data);
        mysqli_close($conn);
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
            margin-top: 100px;
        }
        form {
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 186px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        input[type="time"],
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
    <div class="content">
        <div class="card">
            <h2>ONLINE REQUEST</h2>
            <p>Welcome to the Barangay Request Form—a convenient online platform simplifying the process of requesting certificates and services from your local barangay. Ensure accuracy by carefully entering details for a seamless request experience. Specify the type of certificate or service needed, such as barangay clearance or residence certification. Your personal details, purpose of the request, and additional information for processing are essential. This secure submission process guarantees confidentiality, fostering a smooth interaction between residents and barangay officials. Your accurate input is crucial for the timely and precise delivery of requested services.</p>
        </div>
        <div class="card">
            <h2>Request Form</h2>
            <form action="r.php" method="post">
                <div class="input-group">
                    <label for="type_of_request">Please Select type of Request:</label>
                        <select id="type_of_request" name="type_of_request" required>
                            <option value="clearance">Clearance</option>
                            <option value="indigency">Indigency</option>
                            <option value="business">Business</option>
                            <option value="barangay_ID">Barangay ID</option>
                            <option value="barangay_residency">Barangay Residency</option>
                            <option value="good_moral">Good Moral</option>
                        </select>
                </div>
                <div class="input-group">
                    <label for="age">Age:</label>
                    <input type="text" id="age" name="age" placeholder="Current age" required>
                </div>
                <div class="clear"></div>
                <div>
                    <label for="guardian">Guardian (optional):</label>
                    <input type="text" id="guardian" name="guardian" placeholder="Name of Guardian" required>
                </div>
                <div class="clear"></div>
                <div>
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
