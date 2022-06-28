$("#confirmDelete").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var recipient = button.data("title"); // Extract info from data-* attributes
    var identifier = button.data("identifier"); // Extract info from data-* attributes
    var modal = $(this);
    modal.find(".modal-body").text(recipient);
    modal.find(".modal-footer a").attr("href", "?action=postDelete&id=" + identifier);
    // modal.find('.modal-title').text(recipient)
    // modal.find('.modal-body input').val(recipient)
});
