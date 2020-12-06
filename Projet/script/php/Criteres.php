<?php
    include '../../donnees/Donnees.inc.php';

    $DebutRecherche = strtolower(trim($_GET['DebutRecherche']));

    $Ingredients = array();
    foreach($Hierarchie as $NomIngredient => $DetailsIngredient) {
        if(strpos(strtolower(trim($NomIngredient)), $DebutRecherche) === 0)
            array_push($Ingredients, $NomIngredient);
    }

    echo json_encode($Ingredients);

?>