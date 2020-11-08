<?php
function RWFav($Favoris){

    $buffer = "<?php\n\$Favoris = array(\n";
    foreach ($Favoris as $x => $fav) {
        $buffer .= "\t'".$x."' =>array(\n";
        foreach ($fav as $y => $item) {
            $buffer .= "\t\t".$y." => ".$item.",\n";
        }
        $buffer .= "\t),\n";
    };
    $buffer .= ");\n?>";

    $filefav = fopen('Favoris.inc.php', 'w');
    fwrite($filefav, $buffer);
    fclose($filefav);
};



function IsFav($idCocktail){
    if(isset($_SESSION['login'])){ // Utilisateur connecter
        include "Favoris.inc.php";
        if (isset($Favoris)) return in_array($idCocktail, $Favoris[$_SESSION['login']]);
        return false;

    }else{ // Utilisateur non connecter
        if(!isset($_SESSION['favoris'])) return false;
        return in_array($idCocktail, $_SESSION['favoris']);
    }
};



function addFav($idCocktail){
    if(isset($_SESSION['login'])){ // Utilisateur connecter
        include "Favoris.inc.php";
        if (isset($Favoris)) {
            if(in_array($idCocktail, $Favoris[$_SESSION['login']])) return false;
            $Favoris[$_SESSION['login']][] = $idCocktail;
            RWFav($Favoris);
            return true;
        }
    }else{ // Utilisateur non connecter
        if(!isset($_SESSION['favoris'])) $_SESSION['favoris'] = array();
        if(in_array($idCocktail, $_SESSION['favoris'])) return false;
        $_SESSION['favoris'][] = $idCocktail;
        return true;
    }
};



function delFav($idCocktail){
    if(isset($_SESSION['login'])){ // Utilisateur connecter
        include "Favoris.inc.php";
        if (isset($Favoris)) {
            if(!in_array($idCocktail, $Favoris[$_SESSION['login']])) return false;
            unset($Favoris[$_SESSION['login']][array_search($idCocktail, $Favoris[$_SESSION['login']])]);
            RWFav($Favoris);
            return true;
        }
    }else{ // Utilisateur non connecter
        if(!isset($_SESSION['favoris'])) $_SESSION['favoris'] = array();
        if(!in_array($idCocktail, $_SESSION['favoris'])) return false;
        unset($_SESSION['favoris'][array_search($idCocktail, $_SESSION['favoris'])]);
        return true;
    }
};
?>