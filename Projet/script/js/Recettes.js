$(function () {

    $.ajax({
        type:'POST',
        url:'../php/Favoris.funct.php',
        data: {
            mode: "test",
            id: idCocktail,
        },
        success : function (data) {
            if(data) $("#btn_fav").text("Supprimer des Favoris");
            else $("#btn_fav").text("Ajouter aux Favoris");
        },
        error : function () {
            console.log("error");
        },
    });


    $("#btn_fav").click(function () {
        $.ajax({
            type:'POST',
            url:'../php/Favoris.funct.php',
            data: {
                mode: "mod",
                id: idCocktail,
            },
            success : function (data) {
                if(data == "del") $("#btn_fav").text("Ajouter aux Favoris");
                else $("#btn_fav").text("Supprimer des Favoris");
            },
            error : function () {
                console.log("error");
            },
        });
    });
});