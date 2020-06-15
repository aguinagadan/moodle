function showCoursesByCategory(catId) {

    var courseCatTable = $('.cc-course-cat-table');
    var courseCatDynamicTable = $(".cc-course-cat-table[data-categoryid='"+catId+"']");
    courseCatDynamicTable.addClass('active-cat');

    courseCatTable.addClass('hidden');
    courseCatTable.css('display','none');
    courseCatDynamicTable.css('display','block');
    return courseCatDynamicTable.removeClass('hidden');
}

function placeAfter($lastBlock, $currentBlock, catId) {
    $('.cc-block-container').css('background-color','transparent');

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

        var position = $currentBlock.find('.cc-main-category').offset();
        var positionLeft = position.left;
        var positionTop = position.top - 66;

        $('.moved-background').css('background-color','#ececec');

        var blockWidth = $currentBlock.find('.cc-main-category').width();
        var blockHeight = $currentBlock.find('.cc-main-category').height();

        $('.moved-background').width(blockWidth);
        $('.moved-background').height(blockHeight + 20);
        $('.moved-background').animate({ top: positionTop, left: positionLeft},100);

        $currentBlock.find('.cc-category-div-box').addClass('cc-shrink-category');
        $lastBlock.after(contenedorDeCurso.slideDown(function() {
            var cursoActual = $(".cc-courses-div-detail[category-id='"+catId+"']");
            cursoActual.css('display','inline-flex');
            $('.cc-block-container').removeClass('active-cat');
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

    $('.cc-block-container').on('click', function() {
        $chosen = $(this);
        var catId = $chosen.attr('category-id');

        var top = $(this).offset().top;
        var $blocks = $(this).nextAll('.cc-block-container');
        if ($blocks.length == 0) {
            placeAfter($(this), $chosen, catId);
            return false;
        }
        $blocks.each(function(i, j) {
            if($(this).offset().top != top) {
                placeAfter($(this).prev('.cc-block-container'), $chosen, catId);
                $(".cc-courses-div").css('display','inline-block');
                return false;
            } else if ((i + 1) == $blocks.length) {
                placeAfter($(this), $chosen, catId);
                return false;
            }
        });
    });

     // IMPLEMENTACION PREVIA

     // $('.cc-category-div-box').on('click', function() {
     //     var catId = $(this).attr('data-categoryid');
     //     var dynamicBoxHeight =$(".cc-course-cat-table[data-categoryid='"+catId+"']").height();
     //     $('.cc-main-category').removeClass('cc-shrink-category');
     //
     //     var newAddedTable = $(".cc-course-cat-table[data-categoryid='"+catId+"']");
     //
     //     if(newAddedTable.hasClass('active-cat')) {
     //         newAddedTable.slideToggle('fast');
     //         newAddedTable.removeClass('active-cat');
     //         newAddedTable.addClass( "hidden");
     //         $('.cc-category-table').css('margin-top',0);
     //         $('.cc-main-ultimos-container').css('margin-top',0);
     //         $('.moved-background').css('background-color','transparent');
     //     } else {
     //         $('.cc-category-table').css('margin-top',0);
     //         $('.cc-main-ultimos-container').css('margin-top',0);
     //
     //         if(newAddedTable.length != 0) {
     //             $(this).find('.cc-main-category').addClass('cc-shrink-category');
     //         }
     //
     //         $('.cc-course-cat-table').removeClass('active-cat');
     //         newAddedTable.slideToggle( "slow" );
     //
     //         var tableNumber = $(this).closest('.cc-category-table').attr('data-table-number');
     //         var tableNumberIdNext = parseInt(tableNumber)+1;
     //
     //         if($('#cc-total-cursos').val()/3 <= tableNumber) {
     //             var nextCategoryElement = $(".cc-main-ultimos-container");
     //         } else {
     //             var nextCategoryElement = $(".cc-category-table[data-table-number='"+tableNumberIdNext+"']");
     //         }
     //
     //         nextCategoryElement.css('margin-top',dynamicBoxHeight+55);
     //         nextCategoryElement.css('position','relative');
     //
     //         $(this).closest('.cc-category-table').after(showCoursesByCategory(catId));
     //
     //
     //         var position = $(this).offset();
     //         $('.moved-background').css('background-color','#ececec');
     //         $('.moved-background').animate({ top: position.top-66, left: position.left},250);
     //     }
     // });
});