<?php
include 'Donnees.inc.php';
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Recettes Cocktails</title>
    </head>
    <body>
    <?php $id =0;
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
        <button type="button">Ajouter aux Favoris</button><?php } ?>   
    </body>
</html>