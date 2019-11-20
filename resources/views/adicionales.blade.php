@extends('layouts.layout')

@section('title', 'Adicionales')

@section('sidebar')
    @parent
@endsection

@section('content')
        <!-- Begin Page Content -->
        
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-2 text-gray-800">Adicionales <i class="fas fa-fw fa-user"></i></h1>
            <a href="#" class="d-inline-block btn btn-sm btn-primary shadow-sm" data-target="#anadirAdicionalModal" data-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i> Añadir Adicional</a>
          </div>
          
          <p class="mb-4">Navega por el listado de adicionales y accede a sus detalles en la sección "Opciones". Utiliza el buscador "Search" si quieres encontrar un adicional rápidamente. Los adicionales podrán realizar compras con crédito, pero estas se cargarán a la cuenta del titular correspondiente</p>
          <!--este input está para arreglar el bug de funciones.js-->
          <input type="date" class="form-control dateLimited" id="arreglarBug" name="arreglarBug" placeholder=""  min ="{{ date("Y-m-d",strtotime(date("Y-m-d")."+ 14 days"))}}" style="display: none;">

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Listado de Adicionales</h6>
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
                      <th>Titular</th>
                      <th>Teléfono</th>
                      <th>Correo</th>
                      <th>Opciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Rut</th>
                      <th>Nombre</th>
                      <th>Titular</th>
                      <th>Teléfono</th>
                      <th>Correo</th>
                      <th>Opciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    @foreach ($adicionales as $adicional)
                      <tr>
                        <td>{{ $adicional->rutAdicional }}</td>
                        <td>{{ $adicional->nombreAdicional }} {{ $adicional->apellidoPatAdicional }} {{ $adicional->apellidoMatAdicional }}</td>
                        <td>{{$adicional->nombreCliente}} {{$adicional->apellidoPatCliente}} {{$adicional->apellidoMatCliente}}</td>
                        <td>{{ $adicional->telefonoAdicional }}</td>
                        <td>{{ $adicional->correoAdicional }}</td>
                        <td>
                          <a href="#" id="{{ $adicional->idAdicional }}" class="btn btn-info btn-circle btn-sm botonDetalleAdicional" data-target="#detallesAdicionalModal" data-toggle="modal">
                            <i class="fas fa-search"></i>
                          </a>
                          <a href="#" id="{{ $adicional->idAdicional }}" class="btn btn-danger btn-circle btn-sm botonEliminarAdicional" data-target="#eliminarAdicionalModal" data-toggle="modal">
                            <i class="fas fa-trash"></i>
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

  <!-- Modal Añadir adicional -->
  <div class="modal fade" id="anadirAdicionalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Añadir Adicional</h5>
          <button class="close cerrarModalAdicional" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="uploadAdicional" method="post" enctype="multipart/form-data" id="formularioAñadirAdicional">
        @csrf
        <div class="modal-body">
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" list="items" class="form-control" id="rutTitularAdicional" name="rutTitularAdicional" placeholder="Cuenta Titular" required>
                    <!-- Lista de opciones -->
                    <datalist id="items" class="datalist">
                      @foreach ($clientes as $cliente)
                          <option value="{{ $cliente->rutCliente }}">{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}
                          </option>
                      @endforeach
                    </datalist>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" name="rutAdicional" id="rutAdicional" placeholder="Rut" onkeyup="formateaRut('rutAdicional')" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" id="nombreAdicional" name="nombreAdicional" placeholder="Nombre" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" id="apellidoPatAdicional" name="apellidoPatAdicional" placeholder="Apellido Paterno" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="apellidoMatAdicional" name="apellidoMatAdicional" placeholder="Apellido Materno" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="email" class="form-control" id="correoAdicional" name="correoAdicional" placeholder="Correo (Opcional)">
                  </div>
                  <div class="col-sm-6">
                    <input type="tel" class="form-control" id="telefonoAdicional" name="telefonoAdicional" placeholder="Teléfono" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" id="direccionAdicional" name="direccionAdicional" placeholder="Domicilio" required>
                  </div>
                </div>                 
        </div>
        <div class="modal-footer"> 
          <button type="submit" class="btn btn-primary spin"  href="login.html" {{ Request::is('uploadAdicional')  ? this.disabled: true }} >Añadir</button>
          <button class="btn btn-secondary cerrarModalAdicional" type="button" data-dismiss="modal">Salir</button>
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
                <div class="form-group row">
                  <div class="col-12">
                    <input type="text" class="form-control" id="direccionAdicionalDet" name="direccionAdicionalDet" placeholder="Domicilio" required>
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

  <!-- Modal eleminar adicional-->
  <div class="modal fade" id="eliminarAdicionalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Eliminar Adicional</h5>
          <button class="close cerrarModalDetalle" type="button" data-dismiss="modal" aria-label="Close" id="cerrarModalDetalle">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form class="user" action="deleteAdicional" method="post" enctype="multipart/form-data" id="formularioEliminarAdicional">
        @csrf
        <div id="modal-body eliminar-Adicional" class="modal-body">
          <p>¿Está seguro que desea eliminar el adicional?.</p> 
        </div>
        <div class="modal-footer"> 
          <button type="submit" class="btn btn-primary spin" id="botonEliminarAdicional" onclick=" {{ Request::is('deleteAdicional')  ? this.disabled: true }}">Eliminar</button>
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
  <script src="{{asset("js/JQueryAdicionales.js")}}"></script>
@endsection

