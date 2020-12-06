<?php
    include 'Utilisateurs.inc.php';

    session_start();

    if(isset($_GET['Deconnexion']) && $_GET['Deconnexion'] == true)
        unset($_SESSION['Login']);

    $error = array();

    $Submitted = false;

    $InscritBon = false;


    if(isset($_POST['submit'])) { //Vérifie si le formulaire a été rempli
        $Submitted = true;

        if(isset($_POST["login"])) { //Vérifie si le login a bien été saisi
            $Login = trim($_POST["login"]);
            if(strlen(str_replace(' ', '', $Login)) < 3) { //Login d'au moins 3 caractères
                $error["Login"] = "Le nom d'utilisateur doit au moins contenir 3 lettres.";
            }
            else {
                foreach($Utilisateurs as $User) { //Vérifie si le login est déjà utilisé
                    if($User['Login'] == $Login) {
                        $error["Login"] = "Nom d'utilisateur déjà utilisé.";
                    }
                }
            }
        } else { //Vérification pour les champs obligatoires
            $error["Login"] = "Nom d'utilisateur nécessaire.";
        }

        if(isset($_POST["mdp"])) { //Vérification du mot de passe
            $Mdp = $_POST["mdp"];
            if(strlen(str_replace(' ', '', $Mdp)) < 3) {
                $error["Mdp"] = "Le mot de passe doit au moins contenir 3 lettres.";
            }
        } else {
            $error["Mdp"] = "Mot de passe nécessaire.";
        }

        if(isset($_POST["confMdp"])) { //Vérification de la confirmation du mot de passe
            $ConfMdp = $_POST["confMdp"];
            if($ConfMdp !== $Mdp) { //Vérifie que les mots de passe correspondent
                $error["MdpConf"] = "Les mots de passes doivent correspondre.";
            }
        } else {
            $error["MdpConf"] = "Confirmation du mot de passe nécessaire.";
        }

        if(isset($_POST["nom"])) { //Vérification du nom
            $Nom = trim($_POST["nom"]);
            if((strlen(str_replace(' ', '', $Nom)) < 2 || !preg_match("/^[a-zA-Z-' ]{2,}$/",$Nom)) && $Nom != "") { //Vérifie que le nom n'est ni vide, ni un faux
                $error["Nom"] = "Nom incorrect.";
            }
        }

        if(isset($_POST["prenom"])) { //Vérification du prénom
            $Prenom = trim($_POST["prenom"]);
            if((strlen(str_replace(' ', '', $Prenom)) < 2 || !preg_match("/^[a-zA-Z-' ]{2,}$/",$Prenom)) && $Prenom != "") {
                $error["Prenom"] = "Prenom incorrect.";
            }
        }

        if(isset($_POST["sexe"])) { //Vérification du sexe
            $Sexe = trim($_POST["sexe"]);
            if(($Sexe != 'f' && $Sexe != 'h') && $Sexe != "") { //Vérifie que le sexe correspond à l'un des boutons
                $error["Sexe"] = "Problème dans le choix du sexe.";
            }
        }

        if(isset($_POST["naissance"])) { //Vérification de la date de naissance
            $Naissance = trim($_POST["naissance"]);
            if($Naissance != "") {
                if(preg_match("/^[0-9-]+$/",$Naissance)) {
                    if(!checkdate(substr($Naissance, 5, 2), substr($Naissance, -2, 2), substr($Naissance, 0, 4))) {
                        $error["DateNai"] = "Date incorrecte.";
                    } else if($Naissance >= date("Y-m-d")) { //Vérifie que la date de naissance soit au moins avant aujourd'hui
                        $error["DateNai"] = "Date de naissance impossible.";
                    }
                } else {
                    $error["DateNai"] = "Date incorrecte.";
                }
            }
        }

        if(isset($_POST["adElec"])) { //Vérification du mail
            $AdElec = trim($_POST["adElec"]);
            if(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",$AdElec) && $AdElec != "") {
                $error["AdElec"] = "Adresse électronique incorrecte.";
            }
        }

        if(isset($_POST["adPostale"])) { //Vérification de l'adresse postale
            $AdPostale = trim($_POST["adPostale"]);
            if(!preg_match("/^[0-9]{1,3}[a-zA-Z]{0,2}[ ]{1,}[a-zA-Z-']{2,}[ ]{1,}[a-zA-Z-' ]{2,}$/",$AdPostale) && $AdPostale != "") { 
                $error["AdPost"] = "Adresse postale incorrecte.";
            }
        }

        if(isset($_POST["codePostale"])) { //Vérification du code postale
            $CodePostale = trim($_POST["codePostale"]);
            if(!preg_match("/^[0-9]{0,5}$/",$CodePostale) && $CodePostale != "") {
                $error["CodePost"] = "Code postale incorrect.";
            }
        }

        if(isset($_POST["ville"])) { //Vérification du nom de la ville
            $Ville = trim($_POST["ville"]);
            if((strlen(str_replace(' ', '', $Ville)) < 2 || !preg_match("/^[a-zA-Z-' ]{2,}$/",$Ville)) && $Ville != "") {
                $error["Ville"] = "Nom de la ville incorrect.";
            }
        }

        if(isset($_POST["numero"])) { //Vérification du numéro de téléphone
            $Numero = trim($_POST["numero"]);
            if(!preg_match("/^0[1-9][0-9]{8}|[+][1-9]{3,4}[0-9]{8}|00[1-9]{3,4}[0-9]{8}$/",$Numero) && $Numero != "") {
                $error["Numero"] = "Numéro de téléphone incorrect.";
            }
        }
    }

    if($Submitted && empty($error)) { //Effectue l'enregistrement de l'utilisateur et le connecte s'il n'y a pas d'erreur
        $_SESSION['Login'] = $Login;
        $NouvUtilisateur = array (
            "Login" => $Login,
            "Mdp" => $Mdp,
        );
        if($Nom != "") //Vérifie si l'on enregistre ou non les informations secondaires (si elles ont été saisi)
            $NouvUtilisateur['Nom'] = $Nom;
        if($Prenom != "")
            $NouvUtilisateur['Prenom'] = $Prenom;
        if(isset($Sexe) && $Sexe != "")
            $NouvUtilisateur['Sexe'] = $Sexe;
        if($Naissance != "")
            $NouvUtilisateur['Nom'] = $Naissance;
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

        //Ajout dans le fichier des utilisateurs
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

        if (!empty($_SESSION["favoris"])) { //Permet d'ajouter les favoris d'un utilisateur qui vient de s'inscrire s'il en a
            include 'script/Favoris.funct.php';
            TransfertFav($_SESSION["favoris"]);
            unset($_SESSION["favoris"]);
        }
    }

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Inscription</title>
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

        <?php if($InscritBon) { //Message en cas de réussite de l'inscription ?>
                <p>Vous êtes maintenant inscrit!</p>
                <a href="Accueil.php">Retour à l'accueil</a>
        <?php } else {
        ?>

        <h2>Inscription</h2>

        <form method="post" action="#"> <!-- Formulaire d'inscription -->
        <table>
            <tr>
                <td>Login* :</td> <!-- Nom du champs -->
                <td><input id="Login" type="text" name="login" required="required"
                           value="<?php if (isset($_POST['submit']) && empty($error["Login"])) echo $_POST['login']; ?>"/></td> <!-- Input du champs avec les anciennes données si besoin -->
                <?php if(!empty($error["Login"])) { ?>
                    <td><span><?php echo $error["Login"]; ?></span></td> <!-- Affiche le message d'erreur si besoin -->
                <?php } ?>
            </tr>
            <tr>
                <td>Mot de passe* :</td>
                <td><input id="Mdp" type="password" name="mdp" required="required"
                           value="<?php if (isset($_POST['submit']) && empty($error["Mdp"])) echo $_POST['mdp']; ?>" /></td>
                <td><img src="images/eye.png" id="viewpw" alt="afficher mot de passe" width="30px" border="1"></td>
                <?php if(!empty($error["Mdp"])) { ?>
                    <td><span><?php echo $error["Mdp"]; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Confirmation du mot de passe* :</td>
                <td><input id="MdpConf" type="password" name="confMdp" required="required"
                           value="<?php if (isset($_POST['submit']) && empty($error["MdpConf"])) echo $_POST['confMdp']; ?>" /></td>
                <?php if(!empty($error["MdpConf"])) { ?>
                    <td><span><?php echo $error["MdpConf"]; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Nom :</td>
                <td><input type="text" name="nom"
                           value="<?php if (isset($_POST['submit']) && empty($error["Nom"])) echo $_POST['nom']; ?>" /></td>
                <?php if(!empty($error["Nom"])) { ?>
                    <td><span><?php echo $error["Nom"]; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Prénom :</td>
                <td><input type="text" name="prenom"
                           value="<?php if (isset($_POST['submit']) && empty($error["Prenom"])) echo $_POST['prenom']; ?>" /></td>
                <?php if(!empty($error["Prenom"])) { ?>
                    <td><span><?php echo $error["Prenom"]; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Sexe :</td>
                <td>
                    <input type="radio" name="sexe" value="f" <?php if (isset($_POST['submit']) && $_POST['sexe'] == 'f') echo "checked"; ?>/> Femme
                    <input type="radio" name="sexe" value="h" <?php if (isset($_POST['submit']) && $_POST['sexe'] == 'h') echo "checked"; ?>/> Homme
                </td>
                <?php if(!empty($error["Sexe"])) { ?>
                    <td><span><?php echo $error["Sexe"]; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Date de naissance :</td>
                <td><input type="date" name="naissance"
                           value="<?php if (isset($_POST['submit']) && empty($error["DateNai"])) echo $_POST['naissance']; ?>" /></td>
                <?php if(!empty($error["DateNai"])) { ?>
                    <td><span><?php echo $error["DateNai"]; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Adresse électronique :</td>
                <td><input type="email" name="adElec"
                           value="<?php if (isset($_POST['submit']) && empty($error["AdElec"])) echo $_POST['adElec']; ?>" /></td>
                <?php if(!empty($error["AdElec"])) { ?>
                    <td><span><?php echo $error["AdElec"]; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Adresse postale :</td>
                <td><input type="text" name="adPostale"
                           value="<?php if (isset($_POST['submit']) && empty($error["AdPost"])) echo $_POST['adPostale']; ?>" /></td>
                <?php if(!empty($error["AdPost"])) { ?>
                    <td><span><?php echo $error["AdPost"]; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Code postal :</td>
                <td><input type="text" name="codePostale"
                           value="<?php if (isset($_POST['submit']) && empty($error["CodePost"])) echo $_POST['codePostale']; ?>" /></td>
                <?php if(!empty($error["CodePost"])) { ?>
                    <td><span><?php echo $error["CodePost"]; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Ville :</td>
                <td><input type="text" name="ville"
                           value="<?php if (isset($_POST['submit']) && empty($error["Ville"])) echo $_POST['ville']; ?>" /></td>
                <?php if(!empty($error["Ville"])) { ?>
                    <td><span><?php echo $error["Ville"]; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Numéro de téléphone :</td>
                <td><input type="tel" name="numero"
                           value="<?php if (isset($_POST['submit']) && empty($error["Numero"])) echo $_POST['numero']; ?>" /></td>
                <?php if(!empty($error["Numero"])) { ?>
                    <td><span><?php echo $error["Numero"]; ?></span></td>
                <?php } ?>
            </tr>
        </table>
        <input type="submit" value="S'inscrire" name="submit" />
    </form>
<?php }
?>

</body>

</html>