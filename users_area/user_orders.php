<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Orders</title>
</head>
<body>
    <?php


    $username = $_SESSION['username']; //Récupération de l'utilisateur
    $get_user = "Select * from `user_table` where username='$username'";
    $result = mysqli_query($con, $get_user);
    $row_fetch = mysqli_fetch_assoc($result); //Cherche l'utilisateur en BDD pour récupérer son user_id
    $user_id = $row_fetch['user_id']; 

    <h3 class="text-success">All my Orders</h3>
    <table class="table table-bordered mt-5">
        <thead class="bg-info">
            <tr>
                <th>Sl no</th>
                <th>Amount Due</th>
                <th>Total products</th>
                <th>Invoice number</th>
                <th>Date</th>
                <th>Complete/Incomplete</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody class="bg-secondary text-light">


            <?php

        //La boucle des commandes
            $get_order_details = "Select * from `user_orders` where user_id=$user_id";
            $result_orders = mysqli_query($con, $get_order_details);
            // Récupère toutes les commandes de cet utilisateur

            $number = 1; 
            while ($row_orders = mysqli_fetch_assoc($result_orders)) {
                $order_id = $row_orders['order_id'];
                $amount_due = $row_orders['amount_due'];
                $total_products = $row_orders['total_products'];
                $invoice_number = $row_orders['invoice_number'];
                $order_status = $row_orders['order_status'];
                if ($order_status == 'pending') {
                    $order_status = 'Incomplete';
                } else {
                    $order_status = 'Complete';
                } //Conversion du statut BDD en texte lisible pour l'affichage
                $order_date = $row_orders['order_date'];


                //Affiche les 6 premières colonnes de la ligne
                echo "<tr> 

                <td>$number</td>
                <td>$amount_due</td>
                <td>$total_products</td>
                <td>$invoice_number</td>
                <td>$order_date</td>
                <td>$order_status</td>";
                if ($order_status == 'Complete') {
                    echo "<td>Paid</td></tr>";
                } else {
                    echo "<td><a href='confirm_payment.php?order_id=$order_id' class='text-light'>Confirm</a></td></tr>";
                }
                //Commande complète → affiche "Paid"
                //Commande en attente → affiche un lien vers confirm_payment.php
                $number++;
            }
            ?>
        </tbody>
    </table>
</body>
</html>
