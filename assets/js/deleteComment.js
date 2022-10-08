$('.delete-comment').click(function(e) {
    const button = $(e.currentTarget)[0];
    const id = button.dataset.id;
    const validation = confirm("Voulez-vous vraiment supprimer ce commentaire ?");
    if (validation) {
        $.ajax({
            url: '/comment/delete',
            type: 'post',
            data: {
                'commentId': id
            },
            success: function(result) {
                if (result == 1) {
                    const panel = $(button).parent().parent().parent();
                    location.reload();
                } else {
                    alert("Une erreur est survenue. Réessayer plus tard")
                }
            }
        });
    }
});

$('.cancel-delete-comment').click(function (e) {
    const button = $(e.currentTarget)[0];
    const id = button.dataset.id;
    const validation = confirm(
        "Voulez-vous vraiment annuler la suppression du commentaire?",
        "Le commentaire sera à nouveau soumis à la modération"
    );
    if (validation) {
        $.ajax({
            url: '/comment/cancelDelete',
            type: 'post',
            data: {
                'commentId': id
            },
            success: function(result) {
                if (result == 1) {
                    location.reload()
                } else {
                    alert("Une erreur est survenue. Réessayer plus tard")
                }
            }
        });
    }
});