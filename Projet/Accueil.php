<?php //Verifier orthographe commentaire
    include 'Donnees.inc.php';
    include 'Recherche.funct.php';

    session_start();

    if(isset($_GET['Deconnexion']) && $_GET['Deconnexion'] == true)
        unset($_SESSION['Login']);

    if(!isset($_GET['Position']))
        $Position = 'Aliment';
    else
        $Position = $_GET['Position'];

    if((!isset($_SESSION['CheminAcces'])) || ($Position == 'Aliment'))
        $CheminAcces = array('Aliment');
    else {
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

            $("#nomRecette").keyup(function() {
                var regex = new RegExp('\\b' + $(this).val().toLowerCase());
                $("#ListeRecettes li").filter(function() {
                    $(this).toggle(regex.test($(this).text().toLowerCase()));
                });
            });

            $("#nomIngredient").keyup(function() {
                $('#avecIngredient').prop("disabled", true);
                $('#sansIngredient').prop("disabled", true);
                $('#optionsCompletion').empty();
                if($(this).val().trim() !== "") {
                    $.get("script/get_tags_recherche.php?DebutRecherche="+$(this).val(), function(Data) {
                        var Liste = JSON.parse(Data);
                        var ListeRecherche = $.map(Liste, function(Val,i){return Val.toLowerCase();});
                        var ListeRechercheAvec = $.map(ListeAvecIngredient, function(Val,i){return Val.toLowerCase();});
                        var ListeRechercheSans = $.map(ListeSansIngredient, function(Val,i){return Val.toLowerCase();});                       
                        $.each(Liste, function(Cle, Valeur) {
                            if(!ListeRechercheAvec.includes(Valeur.toLowerCase()) && !ListeRechercheSans.includes(Valeur.toLowerCase())) {
                                $('#optionsCompletion').append($("<option></option>").text(Valeur));
                            }
                        });

                        var ValRecherche = $("#nomIngredient").val().trim().toLowerCase();
                        if(ListeRecherche.includes(ValRecherche) && !ListeRechercheAvec.includes(ValRecherche) && !ListeRechercheSans.includes(ValRecherche)) {
                            $('#avecIngredient').prop("disabled", false);
                            $('#sansIngredient').prop("disabled", false); 
                        } 
                    });
                }
            });

            $.fn.afficherRecettesResultats = function(ResTable) {
                TableAffiche = $('#ResultatsRecherche table > tbody');
                TableAffiche.empty();
                $.each(ResTable, function(Cle, Valeur) {
                    TableAffiche.append("<tr><td>"+Valeur['Nom']+"</td><td>"+Valeur['Score']+"</td></tr>");
                });
            }

            $.fn.rechercheRecettesCriteres = function() {
                $.post("script/recherche_recettes_criteres.php", { 'IngredientsAvec': ListeAvecIngredient, 'IngredientsSans': ListeSansIngredient }, function(Data) {
                    $(this).afficherRecettesResultats(Data);
                }, "json").fail(function(Data) {
                    console.log(Data.responseText);
                });
            }

            $.fn.ajoutIngredientRecherche = function(liste, idtable) {
                var Valeur = $("#nomIngredient").val().trim().toLowerCase();
                if($.inArray(Valeur, liste) != 0) {
                    liste.push(Valeur);
                }
                $("#nomIngredient").val('');
                $('#avecIngredient').prop("disabled", true);
                $('#sansIngredient').prop("disabled", true);
                $('#optionsCompletion').empty();
                $(idtable).append($("<tr><td>"+Valeur.charAt(0).toUpperCase() + Valeur.slice(1).toLowerCase()+"</td><td><input class='btnSupprimerCritere' type='button' value='x'></td></tr>"));
                $(this).rechercheRecettesCriteres();
            };

            $("#avecIngredient").click(function() {
                $(this).ajoutIngredientRecherche(ListeAvecIngredient, '#tableAvec');
            });

            $("#sansIngredient").click(function() {
                $(this).ajoutIngredientRecherche(ListeSansIngredient, '#tableSans');
            });

            $("table").on("click", "input.btnSupprimerCritere", function() {
                var tdActu = $(this).parent();
                var valeur = tdActu.prev().text().trim().toLowerCase();
                if($.inArray(valeur, ListeAvecIngredient) != -1)
                    ListeAvecIngredient.splice(ListeAvecIngredient.indexOf(valeur), 1);
                else if($.inArray(valeur, ListeSansIngredient) != -1)
                    ListeSansIngredient.splice(ListeSansIngredient.indexOf(valeur), 1);
                else {
                    alert("Element introuvable!");
                }
                tdActu.parent().remove();
                if(ListeAvecIngredient.length != 0 || ListeSansIngredient.length != 0)
                    $(this).rechercheRecettesCriteres();
                else
                    $('#ResultatsRecherche table > tbody').empty();;
            });
        });
        </script>
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

        <div id="Navigation">
            <nav>
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
            <datalist id="optionsCompletion" hidden></datalist>

            <h3>Critères de recherche</h3>
            <table id="tableAvec">
                <tr>
                    <td colspan="2">Avec l'ingrédient</td>
                </tr>
            </table>
            <table id="tableSans">
                <tr>
                    <td colspan="2">Sans l'ingrédient</td>
                </tr>
            </table>

            <div id="ResultatsRecherche">
                <table>
                    <thead>
                        <tr>
                            <td>Nom de la recette</td>
                            <td>Score de satisfaction</td>
                        </tr> 
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </body>

</html>