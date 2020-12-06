$(function () {

    $("div#div_affichage > button#changer_vue").click(function () { // afficher le formulaire
        $.ajax({
            type:'POST',
            url:'../script/php/TestMdp.php',
            data: {
                mdp: $("input#VerifMdp").val(),
            },
            success : function (data) {
                if (data) {
                    $("div#div_form").show();
                    $("div#div_affichage").hide();
                    $("input#VerifMdp").css("background-color", "white");
                }else{
                    $("input#VerifMdp").css("background-color", "#ff0000");
                }
            },
            fail : function (error) {
                $("input#VerifMdp").css("background-color", "#ff0000");
                console.log(error); //DEBUGER
            }
        });
    });

    $("div#div_form > button#changer_vue").click(function () { // annuler le formulaire
        $("div#div_affichage").show();
        $("div#div_form").hide();
    });
});