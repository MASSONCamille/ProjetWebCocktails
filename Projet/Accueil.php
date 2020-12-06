<?php
    //Include des données et fonctions nécessaires aux recherches
    include 'Donnees.inc.php';
    include 'Recherche.funct.php';

    session_start();

    if(isset($_GET['Deconnexion']) && $_GET['Deconnexion'] == true) //Vérifie si une demande de déconnexion a été faite
        unset($_SESSION['Login']);

    if(!isset($_GET['Position'])) //Attribut la position dans la hiérarchie
        $Position = 'Aliment';
    else
        $Position = $_GET['Position'];

    if((!isset($_SESSION['CheminAcces'])) || ($Position == 'Aliment')) //On initialise le parcours dans la hiérarchie à Aliment si besoin
        $CheminAcces = array('Aliment');
    else { //Sinon on récupère le parcours effectué jusqu'ici
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

        <!-- PARTIE JQUERY DE LA PAGE ACCUEIL -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
        $(function(){
            var ListeAvecIngredient = [];
            var ListeSansIngredient = [];
            var DivRechercheActu = 1;

            $("#nomRecette").keyup(function() { //Permet de chercher les noms de recettes commençant par le nom entré
                var regex = new RegExp('\\b' + $(this).val().toLowerCase());
                $("#ListeRecettes li").filter(function() {
                    $(this).toggle(regex.test($(this).text().toLowerCase()));
                });
            });

            $("#nomIngredient").keyup(function() { //Permet d'ajouter à la datalist optionsCompletion les ingrédients commençant par le nom entré
                $('#avecIngredient').prop("disabled", true); //Désactivation des boutons d'ajouts des ingrédients
                $('#sansIngredient').prop("disabled", true);
                $('#optionsCompletion').empty(); //Vidage de la datalist
                if($(this).val().trim() !== "") {
                    $.get("script/get_tags_recherche.php?DebutRecherche="+$(this).val(), function(Data) { //On récupère la liste en envoyant le nom au php
                        var Liste = JSON.parse(Data);
                        var ListeRecherche = $.map(Liste, function(Val,i){return Val.toLowerCase();}); //On crée des listes de recherches à partir des listes des critères et de la liste du php
                        var ListeRechercheAvec = $.map(ListeAvecIngredient, function(Val,i){return Val.toLowerCase();});
                        var ListeRechercheSans = $.map(ListeSansIngredient, function(Val,i){return Val.toLowerCase();});                       
                        $.each(Liste, function(Cle, Valeur) { //On vérifie pour chaque membre (suggestion) de la liste php si elle existe déjà dans les listes de critères pour pouvoir les garder ou non
                            if(!ListeRechercheAvec.includes(Valeur.toLowerCase()) && !ListeRechercheSans.includes(Valeur.toLowerCase())) {
                                $('#optionsCompletion').append($("<option></option>").text(Valeur));
                            }
                        });

                        var ValRecherche = $("#nomIngredient").val().trim().toLowerCase();
                        if(ListeRecherche.includes(ValRecherche) && !ListeRechercheAvec.includes(ValRecherche) && !ListeRechercheSans.includes(ValRecherche)) { //On vérifie que le nom entré est dans la liste 
                            $('#avecIngredient').prop("disabled", false);                                                                                       //des suggestions et dans aucune des listes des critères
                            $('#sansIngredient').prop("disabled", false); 
                        } 
                    });
                }
            });

            $.fn.afficherRecettesResultats = function(ResTable) { //Fonction qui permet de remplir la table avec les résultats de la recherche
                TableAffiche = $('#ResultatsRecherche table > tbody');
                TableAffiche.empty();
                $.each(ResTable, function(Cle, Valeur) {
                    TableAffiche.append("<tr><td>"+Valeur['Nom']+"</td><td>"+Valeur['Score']+"</td></tr>");
                });
            }

            $.fn.rechercheRecettesCriteres = function() { //Fonction qui envoie les listes de critères et puis reçoit la liste réponse de la recherche
                $.post("script/recherche_recettes_criteres.php", { 'IngredientsAvec': ListeAvecIngredient, 'IngredientsSans': ListeSansIngredient }, function(Data) {
                    $(this).afficherRecettesResultats(Data);
                }, "json").fail(function(Data) {
                    console.log(Data.responseText);
                    alert("Erreur dans l'envoie de la recherche. Le serveur est peut-être en maintenance.")
                });
            }

            $.fn.ajoutIngredientRecherche = function(liste, idtable) { //Fonction qui ajoute les ingrédients critères à la table correspondante
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

            $("#avecIngredient").click(function() { //Fonction (onClick) du bouton Avec qui ajoute le nom entré à la table de critère correspondant
                $(this).ajoutIngredientRecherche(ListeAvecIngredient, '#tableAvec');
            });

            $("#sansIngredient").click(function() { //Fonction du bouton Sans qui ajoute le nom entré à la table de critère correspondant
                $(this).ajoutIngredientRecherche(ListeSansIngredient, '#tableSans');
            });

            $("table").on("click", "input.btnSupprimerCritere", function() { //Fonction (onClick) des boutons supprimer qui retire le critère associé de sa table
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

            $('#BtnChangerRecherche').click(function() { //Bouton qui permet de passer d'une recherche à l'autre (affiche l'un des deux divs)
                var DivNavigation = $("#Navigation");
                var DivCritere = $("#Recherche");
                if(DivRechercheActu) {
                    DivNavigation.hide(250);
                    DivCritere.show(250);
                    DivRechercheActu = 0;
                }  
                else {
                    DivCritere.hide(250);  
                    DivNavigation.show(250);
                    DivRechercheActu = 1;
                }
                
            });
        });
        </script>
    </head>

    <body>

        <header> <!-- Header qui permet d'accéder aux différentes pages de l'application -->
            <h1><a href="Accueil.php">Recettes de Cocktailes</a></h1>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="Favoris.php">Favoris</a></li>
                <?php if(!isset($_SESSION['Login']) || $_SESSION['Login'] === "") { ?> <!-- Affiche l'accès aux pages connexion, inscription et déconnexion en fonction de si l'utilisateur est connecté -->
                    <li><a href="Connexion.php">Se connecter</a></li>
                    <li><a href="Inscription.php">S'inscrire</a></li>
                <?php }
                if(isset($_SESSION['Login']) && $_SESSION['Login'] !== "") { ?>
                    <li><a href="Utilisateur.php">Mon compte</a></li> <!-- Dans le si connecter -->
                    <li><a href="<?php echo $_SERVER['PHP_SELF']."?Deconnexion=true"; ?>">Se déconnecter</a></li>
                <?php } ?>
            </ul>
        </header>

        <input id="BtnChangerRecherche" type="button" value="Changer de recherche"/>
            <div id="Navigation"> <!-- PARTIE RECHERCHE AVEC PARCOURS -->
                <nav>
                    <h2>Navigation</h2>
                    <p>
                        <?php //Affiche le chemin d'accès (en fonction de la hiérarchie)
                            foreach($CheminAcces as $Element) { ?>
                                <a href="<?php echo $_SERVER['PHP_SELF']."?Position=".$Element; ?>"><?php echo $Element; ?> /</a>
                        <?php }
                        ?>
                    </p>
                    <ul>
                        <?php
                            foreach($Hierarchie as $NomCateg => $TabSousCateg) { //Affiche les différents chemins possibles
                                foreach($TabSousCateg as $NomSousCateg => $SousCateg) {
                                    if($NomSousCateg == 'super-categorie') {
                                        if(in_array($Position, $SousCateg)) { ?>
                                            <li><a href="<?php echo $_SERVER['PHP_SELF']."?Position=".$NomCateg; ?>"> <?php echo $NomCateg; ?></a></li>
                                        <?php }
                                    }
                                }
                            }
                        ?>
                    </ul>
                </nav>

                <div id="NavRes">
                    <h2>Recettes</h2>
                    <input id="nomRecette" type="text" placeholder="Recherche par nom"/>
                    <ul id="ListeRecettes">
                        <?php //Affiche les recettes accessibles depuis l'emplacement dans la hiérarchie
                            foreach($RecettesRecherche as $CleRecette => $Recette) { ?>
                                <li><a href="<?php echo "Recettes.php"."?Recette=".$CleRecette; ?>"><?php echo $Recette; ?></a></li>
                        <?php }
                        ?>
                    </ul>
                </div>
            </div>

            <div id="Recherche" style="display: none;"> <!-- PARTIE RECHERCHE AVEC CRITERES -->
                <div>
                    <h3>Critères de recherche</h3>
                    <input id="nomIngredient" type="text" placeholder="Recherche par ingrédient" list="optionsCompletion"/>
                    <input id="avecIngredient" type="button" value="Avec" disabled/>
                    <input id="sansIngredient" type="button" value="Sans" disabled/>
                    <datalist id="optionsCompletion" hidden></datalist>
                </div>

                <div id="RechercheCriteres">
                    <table id="tableAvec">
                        <tr>
                            <td colspan="2">Avec l'ingrédient</td> <!-- Tables des ingrédients Avec -->
                        </tr>
                    </table>
                    <table id="tableSans">
                        <tr>
                            <td colspan="2">Sans l'ingrédient</td> <!-- Tables des ingrédients Sans -->
                        </tr>
                    </table>
                </div>

                <div id="ResultatsRecherche">
                    <table> <!-- Table des recettes de la recherche -->
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