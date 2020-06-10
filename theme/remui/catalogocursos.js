function showCoursesByCategory(catId) {

    var courseCatTable = $('.cc-course-cat-table');
    var courseCatDynamicTable = $(".cc-course-cat-table[data-categoryid='"+catId+"']");
    courseCatDynamicTable.addClass('active-cat');

    courseCatTable.addClass('hidden');
    courseCatTable.css('display','none');
    courseCatDynamicTable.css('display','block');
    return courseCatDynamicTable.removeClass('hidden');
}

$(document).ready(function(){
     $('.cc-category-div-box').on('click', function() {
         var catId = $(this).attr('data-categoryid');
         var dynamicBoxHeight =$(".cc-course-cat-table[data-categoryid='"+catId+"']").height();
         $('.cc-main-category').removeClass('cc-shrink-category');

         var newAddedTable = $(".cc-course-cat-table[data-categoryid='"+catId+"']");

         $('.cc-category-table').css('margin-top',0);
         $('.cc-main-ultimos-container').css('margin-top',0);

         if(newAddedTable.hasClass('active-cat')) {
             $('.moved-background').css('background-color','transparent');
             newAddedTable.removeClass('active-cat');
             newAddedTable.slideToggle( "slow" );
             newAddedTable.addClass( "hidden");
         } else {
             if(newAddedTable.length != 0) {
                 $(this).find('.cc-main-category').addClass('cc-shrink-category');
             }

             $('.cc-course-cat-table').removeClass('active-cat');
             newAddedTable.slideToggle( "slow" );

             var tableNumber = $(this).closest('.cc-category-table').attr('data-table-number');
             var tableNumberIdNext = parseInt(tableNumber)+1;

             if($('#cc-total-cursos').val()/3 <= tableNumber) {
                 var nextCategoryElement = $(".cc-main-ultimos-container");
             } else {
                 var nextCategoryElement = $(".cc-category-table[data-table-number='"+tableNumberIdNext+"']");
             }

             nextCategoryElement.css('margin-top',dynamicBoxHeight+55);
             nextCategoryElement.css('position','relative');

             $(this).closest('.cc-category-table').after(showCoursesByCategory(catId));
             var position = $(this).offset();
             $('.moved-background').css('background-color','#ececec');
             $('.moved-background').animate({ top: position.top-66, left: position.left},250);
         }
     });
});
