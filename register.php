<?php
require_once 'connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);


        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $name = $_POST["name"];
                $house_number = $_POST["house_number"];
                $street = $_POST["street"];
                $barangay = $_POST["barangay"];
                $city = $_POST["city"];
                $postal_code = $_POST["postal_code"];
                $contact_number = $_POST["contact_number"];
                $username = $_POST["username"];
                $password = $_POST["password"];
                $id_type = $_POST["id_type"];
                $email = $_POST["email"];
                $mothers_maiden_name = $_POST["mothers_maiden_name"];

            $sql = "INSERT INTO users (name, house_number, street, barangay, city, postal_code, contact_number, email, username, password, id_type, profile_image, mothers_maiden_name) 
            VALUES ('$name', '$house_number', '$street', '$barangay', '$city', '$postal_code', '$contact_number', '$email', '$username', '$password', '$id_type', '$target_file', '$mothers_maiden_name')";
    
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $username; 
        $_SESSION['name'] = $name; 
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Sorry, there was an error uploading your file.";
}
} else {
echo "File is not an image.";
}
} else {
echo "File upload error: " . $_FILES['fileToUpload']['error'];
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
            color: black;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgb(0, 0, 0);
            background-color: rgba(255, 255, 255, 0.774);
            margin-top: 60px;
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

    .password-toggle{
        padding: 10px;
        background-color: grey;
        border-radius: 20px;
        cursor: pointer;
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
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="index.html"> <img src="images/caniogan.jpg" alt="logo"> </a>
        </div>
        <div class="menu">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="#">About Us</a></li>
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
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <h2>Caniogan Portal</h2>
            <div>
                <div style="width: 50%; float: left;">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div style="width: 50%; float: left;">
                    <label for="house_number">House Number:</label>
                    <input type="text" id="house_number" name="house_number" required>
                </div>
                <div style="width: 50%; float: left;">
                    <label for="street">Street:</label>
                    <input type="text" id="street" name="street" required>
                </div>
                <div style="width: 50%; float: left;">
                    <label for="barangay">Barangay:</label>
                    <input type="text" id="barangay" name="barangay" required>
                </div>
                <div style="width: 50%; float: left;">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" required>
                </div>
                <div style="width: 50%; float: left;">
                    <label for="postal_code">Postal Code:</label>
                    <input type="text" id="postal_code" name="postal_code" required>
                </div>
            </div>
            <div>
                <div style="width: 50%; float: left;">
                    <label for="contact_number">Contact Number:</label>
                    <input type="tel" id="contact_number" name="contact_number" required>
                </div>
                <div style="width: 50%; float: left;">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
            </div>
            <div>
                <div style="width: 50%; float: left;">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div style="width: 50%; float: left;">
                    <label for="id_type">ID Type:</label>
                    <select id="id_type" name="id_type" required>
                        <option value="Passport">Passport</option>
                        <option value="License">Driver's License</option>
                        <option value="PhilHealth">PhilHealth</option>
                        <option value="Pag-IBIG">Pag-IBIG</option>
                    </select>
                </div>
            </div>
            <div>
                <div style="width: 50%; float: left;">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div style="width: 50%; float: left;">
                    <label for="mothers_maiden_name">Mother's Maiden Name:</label>
                    <input type="text" id="mothers_maiden_name" name="mothers_maiden_name" required>
                </div>
            </div>
            <div>
                <div style="width: 50%; float: left;">
                    <label for="fileToUpload">Upload Image:</label>
                    <input type="file" id="fileToUpload" name="fileToUpload" accept="image/*" required>
                </div>
            </div>
            <div>
                <span class="password-toggle" onclick="togglePasswordVisibility()">Show Password</span><br><br>
                <input type="submit" value="Submit">
            </div>
            <p>Already registered? <a href="login.php">Login here</a>.</p>
        </form>
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