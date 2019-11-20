$(document).ready(function() {
//---------AL CERRAR MODAL AÑADIR VENDEDOR -------//

  $("#anadirVendedorModal").on('hidden.bs.modal', function () {
        $('#formularioAñadirVendedor').trigger("reset");
  });



//----------MODAL DETALLE------//
  $('#rutVendedorDet').attr('disabled',true);
  $('#nombreVendedorDet').attr('disabled',true);
  $('#apellidoPatVendedorDet').attr('disabled',true);
  $('#apellidoMatVendedorDet').attr('disabled',true);
  $('#correoVendedorDet').attr('disabled',true);
  $('#telefonoVendedorDet').attr('disabled',true);
  

  $('#botonActualizarVendedor').hide(); 


  $('#customSwitch1').click(function() {
    if( $('#customSwitch1').prop('checked') ) {
      $('#rutVendedorDet').attr('disabled',false);
      $('#nombreVendedorDet').attr('disabled',false);
      $('#apellidoPatVendedorDet').attr('disabled',false);
      $('#apellidoMatVendedorDet').attr('disabled',false);
      $('#correoVendedorDet').attr('disabled',false);
      $('#telefonoVendedorDet').attr('disabled',false);



      $('#botonActualizarVendedor').show(); 
    }
    else{
      $('#rutVendedorDet').attr('disabled',true);
      $('#nombreVendedorDet').attr('disabled',true);
      $('#apellidoPatVendedorDet').attr('disabled',true);
      $('#apellidoMatVendedorDet').attr('disabled',true);
      $('#correoVendedorDet').attr('disabled',true);
      $('#telefonoVendedorDet').attr('disabled',true);
      
      
      $('#botonActualizarVendedor').hide();  
    }
  });

  $("#detallesVendedorModal").on('hidden.bs.modal', function () {
    $('#customSwitch1').prop('checked', false);

    $('#rutVendedorDet').attr('disabled',true);
    $('#nombreVendedorDet').attr('disabled',true);
    $('#apellidoPatVendedorDet').attr('disabled',true);
    $('#apellidoMatVendedorDet').attr('disabled',true);
    $('#correoVendedorDet').attr('disabled',true);
    $('#telefonoVendedorDet').attr('disabled',true);

    $('#botonActualizarVendedor').hide();
    

    $('#formularioDetalleVendedor').trigger("reset");  
  });


  //Ajax Detalle Vendedor

  $(document).on('click','.botonDetalleVendedor',function(){
      let id = this.id;
      //Ajax para datos del Vendedor
      $('#formularioDetalleVendedor').attr('action', "updateVendedor/"+id);
      $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showVendedor/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
                $('#rutVendedorDet').val(data.rutVendedor);
                $('#nombreVendedorDet').val(data.nombreVendedor);
                $('#apellidoPatVendedorDet').val(data.apellidoPatVendedor);
                $('#apellidoMatVendedorDet').val(data.apellidoMatVendedor);
                $('#correoVendedorDet').val(data.correoVendedor);
                $('#telefonoVendedorDet').val(data.telefonoVendedor);

           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });
  });
});