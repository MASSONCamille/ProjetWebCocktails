$(function () {

    var inputLogin = $("#tr_Login").find("input");
    inputLogin.bind("change keyup blur input" , function () {
        $.ajax({
            type:'POST',
            url:'script/verif_form.php',
            data: {
                Login: inputLogin.val(),
            },
            success : function (data) {
                inputLogin.css("background-color", data);
            },
        });
    });

});