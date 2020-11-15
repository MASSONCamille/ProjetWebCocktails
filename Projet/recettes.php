<?php
    include 'Donnees.inc.php';

    session_start();

    if(isset($_GET['Recette'])){
        $id = $_GET['Recette'];
    }else{
        $id = 0;
    }

    $CheminAcces = $_SESSION['CheminAcces'];
    $VientDeFavoris = false;
    if(is_string($CheminAcces))
        $VientDeFavoris = true;
?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="utf-8">
        <title>Recettes Cocktails</title>
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

        <nav>
            <h2>Navigation</h2>
            <p>
                <?php //Attention mettre une condition si on vient des favoris
                    if($VientDeFavoris) { ?>
                        <a href="Favoris.php">Retour aux favoris</a>
                    <?php } else {
                    foreach($CheminAcces as $Element) { ?>
                        <a href='<?php echo 'Accueil.php?Position='.$Element; ?>'><?php echo $Element; ?>/</a>
                <?php }
                    }
                ?>
            </p>
        </nav>

        <?php
        if(array_key_exists($id, $Recettes)){ ?>
        <h2><?= $Recettes[$id]['titre']?></h2>
        <h3>Ingrédients</h3>
        <ul>
            <?php foreach(explode("|", $Recettes[$id]['ingredients']) as $ing){ ?>
            <li><?=$ing?></li>
            <?php } ?>
        </ul>
        <h3>Description</h3>
        <p><?=$Recettes[$id]['preparation']?></p>
        <button type="button">Ajouter aux Favoris</button>
        <?php } ?>

    </body>
</html>