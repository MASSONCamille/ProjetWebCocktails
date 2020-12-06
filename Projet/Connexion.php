<?php
session_start();

if(isset($_GET['Deconnexion']) && $_GET['Deconnexion'] == true)     // Deconnexion
        unset($_SESSION['Login']);

if (isset($_POST["submit"])){       // si le formulaire a etait validé

    $login = str_replace(' ', '', htmlspecialchars($_POST["Login"])); // recuperation de login et mdp
    $mdp = str_replace(' ', '', htmlspecialchars($_POST["Mdp"]));

    $error = array(); // initialisation de la variable des erreur

    if(empty($login)) $error["Login"] = "Doit etre remplie";    // test si Login est vide
    if(empty($mdp)) $error["Mdp"] = "Doit etre remplie";        // test si mdp est vide

    include "Utilisateurs.inc.php";

    if(!empty($Utilisateurs) && empty($error)) {        // verification pour le login est le mdp
        foreach ($Utilisateurs as $user){
            if ($user['Login'] == $login){
                $flag = true;               //Utilisateur existant
                if ($user['Mdp'] != $mdp) $error["Mdp"] = "Mot de passe incorrect"; // test le mdp
                break;
            }
        }
        if (empty($flag)) $error["Login"] = "Login inconnu";
    }elseif (empty($error)) $error["Connexion"] = "Verification impossible";    // si dans l'include

    if(empty($error)){      // si le formulaire ne contient aucune erreurs
        $_SESSION['Login'] = $login;    // initialisation du Login en session
        if (!empty($_SESSION["favoris"])) { // recuperation des Favoris enregistrer en mode non-Connecter
            include 'script/Favoris.funct.php';
            TransfertFav($_SESSION["favoris"]);
            unset($_SESSION["favoris"]);
        }
        header('Location: Accueil.php'); // redirection vers l'acceuil
    }

}
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Connexion</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="script/verif_form.js"></script>
    </head>

    <body>

        <header>
            <h1><a href="Accueil.php">Recettes de Cocktailes</a></h1>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="Favoris.php">Favoris</a></li>
                <?php if(!isset($_SESSION['Login']) || $_SESSION['Login'] === "") { ?>
                    <li><a href="Connexion.php">Se connecter</a></li>
                    <li><a href="Inscription.php">S'inscrire</a></li>
                <?php }
                if(isset($_SESSION['Login']) && $_SESSION['Login'] !== "") { ?>
                    <li><a href="Utilisateur.php">Mon compte</a></li>
                    <li><a href="<?php echo $_SERVER['PHP_SELF']."?Deconnexion=true"; ?>">Se déconnecter</a></li>
                <?php } ?>
            </ul>
        </header>

        <h2>Connexion</h2>

        <div>
            <form action="Connexion.php" method="post">
                <table>
                    <tr>
                        <td><label for="Login">Login : </label></td>
                        <td><input type="text" name="Login" id="Login" required="required" value="<?php if (isset($_POST['submit'])) echo $login; ?>"</td>
                    </tr>
                    <?php if(!empty($error["Login"])) {?>
                    <tr><td><?php echo $error["Login"];?></td></tr>  <!-- affichage des erreurs -->
                    <?php }?>

                    <tr>
                        <td><label for="Mdp">Mot de passe : </label></td>
                        <td><input type="password" name="Mdp" id="Mdp" required="required" value="<?php if (isset($_POST['submit'])) echo $mdp; ?>"></td>
                        <td><img src="images/eye.png" id="viewpw" alt="afficher mot de passe" width="30px" border="1"></td>
                    </tr>
                    <?php if(!empty($error["Mdp"])) {?>
                        <tr><td><?php echo $error["Mdp"];?></td></tr>  <!-- affichage des erreurs -->
                    <?php }?>

                    <tr>
                        <td colspan="3"><input type="submit" value="Valider" name="submit"></td>
                    </tr>
                    <?php if(!empty($error["Connexion"])) {?>
                        <tr><td><?php echo $error["Connexion"];?></td></tr>  <!-- affichage des erreurs -->
                    <?php }?>
                </table>
            </form>

            <div>
                <a href="Inscription.php">Pas encore inscrit ?</a><br>
            </div>
        </div>

        <div>

        </div>
    </body>

</html>
