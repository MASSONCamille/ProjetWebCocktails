$(function () {

    var colorBon = "#00ff00";
    var colorFaux = "#ff0000";


    $("input#Login").bind("change keyup blur input focusout" , function () { // verifier si le chant est bon
        var login = $("input#Login").val().trim();

        if (login.length == 0){
            $("input#Login").css("background-color", "white");
        }else if(login.length < 3){
            $("input#Login").css("background-color", colorFaux);
        }else{
            $.ajax({
                type:'POST',
                url:'../script/php/VerifForm.php',
                data: {
                    Login: login,
                },
                success : function (data) {
                    if (document.title == "Connexion"){ // si le form est un form de connexion
                        if (data) $("input#Login").css("background-color", colorBon);
                        else $("input#Login").css("background-color", colorFaux);
                    }else if(document.title == "Inscription"){ // si le form est un form d'inscription
                        if (data) $("input#Login").css("background-color", colorFaux);
                        else $("input#Login").css("background-color", colorBon);
                    }
                },
                error : function () {
                    $("input#Login").css("background-color", "white");
                },
            });
        }

    });


    $("#viewpw").click(function () { // affichage du mdp
        if ($("input#Mdp").attr("type") == "password") $("input#Mdp").attr("type", "text");
        else $("input#Mdp").attr("type", "password");
    });
});