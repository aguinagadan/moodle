//Mostrar página principal
function initPaginaPrincipal(role) {
    //Show basic container
    $('.body-basic-container').removeClass('hidden');
    //Show main admin/guest page
    $('.main-page-container-' + role).removeClass('hidden');
    $('.terminos-sugeridos-container').removeClass('hidden');

    //Hide
    $('.search-results-container').addClass('hidden');
    $('.letter-results-container').addClass('hidden');
    $('.termino-detail-container').addClass('hidden');
    $('.sugerencia-letter-results-container').addClass('hidden');
    $('.body-container-' + role).addClass('hidden');
}

//Construir HTML de resultado de búsqueda
function buildHTMLSearchResult(data, groupedNumber) {
    var resHTML= '';
    var current = 0;
    var rowCount = 0;
    var countForColor = 0;
    var color = ''

    $.each(data, (i, item) => {
        countForColor++;

        if(countForColor === 1) {
            color = "#184A7D";
        }else if(countForColor === 2) {
            color = "#4DB8E8";
        }else if(countForColor === 3) {
            color = "#76A140";
        }

        if(current === 0 ||  current%groupedNumber === 0) {
            resHTML += '<tr>';
        }
        resHTML += '<td><div id="'+ item.id +'" class="result-value" type="button" style="background-color:'+color+';">' +
            '<span style="font-weight: bold;">' + item.nombre.toUpperCase() + '<br/></span>' +
            '<span style="font-size: 80%;">' + item.origen.toUpperCase() + '</span></div></td>';
        rowCount++;
        if(rowCount%groupedNumber === 0) {
            resHTML += '</tr>';
            countForColor = 0;
        }
        current++;
    });

    return resHTML;
}

//Mostrar Resultados de busqueda
function showLetterResults(letter, role, showLetter) {
    //Hide
    if(role != 'admin') {
        $('.main-page-container-' + role).addClass('hidden');
    }

    $('.body-container-' + role).addClass('hidden');
    $('.termino-detail-container').addClass('hidden');
    $('.sugerencia-letter-results-container').removeClass('hidden');

    if(showLetter) {
        $('.search-results-container').addClass("hidden");
        $('.letter-results-container').removeClass('hidden');
        $('.big-letter-results-container').removeClass('hidden');
        $('.big-letter-results-container').text(letter);
    } else {
        $('.letter-results-container').addClass('hidden');
        $('.search-results-container').removeClass("hidden");
        $('.search-results-container-title').text("Resultados de la búsqueda");
    }

    var formValues = {
        letra: letter,
        request_type: 'get_termino'
    };

    $.ajax({
        url: 'ajax_controller_termino.php',
        type: 'POST',
        data: formValues,
        dataType: 'json',
        timeout: 30000,
        success: function(res) {
            if (res.status === true) {
                if(!showLetter) {
                    $(".search-results-subtitle").text('Se encontró ' + Object.keys(res.data).length + ' término(s) con la palabra "' + letter + '"');
                    $(".big-letter-results-container").addClass('hidden');
                    $(".letter-results-container").removeClass('hidden');
                }
                var trHTML = buildHTMLSearchResult(res.data, 3);
                $('.list-letter-results-table').html(trHTML);
            }
        },
        error: function() {
            alert("can't retrieve data");
        },
    });
}

//Agregar Visita
function addVisitaTermino(id) {

    var formValues = {
        'request_type' : 'add_termino_visit',
        'id' : id
    };

    $.ajax({
        url: 'ajax_controller_termino.php',
        type: 'POST',
        data: formValues,
        dataType: 'json',
        timeout: 30000
    });
}

//Get Termino Detalle
function ajaxGetTerminoDetail(id) {
    var formValues = 'id=' + id + '&request_type=get_termino_detail';
    var nombreUppercase = '';
    var imgName = '';

    $.ajax({
        url: 'ajax_controller_termino.php',
        type: 'POST',
        data: formValues,
        dataType: 'json',
        timeout: 30000,
        success: function(res) {
            if (res.status === true) {
                if (res.data) {
                    if(res.data.nombre) {
                        nombreUppercase = res.data.nombre.toUpperCase();
                    }

                    $('.termino-detail-container-action').html(
                        '<button id="' + res.data.id + '" class="btn edit-termino">' +
                        '<i class="fa fa-pencil edit-delete-buttons"></i>' +
                        '</button>' +
                        '<button id="' + res.data.id + '" class="btn delete-termino">' +
                        '<i class="fa fa-trash edit-delete-buttons"></i>' +
                        '</button>'
                    );

                    $('.termino-detail-container-name').html(nombreUppercase);
                    $('.termino-detail-container-origin').html(res.data.origen);
                    $('.termino-detail-container-description').html(res.data.descripcion);

                    if(res.data.imageurl) {
                        imgName = res.data.imageurl;
                    } else {
                        imgName = 'img/no-image.png';
                    }
                    $('.custom-file-upload').removeClass('hidden');
                    $('#tmp-image-add').addClass('hidden');
                    $("#img-detail").attr("src", imgName);
                }
            }
        },
        error: function() {
            alert("can't retrieve data");
        },
    });
}

$(document).ready(function(){

    var role = $('.role').val();

    //Click Titulo
    $('.title-link').on('click', function(e){
        initPaginaPrincipal(role);
    });

    //Click LETRA
    $('ul.pagination li a').on('click',function(e){
        e.preventDefault();
        showLetterResults($(this).text(), role, true);
        $('.page-link-terminologia').removeClass('selected');
        $(this).addClass('selected');
    });

    //Escribir Termino y Buscar término
    $('.search-input').bind('click keyup', function(e){

        //if press enter
        if (e.keyCode === 13) {
            var text = $(this).val();
            showLetterResults(text, role, false);
        } else {
            $('.search-dropdown').remove();
            var formValues = {
                request_type: 'get_termino_busqueda',
                keyword: $(this).val()
            };

            $.ajax({
                type: "POST",
                url: "ajax_controller_termino.php",
                data: formValues,
                success: function(res){
                    if(res.data) {
                        $("#example-search-input").after(res.data);
                    }
                }
            });
        }
    });

    //Click en Termino encontrado por búsqueda en DROPDOWN
    $(document).on('click', '.result-search-element', function() {

        var idTermino = $(this).attr('id');

        $('.search-input').val($(this).attr('termino'));
        $('.search-dropdown').remove();

        $('.main-page-container-' + role).addClass('hidden');
        $('.body-container-' + role).addClass('hidden');

        addVisitaTermino(idTermino);
        showLetterResults($(this).attr('termino'), role, false);
    });

    //Buscar Termino (click en boton)
    $(".search-button").on('click', function(){
        var text = $('.search-input').val();
        showLetterResults(text, role, false);
    });

    //Click en Termino y mostrar detalle
    $(document).on('click', '.result-value', function(){
        $('.main-page-container-' + role).addClass('hidden');
        $('.body-container-' + role).addClass('hidden');
        $('.search-results-container').addClass('hidden');
        $('.letter-results-container').addClass('hidden');
        $('.sugerencia-letter-results-container').addClass('hidden');
        $('.termino-detail-container').removeClass('hidden');
        addVisitaTermino($(this).attr('id'));
        ajaxGetTerminoDetail($(this).attr('id'));
    });

    $("#files").on("change", function(e) {
        var files = e.target.files,
            filesLength = files.length;

        for (var i = 0; i < filesLength; i++) {
            var f = files[i]
            var fileReader = new FileReader();
            fileReader.onload = (function(e) {
                var file = e.target;
                $("<span class=\"pip\">" +
                    "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                    "<br/><img class=\"remove\"\ src=\"img/close-icon.png\">" +
                    "</span>").insertAfter("#files");
                $(".remove").click(function(){
                    $("#files").val('');
                    $(this).parent(".pip").remove();
                });
            });
            fileReader.readAsDataURL(f);
        }
    });

    $("#remove-preview").click(function() {
        $("#files-edit").val('');
        $("#uploaded-image").val(0);
        $("#span-preview").css("display","none");
        $("#span-preview").addClass("hidden");
    });

    $("#files-edit").on("change", function(e) {
        $("#img-preview").addClass('hidden');
        var files = e.target.files,
            filesLength = files.length;

        for (var i = 0; i < filesLength; i++) {
            var f = files[i]
            var fileReader = new FileReader();
            fileReader.onload = (function(e) {
                var file = e.target;
                $("<span class=\"pip\">" +
                    "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                    "<br/><img class=\"remove\"\ src=\"img/close-icon.png\">" +
                    "</span>").insertAfter("#files-edit");
                $(".remove").click(function(){
                    $("#files-edit").val('');
                    $("#uploaded-image").val(0);
                    $(this).parent(".pip").remove();
                });
            });
            fileReader.readAsDataURL(f);
        }
    });
});