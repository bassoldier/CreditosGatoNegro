@extends('layouts.layout')

@section('title', 'Vendedores')

@section('sidebar')
    @parent
@endsection

@section('content')
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-2 text-gray-800">Vendedores <i class="fas fa-fw fa-comment-dollar"></i></h1>
            <a href="#" class="d-inline-block btn btn-sm btn-primary shadow-sm" data-target="#anadirVendedorModal" data-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i> Añadir Vendedor</a>
          </div>
          
          <p class="mb-4">Navega por el listado de vendedores y accede a sus detalles en la sección "Opciones". Utiliza el buscador "Search" si quieres encontrar un vendedor rápidamente.</p>

          <!--este input está para arreglar el bug de funciones.js-->
          <input type="date" class="form-control dateLimited" id="arreglarBug" name="arreglarBug" placeholder=""  min ="{{ date("Y-m-d",strtotime(date("Y-m-d")."+ 14 days"))}}" style="display: none;">

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Listado de Vendedores</h6>
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
                      <th>Correo</th>
                      <th>Opciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Rut</th>
                      <th>Nombre</th>
                      <th>Teléfono</th>
                      <th>Correo</th>
                      <th>Opciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    @foreach ($vendedores as $vendedor)
                      <tr>
                        <td>{{ $vendedor->rutVendedor }}</td>
                        <td>{{ $vendedor->nombreVendedor }} {{ $vendedor->apellidoPatVendedor }} {{ $vendedor->apellidoMatVendedor }}</td>
                        <td>{{ $vendedor->telefonoVendedor }}</td>
                        <td>{{ $vendedor->correoVendedor }}</td>
                        <td>
                          <a href="#" id="{{ $vendedor->idVendedor }}" class="btn btn-info btn-circle btn-sm botonDetalleVendedor" data-target="#detallesVendedorModal" data-toggle="modal">
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

  <!-- Modal Añadir vendedor -->
  <div class="modal fade" id="anadirVendedorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Añadir Vendedor</h5>
          <button class="close cerrarModalVendedor" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="uploadVendedor" method="post" enctype="multipart/form-data" id="formularioAñadirVendedor">
        @csrf
        <div class="modal-body">     
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" name="rutVendedor" id="rutVendedor" placeholder="Rut" onkeyup="formateaRut('rutVendedor')" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" id="nombreVendedor" name="nombreVendedor" placeholder="Nombre" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" id="apellidoPatVendedor" name="apellidoPatVendedor" placeholder="Apellido Paterno" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="apellidoMatVendedor" name="apellidoMatVendedor" placeholder="Apellido Materno" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="email" class="form-control" id="correoVendedor" name="correoVendedor" placeholder="Correo (Opcional)">
                  </div>
                  <div class="col-sm-6">
                    <input type="tel" class="form-control" id="telefonoVendedor" name="telefonoVendedor" placeholder="Teléfono" required>
                  </div>
                </div>                 
        </div>
        <div class="modal-footer"> 
          <button type="submit" class="btn btn-primary spin"  href="login.html" onclick=" {{ Request::is('uploadVendedor')  ? this.disabled: true }}">Añadir</button>
          <button class="btn btn-secondary cerrarModalVendedor" type="button" data-dismiss="modal">Salir</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal detalles Vendedors-->
  <div class="modal fade" id="detallesVendedorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detalle Vendedor</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="updateVendedor" method="post" enctype="multipart/form-data" id="formularioDetalleVendedor">
        @csrf
        <div id="modal-body-detalle-Vendedor" class="modal-body">
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
                    <input type="text" class="form-control" name="rutVendedorDet" id="rutVendedorDet" placeholder="Rut" onkeyup="formateaRut('rutVendedorDet')" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" id="nombreVendedorDet" name="nombreVendedorDet" placeholder="Nombre" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" id="apellidoPatVendedorDet" name="apellidoPatVendedorDet" placeholder="Apellido Paterno" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="apellidoMatVendedorDet" name="apellidoMatVendedorDet" placeholder="Apellido Materno" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="email" class="form-control" id="correoVendedorDet" name="correoVendedorDet" placeholder="Correo (Opcional)">
                  </div>
                  <div class="col-sm-6">
                    <input type="tel" class="form-control" id="telefonoVendedorDet" name="telefonoVendedorDet" placeholder="Teléfono" required>
                  </div>
                </div>         
        </div>
        <div class="modal-footer"> 
          <button type="submit" class="btn btn-primary spin" id="botonActualizarVendedor" href="login.html"  onclick=" {{ Request::is('updateVendedor')  ? this.disabled: true }}">Actualizar</button>
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
  <script src="{{asset("js/JQueryVendedores.js")}}"></script>
@endsection

