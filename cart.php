<?php
include('includes/connect.php');
include('functions/common_function.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Website - Cart Details</title>
    <!-- bootstrap CSS File -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <!-- External CSS file -->
    <link rel="stylesheet" href="style.css?vb=1">
</head>
<body>
    <div class="container-fluid p-0">


        <?php
        cart(); //Appelle la fonction cart() de common_function.php
              //→ Affiche la navbar principale avec le logo, les liens et l'icône panier
        ?>
        <nav class="navbar navbar-expand-lg" style="background-color:#f5ede0;">
            <ul class="navbar-nav me-auto">


                <?php
                if(!isset($_SESSION['username'])){ // Si pas connecté → affiche "Welcome Guest" + lien Login
                    echo "<li class='nav-item'>
                    <a class='nav-link text-dark' href='#'>Welcome Guest</a>
                    </li>";
                } else { // Si connecté → affiche le username + lien Logout
                    echo "<li class='nav-item'>
                    <a class='nav-link text-dark' href='#'>Welcome ".$_SESSION['username']."</a>
                    </li>";
                } 
                if(!isset($_SESSION['username'])){
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

        <div style="background-color:#fff;">
            <h3 class="text-center">SUGAR Store</h3>
            <p class="text-center">Shop the Best Online</p>
        </div>

        <div class="container">
            <div class="row">
                <form action="" method="post">  


                            <?php
                            global $con;
                            $get_ip_add = getIPAddress(); //Récupère l'adresse IP du visiteur via la fonction de common_function.php
                             // Sert à identifier le panier car les visiteurs non connectés n'ont pas de user_id
                            $total_price = 0;

                            $cart_query = "
                                SELECT c.product_id, p.product_price, p.product_title, p.product_image1, c.quantity
                                FROM cart_details c 
                                JOIN products p ON c.product_id = p.product_id
                                WHERE c.ip_address = '$get_ip_add'
                            "; //table du panier avec alias c
                            // relie la table produits avec alias p
                            // on sélectionne des colonnes des deux tables
                           //filtre uniquement les articles de CE visiteur



                            $result = mysqli_query($con, $cart_query); //Exécute la requête et stocke le résultat dans $result
                            $result_count=mysqli_num_rows($result); //Compte le nombre de lignes retournées = nombre d'articles dans le panier

                            if($result_count>0){ //Si au moins un article dans le panier → afficher le tableau HTML
                                echo "                    <table class='table table-border text-center'>
                                <thead>
                                    <tr>
                                        <th>Product Title</th>
                                        <th>Product Image</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Remove</th>
                                        <th colspan='2'>Operations</th>
                                    </tr>
                                </thead>
                                <tbody>";
                            while ($row = mysqli_fetch_array($result)) { //Boucle sur chaque article du panier un par un

                                $product_id = $row['product_id']; //Extrait chaque colonne de la ligne courante dans des variables
                                $price_table = $row['product_price'];
                                $product_title = $row['product_title'];
                                $product_image1 = $row['product_image1'];
                                $quantity = $row['quantity'];
                                $total_price += $price_table * $quantity; //+= → ajoute au total : prix unitaire × quantité de ce produit
                            ?>
                            <tr>
                                <td><?php echo $product_title; ?></td>
                                <td><img src="./admin_area/product_images/<?php echo $product_image1; ?>" alt="" class="cart_img"></td>
                                <td><input type="number" name="qty[<?php echo $product_id; //si le product_id est 3
                                         //Crée un tableau associatif en PHP : $_POST['qty'][3] = nouvelle_quantité
                                         //Chaque produit a son propre champ de quantité identifié par son ID ?>]" 
                                value="<?php echo $quantity; ?>" class="form-input w-50"></td>
                                <td><?php echo $price_table * $quantity; ?> MAD</td>
                                <td><input type="checkbox" name="remove[]" value="<?php echo $product_id; ?>"></td>
                                <td> <!--$_POST['remove'] contiendra les IDs de tous les produits cochés
                                                  Ex : si on coche produits 3 et 7 → $_POST['remove'] = [3, 7]-->
                                    <input style="background-color:#f2a7b8; color:white; border:none;" class="px-3 py-2 mx-3" value="Update Cart" name="update_cart" type="submit"/>
                                    <input style="background-color:#fff; color:#f2a7b8; border:1.5px solid #f2a7b8;" class="px-3 py-2 mx-3" type="submit" name="remove_cart" value="Remove Item">
                                </td>
                            </tr>
                            <?php
                            }}else{
                                echo "<h2 class='text-center text-danger'>Cart is empty</h2>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="d-flex mb-5">
                    <?php
                        if ($result_count > 0) { //Si panier non vide → affiche le total + boutons "Continuer" et "Checkout"
                            echo "<h4 class='px-3'>Subtotal : <strong style='color:#f2a7b8;'>" . $total_price . " MAD</strong></h4>
                            <a href='index.php'><button style='background-color:#f2a7b8; color:white; border:none;' class='px-3 py-2 mx-3' type='button'>Continue Shopping</button></a>
                         <button style='background-color:#fff; border:1.5px solid #f2a7b8;' class='px-3 py-2'><a href='./users_area/checkout.php' style='color:#f2a7b8;' class='text-decoration-none'>Checkout</a></button>";
                       
                         } else { // Si panier vide → affiche uniquement le bouton "Continuer les achats"
                            echo "<a href='index.php'><button style='background-color:#f2a7b8; color:white; border:none;' class='px-3 py-2 mx-3' type='button'>Continue Shopping</button></a>";
                        } 
                        //Deux boutons submit dans le même formulaire 
                         //PHP sait lequel a été cliqué via isset($_POST['update_cart']) ou isset($_POST['remove_cart'])

                        ?>
                    </div>
                </form>

                <?php
                if (isset($_POST['update_cart'])) {
                    foreach ($_POST['qty'] as $product_id => $quantity) { // Boucle sur chaque produit du tableau qty
                        $quantity = intval($quantity);  //intval() force la valeur à être un entier
                        //$product_id = la clé (ID du produit) 
                        //  $quantity = la valeur (nouvelle quantité saisie)

                        $update_cart = "UPDATE `cart_details` SET quantity=$quantity WHERE ip_address='$get_ip_add' AND product_id=$product_id";
                        mysqli_query($con, $update_cart); //Met à jour la quantité de CE produit pour CE visiteur (double condition WHERE)
                    }
                    echo "<script>window.open('cart.php','_self')</script>"; 
                }  //Recharge la page pour afficher les nouvelles quantités

                if (isset($_POST['remove_cart'])) {
                    foreach ($_POST['remove'] as $product_id) { //Boucle sur chaque product_id coché dans les cases à cocher
                        $delete_cart = "DELETE FROM `cart_details` WHERE ip_address='$get_ip_add' AND product_id=$product_id"; 
                        //Supprime CET article du panier de CE visiteur
                        mysqli_query($con, $delete_cart); 
                        
                    } //Recharge la page après suppression
                    echo "<script>window.open('cart.php','_self')</script>";
                }
                ?>
            </div>
        </div>
    </div>

<!-- Footer -->
<div style="background-color:#f2a7b8;" class="p-3 text-center">
    <p style="color:white;">All rights Reserved &copy; Designed BY Khouloud.Hanaa</p>
</div>

<!-- bootstrap js File -->
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>