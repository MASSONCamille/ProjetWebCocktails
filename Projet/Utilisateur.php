<?php
session_start();

if(isset($_GET['Deconnexion']) && $_GET['Deconnexion'] == true)
    unset($_SESSION['Login']);

if(empty($_SESSION["Login"]))
    header('Location: Accueil.php');

include "Utilisateurs.inc.php";

if(!empty($Utilisateurs)) {
    foreach ($Utilisateurs as $elem){
        if ($elem['Login'] == $_SESSION["Login"]){
            $user = $elem;
            break;
        }
    }
    if (empty($user)) header('Location: Accueil.php');
}else header('Location: Accueil.php');


$error = array();
$Submitted = false;
$InscritBon = false;

if(isset($_POST['submit'])) {
    $Submitted = true;

    if(isset($_POST["Login"])) {
        $Login = trim($_POST["Login"]);
        if(strlen(str_replace(' ', '', $Login)) < 3) {
            $error["Login"] = "Le nom d'utilisateur doit au moins contenir 3 lettres.";
        }
        else {
            foreach($Utilisateurs as $User) {
                if($User['Login'] == $Login) {
                    $error["Login"] = "Nom d'utilisateur déjà utilisé.";
                }
            }
        }
    } else {
        $error["Login"] = "Nom d'utilisateur nécessaire.";
    }

    if(isset($_POST["Mdp"])) {
        $Mdp = $_POST["Mdp"];
        if(strlen(str_replace(' ', '', $Mdp)) < 3) {
            $error["Mdp"] = "Le mot de passe doit au moins contenir 3 lettres.";
        }
    } else {
        $error["Mdp"] = "Mot de passe nécessaire.";
    }

    if(isset($_POST["MdpConf"])) {
        $ConfMdp = $_POST["MdpConf"];
        if($ConfMdp !== $Mdp) {
            $error["MdpConf"] = "Les mots de passes doivent correspondre.";
        }
    } else {
        $error["MdpConf"] = "Confirmation du mot de passe nécessaire.";
    }

    if(isset($_POST["Nom"])) {
        $Nom = trim($_POST["Nom"]);
        if((strlen(str_replace(' ', '', $Nom)) < 2 || !preg_match("/^[a-zA-Z-' ]{2,}$/",$Nom)) && $Nom != "") {
            $error["Nom"] = "Nom incorrect.";
        }
    }

    if(isset($_POST["Prenom"])) {
        $Prenom = trim($_POST["Prenom"]);
        if((strlen(str_replace(' ', '', $Prenom)) < 2 || !preg_match("/^[a-zA-Z-' ]{2,}$/",$Prenom)) && $Prenom != "") {
            $error["Prenom"] = "Prenom incorrect.";
        }
    }

    if(isset($_POST["Sexe"])) {
        $Sexe = trim($_POST["Sexe"]);
        if(($Sexe != 'f' && $Sexe != 'h') && $Sexe != "") {
            $error["Sexe"] = "Problème dans le choix du sexe.";
        }
    }

    if(isset($_POST["Naissance"])) {
        $Naissance = trim($_POST["Naissance"]);
        if($Naissance != "") {
            if(preg_match("/^[0-9-]+$/",$Naissance)) {
                if(!checkdate(substr($Naissance, 5, 2), substr($Naissance, -2, 2), substr($Naissance, 0, 4))) {
                    $error["Naissance"] = "Date incorrecte.";
                } else if($Naissance >= date("Y-m-d")) {
                    $error["Naissance"] = "Date de naissance impossible.";
                }
            } else {
                $error["Naissance"] = "Date incorrecte.";
            }
        }
    }

    if(isset($_POST["AdElec"])) {
        $AdElec = trim($_POST["AdElec"]);
        if(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",$AdElec) && $AdElec != "") {
            $error["AdElec"] = "Adresse électronique incorrecte.";
        }
    }

    if(isset($_POST["AdPost"])) {
        $AdPostale = trim($_POST["AdPost"]);
        if(!preg_match("/^[0-9]{1,3}[a-zA-Z]{0,2}[ ]{1,}[a-zA-Z-']{2,}[ ]{1,}[a-zA-Z-' ]{2,}$/",$AdPostale) && $AdPostale != "") {
            $error["AdPost"] = "Adresse postale incorrecte.";
        }
    }

    if(isset($_POST["CodePost"])) {
        $CodePostale = trim($_POST["CodePost"]);
        if(!preg_match("/^[0-9]{0,5}$/",$CodePostale) && $CodePostale != "") {
            $error["CodePost"] = "Code postale incorrect.";
        }
    }

    if(isset($_POST["Ville"])) {
        $Ville = trim($_POST["Ville"]);
        if((strlen(str_replace(' ', '', $Ville)) < 2 || !preg_match("/^[a-zA-Z-' ]{2,}$/",$Ville)) && $Ville != "") {
            $error["Ville"] = "Nom de la ville incorrect.";
        }
    }

    if(isset($_POST["Numero"])) {
        $Numero = trim($_POST["Numero"]);
        if(!preg_match("/^0[1-9][0-9]{8}|[+][1-9]{3,4}[0-9]{8}|00[1-9]{3,4}[0-9]{8}$/",$Numero) && $Numero != "") {
            $error["Numero"] = "Numéro de téléphone incorrect.";
        }
    }
}

if($Submitted && empty($error)) {
    $_SESSION['Login'] = $Login;
    $NouvUtilisateur = array (
        "Login" => $Login,
        "Mdp" => $Mdp,
    );
    if($Nom != "")
        $NouvUtilisateur['Nom'] = $Nom;
    if($Prenom != "")
        $NouvUtilisateur['Prenom'] = $Prenom;
    if(isset($Sexe) && $Sexe != "")
        $NouvUtilisateur['Sexe'] = $Sexe;
    if($Naissance != "")
        $NouvUtilisateur['Naissance'] = $Naissance;
    if($AdElec != "")
        $NouvUtilisateur['AdElec'] = $AdElec;
    if($AdPostale != "") {
        $AdPostale = preg_replace('!\s+!', ' ', $AdPostale);
        $NouvUtilisateur['AdPostale'] = $AdPostale;
    }
    if($CodePostale != "")
        $NouvUtilisateur['CodePostale'] = $CodePostale;
    if($Ville != "")
        $NouvUtilisateur['Ville'] = $Ville;
    if($Numero != "")
        $NouvUtilisateur['Numero'] = $Numero;
    array_push($Utilisateurs, $NouvUtilisateur);
    $buffer = "<?php\n\$Utilisateurs = array(\n";
    foreach ($Utilisateurs as $x => $User) {
        $buffer .= "\t'".$x."' => array(\n";
        foreach ($User as $NomParam => $Param) {
            $buffer .= "\t\t'".$NomParam."' => '".$Param."',\n";
        }
        $buffer .= "\t),\n";
    };
    $buffer .= ");\n?>";

    $fileUser = fopen('Utilisateurs.inc.php', 'w');
    fwrite($fileUser, $buffer);
    fclose($fileUser);
    $InscritBon = true;
}
?>


<!DOCTYPE html>
<html>

    <head>
        <title>Connexion</title>
        <meta charset="utf-8" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="script/verif_form.js"></script>
        <script src="script/Utilisateur.js"></script>
    </head>

    <body> <!-- a voir la gestion des erreurs -->

        <h1><a href="Accueil.php">Les recettes de Mamille</a></h1>

        <header>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="Favoris.php">Favoris</a></li>
                <?php if(!isset($_SESSION['Login']) || $_SESSION['Login'] === "") { ?>
                    <li><a href="Connexion.php">Se connecter</a></li>
                    <li><a href="Inscription.php">S'inscrire</a></li>
                <?php }
                if(isset($_SESSION['Login']) && $_SESSION['Login'] !== "") { ?>
                    <li>Mon compte</li> <!-- Dans le si connecter -->
                    <li><a href="<?php echo $_SERVER['PHP_SELF']."?Deconnexion=true"; ?>">Se déconnecter</a></li>
                <?php } ?>
            </ul>
        </header>

        <h2>Information utilisateur :</h2>

        <div id="div_affichage">
            <table>
                <tr>
                    <td>Login : </td>
                    <td><?php echo $user["Login"];?></td>
                </tr>
                <?php if (!empty($user["Nom"])) {?>
                <tr>
                    <td>Nom : </td>
                    <td><?php echo $user["Nom"];?></td>
                </tr>
                <?php }?>
                <?php if (!empty($user["Prenom"])) {?>
                    <tr>
                        <td>Prénom : </td>
                        <td><?php echo $user["Prenom"];?></td>
                    </tr>
                <?php }?>
                <?php if (!empty($user["Sexe"])) {?>
                    <tr>
                        <td>Sexe : </td>
                        <td><?php echo $user["Sexe"] == 'h' ? 'Homme' : 'Femme';?></td>
                    </tr>
                <?php }?>
                <?php if (!empty($user["Prenom"])) {?>
                    <tr>
                        <td>Date de naissance : </td>
                        <td><?php echo date('d/m/Y', strtotime($user["Naissance"]));?></td>
                    </tr>
                <?php }?>
                <?php if (!empty($user["AdElec"])) {?>
                    <tr>
                        <td>Adresse électronique : </td>
                        <td><?php echo $user["AdElec"];?></td>
                    </tr>
                <?php }?>
                <?php if (!empty($user["AdPostale"])) {?>
                    <tr>
                        <td>Adresse postal : </td>
                        <td><?php echo $user["AdPostale"];?></td>
                    </tr>
                <?php }?>
                <?php if (!empty($user["CodePostale"])) {?>
                    <tr>
                        <td>Code postal : </td>
                        <td><?php echo $user["CodePostale"];?></td>
                    </tr>
                <?php }?>
                <?php if (!empty($user["Ville"])) {?>
                    <tr>
                        <td>Ville : </td>
                        <td><?php echo $user["Ville"];?></td>
                    </tr>
                <?php }?>
                <?php if (!empty($user["Numero"])) {?>
                    <tr>
                        <td>Numéro de téléphone : </td>
                        <td><?php echo $user["Numero"];?></td>
                    </tr>
                <?php }?>
            </table>
            <p>Entré votre mot de passe pour modifier</p>
            <input type="password" id="VerifMdp">
            <button id="changer_vue">Modifier</button>
        </div>

        <div id="div_form" hidden>
            <form action="Utilisateur.php" method="post">
                <table>
                    <tr>
                        <td><label for="Login">Login : </label></td>
                        <td><input type="text" name="Login" id="Login"
                                   value="<?php echo $user["Login"];?>"</td>
                    </tr>
                    <tr>
                        <td><label for="Mdp">Nouveau mot de passe : </label></td>
                        <td><input type="password" name="Mdp" id="Mdp"</td>
                    </tr>
                    <tr>
                        <td><label for="MdpConf">Confirmer le nouveau mot de passe : </label></td>
                        <td><input type="password" name="MdpConf" id="MdpConf"</td>
                    </tr>
                    <tr>
                        <td><label for="Nom">Nom : </label></td>
                        <td><input type="text" name="Nom" id="Nom"
                                   value="<?php if (!empty($user["Nom"])) echo $user["Nom"];?>"</td>
                    </tr>
                    <tr>
                        <td><label for="Prenom">Prénom : </label></td>
                        <td><input type="text" name="Prenom" id="Prenom"
                                   value="<?php if (!empty($user["Prenom"])) echo $user["Prenom"];?>"</td>
                    </tr>
                    <tr>
                        <td><label for="Sexe">Sexe : </label></td>
                        <td>
                            <input type="radio" name="Sexe" value="f" <?php if (!empty($user["Sexe"]) && $user['Sexe'] == 'f') echo "checked"; ?>/> Femme
                            <input type="radio" name="Sexe" value="h" <?php if (!empty($user["Sexe"]) && $user['Sexe'] == 'h') echo "checked"; ?>/> Homme
                        </td>
                    </tr>
                    <tr>
                        <td><label for="Naissance">Date de naissance : </label></td>
                        <td><input type="text" name="Naissance" id="Naissance"
                                   value="<?php if (!empty($user["Naissance"])) echo date('d-m-Y', strtotime($user["Naissance"]));?>"</td>
                    </tr>
                    <tr>
                        <td><label for="AdElec">Adresse électronique : </label></td>
                        <td><input type="text" name="AdElec" id="AdElec"
                                   value="<?php if (!empty($user["AdElec"])) echo $user["AdElec"];?>"</td>
                    </tr>
                    <tr>
                        <td><label for="AdPostale">Adresse postale : </label></td>
                        <td><input type="text" name="AdPostale" id="AdPostale"
                                   value="<?php if (!empty($user["AdPostale"])) echo $user["AdPostale"];?>"</td>
                    </tr>
                    <tr>
                        <td><label for="CodePostale">Code postal : </label></td>
                        <td><input type="text" name="CodePostale" id="CodePostale"
                                   value="<?php if (!empty($user["CodePostale"])) echo $user["CodePostale"];?>"</td>
                    </tr>
                    <tr>
                        <td><label for="Ville">Ville : </label></td>
                        <td><input type="text" name="Ville" id="Ville"
                                   value="<?php if (!empty($user["Ville"])) echo $user["Ville"];?>"</td>
                    </tr>
                    <tr>
                        <td><label for="Numero">Numéro de téléphone : </label></td>
                        <td><input type="text" name="Numero" id="Numero"
                                   value="<?php if (!empty($user["Numero"])) echo $user["Numero"];?>"</td>
                    </tr
                </table>
                <input name="valider" type="submit" value="Valider">
            </form>
            <button id="changer_vue">Annuler</button>
        </div>
    </body>
</html>

