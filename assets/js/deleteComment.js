$('.delete-comment').click(function(e) {
    delete_ajax(e, 'delete');
});

$('.cancel-delete-comment').click(function (e) {
    delete_ajax(e, 'restore');
});

function delete_ajax(element, action) {
    let sentence = "";
    const button = $(element.currentTarget)[0];
    const id = button.dataset.id;
    const csrf = button.dataset.csrf;
    if (action == "delete") {
        sentence = "Voulez-vous vraiment supprimer ce commentaire ?";
    } else if (action == "restore") {
        sentence = "Voulez-vous vraiment annuler la suppression du commentaire?";
    }
    const validation = confirm(sentence);
    if (validation) {
        $.ajax({
            url: '/comment/ajax/' + action + '?csrf_token=' + csrf,
            type: 'post',
            data: {
                'commentId': id
            },
            success: function(result) {
                if (result == 'done') {
                    const panel = $(button).parent().parent().parent();
                    location.reload();
                } else {
                    alert("Une erreur est survenue. Réessayer plus tard")
                }
            }
        });
    }
}