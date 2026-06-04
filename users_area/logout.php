<?php

session_start(); // Démarre la session pour pouvoir y accéder
session_unset(); //Supprime toutes les variables stockées dans $_SESSION

session_destroy(); //Détruit complètement la session côté serveur
//session_unset()   → vide le contenu    (le sac est vide)
//session_destroy() → détruit la session (le sac est jeté)


echo "<script>window.open('../index.php','_self')</script>";
//Redirige vers la page d'accueil via JavaScript
?>