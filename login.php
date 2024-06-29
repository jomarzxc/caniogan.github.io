<?php
session_start();
require_once 'connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT user_id, role, failed_attempts FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if ($row['failed_attempts'] >= 3) {
            header("Location: forgot_password.php");
            exit();
        }

        $stmt = $conn->prepare("SELECT user_id, role FROM users WHERE username=? AND password=?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role'];

            $stmt = $conn->prepare("UPDATE users SET failed_attempts=0 WHERE user_id=?");
            $stmt->bind_param("i", $row['user_id']);
            $stmt->execute();

            if ($_SESSION['role'] === 'admin') {
                header("Location: admin.php");
                exit();
            } elseif ($_SESSION['role'] === 'subadmin') {
                header("Location: subadmin.php");
                exit();
            } else {
                header("Location: dashboard.php");
                exit();
            }
        } else {
            $stmt = $conn->prepare("UPDATE users SET failed_attempts = failed_attempts + 1 WHERE username=?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $error_message = "Incorrect username or password";
        }
    } else {
        $error_message = "Incorrect username or password";
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="images/caniogan.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: palatino;
            overflow-x: hidden;
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
            display: flex;
            height: 100%;
            justify-content: center; 
            align-items: center; 
        }

        .login-form {
            width: 300px;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 10px 40px rgb(0, 0, 0);
            background-color: rgba(255, 255, 255, 0.774);
        }

        .login-form h2 {
            text-align: center;
            margin-bottom: 50px;
            font-size: 40px;
            color: black;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group button {
            width: 50%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #0056b3;
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
}

.password-toggle{
        padding: 10px;
        background-color: grey;
        border-radius: 20px;
        cursor: pointer;
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
}

        @media only screen and (max-width: 425px) {
            body, html {
                margin: 0;
                padding: 0;
                width: 100%;
                overflow-x: hidden; 
            }
        }
    </style>
</head>
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
        <div class="login-form">
            <h2>Login</h2>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <?php if (!empty($error_message)): ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <div class="form-group">
                    <button type="submit">Login</button>
                </div>
                <div class="form-group">
                <span class="password-toggle" onclick="togglePasswordVisibility()">Show Password</span><br><br>
                    <a href="register.php">Don't have an account? Register now</a>
                </div>
                <div class="form-group">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        function toggleMenu() {
            var menu = document.querySelector('.menu');
            menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
        }

        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('password');
            var passwordToggle = document.querySelector('.password-toggle') ;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.textContent = 'Hide Password';
            } else {
                passwordInput.type = 'password';
                passwordToggle.textContent = 'Show Password';
            }
        }
    </script>
</body>
</html>