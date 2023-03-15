$(document).ready(function () {
    $("#commentPreview").on("show.bs.modal", function (event) {
        scrollTo(0, 0);
        var button = $(event.relatedTarget); // Button that triggered the modal
        var identifier = button.data("identifier"); // Extract info from data-* attributes
        var content = button.data("content"); // Extract info from data-* attributes
        var post = button.data("post"); // Extract info from data-* attributes
        var postid = button.data("postid"); // Extract info from data-* attributes
        var title = button.data("title"); // Extract info from data-* attributes
        var modal = $(this);
        modal.find(".modal-title").text(title);
        modal.find(".modal-post").text("Post: " + post + " (id: " + postid + ")");
        modal.find(".modal-comment-content").text(content);
    });

    // Check/uncheck all checkboxes
    $('#checkAll').change(function () {
        if ($(this).is(':checked')) {
            $('.checkbox').prop('checked', true);
            $('.multiAction').show();
        } else {
            $('.checkbox').prop('checked', false);
            $('.multiAction').hide();
        }
    });

    // If one checkbox is checked, show the actions
    $('.multiAction').hide();
    $('.checkbox').change(function () {
        if ($('.checkbox:checked').length > 0) {
            $('.multiAction').show();
        } else {
            $('.multiAction').hide();
        }
    });

    // Confirm action
    $('.confirm').click(function (e) {
        if (!confirm("Voulez-vous vraiment effectuer cette action?")) {
            e.preventDefault();
        };
    });

    $('.delete-pic').click(function (e) {
        e.preventDefault();
        var target = $(this).data('target');
        var csrf = $(this).data('csrf');
        if (confirm("Voulez-vous vraiment supprimer cette image?")) {
            $.ajax({
                url: target,
                type: 'post',
                data: {
                    'csrf_token': csrf
                }
            }).done(function () {
                location.reload();
            }).fail(function () {
                alert("Une erreur est survenue. Réessayer")
            });
        };
    });

    $('.ajax-delete').click(function (e) {
        e.preventDefault();
        var target = $(this).data('target');
        var csrf = $(this).data('csrf');
        if (confirm("Voulez-vous vraiment supprimer cet élément?")) {
            $.ajax({
                url: target,
                type: 'post',
                data: {
                    'csrf_token': csrf
                },
                success: function (result) {
                    if (result == 'done') {
                        location.reload();
                    } else {
                        alert("Une erreur est survenue. Réessayer")
                    }
                }
            });
        }
    });
});