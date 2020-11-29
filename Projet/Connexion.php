<?php
session_start();

if (isset($_POST["submit"])){
    echo $_POST["Login"]." - ".$_POST["Mdp"]; // debuger a supp !!!

    $login = str_replace(' ', '', htmlspecialchars($_POST["Login"]));
    $mdp = str_replace(' ', '', htmlspecialchars($_POST["Mdp"]));

    $error = array();

    if(empty($login)) $error["Login"] = "Doit etre remplie";
    if(empty($mdp)) $error["Mdp"] = "Doit etre remplie";

    include "Utilisateurs.inc.php";

    if(!empty($Utilisateurs ) && empty($error)) {
        foreach ($Utilisateurs as $user){
            if ($user['Login'] = $login){
                $flag = true;               //Utilisateur existant
                if ($user['Mdp'] != $mdp) $error["Mdp"] = "Mot de passe incorrect";
                break;
            }
        }
        if (empty($flag)) $error["Login"] = "Login inconnu";
    }elseif (!empty($error)) $error["Connexion"] = "Verification impossible";

    if(empty($error)){

    }




}
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Connexion</title>
        <meta charset="utf-8" />
    </head>

    <body> <!-- a voir la gestion des erreurs -->

        <h1>Connexion</h1>

        <header>
            <ul>
                <li><a href="Favoris.php">Favoris</a></li>
                <li>Se connecter</li> <!-- Devine -->
                <li><a href="Inscription.php">S'inscrire</a></li>
                <!-- Si connecter afficher lien vers profil et déconnection -->
                <li>Mon compte</li> <!-- Dans le si connecter -->
                <li>Se déconnecter</li> <!-- Dans le si connecter -->
            </ul>
        </header>

        <form action="Connexion.php" method="post">
            <table>
                <tr>
                    <td><label for="Login">Login : </label></td>
                    <td><input type="text" name="Login" id="Login" value="<?php if (isset($_POST['submit'])) echo $login; ?>"</td>
                </tr>
                <?php if(!empty($error["Login"])) {?>
                <tr><td><?php echo $error["Login"];?></td></tr>
                <?php }?>

                <tr>
                    <td><label for="Mdp">Mot de passe : </label></td>
                    <td><input type="password" name="Mdp" id="Mdp" value="<?php if (isset($_POST['submit'])) echo $mdp; ?>"></td>
                    <td><button disabled><img id="viewpw" src="images/eye.png" alt="afficher mot de passe" width="30px"></button></td>
                </tr>
                <?php if(!empty($error["Mdp"])) {?>
                    <tr><td><?php echo $error["Mdp"];?></td></tr>
                <?php }?>

                <tr>
                    <td colspan="3"><input type="submit" value="Valider" name="submit"></td>
                </tr>
                <?php if(!empty($error["Connexion"])) {?>
                    <tr><td><?php echo $error["Connexion"];?></td></tr>
                <?php }?>
            </table>
        </form>

        <div>
            <a href="Inscription.php">Pas encore inscrit ?</a><br>
            <a href="">Mot de passe oublié ?</a>
        </div>
    </body>

</html>
