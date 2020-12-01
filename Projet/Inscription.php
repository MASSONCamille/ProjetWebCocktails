<?php
    include 'Utilisateurs.inc.php';

    session_start();

    $Submitted = false;
    $LoginBon = true;
    $MdpBon = true;
    $ConfMdpBon = true;
    $NomBon = true;
    $PrenomBon = true;
    $SexeBon = true;
    $NaissanceBon = true;
    $AdElecBon = true;
    $AdPostaleBon = true;
    $CodePostaleBon = true;
    $VilleBon = true;
    $NumeroBon = true;

    /*$LoginErreur = "";
    $MdpErreur = "";
    $ConfMdpErreur = "";
    $LoginErreur = "";
    $LoginErreur = "";
    $LoginErreur = "";
    $LoginErreur = "";
    $LoginErreur = "";
    $LoginErreur = "";
    $LoginErreur = "";
    $LoginErreur = "";
    $LoginErreur = "";*/

    if(isset($_POST['submit'])) {
        $Submitted = true;

        if(isset($_POST["login"])) {
            $Login = trim($_POST["login"]);
            if(strlen(str_replace(' ', '', $Login)) < 3) {
                $LoginBon = false;
                $LoginErreur = "Le nom d'utilisateur doit au moins contenir 3 lettres.";
            }
            else {
                foreach($Utilisateurs as $User) {
                    if($User['Login'] == $Login) {
                        $LoginBon = false;
                        $LoginErreur = "Nom d'utilisateur déjà utilisé.";
                    }
                }
            }
        } else {
            $LoginBon = false;
            $LoginErreur = "Nom d'utilisateur nécessaire.";
        }

        if(isset($_POST["mdp"])) {
            $Mdp = $_POST["mdp"];
            if(strlen(str_replace(' ', '', $Mdp)) < 3) {
                $MdpBon = false;
                $MdpErreur = "Le mot de passe doit au moins contenir 3 lettres.";
            }
        } else {
            $MdpBon = false;
            $MdpErreur = "Mot de passe nécessaire.";
        }

        if(isset($_POST["confMdp"])) {
            $ConfMdp = $_POST["confMdp"];
            if($ConfMdp !== $Mdp) {
                $ConfMdpBon = false;
                $ConfMdpErreur = "Les mots de passes doivent correspondre.";
            }
        } else {
            $ConfMdpBon = false;
            $ConfMdpErreur = "Confirmation du mot de passe nécessaire.";
        }

        if(isset($_POST["nom"])) {
            $Nom = trim($_POST["nom"]);
            if((strlen(str_replace(' ', '', $Nom)) < 2 || !preg_match("/^[a-zA-Z-' ]{2,}$/",$Nom)) && $Nom != "") {
                $NomBon = false;
                $NomErreur = "Nom incorrect.";
            }
        }

        if(isset($_POST["prenom"])) {
            $Prenom = trim($_POST["prenom"]);
            if((strlen(str_replace(' ', '', $Prenom)) < 2 || !preg_match("/^[a-zA-Z-' ]{2,}$/",$Prenom)) && $Prenom != "") {
                $PrenomBon = false;
                $PrenomErreur = "Prenom incorrect.";
            }
        }

        if(isset($_POST["sexe"])) {
            $Sexe = trim($_POST["sexe"]);
            if(($Sexe != 'f' && $Sexe != 'h') && $Sexe != "") {
                $SexeBon = false;
                $SexeErreur = "Problème dans le choix du sexe.";
            }
        }

        if(isset($_POST["naissance"])) {
            $Naissance = trim($_POST["naissance"]);
            if($Naissance != "") {
                if(preg_match("/^[0-9-]+$/",$Naissance)) {
                    if(!checkdate(substr($Naissance, 5, 2), substr($Naissance, -2, 2), substr($Naissance, 0, 4))) {
                        $NaissanceBon = false;
                        $NaissanceErreur = "Date incorrecte.";
                    } else if($Naissance >= date("Y-m-d")) {
                        $NaissanceBon = false;
                        $NaissanceErreur = "Date de naissance impossible.";
                    }
                } else {
                    $NaissanceBon = false;
                    $NaissanceErreur = "Date incorrecte.";
                }
            }
        }

        if(isset($_POST["adElec"])) {
            $AdElec = trim($_POST["adElec"]);
            if(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",$AdElec) && $AdElec != "") {
                $AdElecBon = false;
                $AdElecErreur = "Adresse électronique incorrecte.";
            }
        }

        if(isset($_POST["adPostale"])) {
            $AdPostale = trim($_POST["adPostale"]);
            if(!preg_match("/^[0-9]{1,3}[a-zA-Z]{0,2}[ ]{1,}[a-zA-Z-']{2,}[ ]{1,}[a-zA-Z-' ]{2,}$/",$AdPostale) && $AdPostale != "") {
                $AdPostaleBon = false;
                $AdPostaleErreur = "Adresse postale incorrecte.";
            }
        }

        if(isset($_POST["codePostale"])) {
            $CodePostale = trim($_POST["codePostale"]);
            if(!preg_match("/^[0-9]{0,5}$/",$CodePostale) && $CodePostale != "") {
                $CodePostaleBon = false;
                $CodePostaleErreur = "Code postale incorrect.";
            }
        }

        if(isset($_POST["ville"])) {
            $Ville = trim($_POST["ville"]);
            if((strlen(str_replace(' ', '', $Ville)) < 2 || !preg_match("/^[a-zA-Z-' ]{2,}$/",$Ville)) && $Ville != "") {
                $VilleBon = false;
                $VilleErreur = "Nom de la ville incorrect.";
            }
        }

        if(isset($_POST["numero"])) {
            $Numero = trim($_POST["numero"]);
            if(!preg_match("/^0[1-9][0-9]{8}|[+][1-9]{3,4}[0-9]{8}|00[1-9]{3,4}[0-9]{8}$/",$Numero) && $Numero != "") {
                $NumeroBon = false;
                $NumeroErreur = "Numéro de téléphone incorrect.";
            }
        }
    }

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Inscription</title>
        <meta charset="utf-8" />
    </head>

    <body> <!-- a voir la gestion des erreurs -->

        <h1><a href="Accueil.php">Les recettes de Mamille</a></h1>

        <header>
            <ul>
                <li><a href="Favoris.php">Favoris</a></li>
                <li><a href="Connexion.php">Se connecter</a></li>
                <li><a href="Inscription.php">S'inscrire</a></li>
                <!-- Si connecter afficher lien vers profil et déconnection -->
                <li>Mon compte</li> <!-- Dans le si connecter -->
                <li>Se déconnecter</li> <!-- Dans le si connecter -->
            </ul>
        </header>

        <?php
            if($Submitted && $LoginBon && $MdpBon && $ConfMdpBon && 
            $NomBon && $PrenomBon && $SexeBon && $NaissanceBon && 
            $AdElecBon && $AdPostaleBon && $CodePostaleBon && $VilleBon && $NumeroBon) { 
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
                ?>
                <p>Vous êtes maintenant inscrit!</p>
                <a href="Accueil.php">Retour à l'accueil</a>
        <?php } else {
        ?>

        <h2>Inscription</h2>

        <form method="post" action="#">
        <table>
            <tr>
                <td>Login* :</td>
                <td><input type="text" name="login" required="required" /></td>
                <?php if(!$LoginBon) { ?>
                    <td><span><?php echo $LoginErreur; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Mot de passe* :</td>
                <td><input type="password" name="mdp" required="required" /></td>
                <?php if(!$MdpBon) { ?>
                    <td><span><?php echo $MdpErreur; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Confirmation du mot de passe* :</td>
                <td><input type="password" name="confMdp" required="required" /></td>
                <?php if(!$ConfMdpBon) { ?>
                    <td><span><?php echo $ConfMdpErreur; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Nom :</td>
                <td><input type="text" name="nom" /></td>
                <?php if(!$NomBon) { ?>
                    <td><span><?php echo $NomErreur; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Prénom :</td>
                <td><input type="text" name="prenom" /></td>
                <?php if(!$PrenomBon) { ?>
                    <td><span><?php echo $PrenomErreur; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Sexe :</td>
                <td>
                    <input type="radio" name="sexe" value="f"/> Femme
                    <input type="radio" name="sexe" value="h"/> Homme
                </td>
                <?php if(!$SexeBon) { ?>
                    <td><span><?php echo $SexeErreur; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Date de naissance :</td>
                <td><input type="date" name="naissance" /></td>
                <?php if(!$NaissanceBon) { ?>
                    <td><span><?php echo $NaissanceErreur; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Adresse électronique :</td>
                <td><input type="email" name="adElec" /></td>
                <?php if(!$AdElecBon) { ?>
                    <td><span><?php echo $AdElecErreur; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Adresse postale :</td>
                <td><input type="text" name="adPostale" /></td>
                <?php if(!$AdPostaleBon) { ?>
                    <td><span><?php echo $AdPostaleErreur; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Code postal :</td>
                <td><input type="text" name="codePostale" /></td>
                <?php if(!$CodePostaleBon) { ?>
                    <td><span><?php echo $CodePostaleErreur; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Ville :</td>
                <td><input type="text" name="ville" /></td>
                <?php if(!$VilleBon) { ?>
                    <td><span><?php echo $VilleErreur; ?></span></td>
                <?php } ?>
            </tr>
            <tr>
                <td>Numéro de téléphone :</td>
                <td><input type="tel" name="numero" /></td>
                <?php if(!$NumeroBon) { ?>
                    <td><span><?php echo $NumeroErreur; ?></span></td>
                <?php } ?>
            </tr>
        </table>
        <input type="submit" value="S'inscrire" name="submit" />

        <?php }
        ?>

    </body>

</html>