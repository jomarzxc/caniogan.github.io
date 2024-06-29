<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $form_id = $_POST['form_id'];
    $date_submitted = date('Y-m-d H:i:s');
    $status = 'Pending';

    $sql = "INSERT INTO form_submissions (user_id, form_id, date_submitted, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('iiss', $user_id, $form_id, $date_submitted, $status);

        if ($stmt->execute()) {
            echo "Form submitted successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Form</title>
</head>
<body>
    <h2>Submit Form</h2>
    <form action="submit_form.php" method="post">
        <label for="form_id">Form ID:</label>
        <input type="number" id="form_id" name="form_id" required>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
