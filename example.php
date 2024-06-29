<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include_once 'connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id']) && isset($_POST['status'])) {
    $requestId = $_POST['request_id'];
    $status = $_POST['status'];


    $updateSql = "UPDATE activities SET status = '$status' WHERE id = '$requestId'";
    if (mysqli_query($conn, $updateSql)) {
        echo '<script>alert("Status updated successfully");</script>';
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}


$sql = "SELECT * FROM activities";
$result = mysqli_query($conn, $sql);


$activities = [];
while ($row = mysqli_fetch_assoc($result)) {
    $activities[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Page</title>

</head>
<body>
    <h1>Activity Page</h1>


    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Old Address</th>
                <th>New Address</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activities as $activity): ?>
                <tr>
                    <td><?php echo $activity['id']; ?></td>
                    <td><?php echo $activity['old_address']; ?></td>
                    <td><?php echo $activity['new_address']; ?></td>
                    <td><?php echo $activity['status']; ?></td>
                    <td>
                        <?php if ($activity['status'] == 'Awaiting Response'): ?>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <input type="hidden" name="request_id" value="<?php echo $activity['id']; ?>">
                                <input type="hidden" name="status" value="Accepted">
                                <button type="submit">Accept</button>
                            </form>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <input type="hidden" name="request_id" value="<?php echo $activity['id']; ?>">
                                <input type="hidden" name="status" value="Declined">
                                <button type="submit">Decline</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
