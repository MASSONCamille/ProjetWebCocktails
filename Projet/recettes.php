<?php
include 'Donnees.inc.php';

if(isset($_GET['Recette'])){
    $id = $_GET['Recette'];
}else{
    $id = 0;
}

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Recettes Cocktails</title>
    </head>
    <body>

    <header> <!-- ou nav -->
        <ul>
            <li>Favoris</li> <!-- Lien vers les favoris -->
            <li>Se connecter</li> <!-- Devine -->
            <li>S'inscrire</li> <!-- Même page que la connection -->
            <!-- Si connecter afficher lien vers profil et déconnection -->
            <li>Mon compte</li> <!-- Dans le si connecter -->
            <li>Se déconnecter</li> <!-- Dans le si connecter -->
        </ul>
    </header>

    <?php
    if(array_key_exists($id, $Recettes)){ ?>
    <h1><?= $Recettes[$id]['titre']?></h1>
    <ul>
        <?php foreach(explode("|", $Recettes[$id]['ingredients']) as $ing){ ?>
        <li><?=$ing?></li>
        <?php } ?>
    </ul>
    <p><?=$Recettes[$id]['preparation']?></p>
    <ul>
        <?php foreach($Recettes[$id]['index'] as $index){?>
        <li><?=$index?></li>
        <?php } ?>
    </ul>
    <button type="button">Ajouter aux Favoris</button>
    <?php } ?>
    </body>
</html>