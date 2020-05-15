$(document).ready(function() {

    //Cancelar
    $('.terminos-sugeridos-button-cancel').on('click', function(e){
        $(".body-maintenance-container").addClass('hidden');
        $(".body-maintenance-edit-container").addClass('hidden');
        $(".body-basic-container").removeClass('hidden');
    });

    //Sugerir palabra
    $('#sugerencia-add-form').submit( function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('request_type', 'add_sugerencia');

        $.ajax({
            url: 'ajax_controller_termino.php',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(res) {
                if (res.status > 0) {
                    alert('Se sugirió el término correctamente al administrador.');
                    $("#sugerencia-input").val('');
                }
            },
            error: function() {
                alert("No se pudo agregar la sugerencia");
            },
        });
    });

});