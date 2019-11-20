function calculaFechaVencimiento(fechaVenta, fechaFacturacion, fechaPago, mode){ 
  var hoy = fechaVenta;
  var fechaActualStr= fechaVenta;

  var array_fechaVenta = fechaActualStr.split("-");
  var diaVenta  = parseInt(array_fechaVenta[2]); //$hoy["mday"];
  var mesVenta  = parseInt(array_fechaVenta[1]); //$hoy["mon"];
  var anoVenta  = parseInt(array_fechaVenta[0]); //$hoy["year"];

  //OBTENEMOS LA FECHA DE FACTURACIÓN ACTUALIZADA AL MES ACTUAL (POR ESO MOD, PORQUE ES LA FECHA DE FAC. MODIFICADA)
  var primeraFechaFacturacionAux = fechaFacturacion;

  var array_fechaFact = primeraFechaFacturacionAux.split("-");
  var primerDiaAux  = parseInt(array_fechaFact[2]);
  var primerMesAux  = parseInt(array_fechaFact[1]);
  var primeraAnoAux  = parseInt(array_fechaFact[0]);

  var fechaFacturacionModStr= anoVenta + "-" + mesVenta + "-" + (primerDiaAux <= 9 ? '0' + primerDiaAux : primerDiaAux);

  //OBTENEMOS LA FCHA DE PAGO ACTUALIZADA AL MES ACTUAL, COMO EN LA SECCIÓN ANTERIOR
  var primeraFechaPagoAux = fechaPago;
  
  var array_fechaPago = primeraFechaPagoAux.split("-");
  var primerDiaPagoAux  = parseInt(array_fechaPago[2]);
  var primerMesPagoAux  = parseInt(array_fechaPago[1]);
  var primeraAnoPagoAux  = parseInt(array_fechaPago[0]);

  var fechaPagoModStr= anoVenta + "-" + mesVenta + "-" + (primerDiaPagoAux <= 9 ? '0' + primerDiaPagoAux : primerDiaPagoAux);

  //CONVERTIMOS TODAS LAS FECHAS OBTENIDAS A TIME PARA PODER COMPARARLAS
  var fecha_actual = new Date(anoVenta, parseInt(mesVenta) - 1, diaVenta); 
  var fecha_facturacion_mod = new Date(anoVenta, parseInt(mesVenta) - 1, primerDiaAux);
  var fecha_pago_mod = new Date(anoVenta, parseInt(mesVenta) - 1, primerDiaPagoAux);

  //COMPARAMOS LA FECHA ACTUAL A LA FECHA DE FACTURACION MODIFICADA, SI LA FECHA ACTUAL ES MAYOR, ENTONCES SE VA A FACTURAR EL PRÓXIMO MES
  var mesInt1 = parseInt(mesVenta);
  var anoInt1 = parseInt(anoVenta);

  if(fecha_actual > fecha_facturacion_mod){

    mesInt1++;
    if(mesInt1==13){
      mesInt1=1;
      anoInt1++;
    }

    fechaFacturacionModStr=anoInt1 + "-" + mesInt1 + "-" + (primerDiaAux <= 9 ? '0' + primerDiaAux : primerDiaAux);
  }

  //VOLVEMOS A OBTEER TIME DE FECHA DE FACTURACION MODIFICADA, ESTO EN CASO DE QUE SE HAYA CAMBIADO EN EL IF ANTERIOR
  fecha_facturacion_mod = new Date(anoInt1, mesInt1 - 1, primerDiaAux);

  //COMPARAMOS LA FECHA DE FACTURACION MODIFICADA CON LA FECHA DE PAGO MODIFICADA, LA FECHA DE PAGO DEBE SER MAYOR A LA FECHA DE FAC, SI ESTO NO ES ASÍ, ENTONCES SE MODIFICA KA FECHA DE PAGO PARA QUE EL QUEDE DESPUÉS DE LA FECHA DE FACTURACIÓN
  var mesInt2 = parseInt(mesVenta);
  var anoInt2 = parseInt(anoVenta);

  if(fecha_facturacion_mod > fecha_pago_mod){

    mesInt2++;
    if(mesInt2==13){
      mesInt2=1;
      anoInt2++;
    }
    fechaPagoModStr=anoInt2 + "-" + mesInt2 + "-" + (primerDiaPagoAux <= 9 ? '0' + primerDiaPagoAux : primerDiaPagoAux);
  }

  if(mode==1){
    return fechaPagoModStr;
  }else if(mode==2){
    return fechaFacturacionModStr;
  }

  
}

function formatearFechaHora(fecha){
  var array_fecha = fecha.split(" ");

  return array_fecha[0]+"T"+ array_fecha[1];
}


$(document).ready(function() {
  var idVentaGlobal = 0;

//---------ORDERNAR LA TABLA DESCENDENTE-----//
$('#dataTableVentas').DataTable( {
    "order": [[ 0, "desc" ]]
});

//---------AL CERRAR MODAL AÑADIR VENDEDOR -------//
  $("#anadirVentaModal").on('hidden.bs.modal', function () {
        $('#formularioAñadirVenta').trigger("reset");
        var num = $('.clonedInput').length; // how many "duplicatable" input fields we currently have
        for (var i = num; i > 0; i--) {
          $('#inputProducto' + i).remove(); // remove the last element
        }
        var newElem = '<fieldset id="inputProducto1" class="clonedInput"><div class="form-group row"> <div class="col-12"><input type="text" class="form-control" id="producto1" name="producto1"></div></div></fieldset>';
        
        $('#marcaPosicionProducto').after(newElem);
        $('#btnAddProducto1').attr('disabled',false);
     
        // if only one element remains, disable the "remove" button
        if (num-1 == 1)
          $('#btnDelProducto1').attr('disabled','disabled');
  });

//------- AJAX NOMBR EY TITULAR PARA AÑADIR VENTA ---------------//
  
$(document).on('change','#rutVenta',function(){
      let id = this.id;
      let rut= this.value;
      $('#nombreVenta').val("No hay coincidencias");
      $('#nombreTitularVenta').val("No hay coincidencias");
      $('#rutTitularVenta').val("No hay coincidencias");
      $('.editarClienteVenta').addClass( "disabled" );
      $('#botonAnadirVenta').attr('disabled',true);

      //Ajax para datos del Venta
      $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "cargaDatosClienteVenta/" + rut,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
                $('#nombreVenta').val(data.nombreCliente);
                $('#nombreTitularVenta').val(data.nombreTitular);
                $('#rutTitularVenta').val(data.rutTitular);


                $('#vencimientoVenta').val(calculaFechaVencimiento($('#fechaHoraVenta').val(),data.fechadeFacturacion,data.fechadePago, 1));
                $('.editarClienteVenta').attr('data-target',data.modalCorrespondiente);
                $(".editarClienteVenta").attr("id", data.idCliente);
 
                $('.editarClienteVenta').removeClass( "botonDetalleAdicional" );
                $('.editarClienteVenta').removeClass( "botonDetalleCliente" );
                $('.editarClienteVenta').addClass( data.class );


                $('.editarClienteVenta').removeClass( "disabled" );

                $('#botonAnadirVenta').attr('disabled',false);

                if(data.bloqueoCliente==1){
                  $("#morosoBloqueadoModal").modal("show");
                  $('#botonAnadirVenta').attr('disabled',true);

                }
                else{
                  if(data.bloqueoAdicional==1){
                    $("#adicionalBloqueadoModal").modal("show");
                    $('#botonAnadirVenta').attr('disabled',true);
                  }
                  else{
                    if(data.morosoCliente==1){
                      $("#morosoModal").modal("show");
                    }
                  }
                }
                
                /*if(data.morosoCliente==1 && data.bloqueoCliente==1){
                  $("#morosoBloqueadoModal").modal("show");
                  $('#botonAnadirVenta').attr('disabled',true);

                }else if(data.morosoCliente==1 && data.bloqueoCliente==0){
                  $("#morosoModal").modal("show");
                }*/

           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });
  });



  

  //--- LLENADO AUTOMÁTICO DE VALORES EN VENTAS ----//

  $(document).on('change','.recargaDatos',function(){
      let id = this.id;
      var aux;

      var montoVenta = $('#montoVenta').val();
      var montoPieVenta = $('#montoPieVenta').val();
      var saldo = parseFloat(montoVenta) - parseFloat(montoPieVenta);
      $('#saldoVenta').val(parseInt(saldo));

      var nCuotas = $('#nCuotasVenta').val();
      switch(nCuotas){
        case '1':
          $('#interesVenta').val(0.961);
          break;
        case '2':
          $('#interesVenta').val(1.886);
          break;
        case '3':
          $('#interesVenta').val(2.775);
          break;
        case '4':
          $('#interesVenta').val(3.629);
          break;
        case '5':
          $('#interesVenta').val(4.451);
          break;
        case '6':
          $('#interesVenta').val(5.240);
          break;
        case '7':
          $('#interesVenta').val(6.000);
          break;
        case '8':
          $('#interesVenta').val(6.730);
          break;
      }

      if( $('#aplicarInteresVenta').prop('checked') ) {
        aux= saldo/$('#interesVenta').val();
        aux=aux/10;
        aux=Math.round(aux);
        aux=aux*10;
      }
      else{
        aux= saldo/nCuotas;
        aux=aux/10;
        aux=Math.round(aux);
        aux=aux*10;
        $('#interesVenta').val(0);
      }

      $('#valorCuotaVenta').val(aux);
      $('#deudaFinalVenta').val(aux*nCuotas);
      $('#montoFinalVenta').val(aux*nCuotas);
  });


  //------- LISTADO DE PRODUCTOS-----//
    //Botones para la subida de archivos Detalle de Cliente
  $('#btnDelProducto1').attr('disabled','disabled');

  $('#btnAddProducto1').click(function() {
    var num = $('.clonedInput').length; // how many "duplicatable" input fields we currently have
    var newNum = new Number(num + 1); // the numeric ID of the new input field being added
 
    // create the new element via clone(), and manipulate it's ID using newNum value
    //var newElem = $('#input1').clone().attr('id', 'input' + newNum);
 
     var newElem = '<fieldset id="inputProducto'+newNum+'" class="clonedInput"><div class="form-group row"> <div class="col-12"><input type="text" class="form-control" id="producto'+newNum+'" name="producto'+newNum+'"></div></div></fieldset>';

    // manipulate the name/id values of the input inside the new element
    //newElem.children().children(':last').attr('id', 'documentoCliente' + newNum).attr('name', 'documentoCliente' + newNum);
 
    // insert the new element after the last "duplicatable" input field
    $('#inputProducto' + num).after(newElem);
 
    // enable the "remove" button
    $('#btnDelProducto1').attr('disabled',false);
 
    
  });
 
  $('#btnDelProducto1').click(function() {
    var num = $('.clonedInput').length; // how many "duplicatable" input fields we currently have
    $('#inputProducto' + num).remove(); // remove the last element
 
    // enable the "add" button
    $('#btnAddProducto1').attr('disabled',false);
 
    // if only one element remains, disable the "remove" button
    if (num-1 == 1)
      $('#btnDelProducto1').attr('disabled','disabled');
  });


  //----------MODAL DETALLE------//
  //$('.editableVentaDet').attr('disabled',true);
  $('#botonActualizarVenta').hide();
  $('#botonImprimirVenta').show();

  $('#marcaPosicionProductoDet').hide();
  $('.clonedInputDet3').hide();
  $('#botonesProductoDet').hide(); 
  $('.eliminaProducto').hide(); 

  $('#customSwitch3').click(function() {
    $.ajax({
          type: "GET",

          dataType: "json",

          url:  "revisionBloqueoEliminarVenta/" + idVentaGlobal,
      })
       .done(function( data, textStatus, jqXHR ) {
          if ( console && console.log ) {
            if(parseInt(data.mora)==1){
              $("#morosoModalDet").modal("show");
              $('#customSwitch3').prop('checked', false);
            }else{
              if( $('#customSwitch3').prop('checked') ) {
                $('.editableVentaDet').attr('disabled',false);
                $('#botonActualizarVenta').show();
                $('#botonImprimirVenta').hide(); 

                $('#marcaPosicionProductoDet').show();
                $('.clonedInputDet3').show();
                $('#botonesProductoDet').show(); 
                $('.eliminaProducto').show(); 
              }
              else{
                $('.editableVentaDet').attr('disabled',true);
                $('#botonActualizarVenta').hide();
                $('#botonImprimirVenta').show(); 

                $('#marcaPosicionProductoDet').hide();
                $('.clonedInputDet3').hide();
                $('#botonesProductoDet').hide(); 
                $('.eliminaProducto').hide();  

                $('.eliminaProducto').hide();  
                $('.backEliminaProducto').hide(); 
                $(".paraEliminarProducto").removeClass( "paraEliminarProducto" );
                $(".textoProducto").removeClass( "bg-warning" );
              }
            }
          }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });

    
  });

  $("#detallesVentaModal").on('hidden.bs.modal', function () {
    $('#customSwitch3').prop('checked', false);

    $('.editableVentaDet').attr('disabled',true);
    $('#botonActualizarVenta').hide(); 
    $('#botonImprimirVenta').show(); 

    $('#marcaPosicionProductoDet').hide();
    $('.clonedInputDet3').hide();
    $('#botonesProductoDet').hide(); 

    $('.divProductos').remove();  
    $('#nohayproductos').remove();

    $('#formularioDetalleVenta').trigger("reset");  
  });

  //Ajax Detalle Venta

  $(document).on('click','.botonDetalleVenta',function(){
      let id = this.id;
      idVentaGlobal=id;
      //Ajax para datos del Venta
      //$('#formularioDetalleVenta').attr('action', "updateVenta/"+id);
      $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showVenta/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
                $('#auxIdVenta').val(id);
                $('#fechaHoraVentaDet').val(formatearFechaHora(data.fechaHoraVenta));
                $('#nboletaDet').val(data.numeroBoletaVenta);
                $('#montoFinalVentaDet').val(data.deudaFinalVenta);
                $('#vendedorVentaDet').val(data.rutVendedor);
                $('#rutVentaDet').val(data.rutCliente);
                $('#nombreVentaDet').val(data.nombreCliente);
                $('#valorCuotaVentaDet').val(data.valorCuotaVenta);

                $('#nombreTitularVentaDet').val(data.nombreTitular);
                $('#rutTitularVentaDet').val(data.rutTitular);
                $('#comentarioVentaDet').val(data.comentarioVenta);
                $('#montoVentaDet').val(data.montoOriginalVenta);
                $('#montoPieVentaDet').val(data.montoPieVenta);
                $('#saldoVentaDet').val(parseInt(data.montoOriginalVenta) - parseInt(data.montoPieVenta));

                
                $('#nCuotasVentaDet').val(data.nCuotasVenta);
                $('#interesVentaDet').val(data.factorInteresVenta);
                $('#deudaFinalVentaDet').val(data.deudaFinalVenta);
                
                if(parseFloat(data.factorInteresVenta)==0){
                  $('#aplicarInteresVentaDet').prop('checked', false);
                }else{
                  $('#aplicarInteresVentaDet').prop('checked', true);
                }

                if(parseInt(data.estadoVenta)==1){
                  $('#titleDetalleVenta').text('Detalle Venta (Anulada) Nota de crédito: ' + data.notaCredito);
                  $('#titleDetalleVenta').css({'color':'red'});
                  $('.hideSwitch').hide();

                }else{
                  $('#titleDetalleVenta').text('Detalle Venta');
                  $('#titleDetalleVenta').css({'color':'#858796'});
                  $('.hideSwitch').show();
                }

                $('#vencimientoVentaDet').val(calculaFechaVencimiento(data.fechaHoraVenta, data.fechadeFacturacion,data.fechadePago, 1));
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });

    //Ajax para productos
      $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showProducto/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
            var newElement;
            if ( console && console.log ) {
              
              if($.isEmptyObject(data)){
                newElem='<div class="alert alert-success" id="nohayproductos">"No hay productos para mostrar"</div>';
                $('#productosTitleDet').after(newElem);
              }
              else{
                $.each(data, function(i,item){
                  newElem = '<div class="input-group mb-3 divProductos" id="divProducto'+data[i].idProducto+'"><input type="text" class="form-control textoProducto" id="textoProducto'+data[i].idProducto+'" name="textoProducto'+data[i].idProducto+'" value="'+data[i].nombreProducto+'" aria-label="Recipient\'s username" aria-describedby="basic-addon2" readonly><div class="input-group-append"><a type="button" id="'+data[i].idProducto+'"  class="btn btn-danger btn-icon-split eliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-times"></i></span></a><a type="button" id="'+data[i].idProducto+'"  class="btn-warning btn-icon-split backEliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span></a></div></div>';
                  $('#productosTitleDet').after(newElem);
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

      


    //--- LLENADO AUTOMÁTICO DE VALORES EN VENTAS DETALLE ----//

  $(document).on('change','.recargaDatosDet',function(){
      let id = this.id;
      var aux;

      var montoVenta = $('#montoVentaDet').val();
      var montoPieVenta = $('#montoPieVentaDet').val();
      var saldo = parseFloat(montoVenta) - parseFloat(montoPieVenta);
      $('#saldoVentaDet').val(parseInt(saldo));

      var nCuotas = $('#nCuotasVentaDet').val();
      switch(nCuotas){
        case '1':
          $('#interesVentaDet').val(0.961);
          break;
        case '2':
          $('#interesVentaDet').val(1.886);
          break;
        case '3':
          $('#interesVentaDet').val(2.775);
          break;
        case '4':
          $('#interesVentaDet').val(3.629);
          break;
        case '5':
          $('#interesVentaDet').val(4.451);
          break;
        case '6':
          $('#interesVentaDet').val(5.240);
          break;
        case '7':
          $('#interesVentaDet').val(6.000);
          break;
        case '8':
          $('#interesVentaDet').val(6.730);
          break;
      }

      if( $('#aplicarInteresVentaDet').prop('checked') ) {
        aux= saldo/$('#interesVentaDet').val();
        aux=aux/10;
        aux=Math.round(aux);
        aux=aux*10;
      }
      else{
        aux= saldo/nCuotas;
        aux=aux/10;
        aux=Math.round(aux);
        aux=aux*10;
        $('#interesVentaDet').val(0);
      }

      $('#valorCuotaVentaDet').val(aux);
      $('#deudaFinalVentaDet').val(aux*nCuotas);
      $('#montoFinalVentaDet').val(aux*nCuotas);
  });

   //Botones para añadir productos Detalle de venta
  $('#btnDelProductoDet1').attr('disabled','disabled');

  $('#btnAddProductoDet1').click(function() {
    var num = $('.clonedInputDet3').length; // how many "duplicatable" input fields we currently have
    var newNum = new Number(num + 1); // the numeric ID of the new input field being added
 
    // create the new element via clone(), and manipulate it's ID using newNum value
    //var newElem = $('#input1').clone().attr('id', 'input' + newNum);
 
     var newElem = '<fieldset id="inputProductoDet'+newNum+'" class="clonedInputDet3"><div class="form-group row"> <div class="col-12"><input type="text" class="form-control" id="productoDet'+newNum+'" name="productoDet'+newNum+'"></div></div></fieldset>';

    // manipulate the name/id values of the input inside the new element
    //newElem.children().children(':last').attr('id', 'documentoCliente' + newNum).attr('name', 'documentoCliente' + newNum);
 
    // insert the new element after the last "duplicatable" input field
    $('#inputProductoDet' + num).after(newElem);
 
    // enable the "remove" button
    $('#btnDelProductoDet1').attr('disabled',false);
 
    
  });
 
  $('#btnDelProductoDet1').click(function() {
    var num = $('.clonedInputDet3').length; // how many "duplicatable" input fields we currently have
    $('#inputProductoDet' + num).remove(); // remove the last element
 
    // enable the "add" button
    $('#btnAddProductoDet1').attr('disabled',false);
 
    // if only one element remains, disable the "remove" button
    if (num-1 == 1)
      $('#btnDelProductoDet1').attr('disabled','disabled');
  });


  //Al cerrar modal DETALLE cliente
  $("#detallesVentaModal").on('hidden.bs.modal', function () {
      
        var num = $('.clonedInputDet3').length; // how many "duplicatable" input fields we currently have
        for (var i = num; i > 0; i--) {
          $('#inputProductoDet' + i).remove(); // remove the last element
        }
        var newElem = '<fieldset id="inputProductoDet1" class="clonedInputDet3"><div class="form-group row"> <div class="col-12"><input type="text" class="form-control" id="productoDet1" name="productoDet1"></div></div></fieldset>';
        
        $('#marcaPosicionProductoDet').after(newElem);

        $('.clonedInputDet3').hide(); 
        
     
        // enable the "add" button
        $('#btnAddProductoDet1').attr('disabled',false);
     
        // if only one element remains, disable the "remove" button
        if (num-1 == 1)
          $('#btnDelProductoDet1').attr('disabled','disabled');
  });

  //Eliminación de productos 

  $(document).on('click','.eliminaProducto',function() {
    let id = this.id;
    $( "#"+id+".eliminaProducto").hide();
    $( "#"+id+".backEliminaProducto").show();
    
    $( "#divProducto"+id ).addClass( "paraEliminarProducto" );
    $( "#textoProducto"+id ).addClass( "bg-warning" );
  });
 
  $(document).on('click','.backEliminaProducto',function() {
    let id = this.id;
    $( "#"+id+".eliminaProducto").show();
    $( "#"+id+".backEliminaProducto").hide();
    $( "#divProducto"+id ).removeClass( "paraEliminarProducto" );
    $( "#textoProducto"+id ).removeClass( "bg-warning" );
    
  });

 
  /*$('#botonActualizarVenta').click(function() {
    $(".paraEliminarProducto").each(function(index) {

      console.log(this.id.replace('divProducto',''));
      id=this.id.replace('divProducto','');
      //Ajax para datos del cliente
      $.ajax({
          type: "GET",

          dataType: "json",

          url:  "deleteProducto/" + id,
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
    });
  });*/

  //-------ELIMINAR VENTA-----//
  $(document).on('click','.botonEliminarVenta',function(){
    let id = this.id;
    
    
    $.ajax({
          type: "GET",

          dataType: "json",

          url:  "revisionBloqueoEliminarVenta/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
            console.log(data.mora);
              if(parseInt(data.mora)==1){
                $('#numeroNotaCredito').hide();
                $('#botonEliminarVenta').hide();
                $('#contenidoEliminarventa').text('No se puede anular la venta. El titular se encuentra moroso.');
              }else{
                $('#botonEliminarVenta').show();
                $('#numeroNotaCredito').show();
                $('#contenidoEliminarventa').text('¿Está seguro que desea anular esta Venta?. Ingrese el número de nota de crédito para realizar la operación');

              }
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });


    $('#formularioEliminarVenta').attr('action', "anularVenta/"+id);
  });

  //------- AJAX NOMBR EY TITULAR PARA DETALLE VENTA ---------------//
  
$(document).on('change','#rutVentaDet',function(){
      let id = this.id;
      let rut= this.value;
      $('#nombreVentaDet').val("No hay coincidencias");
      $('#nombreTitularVentaDet').val("No hay coincidencias");
      $('#rutTitularVentaDet').val("No hay coincidencias");
      $('.editarClienteVentaDet').addClass( "disabled" );
      $('#botonAnadirVentaDet').attr('disabled',true);

      //Ajax para datos del Venta
      $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "cargaDatosClienteVenta/" + rut,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");
                $('#nombreVentaDet').val(data.nombreCliente);
                $('#nombreTitularVentaDet').val(data.nombreTitular);
                $('#rutTitularVentaDet').val(data.rutTitular);


                $('#vencimientoVentaDet').val(calculaFechaVencimiento($('#fechaHoraVenta').val(),data.fechadeFacturacion,data.fechadePago, 1));
                


                

                $('#botonActualizarVenta').attr('disabled',false);

                if(data.bloqueoCliente==1){
                  $("#morosoBloqueadoModal").modal("show");
                  $('#botonActualizarVenta').attr('disabled',true);

                }
                else{
                  if(data.bloqueoAdicional==1){
                    $("#adicionalBloqueadoModal").modal("show");
                    $('#botonActualizarVenta').attr('disabled',true);
                  }
                  else{
                    if(data.morosoCliente==1){
                      $("#morosoModal").modal("show");
                    }
                  }
                }


                /*if(data.morosoCliente==1 && data.bloqueoCliente==1){
                  $("#morosoBloqueadoModal").modal("show");
                  $('#botonActualizarVenta').attr('disabled',true);

                }else if(data.morosoCliente==1 && data.bloqueoCliente==0){
                  $("#morosoModal").modal("show");
                }´*/

           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });
  });

 
  //PARA ASEGURAR QUE LA EL NÚMERO DE BOLETA NO ES IGUAL
  $(document).on('change','#nboleta',function(){
    let nBoleta= this.value;
    $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "revisaNumeroBoleta/" + nBoleta,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
               console.log( "La solicitud se ha completado correctamente.");

                

                if(data.flag==1){
                  $("#nBoletaModal").modal("show");
                }

           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });
  });
});