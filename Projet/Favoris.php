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

    include 'Donnees.inc.php';
    include 'Favoris.inc.php';

    session_start();

    if(isset($_GET['Deconnexion']) && $_GET['Deconnexion'] == true)
        unset($_SESSION['Login']);

    $_SESSION['CheminAcces'] = "Favoris";

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Cocktails Favoris</title>
        <meta charset="utf-8" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="script/Favoris.js"></script>
    </head>

    <body>

        <h1><a href="Accueil.php">Les recettes de Mamille</a></h1>

        <header>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="Favoris.php">Favoris</a></li>
                <?php if(!isset($_SESSION['Login']) || $_SESSION['Login'] === "") { ?>
                    <li><a href="Connexion.php">Se connecter</a></li>
                    <li><a href="Inscription.php">S'inscrire</a></li>
                <?php }
                if(isset($_SESSION['Login']) && $_SESSION['Login'] !== "") { ?>
                    <li>Mon compte</li> <!-- Dans le si connecter -->
                    <li><a href="<?php echo $_SERVER['PHP_SELF']."?Deconnexion=true"; ?>">Se déconnecter</a></li>
                <?php } ?>
            </ul>
        </header>

        <div id="div_fav">
        </div>

    </body>

</html>
