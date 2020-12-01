<?php
$login = str_replace(' ', '', htmlspecialchars($_POST["Login"]));
if (!empty($login)){

    $exist = false;

    include "../Utilisateurs.inc.php";

    if(!empty($Utilisateurs)) {
        foreach ($Utilisateurs as $user){
            if ($user['Login'] == $login){
                $exist = true;
                break;   //Utilisateur existant
            }
        }
    }
    echo $exist;
}
echo false;
?>