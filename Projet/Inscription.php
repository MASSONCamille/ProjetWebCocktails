<?php

    $submitted = false;
    $loginBon = true;
    $mdpBon = true;
    $confMdpBon = true;
    $sexeBon = true;
    $nomBon = true;
    $prenomBon = true;
    $naissanceBon = true;
    $mailBon = true;

    if(isset($_POST['submit'])) {
        $submitted = true;

        if(isset($_POST["login"])) { //Vérifier si existe déjà
            $login = $_POST["login"];
            if(strlen(str_replace(' ', '', $login)) < 2)
                $loginBon = false;
        } else
            $loginBon = false;

        if(isset($_POST["confMdp"])) {
            $confMdp = $_POST["confMdp"];
            if(strlen(str_replace(' ', '', $confMdp)) < 2)
                $confMdpBon = false;
        } else
            $confMdpBon = false;

        if(isset($_POST["mdp"])) {
            $mdp = $_POST["mdp"];
            if(strlen(str_replace(' ', '', $mdp)) < 2)
                $mdpBon = false;
        } else
            $mdpBon = false;

        if(isset($_POST["sexe"])) {
            $sexe = $_POST["sexe"];
            if($sexe != 'f' && $sexe != 'h')
                $sexeBon = false;
        } else
            $sexeBon = false;

        if(isset($_POST["nom"])) {
            $nom = $_POST["nom"];
            if(strlen(str_replace(' ', '', $nom)) < 2)
                $nomBon = false;
        } else
            $nomBon = false;
    }

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Inscription</title>
        <meta charset="utf-8" />
    </head>

    <body> <!-- a voir la gestion des erreurs -->

        <h1><a href="Accueil.php">Les recettes de Mamille</a></h1>

        <header>
            <ul>
                <li><a href="Favoris.php">Favoris</a></li>
                <li>Se connecter</li> <!-- Devine -->
                <li>S'inscrire</li>
                <!-- Si connecter afficher lien vers profil et déconnection -->
                <li>Mon compte</li> <!-- Dans le si connecter -->
                <li>Se déconnecter</li> <!-- Dans le si connecter -->
            </ul>
        </header>

        <h2>Inscription</h2>

        <form method="post" action="">
        <table>
            <tr>
                <td>Login :</td>
                <td><input type="text" name="login" required="required" /></td>
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td><input type="password" name="mdp" required="required" /></td>
            </tr>
            <tr>
                <td>Confirmation du mot de passe :</td>
                <td><input type="password" name="confMdp" required="required" /></td>
            </tr>
            <tr>
                <td>Nom :</td>
                <td><input type="text" name="nom" /></td>
            </tr>
            <tr>
                <td>Prénom :</td>
                <td><input type="text" name="prenom" /></td>
            </tr>
            <tr>
                <td>Sexe :</td>
                <td>
                    <input type="radio" name="sexe" value="f"/> Femme
                    <input type="radio" name="sexe" value="h"/> Homme
                </td>
            </tr>
            <tr>
                <td>Date de naissance :</td>
                <td><input type="date" name="naissance" /></td>
            </tr>
            <tr>
                <td>Adresse électronique :</td>
                <td><input type="email" name="adElec" /></td>
            </tr>
            <tr>
                <td>Adresse postale :</td>
                <td><input type="text" name="adPostale" /></td>
            </tr>
            <tr>
                <td>Code postal :</td>
                <td><input type="text" name="codePostale" /></td>
            </tr>
            <tr>
                <td>Ville :</td>
                <td><input type="text" name="ville" /></td>
            </tr>
            <tr>
                <td>Numéro de téléphone :</td>
                <td><input type="tel" name="numero" /></td>
            </tr>
        </table>
        <input type="submit" value="S'inscrire" name="submit" />

    </body>

</html>