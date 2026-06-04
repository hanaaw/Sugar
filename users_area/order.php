<?php  
include('../includes/connect.php');
include('../functions/common_function.php');

if(isset($_GET['user_id'])){
    $user_id=$_GET['user_id'];
}


// Calcul du prix total

$get_ip_address=getIPAddress(); //Récupère l'IP du visiteur, initialise le total à 0
$total_price=0;


$cart_query_price="Select * from `cart_details` where ip_address='$get_ip_address'";
$result_cart_price=mysqli_query($con,$cart_query_price);//Récupère tous les articles du panier liés à cette IP

$invoice_number=mt_rand(); //mt_rand() génère un numéro de facture aléatoire
$status='pending'; //$status initialisé à 'pending'
$count_products=mysqli_num_rows($result_cart_price); //$count_products = nombre total d'articles dans le panier


while($row_price=mysqli_fetch_array($result_cart_price)){ //pour chaque article du panier, on cherche son prix dans la table products
    $product_id=$row_price['product_id'];
    $select_product="Select * from `products` where product_id=$product_id";
    $run_price=mysqli_query($con,$select_product);
    while($row_product_price=mysqli_fetch_array($run_price)){
        $product_price=array($row_product_price['product_price']); //crée un tableau d'un seul élément
        $product_values=array_sum($product_price); //additionne ce tableau
        $total_price+=$product_values; //accumule le total
    }
}


// Calcul de la quantité et du sous-total
$get_cart="select * from `cart_details`";
$run_cart=mysqli_query($con,$get_cart);
$get_item_quantity=mysqli_fetch_array($run_cart);
$quantity=$get_item_quantity['quantity'];

if($quantity==0){
    $quantity=1;
    $subtotal=$total_price; // Si quantité = 0 → on force à 1 et le sous-total = prix total
}else{
    $quantity=$quantity;
    $subtotal=$total_price*$quantity; //Sinon → sous-total = prix × quantité
}

$insert_orders="Insert into `user_orders` (user_id,amount_due,invoice_number,total_products,order_date,order_status) values ($user_id,$subtotal,$invoice_number,$count_products,NOW(),'$status')";
$result_query=mysqli_query($con,$insert_orders); //Insère la commande globale avec le montant total                                                         //NOW() = fonction MySQL qui insère la date et heure actuelles
if($result_query){
    echo "<script>alert('Orders are submitted successfully')</script>";
    echo "<script>window.open('profile.php','_self')</script>";
}

// orders pending
$insert_pending_orders="Insert into `orders_pending` (user_id,invoice_number,product_id,quantity,order_status) values ($user_id,$invoice_number,$product_id,$quantity,'$status')";
$result_pending_orders=mysqli_query($con,$insert_pending_orders); //Insère le détail de la commande dans une table séparée pour le suivi admin


// delete items from cart
$empty_cart="Delete from `cart_details` where ip_address='$get_ip_address'";
$result_delete=mysqli_query($con,$empty_cart); //Vide le panier de l'utilisateur après la commande

?>