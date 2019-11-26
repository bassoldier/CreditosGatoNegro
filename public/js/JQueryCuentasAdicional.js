
$(document).ready(function() {

  $('.switchMora').click(function() {
    let id = this.id;
    console.log(id);
    let block=0;
    if( $('#'+id+'.switchMora').prop('checked') ) {
      block=1;
    }
    else{
      block=0;
    }
    $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "cambiarBloqueoAdicional/"+id+"/" + block,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
              console.log("nice");
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });
  }); 

  $(document).on('click','.cargarMora',function(){
    let id = this.id;
    var montoMora=$('#mora'+id).val();
    $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "cargarMora/"+id+"/" + montoMora,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
              $('#mora'+id).val(0);
              $('#labelMora'+id).text(data.nuevaMora);
              $('#'+id+'.eliminarMora').removeClass( "disabled" );
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });

    
  }); 

  $(document).on('click','.eliminarMora',function(){
    let id = this.id;
   
    $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "eliminarMora/"+id,
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
              $('#mora'+id).val(0);
              $('#labelMora'+id).text(data.nuevaMora);
              $('#'+id+'.eliminarMora').addClass( "disabled" );
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });

    
  }); 

  $(document).on('click','.botonDetalleCuenta',function(){
    let id = this.id;
   
    $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showDeudaMensual/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
            var newElement;
            if ( console && console.log ) {
              
              if($.isEmptyObject(data)){
                newElem='<div class="alert alert-success" id="nohayDeudas">"No hay deudas para mostrar"</div>';
                $('#tBodyDeudas').html(newElem);
              }
              else{
                $.each(data, function(i,item){
                  var fechaActualTime = new Date();
                  var diaActual  = parseInt(fechaActualTime.getDate());
                  var mesActual = parseInt(fechaActualTime.getMonth());
                  var anoActual  = parseInt(fechaActualTime.getFullYear());

                  //console.log(anoActual + "-" + (mesActual + 1) + "-" + diaActual);

                  var fechaActual = new Date(anoActual, mesActual, diaActual);

                  var array_fechaPago = data[i].fechaVencimientoDeudaMensual.split("-");
                  var diaPago  = parseInt(array_fechaPago[2]);
                  var mesPago = parseInt(array_fechaPago[1]);
                  var anoPago  = parseInt(array_fechaPago[0]);

                  var fechaPago = new Date(anoPago, mesPago - 1, diaPago);

                  if(fechaPago >= fechaActual){
                    var casilla='Al día <a href="#" class="btn btn-success btn-circle btn-sm disabled">'+
                                  '<i class="fas fa-check-circle"></i>'+
                                '</a>';
                  }else{
                    var casilla='Vencida <a href="#" class="btn btn-danger btn-circle btn-sm disabled">'+
                                  '<i class="fas fa-exclamation-triangle"></i>'+
                                '</a>';
                  }

                  newElem = '<tr>' + 
                              '<td>'+data[i].mesCorrespondienteDeudaMensual+'</td>' +
                              '<td>'+data[i].fechaVencimientoDeudaMensual+'</td>' +
                              '<td>$ '+data[i].montoDeudaMensual+'</td>'+
                              '<td>' +
                                casilla +
                              '</td>'+
                            
                              '<td>' +
                                '<a href="#" id="'+data[i].idDeudaMensual+'" class="btn btn-warning btn-circle btn-sm botonDetalleDeuda" data-target="#detallesCuentaVentasModal" data-toggle="modal">'+
                                  '<i class="fas fa-dollar-sign"></i>'+
                                '</a>'+
                                '<a href="#" id="'+data[i].idDeudaMensual+'" name="'+id+'" class="btn btn-success btn-circle btn-sm botonDetalleDeudaAbono" data-target="#abonoCuentaModal" data-toggle="modal">'+
                                  '<i class="fas fa-dollar-sign"></i>'+
                                '</a>'+
                              '</td>'+
                            '</tr>';

                  //newElem = '<div class="input-group mb-3 divProductos" id="divProducto'+data[i].idProducto+'"><input type="text" class="form-control textoProducto" id="textoProducto'+data[i].idProducto+'" placeholder="'+data[i].nombreProducto+'" aria-label="Recipient\'s username" aria-describedby="basic-addon2" readonly><div class="input-group-append"><a type="button" id="'+data[i].idProducto+'"  class="btn btn-danger btn-icon-split eliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-times"></i></span></a><a type="button" id="'+data[i].idProducto+'"  class="btn-warning btn-icon-split backEliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span></a></div></div>';
                  if(i==0){
                    $('#tBodyDeudas').html(newElem);
                  }
                  else{
                    $('#tBodyDeudas').append(newElem);
                  }
                  
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

  $(document).on('click','.botonDetalleCuentaVentas',function(){
    let id = this.id;

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
            var newElement;
            if ( console && console.log ) {
              $('#titleVentasAdicional').text('Ventas Asociadas: '+ data.nombreAdicional + ' ' + data.apellidoPatAdicional + ' ' + data.apellidoMatAdicional);
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });
   
    $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showCuentaVentasAdicional/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
            var newElement;
            if ( console && console.log ) {
              
              if($.isEmptyObject(data)){
                newElem='<div class="alert alert-success" id="nohayVentas">"No hay ventas para mostrar"</div>';
                $('#tBodyVentas').html(newElem);
              }
              else{
                $.each(data, function(i,item){

                  if(parseInt(data[i].estadoVenta) == 0){
                    var casilla='<a href="#" class="btn btn-success btn-circle btn-sm disabled">'+
                                  '<i class="fas fa-check-circle"></i>'+
                                '</a>';

                    var casilla2='<a href="#" id="'+data[i].idVenta+'" class="btn btn-info btn-circle btn-sm botonDetalleVenta" data-target="#detallesVentaModal" data-toggle="modal">'+
                                  '<i class="fas fa-search"></i>'+
                                '</a>'+
                                '<a href="#" id="'+data[i].idCliente+'" name="'+id+'" class="btn btn-success btn-circle btn-sm botonAbonoCuenta" data-target="#abonoCuentaModal" data-toggle="modal">'+
                                  '<i class="fas fa-dollar-sign"></i>'+
                                '</a>'+
                                '<a href="#" id="'+data[i].idVenta+'" class="btn btn-danger btn-circle btn-sm botonEliminarVenta" data-target="#eliminarVentaModal" data-toggle="modal">'+
                                  '<i class="fas fa-trash"></i>'+
                                '</a>';
                  }else{
                    var casilla='<a href="#" class="btn btn-danger btn-circle btn-sm disabled">'+
                                  '<i class="fas fa-exclamation-triangle"></i>'+
                                '</a>';
                    var casilla2='<a href="#" id="'+data[i].idVenta+'" class="btn btn-info btn-circle btn-sm botonDetalleVenta" data-target="#detallesVentaModal" data-toggle="modal">'+
                                  '<i class="fas fa-search"></i>'+
                                '</a>';
                  }

                  newElem = '<tr>' + 
                              '<td>'+data[i].fechaHoraVenta+'</td>' +
                              '<td>'+data[i].numeroBoletaVenta+'</td>' +
                              '<td>$'+data[i].montoPostInteresVenta+'</td>'+
                              '<td>'+data[i].nombreAdicional+" "+data[i].apellidoPatAdicional+" "+data[i].apellidoMatAdicional+'</td>'+
                              '<td>' +
                                casilla +
                              '</td>'+
                            
                              '<td>' +
                                casilla2 +
                              '</td>'+
                            '</tr>';

                  //newElem = '<div class="input-group mb-3 divProductos" id="divProducto'+data[i].idProducto+'"><input type="text" class="form-control textoProducto" id="textoProducto'+data[i].idProducto+'" placeholder="'+data[i].nombreProducto+'" aria-label="Recipient\'s username" aria-describedby="basic-addon2" readonly><div class="input-group-append"><a type="button" id="'+data[i].idProducto+'"  class="btn btn-danger btn-icon-split eliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-times"></i></span></a><a type="button" id="'+data[i].idProducto+'"  class="btn-warning btn-icon-split backEliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span></a></div></div>';
                  if(i==0){
                    $('#tBodyVentas').html(newElem);
                  }
                  else{
                    $('#tBodyVentas').append(newElem);
                  }
                  
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

  $(document).on('click','.botonDetalleDeuda',function(){
    let id = this.id;
   
    $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showDeudaVentas/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
            var newElement;
            if ( console && console.log ) {
              $('#titleVentasAdicional').text('Ventas Asociadas por Deuda');
              
              if($.isEmptyObject(data)){
                newElem='<div class="alert alert-success" id="nohayVentas">"No hay ventas para mostrar"</div>';
                $('#tBodyVentas').html(newElem);
              }
              else{
                $.each(data, function(i,item){
                  if(i==0){

                  }else{
                  if(parseInt(data[i].estadoVenta) == 0){
                    var casilla='<a href="#" class="btn btn-success btn-circle btn-sm disabled">'+
                                  '<i class="fas fa-check-circle"></i>'+
                                '</a>';

                    var casilla2='<a href="#" id="'+data[i].idVenta+'" class="btn btn-info btn-circle btn-sm botonDetalleVenta" data-target="#detallesVentaModal" data-toggle="modal">'+
                                  '<i class="fas fa-search"></i>'+
                                '</a>'+
                                '<a href="#" id="'+data[i].idCliente+'" name="'+id+'" class="btn btn-success btn-circle btn-sm botonAbonoCuenta" data-target="#abonoCuentaModal" data-toggle="modal">'+
                                  '<i class="fas fa-dollar-sign"></i>'+
                                '</a>'+
                                '<a href="#" id="'+data[i].idVenta+'" class="btn btn-danger btn-circle btn-sm botonEliminarVenta" data-target="#eliminarVentaModal" data-toggle="modal">'+
                                  '<i class="fas fa-trash"></i>'+
                                '</a>';
                  }else{
                    var casilla='<a href="#" class="btn btn-danger btn-circle btn-sm disabled">'+
                                  '<i class="fas fa-exclamation-triangle"></i>'+
                                '</a>';
                    var casilla2='<a href="#" id="'+data[i].idVenta+'" class="btn btn-info btn-circle btn-sm botonDetalleVenta" data-target="#detallesVentaModal" data-toggle="modal">'+
                                  '<i class="fas fa-search"></i>'+
                                '</a>';
                  }

                  newElem = '<tr>' + 
                              '<td>'+data[i].fechaHoraVenta+'</td>' +
                              '<td>'+data[i].numeroBoletaVenta+'</td>' +
                              '<td>$'+data[i].montoPostInteresVenta+'</td>'+
                              '<td>'+data[i].nombreCliente+'</td>'+
                              '<td>' +
                                casilla +
                              '</td>'+
                            
                              '<td>' +
                                casilla2 +
                              '</td>'+
                            '</tr>';

                  //newElem = '<div class="input-group mb-3 divProductos" id="divProducto'+data[i].idProducto+'"><input type="text" class="form-control textoProducto" id="textoProducto'+data[i].idProducto+'" placeholder="'+data[i].nombreProducto+'" aria-label="Recipient\'s username" aria-describedby="basic-addon2" readonly><div class="input-group-append"><a type="button" id="'+data[i].idProducto+'"  class="btn btn-danger btn-icon-split eliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-times"></i></span></a><a type="button" id="'+data[i].idProducto+'"  class="btn-warning btn-icon-split backEliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span></a></div></div>';
                  if(i==1){
                    $('#tBodyVentas').html(newElem);
                  }
                  else{
                    $('#tBodyVentas').append(newElem);
                  }
                  }
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


  //ABRE EL MODAL DE ABONOS
  $(document).on('click','.botonAbonoCuenta',function(){
    let id = this.id;
    
    $(".abono").removeAttr("id");
    $(".abono").attr("id",id);
    $("#auxIdCliente").val(id);

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
              $('#abonosTitle').text('Abonos: ' + data.nombreCliente + " " + data.apellidoPatCliente + " " + data.apellidoMatCliente);
              $('#labelDeudaTotal').text(formatNumber(data.deudaCliente));
              $('.abono').attr({"max" : data.deudaCliente, "min" : 10 });
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });

    $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showAbonoCuenta/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
            var newElement;
            if ( console && console.log ) {
              
              if($.isEmptyObject(data)){
                newElem='<div class="alert alert-success" id="nohayAbonos">"No hay pagos para mostrar"</div>';
                $('#tBodyAbonos').html(newElem);
              }
              else{
                $.each(data, function(i,item){
                  newElem = '<tr>' + 
                              '<td> $'+data[i].montoAbono+'</td>' +
                              '<td>'+data[i].fechaAbono+'</td>' +
                              '<td><a href="imprimirAbono/'+data[i].idAbono+'" id="'+data[i].idAbono+'" class="btn btn-info btn-circle btn-sm">'+
                                  '<i class="fas fa-print"></i>'+
                                '</a></td>'+     
                            '</tr>';

                  //newElem = '<div class="input-group mb-3 divProductos" id="divProducto'+data[i].idProducto+'"><input type="text" class="form-control textoProducto" id="textoProducto'+data[i].idProducto+'" placeholder="'+data[i].nombreProducto+'" aria-label="Recipient\'s username" aria-describedby="basic-addon2" readonly><div class="input-group-append"><a type="button" id="'+data[i].idProducto+'"  class="btn btn-danger btn-icon-split eliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-times"></i></span></a><a type="button" id="'+data[i].idProducto+'"  class="btn-warning btn-icon-split backEliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span></a></div></div>';
                  if(i==0){
                    $('#tBodyAbonos').html(newElem);
                  }
                  else{
                    $('#tBodyAbonos').append(newElem);
                  }
                  
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


  //MIMPIDE QUE EL MONTO A ABONAR SEA MAYOR A LA DEUDA
  $(document).on('change','.abono',function(){
    let id = this.id;
    
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
              if(parseInt($('#'+id+'.abono').val()) > parseInt(data.deudaCliente)){
                
                $('#'+id+'.abono').val(data.deudaCliente);
                $("#abonoExceso").modal("show");
              }
              
            

           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });

    
  }); 

  //AL CERRAR MODAL ABONOS
  $("#abonoCuentaModal").on('hidden.bs.modal', function () {
        $('#formularioAbono').trigger("reset");
  });

  $(document).on('click','.botonDetalleDeudaAbono',function(){
      let idDeudaMensual = this.id;
      let id= $('#'+idDeudaMensual+".botonDetalleDeudaAbono").attr('name');
    console.log("Ide del cliente:" + id);
    $(".abono").removeAttr("id");
    $(".abono").attr("id",id);
    $("#auxIdCliente").val(id);

    $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showClientePorDeuda/" + id + "/"+ idDeudaMensual,
      })
       .done(function( data, textStatus, jqXHR ) {
            if ( console && console.log ) {


              $('#abonosTitle').text('Abonos: ' + data.nombreCliente + " " + data.apellidoPatCliente + " " + data.apellidoMatCliente);
              $('#labelDeudaText').text("Deuda Mes:");
              $('#labelDeudaTotal').text(formatNumber(data.montoDeudaMensual));
              $('.abono').attr({"max" : data.deudaCliente, "min" : 10 });
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);

           }
      });

    $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          //Cambiar a type: POST si necesario
          type: "GET",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url:  "showAbonoCuenta/" + id,
      })
       .done(function( data, textStatus, jqXHR ) {
            var newElement;
            if ( console && console.log ) {
              
              if($.isEmptyObject(data)){
                newElem='<div class="alert alert-success" id="nohayAbonos">"No hay pagos para mostrar"</div>';
                $('#tBodyAbonos').html(newElem);
              }
              else{
                $.each(data, function(i,item){
                  newElem = '<tr>' + 
                              '<td> $'+data[i].montoAbono+'</td>' +
                              '<td>'+data[i].fechaAbono+'</td>' +
                              '<td><a href="imprimirAbono/'+data[i].idAbono+'" id="'+data[i].idAbono+'" class="btn btn-info btn-circle btn-sm">'+
                                  '<i class="fas fa-print"></i>'+
                                '</a></td>'+     
                            '</tr>';

                  //newElem = '<div class="input-group mb-3 divProductos" id="divProducto'+data[i].idProducto+'"><input type="text" class="form-control textoProducto" id="textoProducto'+data[i].idProducto+'" placeholder="'+data[i].nombreProducto+'" aria-label="Recipient\'s username" aria-describedby="basic-addon2" readonly><div class="input-group-append"><a type="button" id="'+data[i].idProducto+'"  class="btn btn-danger btn-icon-split eliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-times"></i></span></a><a type="button" id="'+data[i].idProducto+'"  class="btn-warning btn-icon-split backEliminaProducto" style="display:none;"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span></a></div></div>';
                  if(i==0){
                    $('#tBodyAbonos').html(newElem);
                  }
                  else{
                    $('#tBodyAbonos').append(newElem);
                  }
                  
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

});