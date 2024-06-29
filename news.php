<?php
require_once 'connection.php';

$sql_news_database= "SELECT * FROM news_database ORDER BY created_at DESC";
$result_news_database = mysqli_query($conn, $sql_news_database);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            font-family: palatino;
             min-height: 100%;
        }

        body {
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.651), rgba(0, 0, 0, 0.73)), url('images/canioganbarangay.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .navbar {
    position: fixed;
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
    margin-top: 150px;
    display: flex;
    justify-content: center;
}

.news-container {
    width: 70%;
    background-color: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    text-align: center;
}

        .news-container h2 {
            margin-bottom: 20px;
        }

.news-item {
    display: flex;
    margin-bottom: 20px;
    border-bottom: 1px solid #ccc;
}

.news-text {
    flex: 1;
    padding: 20px;
}

.news-image {
    flex-basis: 30%; 
}

.news-image img {
    width: 70%; 
}

.news-item h3{
    padding: 20px;
}

.news-item p{
    padding: 20px;
}


    </style>
</head>
<body>
<nav class="navbar">
    <div class="logo">
        <a href="index.html"> <img src="images/caniogan.jpg" alt="logo"> </a>
    </div>
    <div class="burger-menu" onclick="toggleMenu()">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
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
</nav>

<div class="container">
<div class="news-container">
    <h2>Latest News</h2>
    <?php
    if (mysqli_num_rows($result_news_database) > 0) {
        while ($row = mysqli_fetch_assoc($result_news_database)) {
            echo "<div class='news-item'>";
            echo "<div class='news-text'>";
            echo "<h3>" . $row['description'] . "</h3>";
            echo "<p>" . $row['content'] . "</p>";
            echo "<p><strong>Date Posted:</strong> " . $row['created_at'] . "</p>";
            echo "</div>";
            echo "<div class='news-image'>";
            echo "<img src='uploads/" . $row['image'] . "' alt='News Image'>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No news available.</p>";
    }
    ?>
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