<?php

function RWFav($Favoris){
    if (dirname($_SERVER['PHP_SELF']) == '/ProjetCocktails/Projet/script') $path = '../';
    else $path  = '';

    $buffer = "<?php\n\$Favoris = array(\n";
    foreach ($Favoris as $x => $fav) {
        $buffer .= "\t'".$x."' =>array(\n";
        foreach ($fav as $y => $item) {
            $buffer .= "\t\t".$y." => ".$item.",\n";
        }
        $buffer .= "\t),\n";
    };
    $buffer .= ");\n?>";

    $filefav = fopen($path.'Favoris.inc.php', 'w');
    fwrite($filefav, $buffer);
    fclose($filefav);
};



function IsFav($idCocktail){
    if (dirname($_SERVER['PHP_SELF']) == '/ProjetCocktails/Projet/script') $path = '../';
    else $path  = '';

    if (session_status() != PHP_SESSION_ACTIVE) session_start();
    if(isset($_SESSION['Login'])){ // Utilisateur connecter
        include $path.'Favoris.inc.php';
        if (isset($Favoris)) return in_array($idCocktail, $Favoris[$_SESSION['Login']]);

    }else{ // Utilisateur non connecter
        if(isset($_SESSION['favoris'])) return in_array($idCocktail, $_SESSION['favoris']);
    }

    return false;
};



function addFav($idCocktail){
    if (dirname($_SERVER['PHP_SELF']) == '/ProjetCocktails/Projet/script') $path = '../';
    else $path  = '';

    if (session_status() != PHP_SESSION_ACTIVE) session_start();
    if (IsFav($idCocktail)) return false;
    if(isset($_SESSION['Login'])){ // Utilisateur connecter
        include $path.'Favoris.inc.php';
        if (!isset($Favoris)) $Favoris = array();
        if (empty($Favoris[$_SESSION['Login']])) $Favoris[$_SESSION['Login']] = array();
        $Favoris[$_SESSION['Login']][] = $idCocktail;
        RWFav($Favoris);
    }else{ // Utilisateur non connecter
        if(!isset($_SESSION['favoris'])) $_SESSION['favoris'] = array();
        $_SESSION['favoris'][] = $idCocktail;
    }
    return true;
};



function delFav($idCocktail){
    if (dirname($_SERVER['PHP_SELF']) == '/ProjetCocktails/Projet/script') $path = '../';
    else $path  = '';

    if (session_status() != PHP_SESSION_ACTIVE) session_start();
    if (!IsFav($idCocktail)) return false;
    if(isset($_SESSION['Login'])){ // Utilisateur connecter
        include $path.'Favoris.inc.php';
        if (!isset($Favoris)) return false;
        unset($Favoris[$_SESSION['Login']][array_search($idCocktail, $Favoris[$_SESSION['Login']])]);
        RWFav($Favoris);
    }else{ // Utilisateur non connecter
        if(!isset($_SESSION['favoris'])) $_SESSION['favoris'] = array();
        unset($_SESSION['favoris'][array_search($idCocktail, $_SESSION['favoris'])]);
    }
    return true;
};

function TransfertFav($ListId){
    if (dirname($_SERVER['PHP_SELF']) == '/ProjetCocktails/Projet/script') $path = '../';
    else $path  = '';

    if (session_status() != PHP_SESSION_ACTIVE) session_start();

    if(isset($_SESSION['Login'])){
        include $path.'Favoris.inc.php';

        if (!isset($Favoris)) $Favoris = array();
        if (empty($Favoris[$_SESSION['Login']])) $Favoris[$_SESSION['Login']] = array();

        foreach ($ListId as $idFav){
            if (!in_array($idFav, $Favoris[$_SESSION['Login']]))
                $Favoris[$_SESSION['Login']][] = $idFav;
        }

        RWFav($Favoris);
        return true;
    }
    return false;
};

function modFav($idCocktail){
    if (IsFav($idCocktail)) {
        delFav($idCocktail);
        return "del";
    }else {
        addFav($idCocktail);
        return "add";
    }
}

        // en cas d'appel avec un JQuery
if (!empty($_POST["mode"])){
    if ($_POST["mode"] == "del") echo delFav($_POST["id"]);
    else if($_POST["mode"] == "add") echo addFav($_POST["id"]);
    else if($_POST["mode"] == "mod") echo modFav($_POST["id"]);
    else if($_POST["mode"] == "test") echo IsFav($_POST["id"]);
    else echo "Error mode";
}