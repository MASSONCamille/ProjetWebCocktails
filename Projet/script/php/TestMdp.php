<?php
if (session_status() != PHP_SESSION_ACTIVE) session_start();

if (!empty($_POST["mdp"]) && !empty($_SESSION["Login"])){

    include "../../donnees/Utilisateurs.inc.php";

    if(!empty($Utilisateurs)) {
        foreach ($Utilisateurs as $user){
            if ($user['Login'] == $_SESSION["Login"] && $user["Mdp"] == $_POST["mdp"]){
                echo true;
                break;
            }
        }
    }
}