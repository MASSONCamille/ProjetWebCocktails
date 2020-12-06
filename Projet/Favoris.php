<?php
    session_start();

    if(isset($_GET['Deconnexion']) && $_GET['Deconnexion'] == true) // Deconnexion
        unset($_SESSION['Login']);

    $_SESSION['CheminAcces'] = "Favoris";       // pour initialiser le fils d'Ariane dans Recette

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Cocktails Favoris</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="script/Favoris.js"></script>
    </head>

    <body>

        <header>
            <h1><a href="Accueil.php">Recettes de Cocktailes</a></h1>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="Favoris.php">Favoris</a></li>
                <?php if(!isset($_SESSION['Login']) || $_SESSION['Login'] === "") { ?>
                    <li><a href="Connexion.php">Se connecter</a></li>
                    <li><a href="Inscription.php">S'inscrire</a></li>
                <?php }
                if(isset($_SESSION['Login']) && $_SESSION['Login'] !== "") { ?>
                    <li><a href="Utilisateur.php">Mon compte</a></li>
                    <li><a href="<?php echo $_SERVER['PHP_SELF']."?Deconnexion=true"; ?>">Se déconnecter</a></li>
                <?php } ?>
            </ul>
        </header>

        <h2>Liste des Favoris</h2>

        <div id="div_fav"> <!-- Affichage de la liste des Favoris en dynamique (Remplie par le JQuery) -->
        </div>

    </body>

</html>
