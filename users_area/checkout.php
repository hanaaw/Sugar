<!-- connect file -->
<?php

//Ce fichier gère la page de checkout. 
// Il redirige vers le login si non connecté, ou vers le paiement si connecté.

include('../includes/connect.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Website using PHP and MySQL</title>
    <!-- bootstrap CSS File -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <!-- External CSS file -->
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <!-- navbar -->
    <div class="container-fluid p-0">
        <!-- first child -->
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#fff; border-bottom:2px solid #f2a7b8;">
            <div class="container-fluid">
                <img src="../images/logo.png" alt="E-Commerce Image Logo" class="logo">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../display_all.php">Products</a>
                        </li>
                        <?php
                        if (isset($_SESSION['username'])) {
                            echo "<li class='nav-item'>
                            <a class='nav-link' href='profile.php'>My Account</a>
                            </li>";
                        } else {
                            echo "<li class='nav-item'>
                            <a class='nav-link' href='user_registration.php'>Register</a>
                            </li>";
                        }
                        ?>
                    </ul>
                    <form class="d-flex" action="../search_product.php" method="get">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_data">
                        <input type="submit" value="Search" class="btn" style="border:1.5px solid #f2a7b8; color:#f2a7b8; background:#fff;" name="search_data_product">
                    </form>
                </div>
            </div>
        </nav>

        <!-- second child -->
        <nav class="navbar navbar-expand-lg" style="background-color:#f5ede0;">
            <ul class="navbar-nav me-auto">
               
               
               
               <?php

                if (!isset($_SESSION['username'])) {
                    echo "<li class='nav-item'>
                    <a class='nav-link text-dark' href='#'>Welcome Guest</a> 
                    </li>"; //Si connecté → lien vers le profil
                } else {
                    echo "<li class='nav-item'>
                    <a class='nav-link text-dark' href='#'>Welcome " . $_SESSION['username'] . "</a>
                    </li>"; //Si non connecté → lien vers l'inscription
                }

                if (!isset($_SESSION['username'])) {
                    echo "<li class='nav-item'>
                    <a class='nav-link text-dark' href='./user_login.php'>Login</a>
                    </li>";
                } else {
                    echo "<li class='nav-item'>
                    <a class='nav-link text-dark' href='logout.php'>Logout</a>
                    </li>";
                }
                ?>
            </ul>
        </nav>

        <!-- third child -->
        <div style="background-color:#fff;">
            <h3 class="text-center">SUGAR Store</h3>
            <p class="text-center">BEST PRICES! SHOP NOW</p>
        </div>

        <!-- fourth child -->
        <div class="row px-1">
            <div class="col-md-12">
                <div class="row">



                    <?php

                    if (!isset($_SESSION['username'])) {
                        include('user_login.php');  // visiteur non connecté → formulaire login
                    } else {
                        include('payment.php'); // utilisateur connecté → page paiement
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php include("../includes/footer.php"); ?>
    </div>
    <!-- bootstrap js File -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>