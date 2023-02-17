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
            }
        }).done(function () {
            location.reload();
        }).fail(function () {
            alert("Une erreur est survenue. Réessayer plus tard")
        });
    }
}