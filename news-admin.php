<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';

$submission_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['description']) && isset($_POST['content'])) {
    $description = $_POST['description'];
    $content = $_POST['content'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

 
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $image = basename($_FILES["image"]["name"]);
        } else {
            echo "File is not an image.";
            exit();
        }
    }

    $sql_insert_news = "INSERT INTO news_database (description, content, image) VALUES ('$description', '$content', '$image')";
    if (mysqli_query($conn, $sql_insert_news)) {
        $submission_message = "News submitted successfully!";
    } else {
        echo "Error adding news: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create News</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        textarea,
        input[type="file"] {
            width: calc(100% - 22px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 150px;
        }
        button {
            background-color: blue;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }
        button:hover {
            background-color: darkblue;
        }
        .message {
            text-align: center;
            color: green;
            margin-bottom: 20px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create News</h2>

        <?php if (!empty($submission_message)): ?>
            <p class="message"><?php echo $submission_message; ?></p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>
            
            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="4" required></textarea>
            
            <label for="image">Upload Image:</label>
            <input type="file" name="image" id="image" accept="image/*" required>
            
            <button type="submit" name="submit">Add News</button>
        </form>

        <a href="admin.php" class="back-link">Back to Admin Dashboard</a>
    </div>
</body>
</html>
