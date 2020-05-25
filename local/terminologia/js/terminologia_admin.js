//Mostrar Termino Detalle
function showTerminoDetail(id) {
    $('.body-container-admin').addClass('hidden');
    $('.body-basic-container').removeClass('hidden');
    $('.termino-detail-container').removeClass('hidden');

    ajaxGetTerminoDetail(id);
}

$(document).ready(function(){

    var role = $('.role').val();

    //Agregar termino (get form)
    $('.terminos-sugeridos-button').on('click', function(e){
        e.preventDefault();

        $('.body-basic-container').addClass('hidden');
        $('.main-page-container-' + role).addClass('hidden');
        $('.body-maintenance-edit-container').addClass('hidden');
        $('.search-results-container').addClass('hidden');
        $('.letter-results-container').addClass('hidden');

        $('.body-container-admin').removeClass('hidden');
        $('.body-maintenance-container').removeClass('hidden');

        $("#termino-nombre").val('');
        $("#termino-origen").val('');
        $("#termino-descripcion").val('');
        $("#tmp-image-add").addClass('hidden');
    });

    //Agregar termino (submit)
    $('#termino-add-form').submit( function(e){
            e.preventDefault();

            var formData = new FormData(this);
            formData.append('request_type', 'add_termino');

            $.ajax({
                url: 'ajax_controller_termino.php',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.id != 0) {
                        alert('Se agregó el término correctamente');
                        showTerminoDetail(res.id);
                    }
                },
                error: function() {
                    alert("Ha ocurrido un error");
                },
            });
        });

    //Editar Termino (get form)
    $(document).on('click', '.edit-termino', function(){
        $('.body-basic-container').addClass('hidden');
        $('.termino-detail-container').addClass('hidden');
        $('.body-container-admin').removeClass('hidden');
        $('.body-maintenance-container').addClass('hidden');
        $('.body-maintenance-edit-container').removeClass('hidden');
        var imgName = '';

        var formValues = 'id=' + this.id + '&request_type=get_termino_detail';

        $.ajax({
            url: 'ajax_controller_termino.php',
            type: 'POST',
            data: formValues,
            dataType: 'json',
            timeout: 30000,
            success: function(res) {
                if (res.status === true) {
                    if (res.data) {
                        $('#termino-id').val(res.data.id);
                        $('#termino-nombre-edit').val(res.data.nombre);
                        $('#termino-origen-edit').val(res.data.origen);
                        $('#termino-descripcion-edit').val(res.data.descripcion);
                        $("#span-preview").removeClass('hidden');
                        $("#span-preview").css("display","inline-block");
                        $("#img-preview").removeClass('hidden');
                        $(".imageThumb").remove();
                        $(".pip").remove();

                        if(res.data.imageurl) {
                            imgName = res.data.imageurl;
                            $("#uploaded-image").val(1);
                        } else {
                            imgName = 'img/no-image.png';
                        }
                        $("#img-preview").attr("src", imgName);
                    }
                }
            },
            error: function() {
                alert("can't retrieve data");
            },
        });
    });

    //Editar Termino (submit)
    $('#termino-edit-form').submit( function(e){
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('request_type', 'edit_termino');

        $.ajax({
            url: 'ajax_controller_termino.php',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(res) {
                if (res.status) {
                    alert("Término modificado correctamente");
                    showTerminoDetail($("#termino-id").val());
                }
            },
            error: function() {
                alert("No se pudo editar el término correctamente");
            },
        });
    });

    //Eliminar Termino
    $(document).on('click', '.delete-termino', function(){
        if(confirm("Seguro que deseas eliminar este término?")){
            var terminoId = $(this).attr("id");

            var formValues = {
                request_type: 'delete_termino',
                id: terminoId
            };

            $.ajax({
                url: 'ajax_controller_termino.php',
                type: 'POST',
                data: formValues,
                dataType: 'json',
                timeout: 30000,
                success: function(res) {
                    if (res.status) {
                        alert("Término eliminado correctamente");
                        location.reload(true);
                    }
                },
                error: function() {
                    alert("No se pudo eliminar el término correctamente");
                },
            });
        }
        else{
            return false;
        }
    });

    //Cancelar
    $('.terminos-sugeridos-button-cancel').on('click', function(e){
        $(".pip").remove();
        $("#files").val('');
        $("#files-edit").val('');
        initPaginaPrincipal(role);
    });

    //Click Termino Sugerido
    $(document).on('click', '.sugeridos-list-item', function(e){
            $(".body-basic-container").addClass('hidden');
            $(".terminos-sugeridos-container").addClass('hidden');
            $(".body-maintenance-edit-container").addClass('hidden');
            $(".body-container-admin").removeClass('hidden');
            $(".body-maintenance-container").removeClass('hidden');
            $("#termino-nombre").val($(this).attr("nombre-sugerencia"));
            $("#termino-origen").val('');
            $("#termino-descripcion").val('');
        });

    //Delete Termino Sugerido
    $(document).on('click', '.termino_sugerido', function(e){
            e.stopPropagation();
            var $this = $(this);

            if(confirm("Seguro que deseas eliminar este término?")){
                var sugerenciaId = $(this).attr("id");

                var formValues = {
                    request_type: 'delete_sugerencia',
                    id: sugerenciaId
                };

                $.ajax({
                    url: 'ajax_controller_termino.php',
                    type: 'POST',
                    data: formValues,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(res) {
                        if (res.status) {
                            alert("Sugerencia eliminada correctamente");
                            $this.closest("tr").remove();
                        }
                    },
                    error: function() {
                        alert("No se pudo eliminar el término correctamente");
                    },
                });
            }
            else{
                return false;
            }
        });

});