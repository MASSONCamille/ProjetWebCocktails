<!--
Liste des Favoris pour un Utilisateur : (Camille MASSON)

Si conecter (via $_SESSION)
    |recuperer dans le fichier favori la liste correspondant a l'utilisateur
Sinon
    |Créer un cookie qui stock les données
   Fin Si
________________________________________________________________________________________________________________________

-->

<?php

    include 'Donnees.inc.php';
    include 'Favoris.funct.php';

    session_start();

    if(isset($_GET['Deconnexion']) && $_GET['Deconnexion'] == true)
        unset($_SESSION['Login']);

    // BLOCK TEMPORAIRE imite la connexion
    if (!empty($_POST['login'])){
        $_SESSION['Login'] = $_POST['login'];
        unset($_SESSION['favoris']);
    }

    if (isset($_POST['deco'])){
        unset($_SESSION['Login']);
    }

    if (isset($_POST['idcocktail'])){
        if ($_POST['mode'] == 'Add') addFav(intval($_POST['idcocktail']));
        else delFav(intval($_POST['idcocktail']));
    }

    if (isset($_SESSION['Login'])){
        include 'Favoris.inc.php';

        if (isset($Favoris[$_SESSION["Login"]])) $Fav = $Favoris[$_SESSION['Login']];
        else $Fav = array();

    }else{
        if(isset($_SESSION['favoris'])) $Fav = $_SESSION['favoris'];
        else $Fav = NULL;
    }

    $_SESSION['CheminAcces'] = "Favoris";

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Cocktails Favoris</title>
        <meta charset="utf-8" />
    </head>

    <body>

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

        <div>
            <?php if (empty($Fav)) {?>
                <p>Pas de favoris</p>
            <?php }else{ ?>

                <ul>
                <?php
                    foreach ($Fav as $idFav => $idRec) {
                        echo "<li>".$idRec."-> <a href='Recettes.php".'?Recette='.$idRec."'>".$Recettes[$idRec]["titre"]."</a></li>";
                    }
                ?>
                </ul>
                <?php
                if (IsFav(0)) echo "la recette 0 est favori"; // Test pour IsFav()
                else echo "la recette 0 n'est pas favori";
                ?>

            <?php } ?>

            <form action="Favoris.php" method="post">
                <input type="text" name="idcocktail">
                <input type="submit" value="Add" name="mode">
                <input type="submit" value="Del" name="mode">
            </form>


            <!-- BLOCK TEMPORAIRE formulaire de connection simplifier -->
            <br><br><br><br>
            <p>Donné votre login :</p>
            <form action="Favoris.php" method="post">
                <input type="text" name="login">
                <input type="submit" value="Login Session">
            </form>

            <form action="Favoris.php" method="post">
                <input type="submit" value="Deconnexion" name="deco">
            </form>

            <!-- BLOCK TEMPORAIRE affichage $_SESSION -->
            <br><br>
            <?php print_r($_SESSION); ?>
        </div>
    </body>

</html>
