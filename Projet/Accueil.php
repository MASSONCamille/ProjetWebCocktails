<?php //Verifier orthographe commentaire
    include 'Donnees.inc.php';

    session_start();

    if(isset($_GET['Deconnexion']) && $_GET['Deconnexion'] == true)
        unset($_SESSION['Login']);

    if(!isset($_GET['Position']))
        $Position = 'Aliment';
    else
        $Position = $_GET['Position'];

    if((!isset($_SESSION['CheminAcces'])) || ($Position == 'Aliment'))
        $CheminAcces = array('Aliment');
    else { // Potentiel erreur clé car elles ne sont pas nommées ?
        $CheminAcces = $_SESSION['CheminAcces'];
        $taille = count($CheminAcces);
        $Cle = 0;
        $Trouve = false;
        while(($Cle < $taille) && (!$Trouve)) {
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
        <link rel="stylesheet" href="style.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
        $(function(){
            var ListeAvecIngredient = [];
            var ListeSansIngredient = [];

            /*Array.prototype.remove = function() {
                var what, liste = arguments, L = liste.length, ax;
                while (L && this.length) {
                    what = a[--L];
                    while ((ax = this.indexOf(what)) !== -1) {
                        this.splice(ax, 1);
                    }
                }
                return this;
            };*/

            $("#nomRecette").keyup(function() {
                var regex = new RegExp('\\b' + $(this).val().toLowerCase());
                $("#ListeRecettes li").filter(function() {
                    $(this).toggle(regex.test($(this).text().toLowerCase()));
                });
            });

            $("#nomIngredient").keyup(function() {
                $('#optionsCompletion').empty();
                if($(this).val().trim() !== "") {
                    $.get("script/get_tags_recherche.php?DebutRecherche="+$(this).val(), function(data) {
                        var liste = JSON.parse(data);
                        var listeRech = $.map(liste, function(n,i){return n.toLowerCase();});
                        var listeRechAvec = $.map(ListeAvecIngredient, function(n,i){return n.toLowerCase();});
                        var listeRechSans = $.map(ListeSansIngredient, function(n,i){return n.toLowerCase();});

                        var ValRecherche = $("#nomIngredient").val().toLowerCase();
                        
                        $.each(liste, function(cle, valeur) {
                            /*var Dedans1 = $.inArray(valeur, listeRechAvec);
                            var Dedans2 = $.inArray(valeur, listeRechSans);
                            if(Dedans1 != -1) {

                            }
                            else if(Dedans2 != -1) {

                            }
                            else {*/
                                $('#optionsCompletion').append($("<option></option>").text(valeur));

                                var Dedans3 = $.inArray(ValRecherche, listeRech);
                                if(Dedans3 != -1) {
                                    $('#avecIngredient').prop("disabled", false);
                                    $('#sansIngredient').prop("disabled", false);
                                } else {
                                    $('#avecIngredient').prop("disabled", true);
                                    $('#sansIngredient').prop("disabled", true);
                                }
                            //}
                        });
                    });
                }
            });

            $("#avecIngredient").click(function() {
                var valeur = $("#nomIngredient").val();
                if($.inArray(valeur, ListeAvecIngredient) != 0) {
                    ListeAvecIngredient.push(valeur);
                }
                $("#nomIngredient").val('');
                $(this).prop("disabled", true);
                $('#sansIngredient').prop("disabled", true);
            });

            $("#sansIngredient").click(function() {
                var valeur = $("#nomIngredient").val();
                if($.inArray(valeur, ListeSansIngredient) != 0) {
                    ListeSansIngredient.push(valeur);
                }
                $("#nomIngredient").val('');
                $(this).prop("disabled", true);
                $('#avecIngredient').prop("disabled", true);
            });
        });
        </script>
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

        <div id="Navigation">
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

            <input id="nomRecette" type="text" placeholder="Recherche par nom">

            <div>
                <h2>Recettes</h2>
                <ul id="ListeRecettes">
                    <?php
                        foreach($RecettesRecherche as $CleRecette => $Recette) { ?>
                            <li><a href="<?php echo "Recettes.php"."?Recette=".$CleRecette; ?>"><?php echo $Recette; ?></a></li>
                    <?php }
                    ?>
                </ul>
            </div>
        </div>

        <div id="Recherche">
            <input id="nomIngredient" type="text" placeholder="Recherche par ingrédient" list="optionsCompletion">
            <input id="avecIngredient" type="button" value="Avec" disabled>
            <input id="sansIngredient" type="button" value="Sans" disabled>
            <datalist id="optionsCompletion" hidden>
            </datalist>
        </div>

    </body>

</html>