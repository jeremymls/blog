$(document).ready(function () {
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
                            $("button[type='submit']").removeClass('disabled');
                            break;
                        case 'already':
                            $("#uname_response").html('C\'est déjà le votre !');
                            $("#uname_response").css('color', 'orange');
                            $("button[type='submit']").removeClass('disabled');
                            break;
                        case 'unavailable':
                        default:
                            $("#uname_response").html('Pseudo indisponible');
                            $("#uname_response").css('color', 'red');
                            $("button[type='submit']").addClass('disabled');
                            break;
                    }
                }
            });
        } else {
            $("#uname_response").html("");
            $("button[type='submit']").removeClass('disabled');
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
                            $("button[type='submit']").removeClass('disabled');
                            break;
                        case 'available':
                            $("#email_response").html('Email disponible');
                            $("#email_response").css('color', 'green');
                            $("button[type='submit']").removeClass('disabled');
                            break;
                        case 'unavailable':
                        default:
                            $("#email_response").html("Email indisponible");
                            $("#email_response").css('color', 'red');
                            $("button[type='submit']").addClass('disabled');
                    }
                }
            });
        } else {
            $("#email_response").html("");
            $("button[type='submit']").removeClass('disabled');
        }
    });
    $("button[type='submit']").click(function () {
        if ($(this).hasClass('disabled')) {
            return false;
        }
    });
});