// DELETE FIELD
$('.emptyOne').each(function() {
    $(this).click(function(e) {
        e.preventDefault();
        $(this).parent().find('textarea').val('');
    });
});

// DELETE IMAGE
$('#btnOverview').click(function(e){
    e.preventDefault();
    var target = "{{ getPath('admin:config:delete:value', {value: config.identifier}) }}";
    var csrf = "{{ getCsrf() }}";
    if (confirm("Voulez-vous vraiment supprimer cette image?")){
        $.ajax({
            url: target,
            type: 'post',
            data: { 'csrf_token': csrf },
            success: function (result) {
                console.log(result);
                if (result == 'done') {
                    location.reload();
                } else {
                    alert("Une erreur est survenue. Réessayer")
                }
            }
        });
    };
});

// SHOW SELECTED CONTENT
$('#type').change(function(){
    $('#no_content').prop('disabled', true);
    $('#mytextarea').prop('disabled', true);
    $('.tox-tinymce').hide();
    $('#picture').prop('disabled', true);
    $('#image').hide();
    $('#select_template').prop('disabled', true);
    $('#template').hide();
    switch ($(this).val()) {
        case 'content':
            $('.tox-tinymce').show();
            $('#mytextarea').prop('disabled', false);
            break;
        case 'image':
            $('#image').show();
            $('#picture').prop('disabled', false);
            break;
        case 'template':
            $('#template').show();
            $('#select_template').prop('disabled', false);
            break;
        default:
            $('#no_content').prop('disabled', false);
            break;
    }
});

// CHANGE TEMPLATE OVERVIEW
$('document').ready(function(){
changeTemplateOverview($('#select_template').val());
});
$('#select_template').change(function(){
    changeTemplateOverview($(this).val());
});
const template_contact = '<h3>Titre</h3><ul class="list-unstyled"><li><i class="fa fa-map-marker fa-fw"></i> 1 rue de la libération 75000 PARIS</li><li><i class="fa fa-phone fa-fw"></i> <a>0123456789</a></li><li><i class="fa fa-envelope-o fa-fw"></i> <a>votre@adresse.fr</a></li><li class="page-scroll"><a>Formulaire de contact</a></li></ul>';
const template_reseaux = '<ul class="list-inline"><li><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-github"></i></a></li><li><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-linkedin"></i></a></li><li><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-instagram"></i></a></li><li><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-facebook"></i></a></li><li><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-twitter"></i></a></li><li><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-youtube"></i></a></li><li><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-dribbble"></i></a></li><li><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-google-plus"></i></a></li></ul>';
const template_contact_reseaux = template_contact + template_reseaux;
function changeTemplateOverview(template) {
    switch (template) {
        case '[%contact%]':
            $('#template_overview').html(template_contact);
            break;
        case '[%reseaux%]':
            $('#template_overview').html(template_reseaux);
            break;
        case '[%contact+reseaux%]':
            $('#template_overview').html(template_contact_reseaux);
            break;
        default:
            $('#template_overview').html('Selectionnez un template pour voir un aperçu');
            break;
    }
}
