<?php 

include('../includes/connect.php'); //pour creer la var $con
include('../functions/common_function.php');// getipAddress()
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
    <h2 class="text-center" style="color:#f2a7b8;">New User Registration</h2>
    <div class="row d-flex align-items-center justify-content-center">
        <div class="col-lg-12 col-xl-6" style="background-color:#fff; border:1.5px solid #f2a7b8; border-radius:12px; padding:2rem;">
<form action="" method="post" enctype="multipart/form-data"> 
     <!--enctype="multipart/form-data" → obligatoire quand on upload un fichier (la photo de profil)>


<!-- formulaire html --> 
    <!-- username field -->
    <div class="form-outline mb-4">
         <label for="user_username" class="form-label">Username</label>
         <input type="text" id="user_username" class="form-control" placeholder="Enter your username" autocomplete="off" required="required" name="user_username"/>
    </div>
    <!-- email field -->
    <div class="form-outline mb-4">
         <label for="user_email" class="form-label">Email</label>
         <input type="email" id="user_email" class="form-control" placeholder="Enter your email" autocomplete="off" required="required" name="user_email"/>
    </div>

    <!-- image field -->
    <div class="form-outline mb-4">
         <label for="user_image" class="form-label">User Image</label>
         <input type="file" id="user_image" class="form-control" required="required" name="user_image"/>
    </div>

    <!-- password field -->
    <div class="form-outline mb-4">
         <label for="user_password" class="form-label">Password</label>
         <input type="password" id="user_password" class="form-control" placeholder="Enter your password" autocomplete="off" required="required" name="user_password"/>
    </div>

    <!-- confirm password field -->
    <div class="form-outline mb-4">
         <label for="conf_user_password" class="form-label">Confirm Password</label>
         <input type="password" id="conf_user_password" class="form-control" placeholder="Confirm password" autocomplete="off" required="required" name="conf_user_password"/>
    </div>

    <!-- Address field -->
    <div class="form-outline mb-4">
         <label for="user_address" class="form-label">Address</label>
         <input type="text" id="user_address" class="form-control" placeholder="Enter your address" autocomplete="off" required="required" name="user_address"/>
    </div>

    <!-- Contact field -->
    <div class="form-outline mb-4">
         <label for="user_contact" class="form-label">Contact</label>
         <input type="text" id="user_contact" class="form-control" placeholder="Enter your mobile number" autocomplete="off" required="required" name="user_contact"/>
    </div>

    <div class="mt-4 pt-2">
        <input type="submit" value="Register" style="background-color:#f2a7b8; color:white; border:none; border-radius:6px; cursor:pointer;" class="py-2 px-3" name="user_register">
        <p class="small fw-bold mt-2 pt-1 mb-0">Already have an account ? <a href="user_login.php" style="color:#f2a7b8;">Login</a></p>
    </div>
</form>
        </div>
    </div>
</div>
</body>
</html>


<!-- php code -->
<?php
if(isset($_POST['user_register'])){

    // -----------------------------------------------
    // RÉCUPÉRATION DES DONNÉES
    // -----------------------------------------------
    $user_username      = $_POST['user_username'];
    $user_email         = $_POST['user_email'];
    $user_password      = $_POST['user_password'];
    $hash_password      = password_hash($user_password, PASSWORD_DEFAULT);
    $conf_user_password = $_POST['conf_user_password'];
    $user_address       = $_POST['user_address'];
    $user_contact       = $_POST['user_contact'];
    $user_image         = $_FILES['user_image']['name']; 
    $user_image_tmp     = $_FILES['user_image']['tmp_name'];
    $user_ip            = getIPAddress();


    // -----------------------------------------------
    // REQUÊTE SELECT — Vérifier si user existe déjà
    // -----------------------------------------------
    $select_query = $con->prepare(
        "SELECT * FROM `user_table` 
         WHERE username = :username OR user_email = :email"
    );

    $select_query->bindParam(':username', $user_username, PDO::PARAM_STR);
    $select_query->bindParam(':email',    $user_email,    PDO::PARAM_STR);

    $select_query->execute();
    $rows_count = $select_query->rowCount();


    // -----------------------------------------------
    // VÉRIFICATIONS
    // -----------------------------------------------
    if($rows_count > 0){
        echo "<script>alert('Username ou Email déjà utilisé')</script>";

    } else if($user_password != $conf_user_password){
        echo "<script>alert('Les mots de passe ne correspondent pas')</script>";

    } else {

        // -------------------------------------------
        // UPLOAD DE L'IMAGE
        // -------------------------------------------
        move_uploaded_file($user_image_tmp, "./user_images/$user_image");


        // -------------------------------------------
        // REQUÊTE INSERT — Enregistrer le nouvel user
        // -------------------------------------------
        $insert_query = $con->prepare(
            "INSERT INTO `user_table` 
                (username, user_email, user_password, user_image, user_ip, user_address, user_mobile) 
             VALUES 
                (:username, :email, :password, :image, :ip, :address, :mobile)"
        );

        $insert_query->bindParam(':username', $user_username, PDO::PARAM_STR);
        $insert_query->bindParam(':email',    $user_email,    PDO::PARAM_STR);
        $insert_query->bindParam(':password', $hash_password, PDO::PARAM_STR);
        $insert_query->bindParam(':image',    $user_image,    PDO::PARAM_STR);
        $insert_query->bindParam(':ip',       $user_ip,       PDO::PARAM_STR);
        $insert_query->bindParam(':address',  $user_address,  PDO::PARAM_STR);
        $insert_query->bindParam(':mobile',   $user_contact,  PDO::PARAM_STR);

        $insert_query->execute(); 
    }


    // -----------------------------------------------
    // REQUÊTE SELECT PANIER — Vérifier le cart
    // -----------------------------------------------
    $select_cart = $con->prepare(
        "SELECT * FROM `cart_details` 
         WHERE ip_address = :ip"
    );

    $select_cart->bindParam(':ip', $user_ip, PDO::PARAM_STR);
    $select_cart->execute();
    $rows_count = $select_cart->rowCount(); //Vérifie si le visiteur avait des articles 
    // dans son panier avant de s'inscrire (identifié par son IP)


    // -----------------------------------------------
    // REDIRECTION
    // -----------------------------------------------
    if($rows_count > 0){
        $_SESSION['username'] = $user_username;
        echo "<script>alert('Vous avez des articles dans votre panier')</script>";
        echo "<script>window.open('checkout.php','_self')</script>";
    } else {
        echo "<script>window.open('../index.php','_self')</script>";
    } //si le visiteur avait un panier → il est redirigé vers checkout directement après inscription

}
?>