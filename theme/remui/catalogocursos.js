function placeAfter($lastBlock, $currentBlock, catId) {
    $('.cc-category-container').css('background-color','transparent');

    var contenedorDeCurso = $('.cc-courses-div');
    var cursosTotal = $('.cc-courses-div-detail');
    cursosTotal.hide();

    if($currentBlock.hasClass('active-cat')) {
        $('.moved-background').css('background-color','transparent');
        $currentBlock.removeClass('active-cat');
        $currentBlock.find('.cc-category-div-box').removeClass('cc-shrink-category');
        $('.cc-courses-div').slideUp();
    } else {
        $('.cc-category-div-box').removeClass('cc-shrink-category');

        var position = $currentBlock.find('.cc-category-image').offset();
        var positionLeft = position.left;
        var positionTop = position.top - 66;

        $('.moved-background').css('background-color','#ececec');

        var blockWidth = $currentBlock.find('.cc-category-image').width();
        var blockHeight = $currentBlock.find('.cc-category-image').height();

        $('.moved-background').width(blockWidth);
        $('.moved-background').height(blockHeight + 22);
        $('.moved-background').animate({ top: positionTop, left: positionLeft},200);

        $currentBlock.find('.cc-category-div-box').addClass('cc-shrink-category');
        $lastBlock.after(contenedorDeCurso.slideDown(function() {
            var cursoActual = $(".cc-courses-div-detail[category-id='"+catId+"']");
            cursoActual.css('display','inline-flex');
            $('.cc-category-container').removeClass('active-cat');
            $currentBlock.addClass('active-cat');
        }));
    }
}

$(document).ready(function(){

    var $chosen = null;

    $(window).on('resize', function() {
        if ($chosen != null) {
            $(".cc-courses-div").css('display','none');
            $('body').append($(".cc-courses-div"));
            $chosen.trigger('click');
        }
    });

    $('.cc-category-container').on('click', function() {
        $chosen = $(this);
        var catId = $chosen.attr('category-id');

        var top = $(this).offset().top;
        var $blocks = $(this).nextAll('.cc-category-container');
        if ($blocks.length == 0) {
            placeAfter($(this), $chosen, catId);
            return false;
        }
        $blocks.each(function(i, j) {
            if($(this).offset().top != top) {
                placeAfter($(this).prev('.cc-category-container'), $chosen, catId);
                $(".cc-courses-div").css('display','inline-block');
                return false;
            } else if ((i + 1) == $blocks.length) {
                placeAfter($(this), $chosen, catId);
                return false;
            }
        });
    });
});