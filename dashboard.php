<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT name, house_number, street, barangay, city, postal_code, contact_number, email, profile_image FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
} else {
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $house_number = $row['house_number'];
    $street = $row['street'];
    $barangay = $row['barangay'];
    $city = $row['city'];
    $postal_code = $row['postal_code'];
    $contact_number = $row['contact_number'];
    $email = $row['email'];
    $profile_image = $row['profile_image'];
}

$sql_submissions = "SELECT COUNT(*) AS count FROM form_submissions WHERE user_id = $user_id AND status = 'Approve'";
$result_submissions = mysqli_query($conn, $sql_submissions);

if (!$result_submissions) {
    echo "Error: " . mysqli_error($conn);
} else {
    $row_submissions = mysqli_fetch_assoc($result_submissions);
    $num_approvals = $row_submissions['count'];

    $notification = '';
    if ($num_approvals > 0) {
        $notification = '<div class="notification">You have ' . $num_approvals . ' approved submission(s)!</div>';
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
        height: 100vh;
        padding: 20px;
        background-color: white;
        color: black;
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
    .content {
        flex: 1;
        padding: 20px;
    }
    .header {
        padding: 20px;
        background-color: #007bff;
        color: #fff;
        text-align: center;
        border-radius: 5px 5px 0 0;
    }
    .welcome-message {
        font-size: 24px;
        margin-bottom: 20px;
    }
    .logout-button {
        padding: 10px 20px;
        background-color: #007bff;
        border: none;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
    }
    .logout-button:hover {
        background-color: #0056b3;
    }
    .card {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
    }
    .card h2 {
        font-size: 24px;
        margin-bottom: 20px;
    }
    .card p {
        margin-bottom: 0;
    }
    img {
        width: 200px;
        height: 200px;
        margin-bottom: 20px;
        border-radius: 50%;
    }
    .notification {
        background-color: #5cb85c;
        color: white;
        text-align: center;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
    }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <a href="dashboard.php"><img src="images/caniogan.jpg" alt="Caniogan"></a>
        <a href="bf.php">Birth Fact</a>
        <a href="b.php">Blotter</a>
        <a href="es.php">Environment Support</a>
        <a href="fp.php">Fire Protection</a>
        <a href="ftjsa.php">First Time Job Seekers Assistance</a>
        <a href="hs.php">Health Service</a>
        <a href="nid.php">No Income Declaration</a>
        <a href="permit.php">Permit</a>
        <a href="r.php">Request</a>
        <a href="spid.php">Solo Parent ID</a>
        <a href="tor.php">Transfer of Residency</a>
        <a href="a.php">Activity</a>
        <form action="index.html" method="post">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
    <div class="content">
        <div class="header">
            <div class="welcome-message">Welcome, <?php echo htmlspecialchars($name); ?>, to the Dashboard!</div>
        </div>
        <div class="card">
            <h2>User Information</h2>
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($house_number . ' ' . $street . ', ' . $barangay . ', ' . $city . ' ' . $postal_code); ?></p>
            <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($contact_number); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        </div>

        <?php echo $notification; ?>

    </div>
</div>
</body>
</html>
