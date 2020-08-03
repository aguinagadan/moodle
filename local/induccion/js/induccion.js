$(document).ready(function(){
   $('#procesar').on('click',function () {

      var formValues = {
         request_type: $('#request_name').val()
      };

      $.ajax({
         type: "POST",
         url: "ajax_controller_induccion.php",
         data: formValues,
         success: function(res){
            if(res.status) {
               $("#results").val(res.data);
            }
         }
      });
   });

   $('#procesarMillas').on('click',function () {

      var formValues = {
         cursoId: $('#cursoId').val(),
         userId: $('#userId').val(),
         xp: $('#xp').val(),
         request_type: 'grabarMillas'
      };

      $.ajax({
         type: "POST",
         url: "ajax_controller_induccion.php",
         data: formValues,
         success: function(res){
            if(res.status) {
               $("#procesarMillasRes").text('Grabado con éxito');
            }
         }
      });
   });

   $('#obtenerMillas').on('click',function () {

      var formValues = {
         cursoIdGet: $('#cursoIdGet').val(),
         userIdGet: $('#userIdGet').val(),
         request_type: 'obtenerUltimasMillasGanadas'
      };

      $.ajax({
         type: "POST",
         url: "ajax_controller_induccion.php",
         data: formValues,
         success: function(res){
            if(res.status) {
               $("#resultsMiles").val(res.data);
            }
         }
      });
   });
});