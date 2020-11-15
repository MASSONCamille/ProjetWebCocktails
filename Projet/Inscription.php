<?php

    $Submitted = false;
    $LoginBon = true;
    $MdpBon = true;
    $ConfMdpBon = true;
    $SexeBon = true;
    $NomBon = true;
    $PrenomBon = true;
    $NaissanceBon = true;
    $MailBon = true;

    if(isset($_POST['submit'])) {
        $Submitted = true;

        if(isset($_POST["login"])) { //Vérifier si existe déjà
            $Login = $_POST["login"];
            if(strlen(str_replace(' ', '', $Login)) < 3)
                $LoginBon = false;
        } else
            $LoginBon = false;

        if(isset($_POST["mdp"])) {
            $Mdp = $_POST["mdp"];
            if(strlen(str_replace(' ', '', $Mdp)) < 3)
                $MdpBon = false;
        } else
            $MdpBon = false;

        if(isset($_POST["confMdp"])) {
            $ConfMdp = $_POST["confMdp"];
            if($ConfMdp != $Mdp)
                $ConfMdpBon = false;
        } else
            $ConfMdpBon = false;

        /*if(isset($_POST["sexe"])) {
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
            $nomBon = false;*/
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
                <li><a href="Inscription.php">S'inscrire</a></li>
                <!-- Si connecter afficher lien vers profil et déconnection -->
                <li>Mon compte</li> <!-- Dans le si connecter -->
                <li>Se déconnecter</li> <!-- Dans le si connecter -->
            </ul>
        </header>

        <?php
            if($Submitted && $LoginBon && $MdpBon && $ConfMdpBon) { 
                $_SESSION['Login'] = $Login ?>
                <p>Vous êtes maintenant inscrit!</p>
                <a href="Accueil.php">Retournez à l'accueil</a>
        <?php } else {
        ?>

        <h2>Inscription</h2>

        <form method="post" action="#">
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

        <?php }
        ?>

    </body>

</html>