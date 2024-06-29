<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet"> <!-- Remixicon icons -->
    <link rel="icon" href="brg.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,700;1,900&display=swap" rel="stylesheet">
    <style>
        .footer-content ul {
            padding-left: 0;
        }

        .footer-content ul li {
            list-style: none;
        }

        .bottom-bar p {
            margin-bottom: 0;
        }

        .footer-links {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .logo-footer img {
            max-width: 100px; 
        }

        .footer-content {
            text-align: center;
        }

        .footer-heading {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <footer class="bg-dark text-light">
        <div class="container" style="margin-left: 25%;">
            <div class="row">
                <div class="col-md-6">
                    <div class="logo-footer">
                        <img src="brgy-logo.png" alt="Barangay Logo" class="img-fluid mb-3 " style="margin-left:40%;">
                        <p style="text-align: center;">A barangay offers community services online that help its area covered.</p>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="footer-content mt-5" style="margin-left:50%;">
                        <h3 class="footer-heading">More Pages</h3>
                        <ul class="list-unstyled footer-links">
                            <li><a href="index.php" class="text-light">Home</a></li>
                            <li><a href="about.html" class="text-light">About</a></li>
                            <li><a href="blotter.php" class="text-light">Blotter</a></li>
                            <li><a href="request.php" class="text-light">Request</a></li>
                            <li><a href="news.php" class="text-light">News</a></li>
                            <li><a href="calamity.html" class="text-light">Calamity</a></li>
                            <li><a href="contact.html" class="text-light">Contact</a></li>
                            <li><a href="#" class="text-light">FAQ</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-bar bg-secondary text-center py-2">
            <p class="m-0">&copy; 2024 | COPYRIGHT: BARANGAY CANIOGAN | PARA SA CANIOGAN</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
