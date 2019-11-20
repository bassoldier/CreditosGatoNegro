$(document).ready(function() {
//---------AL CERRAR MODAL AÑADIR VENDEDOR -------//

  $("#anadirAdicionalModal").on('hidden.bs.modal', function () {
        $('#formularioAñadirAdicional').trigger("reset");
  });



//----------MODAL DETALLE------//
  $('#rutAdicionalDet').attr('disabled',true);
  $('#nombreAdicionalDet').attr('disabled',true);
  $('#apellidoPatAdicionalDet').attr('disabled',true);
  $('#apellidoMatAdicionalDet').attr('disabled',true);
  $('#correoAdicionalDet').attr('disabled',true);
  $('#telefonoAdicionalDet').attr('disabled',true);
  $('#rutTitularAdicionalDet').attr('disabled',true);
  $('#nombreTitularDet').attr('disabled',true);
  $('#direccionAdicionalDet').attr('disabled',true);

  $('#botonActualizarAdicional').hide(); 


  $('#customSwitch2').click(function() {
    if( $('#customSwitch2').prop('checked') ) {
      $('#rutAdicionalDet').attr('disabled',false);
      $('#nombreAdicionalDet').attr('disabled',false);
      $('#apellidoPatAdicionalDet').attr('disabled',false);
      $('#apellidoMatAdicionalDet').attr('disabled',false);
      $('#correoAdicionalDet').attr('disabled',false);
      $('#telefonoAdicionalDet').attr('disabled',false);
      $('#rutTitularAdicionalDet').attr('disabled',false);
      $('#direccionAdicionalDet').attr('disabled',false);


      $('#botonActualizarAdicional').show(); 
    }
    else{
      $('#rutAdicionalDet').attr('disabled',true);
      $('#nombreAdicionalDet').attr('disabled',true);
      $('#apellidoPatAdicionalDet').attr('disabled',true);
      $('#apellidoMatAdicionalDet').attr('disabled',true);
      $('#correoAdicionalDet').attr('disabled',true);
      $('#telefonoAdicionalDet').attr('disabled',true);
      $('#rutTitularAdicionalDet').attr('disabled',true);
      $('#nombreTitularDet').attr('disabled',true);
      $('#direccionAdicionalDet').attr('disabled',true);
      
      $('#botonActualizarAdicional').hide();  
    }
  });

  $("#detallesAdicionalModal").on('hidden.bs.modal', function () {
    $('#customSwitch2').prop('checked', false);

    $('#rutAdicionalDet').attr('disabled',true);
    $('#nombreAdicionalDet').attr('disabled',true);
    $('#apellidoPatAdicionalDet').attr('disabled',true);
    $('#apellidoMatAdicionalDet').attr('disabled',true);
    $('#correoAdicionalDet').attr('disabled',true);
    $('#telefonoAdicionalDet').attr('disabled',true);
    $('#rutTitularAdicionalDet').attr('disabled',true);
    $('#nombreTitularDet').attr('disabled',true);
    $('#direccionAdicionalDet').attr('disabled',true);

    $('#botonActualizarAdicional').hide();
    

    $('#formularioDetalleAdicional').trigger("reset");  
  });


  //Ajax Detalle Adicional

  $(document).on('click','.botonDetalleAdicional',function(){
      let id = this.id;
      //Ajax para datos del Adicional
      $('#formularioDetalleAdicional').attr('action', "updateAdicional/"+id);
      $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showAdicional/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
                $('#rutTitularAdicionalDet').val(data.rutCliente);
                $('#nombreTitularDet').val(data.nombreCliente);
                $('#rutAdicionalDet').val(data.rutAdicional);
                $('#nombreAdicionalDet').val(data.nombreAdicional);
                $('#apellidoPatAdicionalDet').val(data.apellidoPatAdicional);
                $('#apellidoMatAdicionalDet').val(data.apellidoMatAdicional);
                $('#correoAdicionalDet').val(data.correoAdicional);
                $('#telefonoAdicionalDet').val(data.telefonoAdicional);
                $('#direccionAdicionalDet').val(data.direccionAdicional);

           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });
  });

  //Ajax Carga nombre titular en base a rut de cliente

  $(document).on('change','#rutTitularAdicionalDet',function(){
      let rut = this.value;
      $('#nombreTitularDet').val("No hay coincidencias");
      //Ajax para datos del Adicional
      $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "nombreCliente/" + rut,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
                $('#nombreTitularDet').val(data.nombreCliente);
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });
  });


  //-------ELIMINAR ADICIONAL-----//
  $(document).on('click','.botonEliminarAdicional',function(){
    let id = this.id;
    $('#formularioEliminarAdicional').attr('action', "deleteAdicional/"+id);
  });


});