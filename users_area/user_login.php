<?php
include('../includes/connect.php');
include('../functions/common_function.php');
@session_start(); //demarer la session
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

<body style="background-color:#fdf8f2;">
    <div class="container-fluid my-3">
        <h2 class="text-center" style="color:#f2a7b8;">User Login</h2>
        <div class="row d-flex align-items-center justify-content-center mt-5">
            <div class="col-lg-12 col-xl-6" style="background-color:#fff; border:1.5px solid #f2a7b8; border-radius:12px; padding:2rem;">
                <form action="" method="post">
                    <!-- username field -->
                    <div class="form-outline mb-4">
                        <label for="user_username" class="form-label">Username</label>
                        <input type="text" id="user_username" class="form-control" placeholder="Enter your username" autocomplete="off" required="required" name="user_username"/>
                    </div>

                    <!-- password field -->
                    <div class="form-outline mb-4">
                        <label for="user_password" class="form-label">Password</label>
                        <input type="password" id="user_password" class="form-control" placeholder="Enter your password" autocomplete="off" required="required" name="user_password"/>
                    </div>

                    <div class="mt-4 pt-2">
                        <input type="submit" value="Login" style="background-color:#f2a7b8; color:white; border:none; border-radius:6px; cursor:pointer;" class="py-2 px-3" name="user_login">
                        <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="user_registration.php" style="color:#f2a7b8;">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


<?php

//recuperation des donnees
if(isset($_POST['user_login'])){

    $user_username = $_POST['user_username'];
    $user_password = $_POST['user_password'];
    $user_ip       = getIPAddress(); 


    //chercher user
    $select_query = $con->prepare(
        "SELECT * FROM `user_table` WHERE username = :username"
    );

    $select_query->bindParam(':username', $user_username, PDO::PARAM_STR);
    $select_query->execute();

    $row_count = $select_query->rowCount();
    $row_data  = $select_query->fetch(PDO::FETCH_ASSOC);


 //verifier le panier: 
    $select_cart = $con->prepare(
        "SELECT * FROM `cart_details` WHERE ip_address = :ip"
    );

    $select_cart->bindParam(':ip', $user_ip, PDO::PARAM_STR);
    $select_cart->execute();

    $row_count_cart = $select_cart->rowCount();


    //redirection
    if($row_count > 0){

        if(password_verify($user_password, $row_data['user_password'])){

            $_SESSION['username'] = $user_username;
            echo "<script>alert('Login successful')</script>";

            if($row_count_cart == 0){
                // Pas de panier → page profil
                echo "<script>window.open('profile.php','_self')</script>";
            } else {
                // Panier existant → page paiement
                echo "<script>window.open('payment.php','_self')</script>";
            }

        } else {
            echo "<script>alert('Invalid Credentials')</script>";
        }

    } else {
        echo "<script>alert('Invalid Credentials')</script>";
    }
}

/*Login soumis
│
├── Username existe en BDD ?
│   │
│   ├── NON → "Invalid Credentials"
│   │
│   └── OUI → Mot de passe correct ?
│               │
│               ├── NON → "Invalid Credentials"
│               │
│               └── OUI → Panier existant (par IP) ?
│                           │
│                           ├── NON → profile.php
│                           └── OUI → payment.php 
*/
?>