<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM sia_db.form_submissions WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["error" => mysqli_error($conn)]); 
    exit();
}

$activities = []; 

while ($row = mysqli_fetch_assoc($result)) {

    $activity = [
        "id" => $row['id'],
        "date_submitted" => $row['date_submitted'],
        "status" => $row['status']
    ];
    $activities[] = $activity;
}

echo json_encode($activities);
?>
