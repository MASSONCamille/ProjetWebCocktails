<?php

    //FONCTIONS DE RECHERCHE
    function RechercheIngredients($PositionRecherche, $IngredientsRecherche, $Hierarchie) { //FONCTION DE RECHERCHE DES INGREDIENTS
        foreach($Hierarchie[$PositionRecherche] as $NomSousCateg => $SousCateg) {
            if($NomSousCateg == 'sous-categorie') {
                foreach($SousCateg as $ElementSousCateg) {
                    if(!in_array($ElementSousCateg, $IngredientsRecherche)) {
                        array_push($IngredientsRecherche, $ElementSousCateg);
                        $IngredientsRecherche = RechercheIngredients($ElementSousCateg, $IngredientsRecherche, $Hierarchie);
                    }
                }
            }
        }
        return $IngredientsRecherche;
    }

    function RechercheRecettes($IngredientsRecherche, $Recettes) { //FONCTION DE RECHERCHE DES RECETTES
        $RecettesRecherche = array();
        foreach($Recettes as $CleRecette => $Proprietes) {
            foreach($Proprietes['index'] as $Ingredient) {
                if(in_array($Ingredient, $IngredientsRecherche)) {
                    //if(!in_array($Proprietes['titre'], $RecettesRecherche))
                        $RecettesRecherche[$CleRecette] = $Proprietes['titre'];
                }
            }
        }
        return $RecettesRecherche;
    }

?>