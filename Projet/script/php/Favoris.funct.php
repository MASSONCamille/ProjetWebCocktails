<?php
    /** Function qui reecri le fichier mémoire de favori */
function RWFav($Favoris){
    if (dirname($_SERVER['PHP_SELF']) == '/ProjetCocktails/Projet/script') $path = '../'; // Verification du lien
    else $path  = '';                                                                     // appel JQuery ou include

    $buffer = "<?php\n\$Favoris = array(\n";            // initialisation de du buffer a inscrire dans le fichier
    foreach ($Favoris as $x => $fav) {
        $buffer .= "\t'".$x."' =>array(\n";
        foreach ($fav as $y => $item) {
            $buffer .= "\t\t".$y." => ".$item.",\n";
        }
        $buffer .= "\t),\n";
    };
    $buffer .= ");\n?>";

    $filefav = fopen($path.'Favoris.inc.php', 'w');     // ouverture du fichier memoire de favoris
    fwrite($filefav, $buffer);
    fclose($filefav);
};


    /** Function de test pour savoir si un cocktail est favori  */
function IsFav($idCocktail){
    if (dirname($_SERVER['PHP_SELF']) == '/ProjetCocktails/Projet/script') $path = '../';   // idem ligne 4
    else $path  = '';

    if (session_status() != PHP_SESSION_ACTIVE) session_start();               // assure le session_start

    if(isset($_SESSION['Login'])){                                             // Utilisateur connecter
        include $path.'Favoris.inc.php';
        if (isset($Favoris)) return in_array($idCocktail, $Favoris[$_SESSION['Login']]);    // test varible du fichier

    }else{                                                                     // Utilisateur non connecter
        if(isset($_SESSION['favoris'])) return in_array($idCocktail, $_SESSION['favoris']); // test varible de session
    }

    return false;
};


    /** Function d'ajout en favori  */
function addFav($idCocktail){
    if (dirname($_SERVER['PHP_SELF']) == '/ProjetCocktails/Projet/script') $path = '../';   // idem ligne 4
    else $path  = '';

    if (session_status() != PHP_SESSION_ACTIVE) session_start();                // assure le session_start
    if (IsFav($idCocktail)) return false;

    if(isset($_SESSION['Login'])){                                              // Utilisateur connecter
        include $path.'Favoris.inc.php';
        if (!isset($Favoris)) $Favoris = array();
        if (empty($Favoris[$_SESSION['Login']])) $Favoris[$_SESSION['Login']] = array();

        $Favoris[$_SESSION['Login']][] = $idCocktail;                           // modif varible du fichier
        RWFav($Favoris);                                                        // reecri le fichier
    }else{                                                                      // Utilisateur non connecter
        if(!isset($_SESSION['favoris'])) $_SESSION['favoris'] = array();        // modif varible de session
        $_SESSION['favoris'][] = $idCocktail;
    }
    return true;
};


    /** Function de suppression d'un favori */
function delFav($idCocktail){
    if (dirname($_SERVER['PHP_SELF']) == '/ProjetCocktails/Projet/script') $path = '../';   // idem ligne 4
    else $path  = '';

    if (session_status() != PHP_SESSION_ACTIVE) session_start();                    // assure le session_start
    if (!IsFav($idCocktail)) return false;

    if(isset($_SESSION['Login'])){                                                  // Utilisateur connecter
        include $path.'Favoris.inc.php';
        if (!isset($Favoris)) return false;
        unset($Favoris[$_SESSION['Login']][array_search($idCocktail, $Favoris[$_SESSION['Login']])]);   // modif varible du fichier
        RWFav($Favoris);                                                                    // reecri le fichier
    }else{                                                                          // Utilisateur non connecter
        if(!isset($_SESSION['favoris'])) $_SESSION['favoris'] = array();                // modif varible de session
        unset($_SESSION['favoris'][array_search($idCocktail, $_SESSION['favoris'])]);
    }
    return true;
};


    /** Function qui recupere tout les favoris hors connexion pour les mettres dans le fichier mémoire */
function TransfertFav($ListId){
    if (dirname($_SERVER['PHP_SELF']) == '/ProjetCocktails/Projet/script') $path = '../';   // idem ligne 4
    else $path  = '';

    if (session_status() != PHP_SESSION_ACTIVE) session_start();            // assure le session_start

    if(isset($_SESSION['Login'])){
        include $path.'Favoris.inc.php';

        if (!isset($Favoris)) $Favoris = array();
        if (empty($Favoris[$_SESSION['Login']])) $Favoris[$_SESSION['Login']] = array();

        foreach ($ListId as $idFav){                                        // modif varible du fichier
            if (!in_array($idFav, $Favoris[$_SESSION['Login']]))
                $Favoris[$_SESSION['Login']][] = $idFav;
        }

        RWFav($Favoris);                                                    // reecri le fichier
        return true;
    }
    return false;
};


    /** Function de modification (Favori/pas Favori) d'un favori */
function modFav($idCocktail){
    if (IsFav($idCocktail)) {           // test si à supprimer ou a ajouter
        delFav($idCocktail);
        return "del";
    }else {
        addFav($idCocktail);
        return "add";
    }
}

        /** En cas d'appel avec un JQuery/Ajax */
if (!empty($_POST["mode"])){
    if ($_POST["mode"] == "del") echo delFav($_POST["id"]);
    else if($_POST["mode"] == "add") echo addFav($_POST["id"]);
    else if($_POST["mode"] == "mod") echo modFav($_POST["id"]);
    else if($_POST["mode"] == "test") echo IsFav($_POST["id"]);
    else echo "Error mode";                                         // NOTE: TransfertFav non utiliser pas le JQuery
}