$(document).ready(function() {

  $('#btnDelCliente1').attr('disabled','disabled');

  $('#btnAddCliente1').click(function() {
    var num = $('.clonedInput').length; // how many "duplicatable" input fields we currently have
    var newNum = new Number(num + 1); // the numeric ID of the new input field being added
 
    // create the new element via clone(), and manipulate it's ID using newNum value
    //var newElem = $('#input1').clone().attr('id', 'input' + newNum);
 
    //var newElem = '<fieldset id="input'+newNum+'" class="clonedInput"><div class="form-group"> <input type="file" class="form-control-file" id="documentoCliente'+newNum+'" name="documentoCliente'+newNum+'"></div></fieldset>';
    var newElem = '<fieldset id="input'+newNum+'" class="clonedInput"><div class="form-group"> <div class="custom-file"><input type="file" class="custom-file-input" id="documentoCliente'+newNum+'" name="documentoCliente'+newNum+'"><label class="custom-file-label" for="documentoCliente'+newNum+'">Selecciona un Archivo</label></div></div></fieldset>';
    // manipulate the name/id values of the input inside the new element
    //newElem.children().children(':last').attr('id', 'documentoCliente' + newNum).attr('name', 'documentoCliente' + newNum);
 
    // insert the new element after the last "duplicatable" input field
    $('#input' + num).after(newElem);
 
    // enable the "remove" button
    $('#btnDelCliente1').attr('disabled',false);
 
    
  });
 
  $('#btnDelCliente1').click(function() {
    var num = $('.clonedInput').length; // how many "duplicatable" input fields we currently have
    $('#input' + num).remove(); // remove the last element
 
    // enable the "add" button
    $('#btnAddCliente1').attr('disabled',false);
 
    // if only one element remains, disable the "remove" button
    if (num-1 == 1)
      $('#btnDelCliente1').attr('disabled','disabled');
  });

  //Al cerrar modal añadir cliente
  $('.cerrarModalCliente').click(function() {
      $('#formularioAñadirCliente').trigger("reset");
        var num = $('.clonedInput').length; // how many "duplicatable" input fields we currently have
        for (var i = num; i > 0; i--) {
          $('#input' + i).remove(); // remove the last element
        }
        var newElem = '<fieldset id="input1" class="clonedInput"><div class="form-group"> <div class="custom-file"><input type="file" class="custom-file-input" id="documentoCliente1" name="documentoCliente1"><label class="custom-file-label" for="documentoCliente1">Selecciona un Archivo</label></div></div></fieldset>';
        $('#marcaPosicionInputFile').after(newElem);
        
     
        // enable the "add" button
        $('#btnAddCliente1').attr('disabled',false);
     
        // if only one element remains, disable the "remove" button
        if (num-1 == 1)
          $('#btnDelCliente1').attr('disabled','disabled');
  });

  $("#anadirClienteModal").on('hidden.bs.modal', function () {
        $('#formularioAñadirCliente').trigger("reset");
        var num = $('.clonedInput').length; // how many "duplicatable" input fields we currently have
        for (var i = num; i > 0; i--) {
          $('#input' + i).remove(); // remove the last element
        }
        var newElem = '<fieldset id="input1" class="clonedInput"><div class="form-group"> <div class="custom-file"><input type="file" class="custom-file-input" id="documentoCliente1" name="documentoCliente1"><label class="custom-file-label" for="documentoCliente1">Selecciona un Archivo</label></div></div></fieldset>';
        $('#marcaPosicionInputFile').after(newElem);
        
     
        // enable the "add" button
        $('#btnAddCliente1').attr('disabled',false);
     
        // if only one element remains, disable the "remove" button
        if (num-1 == 1)
          $('#btnDelCliente1').attr('disabled','disabled');
  });



//----------MODAL DETALLE------//
  $('#rutClienteDet').attr('disabled',true);
  $('#nombreClienteDet').attr('disabled',true);
  $('#apellidoPatClienteDet').attr('disabled',true);
  $('#apellidoMatClienteDet').attr('disabled',true);
  $('#correoClienteDet').attr('disabled',true);
  $('#telefonoClienteDet').attr('disabled',true);
  $('#direccionClienteDet').attr('disabled',true);
  $('#recomendadoPorDet').attr('disabled',true);
  $('#fechaPagoClienteDet').attr('disabled',true);

  $('#marcaPosicionInputFileDet').hide(); 
  $('.clonedInputDet').hide(); 
  $('#botonesDocumentoDet').hide();
  $('.eliminaArchivo').hide();  
  

  $('#botonActualizarCliente').hide(); 


  $('#customSwitch1').click(function() {
    if( $('#customSwitch1').prop('checked') ) {
      $('#rutClienteDet').attr('disabled',false);
      $('#nombreClienteDet').attr('disabled',false);
      $('#apellidoPatClienteDet').attr('disabled',false);
      $('#apellidoMatClienteDet').attr('disabled',false);
      $('#correoClienteDet').attr('disabled',false);
      $('#telefonoClienteDet').attr('disabled',false);
      $('#direccionClienteDet').attr('disabled',false);
      $('#recomendadoPorDet').attr('disabled',false);

      //$('.avisador').html("Fecha de Pago (Cuenta en 0 para editar)");       
      //$('.editableDet').attr('disabled',false); 

      $('#marcaPosicionInputFileDet').show(); 
      $('.clonedInputDet').show(); 
      $('#botonesDocumentoDet').show(); 
      $('.eliminaArchivo').show(); 


      $('#botonActualizarCliente').show(); 
    }
    else{
      $('#rutClienteDet').attr('disabled',true);
      $('#nombreClienteDet').attr('disabled',true);
      $('#apellidoPatClienteDet').attr('disabled',true);
      $('#apellidoMatClienteDet').attr('disabled',true);
      $('#correoClienteDet').attr('disabled',true);
      $('#telefonoClienteDet').attr('disabled',true);
      $('#direccionClienteDet').attr('disabled',true);
      $('#recomendadoPorDet').attr('disabled',true);

      //$('.avisador').html("Fecha de Pago");   
      //$('.editableDet').attr('disabled',true);  

      $('#marcaPosicionInputFileDet').hide(); 
      $('.clonedInputDet').hide(); 
      $('#botonesDocumentoDet').hide();

      $('.eliminaArchivo').hide();  
      $('.backEliminaArchivo').hide(); 
      $(".paraEliminar").removeClass( "paraEliminar" );
      $(".textoArchivo").removeClass( "bg-warning" );
      
      
      $('#botonActualizarCliente').hide();  
    }
  });

  $('#switchFechaDetCliente').click(function() {
    if( $('#switchFechaDetCliente').prop('checked') ) {

      $('.avisador').html("Fecha de Pago (Cuenta en 0 para editar)");       
      $('.editableDet').attr('disabled',false); 

    }
    else{

      $('.avisador').html("Fecha de Pago");   
      $('.editableDet').attr('disabled',true);  

    }
  });


  $('.cerrarModalDetalle').click(function() {

    $('#customSwitch1').prop('checked', false);

    $('#rutClienteDet').attr('disabled',true);
    $('#nombreClienteDet').attr('disabled',true);
    $('#apellidoPatClienteDet').attr('disabled',true);
    $('#apellidoMatClienteDet').attr('disabled',true);
    $('#correoClienteDet').attr('disabled',true);
    $('#telefonoClienteDet').attr('disabled',true);
    $('#direccionClienteDet').attr('disabled',true);
    $('#recomendadoPorDet').attr('disabled',true);
    //$('#fechaPagoClienteDet').attr('disabled',true);

    $('#marcaPosicionInputFileDet').hide(); 
    $('.clonedInputDet').hide(); 
    $('#botonesDocumentoDet').hide(); 
    $('#botonActualizarCliente').hide();
    $('.divArchivos').remove();  
    $('#nohayarchivos').remove(); 

    $('#formularioDetalleCliente').trigger("reset");   
  });

  $("#detallesClienteModal").on('hidden.bs.modal', function () {
    $('#customSwitch1').prop('checked', false);

    $('#rutClienteDet').attr('disabled',true);
    $('#nombreClienteDet').attr('disabled',true);
    $('#apellidoPatClienteDet').attr('disabled',true);
    $('#apellidoMatClienteDet').attr('disabled',true);
    $('#correoClienteDet').attr('disabled',true);
    $('#telefonoClienteDet').attr('disabled',true);
    $('#direccionClienteDet').attr('disabled',true);
    $('#recomendadoPorDet').attr('disabled',true);

    $('.avisador').html("Fecha de Pago"); 
    $('.editableDet').attr('disabled',true);

    $('#marcaPosicionInputFileDet').hide(); 
    $('.clonedInputDet').hide(); 
    $('#botonesDocumentoDet').hide(); 
    $('#botonActualizarCliente').hide();
    $('.divArchivos').remove();  
    $('#nohayarchivos').remove(); 

    $('#formularioDetalleCliente').trigger("reset");  
  });


  //Ajax Detalle Cliente

  $(document).on('click','.botonDetalleCliente',function(){
      let id = this.id;
      //Ajax para datos del cliente
      $('#formularioDetalleCliente').attr('action', "updateCliente/"+id);
      $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showCliente/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
                $('#rutClienteDet').val(data.rutCliente);
                $('#nombreClienteDet').val(data.nombreCliente);
                $('#apellidoPatClienteDet').val(data.apellidoPatCliente);
                $('#apellidoMatClienteDet').val(data.apellidoMatCliente);
                $('#correoClienteDet').val(data.correoCliente);
                $('#telefonoClienteDet').val(data.telefonoCliente);
                $('#direccionClienteDet').val(data.direccionCliente);
                $('#recomendadoPorDet').val(data.recomendadoPorCliente);
                $('#fechaPagoClienteDet').val(data.fechaPagoCliente);
                $('#fechaFacturacionClienteDet').val(data.fechaFacturacionCliente);

                $( "#labelFechaPagoDet").removeClass( "avisador" );
                $( "#labelFechaPagoDet").removeClass( "Not" );

                $( "#fechaPagoClienteDet").removeClass( "editableDet" );
                $( "#fechaPagoClienteDet").removeClass( "Not" );

                $( "#labelFechaPagoDet").addClass( data.classLabel);

                $( "#fechaPagoClienteDet").addClass( data.classFecha );

           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });

      //Ajax para documentos
      $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showDocumento/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
            var newElement;
            if ( console && console.log ) {
              
              if($.isEmptyObject(data)){
                newElem='<div class="alert alert-success" id="nohayarchivos">"No hay archivos para mostrar"</div>';
                $('#archivosAdjuntosTitleDet').after(newElem);
              }
              else{
                $.each(data, function(i,item){
                  newElem = '<div class="input-group mb-3 divArchivos" id="divArchivo'+data[i].idDocumento+'"><input type="text" class="form-control textoArchivo" id="textArchivo'+data[i].idDocumento+'" placeholder="'+data[i].nombreDocumento+'" aria-label="Recipient\'s username" aria-describedby="basic-addon2" readonly><div class="input-group-append"><a href="http://localhost/CreditosGatoNegro/public/download/'+data[i].nombreDocumento+'" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-download"></i></span></a><a type="button" id="'+data[i].idDocumento+'"  class="btn btn-danger btn-icon-split eliminaArchivo" style="display:none;"><span class="icon text-white-50"><i class="fas fa-times"></i></span></a><a type="button" id="'+data[i].idDocumento+'"  class="btn-warning btn-icon-split backEliminaArchivo" style="display:none;"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span></a></div></div>';
                  $('#archivosAdjuntosTitleDet').after(newElem);
                })
              }
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });


  });


  //Botones para la subida de archivos Detalle de Cliente
  $('#btnDelClienteDet1').attr('disabled','disabled');

  $('#btnAddClienteDet1').click(function() {
    var num = $('.clonedInputDet').length; // how many "duplicatable" input fields we currently have
    var newNum = new Number(num + 1); // the numeric ID of the new input field being added
 
    // create the new element via clone(), and manipulate it's ID using newNum value
    //var newElem = $('#input1').clone().attr('id', 'input' + newNum);
 
     var newElem = '<fieldset id="inputDet'+newNum+'" class="clonedInputDet"><div class="form-group"> <div class="custom-file"><input type="file" class="custom-file-input" id="documentoClienteDet'+newNum+'" name="documentoClienteDet'+newNum+'"><label class="custom-file-label" for="documentoClienteDet'+newNum+'">Selecciona un Archivo</label></div></div></fieldset>';

    // manipulate the name/id values of the input inside the new element
    //newElem.children().children(':last').attr('id', 'documentoCliente' + newNum).attr('name', 'documentoCliente' + newNum);
 
    // insert the new element after the last "duplicatable" input field
    $('#inputDet' + num).after(newElem);
 
    // enable the "remove" button
    $('#btnDelClienteDet1').attr('disabled',false);
 
    
  });
 
  $('#btnDelClienteDet1').click(function() {
    var num = $('.clonedInputDet').length; // how many "duplicatable" input fields we currently have
    $('#inputDet' + num).remove(); // remove the last element
 
    // enable the "add" button
    $('#btnAddClienteDet1').attr('disabled',false);
 
    // if only one element remains, disable the "remove" button
    if (num-1 == 1)
      $('#btnDelClienteDet1').attr('disabled','disabled');
  });


  //Al cerrar modal DETALLE cliente
  $("#detallesClienteModal").on('hidden.bs.modal', function () {
      $('#formularioDetalleCliente').trigger("reset");
        var num = $('.clonedInputDet').length; // how many "duplicatable" input fields we currently have
        for (var i = num; i > 0; i--) {
          $('#inputDet' + i).remove(); // remove the last element
        }
        var newElem = '<fieldset id="inputDet1" class="clonedInputDet"><div class="form-group"> <div class="custom-file"><input type="file" class="custom-file-input" id="documentoClienteDet1" name="documentoClienteDet1"><label class="custom-file-label" for="documentoClienteDet1">Selecciona un Archivo</label></div></div></fieldset>';
        
        $('#marcaPosicionInputFileDet').after(newElem);

        $('.clonedInputDet').hide(); 
        
     
        // enable the "add" button
        $('#btnAddClienteDet1').attr('disabled',false);
     
        // if only one element remains, disable the "remove" button
        if (num-1 == 1)
          $('#btnDelClienteDet1').attr('disabled','disabled');
  });


  //Eliminación de archivos 

  $(document).on('click','.eliminaArchivo',function() {
    let id = this.id;
    $( "#"+id+".eliminaArchivo").hide();
    $( "#"+id+".backEliminaArchivo").show();
    
    $( "#divArchivo"+id ).addClass( "paraEliminar" );
    $( "#textArchivo"+id ).addClass( "bg-warning" );
  });
 
  $(document).on('click','.backEliminaArchivo',function() {
    let id = this.id;
    $( "#"+id+".eliminaArchivo").show();
    $( "#"+id+".backEliminaArchivo").hide();
    $( "#divArchivo"+id ).removeClass( "paraEliminar" );
    $( "#textArchivo"+id ).removeClass( "bg-warning" );
    
  });

 
  $('#botonActualizarCliente').click(function() {
    $(".paraEliminar").each(function(index) {

      console.log(this.id.replace('divArchivo',''));
      id=this.id.replace('divArchivo','');
      //Ajax para datos del cliente
      $.ajax({
          type: "GET",

          dataType: "json",

          url:  "deleteDocumento/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
               console.log(data.status);
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
        });
     



      //console.log(index + ": " + $(this).text());
      //console.log($(this).attr('id'));
    });
  });
});