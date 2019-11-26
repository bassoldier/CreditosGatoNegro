@extends('layouts.layout')

@section('title', 'Cuentas por Adicional')

@section('sidebar')
    @parent
@endsection

@section('content')
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-2 text-gray-800">Cuentas por Adicional<i class="fas fa-fw fa-credit-card"></i></h1>
          </div>

          
          <p class="mb-4">Cada compra realizada por un adicional se carga a la cuenta de su titular, en esta sección podrás ver las cuentas titulares asociadas a cada adicional. Ingresa en el ícono <a href="#" class="btn btn-success btn-circle btn-sm disabled"><i class="fas fa-dollar-sign"></i></a> para abonar y ver le historial de abonos.</p>

          <!--este input está para arreglar el bug de funciones.js-->
          <input type="date" class="form-control dateLimited" id="arreglarBug" name="arreglarBug" placeholder=""  min ="{{ date("Y-m-d",strtotime(date("Y-m-d")."+ 14 days"))}}" style="display: none;">
           

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Listado de Cuentas</h6>
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

            @if (session('printAbono'))
              <div class="alert alert-success">
                  {{ session('printAbono') }}
              </div>
              <iframe src="{{ asset('tempPDF/abono.pdf') }}" id="PDFtoPrint" width="100%" height="100%" style="display: none"> </iframe>
                <script type="text/javascript">
                  function printPDF(PDFtoPrint){
                    document.getElementById('PDFtoPrint').focus();
                    document.getElementById('PDFtoPrint').contentWindow.print();
                   
                  }

                  printPDF("{{ asset('tempPDF/abono.pdf') }}");
                </script>
            @endif

            @if (session('printAbonoDet'))
              <div class="alert alert-warning">
                  {{ session('printAbonoDet') }}
              </div>
              <iframe src="{{ asset('tempPDF/abono.pdf') }}" id="PDFtoPrint" width="100%" height="100%" style="display: none"> </iframe>
                <script type="text/javascript">
                  function printPDF(PDFtoPrint){
                    document.getElementById('PDFtoPrint').focus();
                    document.getElementById('PDFtoPrint').contentWindow.print();
                   
                  }

                  printPDF("{{ asset('tempPDF/abono.pdf') }}");
                </script>
            @endif

            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered tablaCuentas" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Rut Adicional</th>
                      <th>Adicional</th>
                      <th>Titular</th>
                      <th>Deuda Titular</th>
                      <th>Titular Moroso</th>
                      <th>Bloqueo Adicional</th>
                      <th>Opciones</th>
                      <th>Abonar</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Rut Adicional</th>
                      <th>Adicional</th>
                      <th>Titular</th>
                      <th>Deuda Titular</th>
                      <th>Titular Moroso</th>
                      <th>Bloqueo Adicional</th>
                      <th>Opciones</th>
                      <th>Abonar</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    @foreach ($clientes as $cliente)
                      <tr>
                        <td>{{ $cliente->rutAdicional }}</td>
                        <td>{{ $cliente->nombreAdicional }} {{ $cliente->apellidoPatAdicional }} {{ $cliente->apellidoMatAdicional }}</td>
                        <td>{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}</td>
                        <td>${{ number_format($cliente->deudaTotalCliente) }}</td>
                        <td>
                          @if ($cliente->morosoCliente === 0)
                            <a href="#" class="btn btn-success btn-circle btn-sm disabled">
                              <i class="fas fa-check-circle"></i>
                            </a>
                          @else
                            <a href="#" class="btn btn-warning btn-circle btn-sm disabled">
                              <i class="fas fa-exclamation-triangle"></i>
                            </a>
                          @endif  
                        </td>
                        <td>
                          <div class="custom-control custom-switch">     
                            <input type="checkbox" class=" switchMora {{ $cliente->idAdicional }}" id="{{ $cliente->idAdicional }}" {{ ($cliente->bloqueoAdicional === 1)  ? 'checked' : null }}>
                          </div>
                        </td>
                        <td>
                          <a href="#" id="{{ $cliente->idCliente }}" class="btn btn-info btn-circle btn-sm botonDetalleCuenta" data-target="#detallesCuentaModal" data-toggle="modal">
                            <i class="fas fa-search"></i>
                          </a>
                          <a href="#" id="{{ $cliente->idAdicional }}" class="btn btn-warning btn-circle btn-sm botonDetalleCuentaVentas" data-target="#detallesCuentaVentasModal" data-toggle="modal">
                            <i class="fas fa-dollar-sign"></i>
                          </a>
                          
                        </td>
                        <td>
                      
                          <a href="#" id="{{ $cliente->idCliente }}" class="btn btn-success btn-circle btn-sm botonAbonoCuenta" data-target="#abonoCuentaModal" data-toggle="modal">
                            <i class="fas fa-dollar-sign"></i>
                          </a>
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

  <!-- Modal detalles cuentas (Deuda)-->
  <div class="modal fade" id="detallesCuentaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detalle Cuenta</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="updateCuenta" method="post" enctype="multipart/form-data" id="formularioDetalleCuenta">
        @csrf
        <div id="modal-body-detalle-cuenta" class="modal-body">
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Deudas Mensuales</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered tablaCuentas" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Fecha Facturación</th>
                      <th>Fecha Pago</th>
                      <th>Monto</th>
                      <th>Estado</th>
                      <th>Opciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Fecha Facturación</th>
                      <th>Fecha Pago</th>
                      <th>Monto</th>
                      <th>Estado</th>
                      <th>Opciones</th>
                    </tr>
                  </tfoot>
                  <tbody id="tBodyDeudas">
                    <!-- Acá se cargan los datos por jquery -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
                               
        </div>
        <div class="modal-footer"> 
          <button class="btn btn-secondary cerrarModalDetalle" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal detalles Cuenta (Ventas)-->
  <div class="modal fade" id="detallesCuentaVentasModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="titleVentasAdicional">Ventas Asociadas</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="updateCuenta" method="post" enctype="multipart/form-data" id="formularioDetalleCuenta">
        @csrf
        <div id="modal-body-detalle-cuenta" class="modal-body">
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Listado de Ventas</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>N° Boleta</th>
                      <th>Monto</th>
                      <th>Cliente</th>
                      <th>Estado</th>
                      <th>Opciones Ventas</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Fecha</th>
                      <th>N° Boleta</th>
                      <th>Monto</th>
                      <th>Cliente</th>
                      <th>Estado</th>
                      <th>Opciones Ventas</th>
                    </tr>
                  </tfoot>
                  <tbody id="tBodyVentas">
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
                               
        </div>
        <div class="modal-footer"> 
          <button class="btn btn-secondary cerrarModalDetalle" type="button" data-dismiss="modal">Salir</button>
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
          <button type="submit" class="btn btn-danger disabled spin" id="botonEliminarVenta" >Anular</button>
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
        <form class="user" action="crearComprobanteVenta" method="post" enctype="multipart/form-data" id="formularioDetalleVenta">
        @csrf
        <div class="modal-body">
                  
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="fechaVentaDet">Fecha y Hora</label>
                    <input type="datetime-local" class="form-control" id="fechaHoraVentaDet" name="fechaHoraVentaDet" placeholder=""  required readonly>
                  </div>
                  <div class="col-sm-6">
                    <label for="nboleta">N° de Boleta</label>
                    <input type="text" class="form-control editableVentaDet" id="nboletaDet" name="nboletaDet" placeholder="" required readonly>
                  </div>
                </div> 
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="montoVentaDet">Monto Final</label>
                    <input type="number" class="form-control" id="montoFinalVentaDet" name="montoFinalVentaDet" placeholder="" required readonly>
                  </div>
                  <div class="col-sm-6">
                    <label for="vendedorVentaDet">Vendedor</label>
                    <input type="text"  class="form-control editableVentaDet" id="vendedorVentaDet" name="vendedorVentaDet" placeholder="" required readonly>
                    <!-- Lista de opciones -->
                    
                  </div>
                </div>    
                <div class="form-group row">
                  <div class="col-4">
                    <label for="rutVentaDet">Rut Cliente</label>
                    <input type="text"  class="form-control" name="rutVentaDet" id="rutVentaDet" required readonly>
                    <!-- Lista de opciones -->
                    
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
                    <input type="number" min="0" class="form-control recargaDatosDet numberPositivo editableVentaDet" id="montoVentaDet" name="montoVentaDet" placeholder="" required readonly>
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
                      <label class="form-check-label" for="aplicarInteresVentaDet" readonly>
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

    <!-- Modal Abonos-->
  <div class="modal fade" id="abonoCuentaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="abonosTitle">Abonos</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        
        <div id="modal-body-detalle-cuenta" class="modal-body">

          <form class="form-inline" action="uploadAbono" method="post" enctype="multipart/form-data" id="formularioAbono">
          @csrf
          <div class="container-fluid">
            
          
          <div class="row ">
            <div class="form-group  col-7 ">
              <label id="labelDeudaText">Deuda total:</label> &nbsp;$<label id="labelDeudaTotal">$00000 </label> &nbsp;
              <input type="number" class="form-control numberPositivo abono col-7" min="10" max="6000" id="abono" name="abono" placeholder="$ Monto Abono" required>
            </div>
            <div class="form-group  col-5">
              <button type="submit" class="btn btn-success mb-2 spin">Pagar</button>
            </div>
            
            <div  class="form-group  hidden" style="display: none;">
              <input type="text" class="form-control" id="auxIdCliente" name="auxIdCliente" required  readonly>
            </div>
          </div>
          </div>
          </form>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Abonos Realizados</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Monto</th>
                      <th>Fecha</th>
                      <th>Imprimir</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Monto</th>
                      <th>Fecha</th>
                      <th>Imprimir</th>
                    </tr>
                  </tfoot>
                  <tbody id="tBodyAbonos">
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
                               
        </div>
        <div class="modal-footer"> 
          <button class="btn btn-secondary cerrarModalDetalle" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Exceso Abono Modal-->
  <div class="modal fade" id="abonoExceso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pago Excesivo</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="deleteAdicional" method="post" enctype="multipart/form-data" id="formularioEliminarAdicional">
        @csrf
        <div id="modal-body" class="modal-body">
          <p>El monto ingresado no puede exceder el monto de deuda.</p> 
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
  <script src="{{asset("js/funcionesJS.js")}}"></script>
  <script src="{{asset("js/JQueryCuentasAdicional.js")}}"></script>
  <script src="{{asset("js/JQueryVentas.js")}}"></script>
@endsection

