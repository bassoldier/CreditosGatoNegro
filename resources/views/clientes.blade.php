@extends('layouts.layout')

@section('title', 'Clientes')

@section('sidebar')
    @parent
@endsection

@section('content')
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-2 text-gray-800">Clientes <i class="fas fa-fw fa-user"></i></h1>
            <a href="#" class="d-inline-block btn btn-sm btn-primary shadow-sm" data-target="#anadirClienteModal" data-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i> Añadir Cliente</a>
          </div>
          
          <p class="mb-4">Navega por el listado de clientes y accede a sus detalles en la sección "Opciones". Utiliza el buscador "Search" si quieres encontrar un cliente rápidamente.</p>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Listado de Clientes</h6>
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
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Rut</th>
                      <th>Nombre</th>
                      <th>Teléfono</th>
                      <th>Deuda</th>
                      <th>Moroso</th>
                      <th>Opciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Rut</th>
                      <th>Nombre</th>
                      <th>Teléfono</th>
                      <th>Deuda</th>
                      <th>Moroso</th>
                      <th>Opciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    @foreach ($clientes as $cliente)
                      <tr>
                        <td>{{ $cliente->rutCliente }}</td>
                        <td>{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}</td>
                        <td>{{ $cliente->telefonoCliente }}</td>
                        <td>$ {{ number_format($cliente->deudaTotalCliente) }}</td>
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
                          <a href="#" id="{{ $cliente->idCliente }}" class="btn btn-info btn-circle btn-sm botonDetalleCliente" data-target="#detallesClienteModal" data-toggle="modal">
                            <i class="fas fa-search"></i>
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
                    <input type="text" list="clientesAnadir" class="form-control" id="recomendadoPor" name="recomendadoPor" placeholder="Recomendado por" >
                    <!-- Lista de opciones -->
                    <datalist id="clientesAnadir" class="datalist">
                      @foreach ($clientes as $cliente)
                        <tr>
                          <option value="{{ $cliente->rutCliente }}">{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}</option>
                      @endforeach
                    </datalist>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-sm-6">
                    <label for="fechaFacturacionCliente">Primera Fecha de Facturación</label>
                    <input type="date" class="form-control" id="fechaFacturacionCliente" name="fechaFacturacionCliente" placeholder="" readonly>
                  </div>
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="fechaPagoCliente">Primera Fecha de Pago</label>
                    <div class="input-group date">
                      <input type="date" class="form-control dateLimited" id="fechaPagoCliente" name="fechaPagoCliente" placeholder=""  min ="{{ date("Y-m-d",strtotime(date("Y-m-d")."+ 14 days"))}}">
                    </div>
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
                    <input type="text" list="clientesDet" class="form-control" id="recomendadoPorDet" name="recomendadoPorDet" placeholder="Recomendado por" >
                    <!-- Lista de opciones -->
                    <datalist id="clientesDet" class="datalist">
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

  

@endsection

@section('scripts')
  @parent
  <!-- Page level plugins -->
  <script src="{{asset("assets/$theme/vendor/datatables/jquery.dataTables.min.js")}}"></script>
  <script src="{{asset("assets/$theme/vendor/datatables/dataTables.bootstrap4.min.js")}}"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset("assets/$theme/js/demo/datatables-demo.js")}}"></script>
  <script src="{{asset("js/funcionesJS.js")}}"></script>
  <script src="{{asset("js/JQueryClientes.js")}}"></script>
@endsection

