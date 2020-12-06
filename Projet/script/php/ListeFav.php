<?php
include '../../donnees/Donnees.inc.php';
if (session_status() != PHP_SESSION_ACTIVE) session_start();            // assure le session_start

if (isset($_SESSION['Login'])){                         // si l'utilisateur est connecter
    include '../../donnees/Favoris.inc.php';

    if (isset($Favoris[$_SESSION["Login"]])) $Fav = $Favoris[$_SESSION['Login']]; // initialisation de la variable $Fav
    else $Fav = array();

}else{                                                  // si l'utilisateur n'est pas connecter
    if(isset($_SESSION['favoris'])) $Fav = $_SESSION['favoris'];
    else $Fav = array();                                                          // initialisation de la variable $Fav
}

 if (empty($Fav)) {?>               <!-- si il n'y a pas de favoris -->
    <p>Pas de favoris</p>
<?php }else{ ?>
    <table>
    <?php
        foreach ($Fav as $idFav => $idRec) {       // creation d'une ligne pour chaque favori
            ?><tr><?php

            echo "<td><input type='image' alt='' src='../images/cross.png' height='20' class='btnsupp' id='".$idRec."'></td>";
            echo "<td><a href='Recettes.php".'?Recette='.$idRec."'>".$Recettes[$idRec]["titre"]."</a></td>";

            ?></tr><?php
        }
    ?>
    </table>
<?php } ?>

