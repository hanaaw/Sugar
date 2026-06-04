<!-- connect file -->
<?php
include('includes/connect.php');
include('functions/common_function.php');
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
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <!-- External CSS file -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- navbar -->
    <div class="container-fluid p-0">
        <!-- first child -->
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#fff; border-bottom:2px solid #f2a7b8;">
            <div class="container-fluid">
                <img src="./images/logo.png" alt="E-Commerce Image Logo" class="logo">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="display_all.php">Products</a>
                        </li>
                        <?php
                        if (isset($_SESSION['username'])) {
                            echo "<li class='nav-item'>
                            <a class='nav-link' href='./users_area/profile.php'>My Account</a>
                            </li>";
                        } else {
                            echo "<li class='nav-item'>
                            <a class='nav-link' href='./users_area/user_registration.php'>Register</a>
                            </li>";
                        }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i><sup><?php cart_item(); ?></sup></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Total Price: <?php total_cart_price(); ?> MAD</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search" action="search_product.php">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_data">
                        <input class="btn" style="border:1.5px solid #f2a7b8; color:#f2a7b8; background:#fff;" type="submit" value="Search" name="search_data_product">
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
                    </li>";
                } else {
                    echo "<li class='nav-item'>
                    <a class='nav-link text-dark' href='#'>Welcome " . $_SESSION['username'] . "</a>
                    </li>";
                }
                if (!isset($_SESSION['username'])) {
                    echo "<li class='nav-item'>
                    <a class='nav-link text-dark' href='./users_area/user_login.php'>Login</a>
                    </li>";
                } else {
                    echo "<li class='nav-item'>
                    <a class='nav-link text-dark' href='./users_area/logout.php'>Logout</a>
                    </li>";
                }
                ?>
            </ul>
        </nav>

        <!-- third child -->
        <div style="background-color:#fff;">
            <h3 class="text-center">SUGAR Store</h3>
            <p class="text-center">Shop the Best Products Online</p>
        </div>

        <!-- fourth child -->
        <div class="row px-1">
            <div class="col-md-10">
                <div class="row">
                    <?php
                    get_all_products();
                    get_unique_categories();
                    get_unique_brands();
                    ?>
                </div>
            </div>
            <div class="col-md-2 p-0" style="background-color:#fce4ec;">
                <!-- brands to be displayed -->
                <ul class="navbar-nav me-auto text-center">
                    <li class="nav-item" style="background-color:#f2a7b8;">
                        <a href="#" class="nav-link text-white"><h4>Delivery Brands</h4></a>
                    </li>
                    <?php getbrands(); ?>
                </ul>

                <!-- categories to be displayed -->
                <ul class="navbar-nav me-auto text-center">
                    <li class="nav-item" style="background-color:#f2a7b8;">
                        <a href="#" class="nav-link text-white"><h4>Categories</h4></a>
                    </li>
                    <?php getcategories(); ?>
                </ul>
            </div>
        </div>

        <!-- last child -->
        <?php include("./includes/footer.php") ?>
    </div>

    <!-- bootstrap js File -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>