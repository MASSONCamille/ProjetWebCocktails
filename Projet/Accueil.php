<?php 
    include 'Donnees.inc.php';
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Recettes de cocktails</title>
        <meta charset="utf-8" />
    </head>

    <body>

        <h1>Les recettes de Mamille</h1> <!-- Lien vers l'accueil -->

        <header>
            <ul>
                <li>Favoris</li> <!-- Lien vers les favoris -->
                <li>Se connecter</li> <!-- Devine -->
                <li>S'inscrire</li> <!-- Même page que la connection -->
                <!-- Si connecter afficher lien vers profil et déconnection -->
                <li>Mon compte</li> <!-- Dans le si connecter -->
                <li>Se déconnecter</li> <!-- Dans le si connecter -->
            </ul>
        </header>

        <div> <!-- Hiérarchie Lorian -->
            <ul>
                <?php
                    foreach($Hierarchie as $NomCateg => $TabSousCateg) { ?>
                        <li>
                            <?php 
                                foreach($TabSousCateg as $NomSousCateg => $SousCateg) {
                                    if($NomSousCateg != )
                                    echo $NomCateg; 
                                }
                            ?>
                        </li>
                <?php }
                ?>
            </ul>
        </div>

    </body>

</html>