<?php
require_once 'connection.php';

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $mothers_maiden_name = $_POST["mothers_maiden_name"];
    $new_password = $_POST["new_password"];

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username=? AND mothers_maiden_name=?");
    $stmt->bind_param("ss", $username, $mothers_maiden_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];

        $stmt = $conn->prepare("UPDATE users SET password=?, failed_attempts=0 WHERE user_id=?");
        $stmt->bind_param("si", $new_password, $user_id);
        if ($stmt->execute()) {
            $success_message = "Password reset successfully. You can now <a href='login.php'>login</a> with your new password.";
        } else {
            $error_message = "Error updating password.";
        }
    } else {
        $error_message = "Incorrect username or mother's maiden name.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" href="images/caniogan.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        font-family: palatino;
        overflow-x: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        background-image:linear-gradient(to bottom, rgba(0, 0, 0, 0.651), rgba(0, 0, 0, 0.73)),url('images/canioganbarangay.jpg');
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
    }
    .navbar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background-color: transparent;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 30px;
    z-index: 1; 
}

.navbar .menu ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
}

.navbar .menu ul li {
    margin: 0 10px;
    font-size: 20px;
}

.navbar .menu ul li a {
    display: block;
    color: white;
    padding: 15px 20px;
    text-decoration: none;
}

.navbar .menu ul li a:hover {
    background-color: teal;
    border-radius: 20px;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: black;
    min-width: 160px;
    z-index: 1;
    border-radius: 20px;
}

.dropdown-content a {
    color: #fff;
    padding: 12px 16px;
    display: block;
    text-decoration: none;
    font-size: 15px;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.user {
    display: flex;
    align-items: center;
    padding: 0 20px;
    margin-right: 50px;
}

.user a {
    color: white;
    padding: 15px 20px;
    text-decoration: none;
    border-radius: 20px;
    font-size: 20px;
}

.user a:hover {
    background-color: teal;
}

.logo img {
    width: 100px;
    height: auto;
}


.container {
            width: 800px;
            margin: auto;

        }
        h2 {
            text-align: center;
            margin-bottom: 50px;
            font-size: 40px;
            color: white;
        }

        form {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgb(0, 0, 0);
            background-color: rgba(255, 255, 255, 0.774);
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        input[type="tel"],
        input[type="email"],
        select {
            width: calc(100% - 12px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            float: left;
        }

        select {
            width: calc(100% - 22px);
        }

        input[type="file"] {
            width: calc(100% - 12px);
            margin-bottom: 15px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            clear: both;
            display: block;
            margin: 0 auto;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .burger-menu {
        display: none; 
        flex-direction: column;
        justify-content: space-around;
        width: 30px;
        height: 25px;
        cursor: pointer;
        z-index: 999;
        position: relative; 
    }

    .line {
        width: 100%;
        height: 3px;
        background-color: white;
    }

    @media only screen and (max-width: 1024px) {
    .navbar .menu {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 1;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .navbar .menu ul {
        flex-direction: column;
    }

    .navbar .menu ul li {
        margin: 10px 0;
    }

    .navbar .menu ul li a {
        padding: 10px 0;
    }

    .navbar .menu ul li.dropdown:hover .dropdown-content {
        display: block;
    }

    .user {
        display: none;
    }

    .logo {
        display: none;
    }

    .burger-menu {
        display: flex; 
    }
    .menu{
        display: none;
    }
    .container{
        width:700px;
    }
}


    @media only screen and (max-width: 768px) {
    .navbar .menu {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 1;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }


    .navbar .menu ul {
        flex-direction: column;
    }

    .navbar .menu ul li {
        margin: 10px 0;
    }

    .navbar .menu ul li a {
        padding: 10px 0;
    }

    .navbar .menu ul li.dropdown:hover .dropdown-content {
        display: block;
    }

    .user {
        display: none;
    }

    .logo {
        display: none;
    }

    .burger-menu {
        display: flex; 
    }   

    .menu{
        display: none;
    }
    .container{
        width: 500px;
    }
}

        @media only screen and (max-width: 425px) {
            body, html {
                margin: 0;
                padding: 0;
                width: 100%;
                overflow-x: hidden; 
            }
            .container{
                width: 300px;
            }
        }


</style>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="index.html"> <img src="images/caniogan.jpg" alt="logo"> </a>
        </div>
        <div class="menu">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li class="dropdown">
                    <a href="#">Services</a>
                    <div class="dropdown-content">
                        <a href="blotter-posting.html">Blotter</a>
                        <a href="request-posting.html">Request</a>
                        <a href="permit-posting.html">Permit</a>
                        <a href="health-posting.html">Health Service</a>
                        <a href="disaster-posting.html">Fire Protection</a>
                        <a href="#">Transfer of Residency</a>
                        <a href="birth.html">Birth Fact</a>
                        <a href="solo-posting.html">Solo Parent ID</a>
                        <a href="noincome-posting.html">No Income Declaration</a>
                        <a href="firsttime-posting.html">First-Time Job Seeker's Assistance</a>
                        <a href="environmental-posting.html">Environmental Support</a>
                    </div>
                </li>
                <li><a href="news.php">News</a></li>
                <li><a href="calamity.html">Calamity</a></li>
                <li><a href="contact.html">Contact Us</a></li>
            </ul>
        </div>
        <div class="user">
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
        <div class="burger-menu" onclick="toggleMenu()">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </nav>
    <div class="container">
        <div class="forgot-password-form">
            <h2>Forgot Password</h2>
            <form action="forgot_password.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="mothers_maiden_name">Mother's Maiden Name</label>
                    <input type="text" id="mothers_maiden_name" name="mothers_maiden_name" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <?php if (!empty($error_message)): ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <?php if (!empty($success_message)): ?>
                <p style="color: green;"><?php echo $success_message; ?></p>
                <?php endif; ?>
                <div class="form-group">
                    <button type="submit">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function toggleMenu() {
            var menu = document.querySelector('.menu');
            menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
        }
    </script>
</body>
</html>
