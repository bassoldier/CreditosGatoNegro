@extends('layouts.layout')

@section('title', 'Ventas')

@section('sidebar')
    @parent
@endsection

@section('content')
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-2 text-gray-800">Ventas <i class="fas fa-fw fa-dollar-sign"></i></h1>
            <a href="#" class="d-inline-block btn btn-sm btn-primary shadow-sm" data-target="#anadirVentaModal" data-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i> Añadir Venta</a>
          </div>
          
          <p class="mb-4">Navega por el listado de ventas y accede a sus detalles en la sección "Opciones". Utiliza el buscador "Search" si quieres encontrar un venta rápidamente.</p>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Listado de Ventas</h6>
            </div>
            @if (session('success'))
              <div class="alert alert-success">
                  {{ session('success') }}
              </div>
            @endif
            @if (session('fail'))
              <div class="alert alert-danger">
                  {{ session('fail') }}
              </div>
            @endif

            @if (session('print'))
              <div class="alert alert-warning">
                  {{ session('print') }}
              </div>
              <iframe src="{{ asset('tempPDF/venta.pdf') }}" id="PDFtoPrint" width="100%" height="100%" style="display: none"> </iframe>
                <script type="text/javascript">
                  function printPDF(PDFtoPrint){
                    document.getElementById('PDFtoPrint').focus();
                    document.getElementById('PDFtoPrint').contentWindow.print();
                   
                  }

                  printPDF("{{ asset('tempPDF/venta.pdf') }}");
                </script>
            @endif

            @if (session('printSuccess'))
              <div class="alert alert-success">
                  {{ session('printSuccess') }}
              </div>
              <iframe src="{{ asset('tempPDF/venta.pdf') }}" id="PDFtoPrint" width="100%" height="100%" style="display: none"> </iframe>
                <script type="text/javascript">
                  function printPDF(PDFtoPrint){
                    document.getElementById('PDFtoPrint').focus();
                    document.getElementById('PDFtoPrint').contentWindow.print();
                   
                  }

                  printPDF("{{ asset('tempPDF/venta.pdf') }}");
                </script>
            @endif
              
            
           
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTableVentas" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>N° Boleta</th>
                      <th>Titular</th>
                      <th>Monto</th>
                      <th>Vendedor</th>
                      <th>Estado</th>
                      <th>Opciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Fecha</th>
                      <th>N° Boleta</th>
                      <th>Titular</th>
                      <th>Monto</th>
                      <th>Vendedor</th>
                      <th>Estado</th>
                      <th>Opciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    @foreach ($ventas as $venta)
                      <tr>
                        <td>{{ $venta->fechaHoraVenta }}</td>
                        <td>{{ $venta->numeroBoletaVenta }}</td>
                        <td>{{ $venta->nombreCliente }} {{ $venta->apellidoPatCliente }} {{ $venta->apellidoMatCliente }}</td>
                        <td>${{$venta->montoOriginalVenta }}</td>
                        <td>{{ $venta->nombreVendedor }} {{ $venta->apellidoPatVendedor }} {{ $venta->apellidoMatVendedor }}</td>
                        <td>
                          @if ($venta->estadoVenta === 0)
                            <a href="#" class="btn btn-success btn-circle btn-sm disabled">
                              <i class="fas fa-check-circle"></i>
                            </a>
                          @else
                            <a href="#" class="btn btn-danger btn-circle btn-sm disabled">
                              <i class="fas fa-exclamation-triangle"></i>
                            </a>
                          @endif </td>
                        <td>
                          <a href="#" id="{{ $venta->idVenta }}" class="btn btn-info btn-circle btn-sm botonDetalleVenta" data-target="#detallesVentaModal" data-toggle="modal">
                            <i class="fas fa-search"></i>
                          </a>
                          @if ($venta->estadoVenta === 0)
                            <a href="#" id="{{ $venta->idVenta }}" class="btn btn-danger btn-circle btn-sm botonEliminarVenta" data-target="#eliminarVentaModal" data-toggle="modal">
                            <i class="fas fa-trash"></i>
                          </a>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->
@endsection

@section('modals')

  <!-- Modal Añadir venta -->
  <div class="modal fade" id="anadirVentaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Añadir Venta</h5>
          <button class="close cerrarModalVenta" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="uploadVenta" method="post" enctype="multipart/form-data" id="formularioAñadirVenta">
        @csrf
        <div class="modal-body"> 
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="fechaVenta">Fecha y Hora</label>
                    <input type="datetime-local" class="form-control" id="fechaHoraVenta" name="fechaHoraVenta" placeholder="" value="{{ date("Y-m-d\TH:i") }}" required readonly>
                  </div>
                  <div class="col-sm-6">
                    <label for="nboleta">N° de Boleta</label>
                    <input type="text" class="form-control" id="nboleta" name="nboleta" placeholder="" required>
                  </div>
                </div> 
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="montoVenta">Monto Final</label>
                    <input type="number" class="form-control" id="montoFinalVenta" name="montoFinalVenta" placeholder="" required readonly>
                  </div>
                  <div class="col-sm-6">
                    <label for="vendedorVenta">Vendedor</label>
                    <input type="text" list="items" class="form-control" id="vendedorVenta" name="vendedorVenta" placeholder="" required>
                    <!-- Lista de opciones -->
                    <datalist id="items" class="datalist">
                      @foreach ($vendedores as $vendedor)
                          <option value="{{ $vendedor->rutVendedor }}">{{ $vendedor->nombreVendedor }} {{ $vendedor->apellidoPatVendedor }} {{ $vendedor->apellidoMatVendedor }}</option>
                      @endforeach
                    </datalist>
                  </div>
                </div>    
                <div class="form-group row">
                  <div class="col-5">
                    <label for="rutVenta">Rut Cliente</label>
                    <input type="text" list="clientes" class="form-control" name="rutVenta" id="rutVenta" required>
                    <!-- Lista de opciones -->
                    <datalist id="clientes" class="datalist">
                      @foreach ($clientes as $cliente)
                          <option value="{{ $cliente->rutCliente }}">{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}</option>
                      @endforeach
                      @foreach ($adicionales as $adicional)
                          <option value="{{ $adicional->rutAdicional }}">{{ $adicional->nombreAdicional }} {{ $adicional->apellidoPatAdicional }} {{ $adicional->apellidoMatAdicional }}</option>
                      @endforeach
                    </datalist>
                  </div>
                  <div class="col-5 ">
                    <label for="rutVenta">Nombre Cliente</label>
                    <input type="text" class="form-control" name="nombreVenta" id="nombreVenta" placeholder="" required readonly>
                  </div>
                  <div class="col-2">
                    <label for="opciones">Opciones</label>
                    <div class="btn-group-toggle" id="opciones" name="opciones">
                      <a href="#" class="d-inline-block btn btn-sm btn-primary shadow-sm disabled editarClienteVenta" data-target="#" data-toggle="modal" id=""><i class="fas fa-search fa-sm text-white-50"></i></a>
                      
                      <a href="#" class="d-inline-block btn btn-sm btn-primary shadow-sm" data-target="#anadirClienteModal" data-toggle="modal" id="editarClienteVenta"><i class="fas fa-fw fa-user fa-sm text-white-50"></i></a>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-6">
                    <label for="nombreTitularVenta">Titular</label>
                    <input type="text" class="form-control" name="nombreTitularVenta" id="nombreTitularVenta" placeholder="" readonly required>
                  </div>
                  <div class="col-sm-6">
                    <label for="ruttitular">Rut Titular</label>
                    <input type="text" class="form-control" id="rutTitularVenta" name="rutTitularVenta" placeholder="" readonly required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <label for="comentarioVenta">Comentario</label>
                    <input type="text" class="form-control" id="comentarioVenta" name="comentarioVenta">
                  </div>
                </div> 
                <!--Datos Crédito-->               
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="montoVenta">Monto Venta $</label>
                    <input type="number" min="10" class="form-control recargaDatos numberPositivo" id="montoVenta" name="montoVenta" placeholder="" required>
                  </div>
                  <div class="col-sm-6">
                    <label for="montoPieVenta">Monto Pie</label>
                    <input type="number" min="0" class="form-control recargaDatos numberPositivo" id="montoPieVenta" name="montoPieVenta" placeholder="" value="0"required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="saldoVenta">Saldo</label>
                    <input type="email" class="form-control" id="saldoVenta" name="saldoVenta" placeholder="" readonly required>
                  </div>
                  <div class="col-sm-6">
                    <label for="nCuotasVenta">N° Cuotas</label>
                    <select class="form-control recargaDatos" id="nCuotasVenta" name="nCuotasVenta" placeholder="" required>
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                      <option>6</option>
                      <option>7</option>
                      <option>8</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-3 mb-3 mb-sm-0">
                    <label for="interesVenta">Factor Interés</label>
                    <input type="number" class="form-control" id="interesVenta" name="interesVenta" placeholder="" required readonly>
                  </div>
                  <div class="col-sm-3 ">
                    <label for="" style="visibility:hidden">Label invisible</label>
                    <div class="form-check row align-items-center">
                      <input class="form-check-input recargaDatos" type="checkbox" value="" id="aplicarInteresVenta" name="aplicarInteresVenta" checked>
                      <label class="form-check-label" for="aplicarInteresVenta">
                        Aplicar
                      </label>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label for="montoPieVenta">Valor Cuota</label>
                    <input type="number" class="form-control" id="valorCuotaVenta" name="valorCuotaVenta" placeholder="" required readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="deudaFinalVenta">Deuda Final</label>
                    <input type="number" class="form-control" id="deudaFinalVenta" name="deudaFinalVenta" placeholder="" required readonly>
                  </div>
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="fechaVenta">Fecha Vencimiento</label>
                    <input type="date" class="form-control" id="vencimientoVenta" name="vencimientoVenta" placeholder="" required readonly>
                  </div>
                </div>  
                <label for="exampleFormControlFile1" id="marcaPosicionProducto">Productos</label>
                <fieldset id="inputProducto1" class="clonedInput3">
                  <div class="form-group row">
                    <div class="col-12">
                      <input type="text" class="form-control" id="producto1" name="producto1">
                    </div>
                  </div>
                </fieldset>
                <div class="form-group">  
                    <fieldset>
                      <label></label>
                      <input type="button" class="btn btn-success btn-circle btn-sm" id="btnAddProducto1" value="+" />
                      <input type="button" class="btn btn-success btn-circle btn-sm" id="btnDelProducto1" value="-" />
                    </fieldset>
                </div>               
        </div>
        <div class="modal-footer"> 
          <button type="submit" class="btn btn-primary spin" id="botonAnadirVenta" href="login.html" onclick=" {{ Request::is('uploadVenta')  ? this.disabled: true }}" disabled>Añadir</button>
          <button class="btn btn-secondary cerrarModalVenta" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal detalles Ventas-->
    <div class="modal fade" id="detallesVentaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="titleDetalleVenta">Detalle Venta</h5>
          <button class="close cerrarModalDetalleVenta" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user"  action="crearComprobanteVenta" method="post" enctype="multipart/form-data" id="formularioDetalleVenta">
        @csrf
        <div class="modal-body">
                  
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="fechaVentaDet">Fecha y Hora</label>
                    <input type="datetime-local" class="form-control" id="fechaHoraVentaDet" name="fechaHoraVentaDet" placeholder=""  required readonly>
                  </div>
                  <div class="col-sm-6">
                    <label for="nboleta">N° de Boleta</label>
                    <input type="text" class="form-control" id="nboletaDet" name="nboletaDet" placeholder="" required readonly>
                  </div>
                </div> 
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="montoVentaDet">Monto Final</label>
                    <input type="number" class="form-control" id="montoFinalVentaDet" name="montoFinalVentaDet" placeholder="" required readonly>
                  </div>
                  <div class="col-sm-6">
                    <label for="vendedorVentaDet">Vendedor</label>
                    <input type="text" list="itemsVendDet" class="form-control editableVentaDet" id="vendedorVentaDet" name="vendedorVentaDet" placeholder="" required readonly>
                    <!-- Lista de opciones -->
                    <datalist id="itemsVendDet" class="datalist">
                      @foreach ($vendedores as $vendedor)
                          <option value="{{ $vendedor->rutVendedor }}">{{ $vendedor->nombreVendedor }} {{ $vendedor->apellidoPatVendedor }} {{ $vendedor->apellidoMatVendedor }}</option>
                      @endforeach
                    </datalist>
                  </div>
                </div>    
                <div class="form-group row">
                  <div class="col-4">
                    <label for="rutVentaDet">Rut Cliente</label>
                    <input type="text" list="clientesDet" class="form-control" name="rutVentaDet" id="rutVentaDet" required readonly>
                    <!-- Lista de opciones -->
                    <datalist id="clientesDet" class="datalist">
                      @foreach ($clientes as $cliente)
                          <option value="{{ $cliente->rutCliente }}">{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}</option>
                      @endforeach
                      @foreach ($adicionales as $adicional)
                          <option value="{{ $adicional->rutAdicional }}">{{ $adicional->nombreAdicional }} {{ $adicional->apellidoPatAdicional }} {{ $adicional->apellidoMatAdicional }}</option>
                      @endforeach
                    </datalist>
                  </div>
                  <div class="col-8 ">
                    <label for="rutVentaDet">Nombre Cliente</label>
                    <input type="text" class="form-control" name="nombreVentaDet" id="nombreVentaDet" placeholder="" required readonly>
                  </div>
                  
                </div>
                <div class="form-group row">
                  <div class="col-6">
                    <label for="nombreTitularVentaDet">Titular</label>
                    <input type="text" class="form-control" name="nombreTitularVentaDet" id="nombreTitularVentaDet" placeholder="" readonly required>
                  </div>
                  <div class="col-sm-6">
                    <label for="ruttitularDet">Rut Titular</label>
                    <input type="text" class="form-control" id="rutTitularVentaDet" name="rutTitularVentaDet" placeholder="" readonly required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <label for="comentarioVentaDet">Comentario</label>
                    <input type="text" class="form-control editableVentaDet" id="comentarioVentaDet" name="comentarioVentaDet" readonly>
                  </div>
                </div> 
                <!--Datos Crédito-->               
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="montoVentaDet">Monto Venta $</label>
                    <input type="number" class="form-control recargaDatosDet numberPositivo editableVentaDet" id="montoVentaDet" name="montoVentaDet" placeholder="" min="10" required readonly>
                  </div>
                  <div class="col-sm-6">
                    <label for="montoPieVentaDet">Monto Pie</label>
                    <input type="number" min="0" class="form-control recargaDatosDet numberPositivo" id="montoPieVentaDet" name="montoPieVentaDet" placeholder="" value="0" readonly required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="saldoVentaDet">Saldo</label>
                    <input type="email" class="form-control" id="saldoVentaDet" name="saldoVentaDet" placeholder="" readonly required>
                  </div>
                  <div class="col-sm-6">
                    <label for="nCuotasVentaDet">N° Cuotas</label>
                    <select class="form-control recargaDatosDet editableVentaDet" id="nCuotasVentaDet" name="nCuotasVenta" placeholder="" required readonly>
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                      <option>6</option>
                      <option>7</option>
                      <option>8</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-3 mb-3 mb-sm-0">
                    <label for="interesVenta">Factor Interés</label>
                    <input type="number" class="form-control" id="interesVentaDet" name="interesVentaDet" placeholder="" required readonly>
                  </div>
                  <div class="col-sm-3 ">
                    <label for="" style="visibility:hidden">Label invisible</label>
                    <div class="form-check row align-items-center">
                      <input class="form-check-input recargaDatosDet editableVentaDet" type="checkbox" value="" id="aplicarInteresVentaDet" name="aplicarInteresVentaDet" readonly>
                      <label class="form-check-label" for="aplicarInteresVentaDet" >
                        Aplicar
                      </label>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label for="montoPieVentaDet">Valor Cuota</label>
                    <input type="number" class="form-control" id="valorCuotaVentaDet" name="valorCuotaVentaDet" placeholder="" required readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="deudaFinalVenta">Deuda Final</label>
                    <input type="number" class="form-control" id="deudaFinalVentaDet" name="deudaFinalVentaDet" placeholder="" required readonly>
                  </div>
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="fechaVentaDet">Fecha Vencimiento</label>
                    <input type="date" class="form-control" id="vencimientoVentaDet" name="vencimientoVentaDet" placeholder="" required readonly>
                  </div>
                </div> 

                <label for="exampleFormControlFile1" id="productosTitleDet">Productos</label>

                <label for="exampleFormControlFile1" id="marcaPosicionProductoDet">Añadir Productos</label>
                <fieldset id="inputProductoDet1" class="clonedInputDet3">
                  <div class="form-group row">
                    <div class="col-12">
                      <input type="text" class="form-control" id="productoDet1" name="productoDet1">
                    </div>
                  </div>
                </fieldset>
                <div class="form-group" id="botonesProductoDet">  
                    <fieldset>
                      <label></label>
                      <input type="button" class="btn btn-success btn-circle btn-sm" id="btnAddProductoDet1" value="+" />
                      <input type="button" class="btn btn-success btn-circle btn-sm" id="btnDelProductoDet1" value="-" />
                    </fieldset>
                </div>
                <div  class="form-group  hidden" style="display: none;">
                  <input type="text" class="form-control" id="auxIdVenta" name="auxIdVenta" required  readonly>
                </div>                
        </div>
        <div class="modal-footer"> 
          
          <button type="submit" class="btn btn-success imprimirModalVenta spin" id="botonImprimirVenta">Imprimir</button>
          <button class="btn btn-secondary cerrarModalVenta" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Añadir Cliente -->
  <div class="modal fade" id="anadirClienteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Añadir Cliente</h5>
          <button class="close cerrarModalCliente" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="uploadCliente" method="post" enctype="multipart/form-data" id="formularioAñadirCliente">
        @csrf
        <div class="modal-body">     
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" name="rutCliente" id="rutCliente" placeholder="Rut" onkeyup="formateaRut('rutCliente')" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" placeholder="Nombre" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" id="apellidoPatCliente" name="apellidoPatCliente" placeholder="Apellido Paterno" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="apellidoMatCliente" name="apellidoMatCliente" placeholder="Apellido Materno" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="email" class="form-control" id="correoCliente" name="correoCliente" placeholder="Correo (Opcional)">
                  </div>
                  <div class="col-sm-6">
                    <input type="tel" class="form-control" id="telefonoCliente" name="telefonoCliente" placeholder="Teléfono" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" id="direccionCliente" name="direccionCliente" placeholder="Domicilio" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" list="itemsRecomendadoPor" class="form-control" id="recomendadoPor" name="recomendadoPor" placeholder="Recomendado por"  required>
                    <!-- Lista de opciones -->
                    <datalist id="itemsRecomendadoPor" class="datalist">
                      @foreach ($clientes as $cliente)
                        <tr>
                          <option value="{{ $cliente->rutCliente }}">{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}</option>
                      @endforeach
                    </datalist>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-sm-6">
                    <label for="fechaFacturacionCliente">Fecha de Facturación</label>
                    <input type="date" class="form-control" id="fechaFacturacionCliente" name="fechaFacturacionCliente" placeholder="" readonly>
                  </div>
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="fechaPagoCliente">Fecha de Pago</label>
                    <input type="date" class="form-control dateLimited" id="fechaPagoCliente" name="fechaPagoCliente" placeholder="" min ="{{ date("Y-m-d",strtotime(date("Y-m-d")."+ 14 days"))}}">
                  </div>
                </div>

                <label for="exampleFormControlFile1" id="marcaPosicionInputFile">Documento (Opcional)</label>
                <fieldset id="input1" class="clonedInput">
                  <div class="form-group">  
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="documentoCliente1" name="documentoCliente1">
                      <label class="custom-file-label" for="documentoCliente1">Selecciona un Archivo</label>
                    </div>
                  </div>
                </fieldset>
                <div class="form-group">  
                    <fieldset>
                      <label></label>
                      <input type="button" class="btn btn-success btn-circle btn-sm" id="btnAddCliente1" value="+" />
                      <input type="button" class="btn btn-success btn-circle btn-sm" id="btnDelCliente1" value="-" />
                    </fieldset>
                </div>
                               
        </div>
        <div class="modal-footer"> 
          <button type="submit" class="btn btn-primary spin" href="login.html"  onclick=" {{ Request::is('uploadCliente')  ? this.disabled: true }}">Añadir</button>
          <button class="btn btn-secondary cerrarModalCliente" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal detalles clientes-->
  <div class="modal fade" id="detallesClienteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detalle Cliente</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="updateCliente" method="post" enctype="multipart/form-data" id="formularioDetalleCliente">
        @csrf
        <div id="modal-body-detalle-cliente" class="modal-body">
                <div class="form-group row">
                  <div class="col align-self-end">
                    <div class="custom-control custom-switch">     
                      <input type="checkbox" class="custom-control-input" id="customSwitch1" id="customSwitch1">
                      <label class="custom-control-label" for="customSwitch1">Editar</label>
                    </div>
                  </div>
                </div>     
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" name="rutClienteDet" id="rutClienteDet" placeholder="Rut" onkeyup="formateaRut('rutClienteDet')" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" id="nombreClienteDet" name="nombreClienteDet" placeholder="Nombre" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" id="apellidoPatClienteDet" name="apellidoPatClienteDet" placeholder="Apellido Paterno" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="apellidoMatClienteDet" name="apellidoMatClienteDet" placeholder="Apellido Materno" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="email" class="form-control" id="correoClienteDet" name="correoClienteDet" placeholder="Correo (Opcional)">
                  </div>
                  <div class="col-sm-6">
                    <input type="tel" class="form-control" id="telefonoClienteDet" name="telefonoClienteDet" placeholder="Teléfono" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" id="direccionClienteDet" name="direccionClienteDet" placeholder="Domicilio" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" list="itemsRecomendadoPorDet" class="form-control" id="recomendadoPorDet" name="recomendadoPorDet" placeholder="Recomendado por" required>
                    <!-- Lista de opciones -->
                    <datalist id="itemsRecomendadoPorDet" class="datalist">
                      @foreach ($clientes as $cliente)
                        <tr>
                          <option value="{{ $cliente->rutCliente }}">{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}</option>
                      @endforeach
                    </datalist>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col align-self-end">
                    <div class="custom-control custom-switch">     
                      <input type="checkbox" class="custom-control-input" id="switchFechaDetCliente" id="customSwitch1">
                      <label class="custom-control-label" for="switchFechaDetCliente">Editar Fecha de Pago</label>
                    </div>
                  </div>
                </div> 

                <div class="form-group row">
                  <div class="col-sm-6">
                    <label for="fechaFacturacionCliente">Fecha de Facturación</label>
                    <input type="date" class="form-control" id="fechaFacturacionClienteDet" name="fechaFacturacionClienteDet" placeholder="" readonly>
                  </div>
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label id="labelFechaPagoDet" for="fechaPagoClienteDet">Fecha de Pago</label>
                    <input type="date" class="form-control dateLimited" id="fechaPagoClienteDet" name="fechaPagoClienteDet" placeholder="" min ="{{ date("Y-m-d",strtotime(date("Y-m-d")."+ 14 days"))}}">
                  </div>
                </div>  

                <label for="exampleFormControlFile1" id="archivosAdjuntosTitleDet">Archivos Adjuntos</label>

                <label for="exampleFormControlFile1" id="marcaPosicionInputFileDet">Subir Documento</label>
                <fieldset id="inputDet1" class="clonedInputDet">
                  <div class="form-group">  
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="documentoClienteDet1" name="documentoClienteDet1">
                      <label class="custom-file-label" for="documentoClienteDet1">Selecciona un Archivo</label>
                    </div>
                  </div>
                </fieldset>
                <div class="form-group" id="botonesDocumentoDet">  
                    <fieldset>
                      <label></label>
                      <label></label>
                      <input type="button" class="btn btn-success btn-circle btn-sm" id="btnAddClienteDet1" value="+" />
                      <input type="button" class="btn btn-success btn-circle btn-sm" id="btnDelClienteDet1" value="-" />
                    </fieldset>
                </div>
                               
        </div>
        <div class="modal-footer"> 
          <button type="submit" class="btn btn-primary spin" id="botonActualizarCliente" href="login.html" onclick=" {{ Request::is('updateCliente')  ? this.disabled: true }}">Actualizar</button>
          <button class="btn btn-secondary cerrarModalDetalle" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal detalles Adicionals-->
  <div class="modal fade" id="detallesAdicionalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detalle Adicional</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="updateAdicional" method="post" enctype="multipart/form-data" id="formularioDetalleAdicional">
        @csrf
        <div id="modal-body-detalle-Adicional" class="modal-body">
                <div class="form-group row">
                  <div class="col align-self-end">
                    <div class="custom-control custom-switch">     
                      <input type="checkbox" class="custom-control-input" id="customSwitch2" id="customSwitch2">
                      <label class="custom-control-label" for="customSwitch2">Editar</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row ">
                  <div class="col-6" id="divTitular">
                    <label for="rutTitularAdicionalDet">Rut Titular</label>
                    <input type="text" list="items" class="form-control" id="rutTitularAdicionalDet" name="rutTitularAdicionalDet" placeholder="Cuenta Titular" required>
                    <!-- Lista de opciones -->
                    <datalist id="items" class="datalist">
                      @foreach ($clientes as $cliente)
                          <option value="{{ $cliente->rutCliente }}">{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}
                          </option>
                      @endforeach
                    </datalist>
                  </div>
                  <div class="col-6">
                    <label for="nombreTitularDet">Nombre Titular</label>
                    <input type="text" class="form-control" id="nombreTitularDet" name="nombreTitularDet" required>
                  </div>
                </div> 

                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" name="rutAdicionalDet" id="rutAdicionalDet" placeholder="Rut" onkeyup="formateaRut('rutAdicionalDet')" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" id="nombreAdicionalDet" name="nombreAdicionalDet" placeholder="Nombre" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" id="apellidoPatAdicionalDet" name="apellidoPatAdicionalDet" placeholder="Apellido Paterno" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="apellidoMatAdicionalDet" name="apellidoMatAdicionalDet" placeholder="Apellido Materno" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="email" class="form-control" id="correoAdicionalDet" name="correoAdicionalDet" placeholder="Correo (Opcional)">
                  </div>
                  <div class="col-sm-6">
                    <input type="tel" class="form-control" id="telefonoAdicionalDet" name="telefonoAdicionalDet" placeholder="Teléfono" required>
                  </div>
                </div>         
        </div>
        <div class="modal-footer"> 
          <button type="submit" class="btn btn-primary spin" id="botonActualizarAdicional" href="login.html"  onclick=" {{ Request::is('updateAdicional')  ? this.disabled: true }}">Actualizar</button>
          <button class="btn btn-secondary cerrarModalDetalle" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Moroso y Bloqueado-->
  <div class="modal fade" id="morosoBloqueadoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Atención: Cliente bloqueado</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="deleteAdicional" method="post" enctype="multipart/form-data" id="formularioEliminarAdicional">
        @csrf
        <div id="modal-body" class="modal-body">
          <p>El titular se encuentra bloqueado por mora, la operación no se podrá realizar. Diríjase a la sección cuentas para desbloquear.</p> 
        </div>
        <div class="modal-footer"> 
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Moroso -->
  <div class="modal fade" id="morosoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Atención: Cliente Moroso</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="deleteAdicional" method="post" enctype="multipart/form-data" id="formularioEliminarAdicional">
        @csrf
        <div id="modal-body" class="modal-body">
          <p>El titular se encuentra moroso. Aún así la operación se puede completar ya que el cliente no se encuentra bloqueado.</p> 
        </div>
        <div class="modal-footer"> 
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal eleminar venta-->
  <div class="modal fade" id="eliminarVentaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Anular Venta</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="deleteVenta" method="post" enctype="multipart/form-data" id="formularioEliminarVenta">
        @csrf
        <div id="modal-body eliminar-Adicional" class="modal-body">
          <p id="contenidoEliminarventa">¿Está seguro que desea anular esta Venta?. Ingrese el número de nota de crédito para realizar la operación</p> 
          <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="text" class="form-control" id="numeroNotaCredito" name="numeroNotaCredito" placeholder="N° Nota de crédito" required>
            </div>        
          </div>
        </div>
        <div class="modal-footer"> 
          <button type="submit" class="btn btn-danger spin" id="botonEliminarVenta" {{ Request::is('anularVenta/..')  ? disabled :  null }} >Anular</button>
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Moroso Edit -->
  <div class="modal fade" id="morosoModalDet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Atención: Cliente Moroso</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="deleteAdicional" method="post" enctype="multipart/form-data" id="formularioEliminarAdicional">
        @csrf
        <div id="modal-body" class="modal-body">
          <p>No se pueden editar los datos de venta. El titular se encuentra moroso.</p> 
        </div>
        <div class="modal-footer"> 
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Para los números de boleta iguales -->
  <div class="modal fade" id="nBoletaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Atención: Número de boleta registrado</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="deleteAdicional" method="post" enctype="multipart/form-data" id="formularioEliminarAdicional">
        @csrf
        <div id="modal-body" class="modal-body">
          <p>El número de boleta ingresado ya se encuentra registrado. Ingrese un nuevo número para procesar la venta.</p> 
        </div>
        <div class="modal-footer"> 
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal adicional Bloqueado-->
  <div class="modal fade" id="adicionalBloqueadoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Atención: Adicional bloqueado</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="deleteAdicional" method="post" enctype="multipart/form-data" id="formularioEliminarAdicional">
        @csrf
        <div id="modal-body" class="modal-body">
          <p>El adicional se encuentra bloqueado, la operación no se podrá realizar. Diríjase a la sección cuentas para desbloquear.</p> 
        </div>
        <div class="modal-footer"> 
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @parent
  <!-- Page level plugins -->
  <script src="{{asset("assets/$theme/vendor/datatables/jquery.dataTables.min.js")}}"></script>
  <script src="{{asset("assets/$theme/vendor/datatables/dataTables.bootstrap4.min.js")}}"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset("assets/$theme/js/demo/datatables-demo.js")}}"></script>
  <script src="{{asset("js/JQueryVentas.js")}}"></script>
  <script src="{{asset("js/JQueryClientes.js")}}"></script>
  <script src="{{asset("js/JQueryAdicionales.js")}}"></script>
  

@endsection

