$(document).ready(function () {
    $("#confirmDeletePost").on("show.bs.modal", function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data("title"); // Extract info from data-* attributes
        var identifier = button.data("identifier"); // Extract info from data-* attributes
        var modal = $(this);
        modal.find(".modal-body").text(recipient);
        modal.find(".modal-footer a").attr("href", "/admin/posts/delete/" + identifier);
        setTimeout(() => scrollTo(0, 0), 500);
    });

    $("#confirmDeleteUser").on("show.bs.modal", function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data("title"); // Extract info from data-* attributes
        var identifier = button.data("identifier"); // Extract info from data-* attributes
        var modal = $(this);
        modal.find(".modal-body").text(recipient);
        modal.find(".modal-footer a").attr("href", "/admin/users/delete/" + identifier);
        setTimeout(() => scrollTo(0, 0), 500);
    });

    // Preview modals
    $("#postPreview").on("show.bs.modal", function (event) {
        scrollTo(0, 0);
        var button = $(event.relatedTarget); // Button that triggered the modal
        var title = button.data("title"); // Extract info from data-* attributes
        var chapo = button.data("chapo"); // Extract info from data-* attributes
        var url = button.data("url"); // Extract info from data-* attributes
        var identifier = button.data("identifier"); // Extract info from data-* attributes
        var modal = $(this);
        modal.find(".modal-title").text(title);
        if (chapo != "") {
            modal.find(".modal-chapo").html('<div id="postContent"><p>' + chapo + '</p></div>');
        }
        if (url != "") {
            modal.find(".modal-iframe").html('<iframe src="' + url + '" width="100%" height="100%"></iframe>');
            modal.find(".modal-footer .externalLink").removeClass("sr-only");
            modal.find(".modal-footer .externalLink").attr("href", url);
        } else {
            modal.find(".modal-iframe").html("");
            modal.find(".modal-footer .externalLink").addClass("sr-only");
        }
        modal.find(".modal-footer .editPost").attr("href", "/admin/posts/update/" + identifier);
        modal.find(".modal-footer .lunchDeleteModal").attr("data-identifier", identifier);
        modal.find(".modal-footer .lunchDeleteModal").attr("data-title", title);
        $(".lunchDeleteModal").on("click", function () {
            modal.modal("hide");
            scrollTo(0, 0);
        });
    });
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
});