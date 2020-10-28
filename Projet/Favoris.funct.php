<?php
function IsFav($idCocktail){
    if($_SESSION['login']){ // Utilisateur connecter
        include 'Favoris.inc.php';
        foreach ($Favoris[$_SESSION['login']] as $idRec){
            if ($idRec == $idCocktail) return true;
        }
        return false;
    }else{ // Utilisateur non connecter

    }
};


function addFav($idCocktail){
    if($_SESSION['login']){ // Utilisateur connecter
        include 'Favoris.inc.php';

    }else{ // Utilisateur non connecter

    }
};


function delFav($idCocktail){
    if($_SESSION['login']){ // Utilisateur connecter
        include 'Favoris.inc.php';

    }else{ // Utilisateur non connecter

    }
};
?>