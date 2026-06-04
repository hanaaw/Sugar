<?php

include('../includes/connect.php');
include('../functions/common_function.php');
session_start();


// Récupération sécurisée du username depuis la session
$username = $_SESSION['username'];


//Récupérer les infos du profil

$stmt = $con->prepare(
    "SELECT username, user_email, user_image, user_address, user_mobile
     FROM `user_table` WHERE username = :username"
    
);
$stmt->bindParam(':username', $username, PDO::PARAM_STR); //bindParam lie la variable $username au placeholder :username
$stmt->execute();

$user_data = $stmt->fetch(PDO::FETCH_ASSOC); //retourne un tableau associatif 

// Si l'utilisateur n'existe pas en BDD, on déconnecte
if(!$user_data){
    session_destroy();
    header('Location: user_login.php');
    exit();
}

// Variables propres pour l'affichage
$user_image   = htmlspecialchars($user_data['user_image']);
//htmlspecialchars() convertit les caractères dangereux en entités HTML
$user_email   = htmlspecialchars($user_data['user_email']);
$user_address = htmlspecialchars($user_data['user_address']);
$user_mobile  = htmlspecialchars($user_data['user_mobile']);

// Valider la section GET demandée (liste blanche) 
//definir les champs permis a un user
$allowed_sections = ['edit_account', 'my_orders', 'delete_account'];
$active_section   = null;
foreach($allowed_sections as $section){
    if(isset($_GET[$section])){
        $active_section = $section;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- htmlspecialchars() dans le title pour éviter XSS -->
    <title>Bienvenue <?php echo htmlspecialchars($username); ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- CSS externe -->
    <link rel="stylesheet" href="../style.css">

    <style>
        body { overflow-x: hidden; }
        .profile_img {
            width: 90%;
            margin: auto;
            display: block;
            object-fit: contain;
        }
        .edit_image {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
    </style>
</head>

<body>
<div class="container-fluid p-0">

    <!-- =====================================================
         NAVBAR PRINCIPALE
    ====================================================== -->
    <nav class="navbar navbar-expand-lg navbar-light"
         style="background-color:#fff; border-bottom:2px solid #f2a7b8;">
        <div class="container-fluid">
            <img src="../images/logo.png" alt="Logo" class="logo">
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="../index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../display_all.php">Produits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Mon Compte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../cart.php">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <sup><?php cart_item(); ?></sup>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            Total: <?php total_cart_price(); ?> MAD
                        </a>
                    </li>
                </ul>

                <form class="d-flex" action="../search_product.php" method="get">
                    <input class="form-control me-2" type="search"
                           placeholder="Rechercher" aria-label="Search"
                           name="search_data">
                    <input type="submit" value="Chercher" class="btn"
                           style="border:1.5px solid #f2a7b8; color:#f2a7b8; background:#fff;"
                           name="search_data_product">
                </form>
            </div>
        </div>
    </nav>

    <!-- Appel de la fonction panier -->
    <?php cart(); ?>

    <!-- =====================================================
         NAVBAR SECONDAIRE — Welcome + Login/Logout
    ====================================================== -->
    <nav class="navbar navbar-expand-lg" style="background-color:#f5ede0;">
        <ul class="navbar-nav me-auto">
            <!-- Affichage sécurisé du nom avec htmlspecialchars() -->
            <li class="nav-item">
                <a class="nav-link text-dark" href="#">
                    Bienvenue <?php echo htmlspecialchars($username); ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="logout.php">Déconnexion</a>
            </li>
        </ul>
    </nav>

    <!-- =====================================================
         EN-TÊTE DU SITE
    ====================================================== -->
    <div style="background-color:#fff;">
        <h3 class="text-center">SUGAR Store</h3>
        <p class="text-center">Communications is at the heart of e-commerce and community</p>
    </div>

    <!-- =====================================================
         CORPS : SIDEBAR + CONTENU PRINCIPAL
    ====================================================== -->
    <div class="row">

        <!-- SIDEBAR GAUCHE -->
        <div class="col-md-2">
            <ul class="navbar-nav text-center" style="height:100vh; background-color:#fce4ec;">

                <li class="nav-item" style="background-color:#f2a7b8;">
                    <a class="nav-link text-white" href="#">
                        <h4>Votre profil</h4>
                    </a>
                </li>

                <!-- Photo de profil — variable claire, sans confusion -->
                <li class="nav-item">
                    <img src="./user_images/<?php echo $user_image; ?>"
                         class="profile_img my-4"
                         alt="Photo de profil de <?php echo htmlspecialchars($username); ?>">
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark" href="profile.php">
                        Commandes en attente
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="profile.php?edit_account">
                        Modifier le compte
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="profile.php?my_orders">
                        Mes commandes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="profile.php?delete_account">
                        Supprimer le compte
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color:#f2a7b8;" href="logout.php">
                        Déconnexion
                    </a>
                </li>
            </ul>
        </div>

        <!-- ZONE PRINCIPALE DROITE -->
        <div class="col-md-10 text-center">
            <?php

            // Affichage des commandes en attente (toujours visible)
            get_user_order_details();

            // Chargement de la section selon l'URL (liste blanche sécurisée)
            //$active_section ne peut contenir que les valeurs validées
            //On inclut le fichier PHP correspondant dans la zone principale

            if($active_section === 'edit_account'){
                include('edit_account.php');
            }
            if($active_section === 'my_orders'){
                include('user_orders.php');
            }
            if($active_section === 'delete_account'){
                include('delete_account.php');
            }
            ?>
        </div>

    </div><!-- fin .row -->

    <!-- FOOTER -->
    <?php include("../includes/footer.php"); ?>

</div><!-- fin .container-fluid -->

<!-- Bootstrap JS -->
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>