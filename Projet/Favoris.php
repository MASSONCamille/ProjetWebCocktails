<!--
Liste des Favoris pour un Utilisateur : (Camille MASSON)

Si conecter (via $_SESSION)
    |recuperer dans le fichier favori la liste correspondant a l'utilisateur
Sinon
    |Créer un cookie qui stock les données
   Fin Si
________________________________________________________________________________________________________________________

-->

<?php
session_start();
include 'Donnees.inc.php';

if (isset($_POST['login'])){
    $_SESSION['login'] = $_POST['login'];
}

if (isset($_SESSION['login'])){
    include 'Favoris.inc.php';
    include 'Favoris.funct.php';
}
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Cocktails Favoris</title>
        <meta charset="utf-8" />

    </head>

    <body>

        <header>
            <ul>
                <li>Favoris</li> <!-- Lien vers les favoris -->
                <li>Se connecter</li> <!-- Devine -->
                <li>S'inscrire</li> <!-- Même page que la connection -->
                <!-- Si connecter afficher lien vers profil et déconnection -->
                <li>Mon compte</li> <!-- Dans le si connecter -->
                <li>Se déconnecter</li> <!-- Dans le si connecter -->
            </ul>
        </header>

        <div>

            <?php if (isset($_SESSION['login'])){ ?> <!-- Si l'utilisateur est connecté -->

                <?php if (array_key_exists($_SESSION['login'], $Favoris)){ ?>

                    <?php
                    foreach ($Favoris[$_SESSION['login']] as $idFav => $idRec){
                        echo $idFav.' => '.$idRec.'<br/>';
                    }
                    ?>

                    <?php
                    if (IsFav(0)) echo "la recette 0 est favori"; // Test pour IsFav()
                    else echo "la recette 0 n'est pas favori";
                    ?>

                <?php }else{ ?>

                    <p>Utilisateur sans favoris</p>

                <?php } ?>


            <?php }else{ ?> <!-- Si l'utilisateur n'est pas connecté -->

                <p>Donné votre login :</p>
                <form action="Favoris.php" method="post">
                    <input type="text" name="login">
                    <input type="submit" value="Login Session">
                </form>

            <?php } ?>

        </div>

    </body>

</html>
