$("#username").keyup(function(){
    var username = $(this).val().trim();
    if(username != ''){
        $.ajax({
            url: '/profil/ajax/checkUsername',
            type: 'post',
            data: {username:username},
            success: function(response){
            // Show response
            if(response == "1"){
                    $("#uname_response").html('Pseudo disponible');
                    $("#uname_response").css('color', 'green');
                }else{
                    $("#uname_response").html('Pseudo indisponible');
                    $("#uname_response").css('color', 'red');
                }
            }
        });
    }else{
        $("#uname_response").html("");
    }
});

$("#email").keyup(function(){
    var username = $(this).val().trim();
    if(username != ''){
        $.ajax({
            url: '/profil/ajax/checkUsername',
            type: 'post',
            data: {username:username},
            success: function(response){
                console.log(response);
            // Show response
            if(response == '1'){
                    $("#email_response").html('Email disponible');
                    $("#email_response").css('color', 'green');
                }else{
                    $("#email_response").html("Email indisponible");
                    $("#email_response").css('color', 'red');
                }
            }
        });
    }else{
        $("#email_response").html("");
    }
});