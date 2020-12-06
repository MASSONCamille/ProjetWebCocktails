$(function () {
    $("div#div_fav").load("../script/php/ListeFav.php"); // recuperer le list

    $(document).on('click', '.btnsupp', function () { // supprimer un favori
        var idCocktail = this.id;
        $.ajax({
            type:'POST',
            url:'../script/php/Favoris.funct.php',
            data: {
                mode: "del",
                id: idCocktail,
            },
            success : function (data) {
                if (!data) console.log("erreur de retour");
            },
            error : function () {
                console.log("error");
            },
        });
        $("div#div_fav").load("../script/php/ListeFav.php");
    });
});