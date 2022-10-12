$("#username").keyup(function () {
    var username = $(this).val().trim();
    if (username != '') {
        $.ajax({
            url: '/profil/ajax/checkUsername',
            type: 'post',
            data: { username: username },
            success: function (response) {
                switch (response) {
                    case 'available':
                        $("#uname_response").html('Pseudo disponible');
                        $("#uname_response").css('color', 'green');
                        break;
                    case 'unavailable':
                    default:
                        $("#uname_response").html('Pseudo indisponible');
                        $("#uname_response").css('color', 'red');
                        break;
                }
            }
        });
    } else {
        $("#uname_response").html("");
    }
});

$("#email").keyup(function () {
    var username = $(this).val().trim();
    if (username != '') {
        $.ajax({
            url: '/profil/ajax/checkUsername',
            type: 'post',
            data: { username: username },
            success: function (response) {
                switch (response) {
                    case 'already':
                        $("#email_response").html("C'est déjà le votre !");
                        $("#email_response").css('color', 'orange');
                        break;
                    case 'available':
                        $("#email_response").html('Pseudo disponible');
                        $("#email_response").css('color', 'green');
                        break;
                    case 'unavailable':
                    default:
                        $("#email_response").html("Email indisponible");
                        $("#email_response").css('color', 'red');
                }
            }
        });
    } else {
        $("#email_response").html("");
    }
});