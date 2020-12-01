<?php
$login = str_replace(' ', '', htmlspecialchars($_POST["Login"]));
$color = "white";

if (!empty($login)){

    $color = "#ec0000";

    include "../Utilisateurs.inc.php";

    if(!empty($Utilisateurs)) {
        foreach ($Utilisateurs as $user){
            if ($user['Login'] == $login){
                $color = "#439c43";
                break;                     //Utilisateur existant
            }
        }
    }
}
echo $color;
?>