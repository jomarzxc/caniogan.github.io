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
    $user_id = $_SESSION['user_id'];
    $type_of_report = $_POST['type_of_report'];
    $incident_date = $_POST['incident_date'];
    $incident_time = $_POST['incident_time'];
    $location = $_POST['location'];
    $name_of_witness = $_POST['name_of_witness'];
    $contact_number_of_witness = $_POST['contact_number_of_witness'];
    $witness_address = $_POST['witness_address'];
    $name_of_suspect = $_POST['name_of_suspect'];
    $description_of_suspect = $_POST['description_of_suspect'];
    $statement = $_POST['statement'];

    $sql = "INSERT INTO blotter (user_id, type_of_report, incident_date, incident_time, location, name_of_witness, contact_number_of_witness, witness_address, name_of_suspect, description_of_suspect, statement) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issssssssss", $user_id, $type_of_report, $incident_date, $incident_time, $location, $name_of_witness, $contact_number_of_witness, $witness_address, $name_of_suspect, $description_of_suspect, $statement);

    if (mysqli_stmt_execute($stmt)) {
        $form_id = 2;
        $submission_date = date('Y-m-d H:i:s');

        $insert_sql = "INSERT INTO form_submissions (user_id, form_id, date_submitted) 
                       VALUES (?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($stmt_insert, "iis", $user_id, $form_id, $submission_date);

        if (mysqli_stmt_execute($stmt_insert)) {
            $submission_id = mysqli_insert_id($conn);

            $form_data = [
                'type_of_report' => $type_of_report,
                'incident_date' => $incident_date,
                'incident_time' => $incident_time,
                'location' => $location,
                'name_of_witness' => $name_of_witness,
                'contact_number_of_witness' => $contact_number_of_witness,
                'witness_address' => $witness_address,
                'name_of_suspect' => $name_of_suspect,
                'description_of_suspect' => $description_of_suspect,
                'statement' => $statement,
            ];

            foreach ($form_data as $field_name => $field_value) {
                $sql_form_data = "INSERT INTO form_data (form_id, field_name, field_value, submission_id) VALUES (?, ?, ?, ?)";
                $stmt_form_data = mysqli_prepare($conn, $sql_form_data);
                mysqli_stmt_bind_param($stmt_form_data, "isss", $form_id, $field_name, $field_value, $submission_id);
                mysqli_stmt_execute($stmt_form_data);
                mysqli_stmt_close($stmt_form_data);
            }

            echo '<script>alert("Record inserted successfully");</script>';
        } else {
            echo "Error inserting record into form_submissions table: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt_insert);
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
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
            <li><a href
="a.php">Activity</a></li>
</ul>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<button type="submit" name="logout" class="logout-button">Logout</button>
</form>
</div>
<div class="content">
<div class="card">
<h2>ONLINE BLOTTER</h2>
<p>Our online blotter form serves as a convenient and efficient tool for reporting incidents or events. This structured platform allows you to provide essential details about an incident in a straightforward manner. By filling out this form, you contribute to accurate documentation, aiding in swift response and resolution.</p>
</div>
<div class="card">
<h2>Blotter Form</h2>
<form action="b.php" method="post">
<div class="input-group">
<label for="type_of_report">Please select type of report:</label>
<select id="type_of_report" name="type_of_report" required>
<option value="Child Abuse">Child Abuse</option>
<option value="Noise Complaint">Noise Complaint</option>
<option value="Hinde nagbabayad ng utang">Hinde nagbabayad ng utang</option>
<option value="Away Kapitbahay">Away Kapitbahay</option>
<option value="Hinde nagbabayad ng upa/rent">Hinde nagbabayad ng upa/rent</option>
<option value="Chismis/Gossip">Chismis/Gossip</option>
</select>
</div>
<div class="input-group">
<label for="incident_date">Incident Date:</label>
<input type="date" id="incident_date" name="incident_date" required>
</div>
<div class="clear"></div>
<div class="input-group">
<label for="incident_time">Incident Time:</label>
<input type="time" id="incident_time" name="incident_time" required>
</div>
<div class="input-group">
<label for="location">Location:</label>
<input type="text" id="location" name="location" required>
</div>
<div class="clear"></div>
<h2>Witness</h2>
<div class="input-group">
<label for="name_of_witness">Name of Witness:</label>
<input type="text" id="name_of_witness" name="name_of_witness" required>
</div>
<div class="input-group">
<label for="contact_number_of_witness">Contact Number of Witness:</label>
<input type="text" id="contact_number_of_witness" name="contact_number_of_witness" required>
</div>
<div class="clear"></div>
<div>
<label for="witness_address">Witness Address:</label>
<input type="text" id="witness_address" name="witness_address" required>
</div>
<div class="clear"></div>
<h2>Suspect</h2>
<div class="input-group">
<label for="name_of_suspect">Name of Suspect:</label>
<input type="text" id="name_of_suspect" name="name_of_suspect" required>
</div>
<div class="input-group">
<label for="description_of_suspect">Description of Suspect:</label>
<textarea id="description_of_suspect" name="description_of_suspect" rows="4" required></textarea>
</div>
<div class="clear"></div>
<div>
<label for="statement">Statement:</label>
<textarea id="statement" name="statement" rows="4" required></textarea>
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
