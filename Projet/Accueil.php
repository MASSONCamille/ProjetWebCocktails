<?php //Verifier orthographe commentaire
    include 'Donnees.inc.php';

    session_start();

    if(isset($_GET['Position']) == false)
        $Position = 'Aliment';
    else
        $Position = $_GET['Position'];

    if((isset($_SESSION['CheminAcces']) == false) || ($Position == 'Aliment'))
        $CheminAcces = array('Aliment');
    else { // Potentiel erreur clé car elles ne sont pas nommées ?
        $CheminAcces = $_SESSION['CheminAcces'];
        $taille = count($CheminAcces);
        $Cle = 0;
        $Trouve = false;
        while(($Cle < $taille) && ($Trouve == false)) {
            if($CheminAcces[$Cle] == $Position) {
                $Trouve = true;
                if($Cle != (count($CheminAcces)-1))
                    $CheminAcces = array_slice($CheminAcces, 0, $Cle+1);
            }
            $Cle++;
        }
        if($Trouve == false)
            array_push($CheminAcces, $Position);
    }
    $_SESSION['CheminAcces'] = $CheminAcces;

    //APPEL DES FONCTIONS DE RECHERCHE POUR LA RECETTE
    $IngredientsRecherche = array($Position);
    $IngredientsRecherche = RechercheIngredients($Position, $IngredientsRecherche, $Hierarchie);
    $RecettesRecherche = RechercheRecettes($IngredientsRecherche, $Recettes);

    //FONCTIONS
    function RechercheIngredients($PositionRecherche, $IngredientsRecherche, $Hierarchie) { //FONCTION DE RECHERCHE DES INGREDIENTS
        foreach($Hierarchie[$PositionRecherche] as $NomSousCateg => $SousCateg) {
            if($NomSousCateg == 'sous-categorie') {
                foreach($SousCateg as $ElementSousCateg) {
                    array_push($IngredientsRecherche, $ElementSousCateg);
                    $IngredientsRecherche = RechercheIngredients($ElementSousCateg, $IngredientsRecherche, $Hierarchie);
                }
            }
        }
        return $IngredientsRecherche;
    }

    function RechercheRecettes($IngredientsRecherche, $Recettes) { //FONCTION DE RECHERCHE DES RECETTES
        $RecettesRecherche = array();
        foreach($Recettes as $CleRecette => $Proprietes) {
            foreach($Proprietes['index'] as $Ingredient) {
                if(in_array($Ingredient, $IngredientsRecherche)) {
                    if(in_array($Proprietes['titre'], $RecettesRecherche) == false)
                        $RecettesRecherche[$CleRecette] = $Proprietes['titre'];
                }
            }
        }
        return $RecettesRecherche;
    }
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Accueil</title>
        <meta charset="utf-8" />
    </head>

    <body> <!-- a voir la gestion des erreurs -->

        <h1><a href="Accueil.php">Les recettes de Mamille</a></h1>

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

        <nav> <!-- Renommer var SousCateg (pas très indicatif) -->
            <h2>Navigation</h2>
            <p>
                <?php
                    foreach($CheminAcces as $Element) { ?>
                        <a href="<?php echo $_SERVER['PHP_SELF']."?Position=".$Element; ?>"><?php echo $Element; ?>/</a>
                <?php }
                ?>
            </p>
            <ul>
                <?php
                    foreach($Hierarchie as $NomCateg => $TabSousCateg) {
                        foreach($TabSousCateg as $NomSousCateg => $SousCateg) {
                            if($NomSousCateg == 'super-categorie') {
                                if(in_array($Position, $SousCateg)) { ?>
                                    <li><a href="<?php echo $_SERVER['PHP_SELF']."?Position=".$NomCateg; ?>"><?php echo $NomCateg; ?></a></li>
                                <?php }
                            }
                        }
                    }
                ?>
            </ul>
        </nav>

        <div>
            <h2>Recettes</h2>
            <ul>
                <?php
                    foreach($RecettesRecherche as $CleRecette => $Recette) { ?>
                        <li><a href="<?php echo "Recettes.php"."?Recette=".$CleRecette; ?>"><?php echo $Recette; ?></a></li>
                <?php }
                ?>
            </ul>
        </div>

    </body>

</html>