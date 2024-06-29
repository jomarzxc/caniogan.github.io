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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $businessName = $_POST['Business_name'];
    $businessAddress = $_POST['Business_address'];
    $ownerName = $_POST['Owner_name'];
    $contactNumber = $_POST['Contact_number'];
    $emailAddress = $_POST['Email_address'];
    $natureOfBusiness = $_POST['nature_of_business'];
    $ownership = $_POST['ownership'];
    $registrationNumber = $_POST['registration_number'];
    $dateOfEstablishment = $_POST['date_of_establishment'];
    $premisesSize = $_POST['premises_size'];
    $zoningCompliance = $_POST['compliance'];
    $numberOfEmployees = $_POST['employees'];
    $workingHours = $_POST['hours'];
    $workingHoursFormatted = date('H:i:s', strtotime($workingHours));
    $specialRequirements = $_POST['considerations'];
    $image_path = '';

    if (isset($_FILES["image_upload"]) && !empty($_FILES["image_upload"]["name"])) {
        $target_directory = "uploads/";
        $target_file = $target_directory . basename($_FILES["image_upload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image_upload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["image_upload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["image_upload"]["name"])) . " has been uploaded.";
                $image_path = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No file uploaded.";
    }

    $sql = "INSERT INTO permit (business_name, business_address, owner_name, contact_number, email_address, nature_of_business, ownership, registration_number, date_of_establishment, premises_size, compliance, number_of_employees, working_hours, special_requirements, image_path) 
    VALUES ('$businessName', '$businessAddress', '$ownerName', '$contactNumber', '$emailAddress', '$natureOfBusiness', '$ownership', '$registrationNumber', '$dateOfEstablishment', '$premisesSize', '$zoningCompliance', '$numberOfEmployees', '$workingHoursFormatted', '$specialRequirements', '$image_path')";

    if (mysqli_query($conn, $sql)) {
        $user_id = $_SESSION['user_id'];
        $form_id = 8;  // Adjust as needed
        $date_submitted = date('Y-m-d H:i:s');

        $insert_sql = "INSERT INTO form_submissions (user_id, form_id, date_submitted) 
                       VALUES ('$user_id', '$form_id', '$date_submitted')";
        if (mysqli_query($conn, $insert_sql)) {
            $submission_id = mysqli_insert_id($conn);  // Get the last inserted id

            $form_data = [
                'business_name' => $businessName,
                'business_address' => $businessAddress,
                'owner_name' => $ownerName,
                'contact_number' => $contactNumber,
                'email_address' => $emailAddress,
                'nature_of_business' => $natureOfBusiness,
                'ownership' => $ownership,
                'registration_number' => $registrationNumber,
                'date_of_establishment' => $dateOfEstablishment,
                'premises_size' => $premisesSize,
                'compliance' => $zoningCompliance,
                'number_of_employees' => $numberOfEmployees,
                'working_hours' => $workingHoursFormatted,
                'special_requirements' => $specialRequirements,
                'image_path' => $image_path
            ];

            $sql_form_data = "INSERT INTO form_data (form_id, field_name, field_value, submission_id) VALUES (?, ?, ?, ?)";
            $stmt_form_data = mysqli_prepare($conn, $sql_form_data);

            foreach ($form_data as $field_name => $field_value) {
                mysqli_stmt_bind_param($stmt_form_data, "issi", $form_id, $field_name, $field_value, $submission_id);
                if (!mysqli_stmt_execute($stmt_form_data)) {
                    echo "Error inserting $field_name into form_data table: " . mysqli_error($conn);
                }
            }

            mysqli_stmt_close($stmt_form_data);

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

    h3{
        margin-top: 30px;
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
            <h2>BUSINESS PERMIT</h2>
            <p>Our online Business Permit Application Form for Barangay offers a user-friendly platform for business owners to digitally submit permit applications, eliminating the need for physical visits. This streamlined process enhances accessibility, efficiency, and accuracy, allowing applicants to upload supporting documents directly. The digital platform fosters transparent communication between the barangay and businesses, aiming to expedite the permit issuance process and support the local business community.</p>
        </div>
        <div class="card">
            <h2>Business Form</h2>
            <form action="permit.php" method="post" enctype="multipart/form-data">
            <div class="clear"></div>
                <h2>Business Information</h2>
                <div Business="clear"></div>
                <div class="input-group">
                    <label for="Business">Business Name:</label>
                    <input type="text" id="Business" name="Business_name" placeholder="Complete name of the Business" required>
                </div>
                <div class="input-group">
                    <label for="Business_address">Business Address:</label>
                    <input type="text" id="Business_address" name="Business_address" placeholder="Input the full Address" required>
                </div>
                <div class="clear"></div>
                <div class="input-group">
                    <label for="Owner_name">Business Owner's Name:</label>
                    <input type="text" id="Owner_name" name="Owner_name" placeholder="First Name / Middle Name / Last Name" required>
                </div>
                <div class="input-group">
                    <label for="Contact_number">Contact Number:</label>
                    <input type="text" id="Contact_number" name="Contact_number" placeholder="Contact Number" required>
                </div>
                <div class="clear"></div>
                <div>
                    <label for="Email_address">Email Address:</label>
                    <input type="text" id="Email_address" name="Email_address" placeholder="Sample@gmail.com" required>
                </div>
                <div class="clear"></div>
                <h2>Business Details</h2>
                <div class="clear"></div>
                <div class="input-group">
                    <label for="nature_of_business">Nature of Business:</label>
                    <input type="text" id="nature_of_business" name="nature_of_business" placeholder="Input the nature of the business" required>
                </div>
                <div class="input-group">
                    <label for="ownership">Type of Ownership:</label>
                    <input type="text" id="ownership" name="ownership" placeholder="Sole / Proprietorship / Corporation / Cooperative" required>
                </div>
                <div class="clear"></div>
                <div class="input-group">
                    <label for="registration_number">Business Registration Number (if applicable):</label>
                    <input type="text" id="registration_number" name="registration_number" placeholder="Month/Day/Year" required>
                </div>
                <div class="input-group">
                    <label for="date_of_establishment">Date of Establishment:</label>
                    <input type="date" id="date_of_establishment" name="date_of_establishment" placeholder="Month/Day/Year" required>
                </div>
                <div class="clear"></div>
                <h2>Premises Information</h2>
                <div class="clear"></div>
                <div class="input-group">
                    <label for="premises_size">Premises Size (in square meters):</label>
                    <input type="text" id="premises_size" name="premises_size" placeholder="Input Size" required>
                </div>
                <div class="input-group">
                    <label for="compliance">Zoning Compliance:</label>
                    <input type="date" id="compliance" name="compliance" placeholder="dd/mm/yyyy" required></input>
                </div>
                <div class="clear"></div>
                <h2>Additional Information</h2>
                <div class="clear"></div>
                <div class="input-group">
                    <label for="employees">Number of Employees:</label>
                    <input type="text" id="employees" name="employees" placeholder="Number" required>
                </div>
                <div class="input-group">
                    <label for="hours">Working Hours:</label>
                    <input type="time" id="hours" name="hours" required></input>
                </div>
                <div class="clear"></div>
                <div>
                    <label for="considerations">Any Special Requirement or Considerations:</label>
                    <input type="text" id="considerations" name="considerations" required></input>
                </div>
                <h3 style="font-size: 30px; margin-top: 80px;">Supporting documents</h3>
                <h3> Photocopy of Valid ID of Business Owner  </h3>
                <h3> Photocopy of DTI/SEC Registration  </h3>
                <h3> Barangay clearance from Previous Location(if applicable)  </h3>
                <h3> Health and Sanitary Permit </h3>
                <h3> Fire Safely Inspection Permit </h3>
                <label for="image_upload">Upload Supporting Image:</label>
                <input style="  border: 2px solid black; border-radius: 5px;" type="file" id="image_upload" name="image_upload" accept="image/*">
                <div class="clear"></div>
                <div>
                    <button style="margin-top: 50px;" type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>