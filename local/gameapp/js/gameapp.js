$(document).ready(function(){
   $('#procesar').on('click',function () {

      var formValues = {
         request_type: $('#request_name').val()
      };

      $.ajax({
         type: "POST",
         url: "ajax_controller_gameapp.php",
         data: formValues,
         success: function(res){
            if(res.status) {
               $("#results").val(res.data);
            }
         }
      });
   });
});