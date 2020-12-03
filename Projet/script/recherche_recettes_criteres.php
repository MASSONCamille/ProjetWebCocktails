<?php 
    include '../Donnees.inc.php';
    include '../Recherche.funct.php';

    $AvecBon = isset($_POST['IngredientsAvec']);
    $SansBon = isset($_POST['IngredientsSans']);

    if($AvecBon) {
        $IngredientsAvec = array_map('ucfirst', $_POST['IngredientsAvec']);
        $IngredientsAvec = RechercheIngredients($IngredientsAvec[0], $IngredientsAvec, $Hierarchie);
        $RecettesAvec = RechercheRecettes($IngredientsAvec, $Recettes);
        foreach($RecettesAvec as $Index => $NomRecette) {
            $Score = 0;
            foreach($IngredientsAvec as $Ingredient) {
                if(in_array($Ingredient, $Recettes[$Index]['index']))
                    $Score++;
            }
            $RecettesAvec[$Index] = array(
                'Nom' => $NomRecette,
                'Score' => $Score,
            );
        }
        if(!$SansBon)
            echo json_encode($RecettesAvec);
    }
    if($SansBon) {
       $IngredientsSans = array_map('ucfirst', $_POST['IngredientsSans']);
       $IngredientsSansTemp = $IngredientsSans;
       foreach($IngredientsSans as $IngredientSans) {
            $IngredientsSansTemp = RechercheIngredients($IngredientSans, $IngredientsSansTemp, $Hierarchie);
       }
       $IngredientsSans = array_unique($IngredientsSansTemp);
       $NbIngredientsSans = count($IngredientsSans);
       $RecettesSans = RechercheRecettes($IngredientsSans, $Recettes);
       $RecettesSansInverse = array();
        foreach($Recettes as $CleRecette => $Proprietes) {
            if(!in_array($Proprietes['titre'], $RecettesSans)) {
                $RecettesSansInverse[$CleRecette] = array(
                    'Nom' => $Proprietes['titre'],
                    'Score' => $NbIngredientsSans,
                );
            }
        }
        if(!$AvecBon)
            echo json_encode($RecettesSansInverse);
    }
    if(!$AvecBon && !$SansBon) 
        echo -1;
    else if($AvecBon && $SansBon) {
        $RecettesRes = array();
        foreach($RecettesAvec as $IndexAvec => $RecetteAvecDetails) {
            $ScoreRes = $RecetteAvecDetails['Score'];
            if(in_array($RecetteAvecDetails['Nom'], $RecettesSans)) {
                $ScoreDif = 0;
                foreach($IngredientsSans as $Ingredient) {
                    if(in_array($Ingredient, $Recettes[$IndexAvec]['index']))
                        $ScoreDif--;
                }
                $ScoreRes += $ScoreDif;
            }
            if(in_array($IndexAvec, $RecettesSansInverse))
                $ScoreRes += $RecettesSansInverse[$IndexAvec]['Score'];
            if($ScoreRes >= 0) {
                $RecettesRes[$IndexAvec] = array (
                    'Nom' => $RecetteAvecDetails['Nom'],
                    'Score' => $ScoreRes,
                );
            }
        }
        foreach($RecettesSansInverse as $IndexSans => $RecetteSansDetails) {
            if(!in_array($IndexSans, $RecettesAvec)) {
                $RecettesRes[$IndexSans] = array (
                    'Nom' => $RecetteSansDetails['Nom'],
                    'Score' => $RecetteSansDetails['Score'],
                );
            }
        }
        echo json_encode($RecettesRes);
    }
?>