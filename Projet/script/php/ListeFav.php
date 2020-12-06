<?php
include '../../donnees/Donnees.inc.php';
if (session_status() != PHP_SESSION_ACTIVE) session_start();

if (isset($_SESSION['Login'])){
    include '../../donnees/Favoris.inc.php';

    if (isset($Favoris[$_SESSION["Login"]])) $Fav = $Favoris[$_SESSION['Login']];
    else $Fav = array();

}else{
    if(isset($_SESSION['favoris'])) $Fav = $_SESSION['favoris'];
    else $Fav = array();
}

 if (empty($Fav)) {?>
    <p>Pas de favoris</p>
<?php }else{ ?>
    <table>
    <?php
        foreach ($Fav as $idFav => $idRec) {
            ?><tr><?php

            echo "<td><input type='image' alt='' src='images/cross.png' height='20' class='btnsupp' id='".$idRec."'></td>";
            echo "<td><a href='Recettes.php".'?Recette='.$idRec."'>".$Recettes[$idRec]["titre"]."</a></td>";

            ?></tr><?php
        }
    ?>
    </table>
<?php } ?>

