$(function () {
    $("div#div_fav").load("script/ListFav.php");

    $(document).on('click', '.btnsupp', function () {
        var idCocktail = this.id;
        $.ajax({
            type:'POST',
            url:'script/Favoris.funct.php',
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
        $("div#div_fav").load("script/ListFav.php");
    });
});