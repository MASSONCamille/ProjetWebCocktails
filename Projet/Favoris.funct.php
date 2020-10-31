<?php
function RWFav($Favoris){
    $ffav = fopen('Favoris.inc.php', 'w');

    $buffer = "<?php\n\$Favoris = array(\n";
    foreach ($Favoris as $x => $fav) {
        $buffer .= "\t'".$x."' =>array(\n";
        foreach ($fav as $y => $item) {
            $buffer .= "\t\t".$y." => ".$item.",\n";
        }
        $buffer .= "\t),\n";
    };
    $buffer .= ");\n?>";

    fwrite($ffav, $buffer);

    fclose($ffav);
}



function IsFav($idCocktail, $Favoris = null){ // si l'utilisateur est connecter passer $Favoris en argument
    if($_SESSION['login'] && $Favoris != null){ // Utilisateur connecter
        foreach ($Favoris[$_SESSION['login']] as $idRec)
        if ($idCocktail == $idRec) return true;
        return false;

    }else{ // Utilisateur non connecter

    }
};



function addFav($idCocktail, $Favoris = null){ // si l'utilisateur est connecter passer $Favoris en argument
    if($_SESSION['login'] && $Favoris != null){ // Utilisateur connecter
        $Favoris[$_SESSION['login']][] = $idCocktail;
        RWFav($Favoris);
    }else{ // Utilisateur non connecter

    }
};



function delFav($idCocktail, $Favoris = null){ // si l'utilisateur est connecter passer $Favoris en argument
    if($_SESSION['login'] && $Favoris != null){ // Utilisateur connecter
        $key = array_search($idCocktail, $Favoris[$_SESSION['login']]);
        if ($idCocktail == $Favoris[$_SESSION['login']][$key]) {
            unset($Favoris[$_SESSION['login']][$key]);
            RWFav($Favoris);
        }
    }else{ // Utilisateur non connecter

    }
};
?>