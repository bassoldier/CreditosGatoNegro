@extends('layouts.layout')

@section('title', 'Informes')

@section('sidebar')
    @parent
@endsection

@section('content')
        <!-- Begin Page Content -->
        
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-2 text-gray-800">Informes <i class="fas fa-fw fa-chart-area"></i></h1>
          </div>
          
          <p class="mb-4">La sección informe posee multiples consultas a los registros del sistema. Cada una descargará la consulta en formato excel.</p>
          <!--este input está para arreglar el bug de funciones.js-->
          <input type="date" class="form-control dateLimited" id="arreglarBug" name="arreglarBug" placeholder=""  min ="{{ date("Y-m-d",strtotime(date("Y-m-d")."+ 14 days"))}}" style="display: none;">

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Informes Globales</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-4">
                  <div class="row">
                    <div class="col-12">
                      <div class="card text-white bg-info" style="width: 18rem;">
                  
                        <div class="card-body">
                          <h5 class="card-title">Nómina de Clientes</h5>
                          <p class="card-text">Listado completo de los clientes registrados en el sistema.</p>
                        </div>
                        <div class="card-body">
                          <a href="{{URL('getClientesExcel')}}" class="btn btn-primary">Descargar</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="card text-white bg-info" style="width: 18rem;">
                  
                        <div class="card-body">
                          <h5 class="card-title">Clientes Morosos</h5>
                          <p class="card-text">Listado de clientes en mora a la fecha.</p>
                        </div>
                        <div class="card-body">
                          <a href="{{URL('getClientesMorososExcel')}}" class="btn btn-primary" >Descargar</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="card text-white bg-info" style="width: 18rem;">
                  
                        <div class="card-body">
                          <h5 class="card-title">Adicionales</h5>
                          <p class="card-text">Listado de los adicionales registrados en el sistema.</p>
                        </div>
                        <div class="card-body">
                          <a href="{{URL('getAdicionalesExcel')}}" class="btn btn-primary" >Descargar</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card text-white bg-info" style="width: 18rem;">
                  
                    <div class="card-body">
                      <h5 class="card-title">Ventas</h5>
                      <p class="card-text">Listado completo de las ventas registradas en el sistema.</p>
                    </div>
                    <form class="user" action="getVentasExcel" method="post" enctype="multipart/form-data" id="formularioDetalleCuenta">
                      @csrf
                    <ul class="list-group list-group-flush">                     
                      <li class="list-group-item text-white bg-info">
                        <label for="fechaVentasInicio">Desde</label>
                        <input type="date" class="form-control inicioAnual" id="fechaVentasInicio" name="fechaVentasInicio" placeholder="" >
                      </li>
                      <li class="list-group-item text-white bg-info">
                        <label for="fechaVentasFin">Hasta</label>
                        <input type="date" class="form-control finalAnual" id="fechaVentasFin" name="fechaVentasFin" placeholder="" >
                      </li>
                    </ul>
                    <div class="card-body">
                      <button type="submit" class="btn btn-primary" onclick="javascript:window.location.reload();">Descargar</button>
                    </div>
                  </form>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card text-white bg-info" style="width: 18rem;">
                  
                    <div class="card-body">
                      <h5 class="card-title">Pagos</h5>
                      <p class="card-text">Listado completo de los pagos registrados hasta la fecha.</p>
                    </div>
                    <form class="user" action="getPagosExcel" method="post" enctype="multipart/form-data">
                      @csrf
                    <ul class="list-group list-group-flush ">                     
                      <li class="list-group-item text-white bg-info">
                        <label for="fechaVentasInicio">Desde</label>
                        <input type="date" class="form-control inicioAnual" id="fechaPagosInicio" name="fechaPagosInicio" placeholder="" >
                      </li>
                      <li class="list-group-item text-white bg-info">
                        <label for="fechaVentasFin">Hasta</label>
                        <input type="date" class="form-control finalAnual" id="fechaPagosFin" name="fechaPagosFin" placeholder="" >
                      </li>
                    </ul>
                    <div class="card-body">
                      <button type="submit" class="btn btn-primary" onclick="javascript:window.location.reload();">Descargar</button>
                    </div>
                  </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- INFORMES POR CLIENTE --->

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Informes por Cliente</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-4">
                  <div class="card text-white bg-info" style="width: 18rem;">
                  
                    <div class="card-body">
                      <h5 class="card-title">Compras</h5>
                      <p class="card-text">Listado de las compras asociadas al cliente.</p>
                    </div>
                    <form class="user" action="getComprasClienteExcel" method="post" enctype="multipart/form-data" id="formularioDetalleCuenta">
                      @csrf
                    <ul class="list-group list-group-flush"> 
                      <li class="list-group-item text-white bg-info">
                        <label for="fechaVentasInicio">Cliente</label>
                        <input type="text" list="listClientesCompras" class="form-control" id="rutClienteCompra" name="rutClienteCompra" placeholder="Cliente"  required>
                        <!-- Lista de opciones -->
                        <datalist id="listClientesCompras" class="datalist">
                          @foreach ($clientes as $cliente)
                            <tr>
                              <option value="{{ $cliente->rutCliente }}">{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}</option>
                          @endforeach
                        </datalist>
                      </li>                    
                      <li class="list-group-item text-white bg-info">
                        <label for="fechaVentasInicio">Desde</label>
                        <input type="date" class="form-control inicioAnual" id="fechaComprasClienteInicio" name="fechaComprasClienteInicio" placeholder="" >
                      </li>
                      <li class="list-group-item text-white bg-info">
                        <label for="fechaVentasFin">Hasta</label>
                        <input type="date" class="form-control finalAnual" id="fechaComprasClienteFin" name="fechaComprasClienteFin" placeholder="" >
                      </li>
                    </ul>
                    <div class="card-body">
                      <button type="submit" class="btn btn-primary" onclick="javascript:window.location.reload();">Descargar</button>
                    </div>
                  </form>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card text-white bg-info" style="width: 18rem;">
                  
                    <div class="card-body">
                      <h5 class="card-title">Pagos</h5>
                      <p class="card-text">Listado de pagos asociados al cliente.</p>
                    </div>
                    <form class="user" action="getPagosClienteExcel" method="post" enctype="multipart/form-data" >
                      @csrf
                    <ul class="list-group list-group-flush"> 
                      <li class="list-group-item text-white bg-info">
                        <label for="fechaVentasInicio">Cliente</label>
                        <input type="text" list="listClientesPagos" class="form-control" id="rutClientePago" name="rutClientePago" placeholder="Cliente"  required>
                        <!-- Lista de opciones -->
                        <datalist id="listClientesPagos" class="datalist">
                          @foreach ($clientes as $cliente)
                            <tr>
                              <option value="{{ $cliente->rutCliente }}">{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}</option>
                          @endforeach
                        </datalist>
                      </li>                    
                      <li class="list-group-item text-white bg-info">
                        <label for="fechaVentasInicio">Desde</label>
                        <input type="date" class="form-control inicioAnual" id="fechaPagosClienteInicio" name="fechaPagosClienteInicio" placeholder="" >
                      </li>
                      <li class="list-group-item text-white bg-info">
                        <label for="fechaVentasFin">Hasta</label>
                        <input type="date" class="form-control finalAnual" id="fechaPagosClienteFin" name="fechaPagosClienteFin" placeholder="" >
                      </li>
                    </ul>
                    <div class="card-body">
                      <button type="submit" class="btn btn-primary" onclick="javascript:window.location.reload();">Descargar</button>
                    </div>
                  </form>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card text-white bg-info" style="width: 18rem;">
                  
                    <div class="card-body">
                      <h5 class="card-title">Registros Mora</h5>
                      <p class="card-text">Historial de morosidad del cliente.</p>
                    </div>
                    <form class="user" action="getMoraClienteExcel" method="post" enctype="multipart/form-data">
                      @csrf
                    <ul class="list-group list-group-flush ">
                      <li class="list-group-item text-white bg-info">
                        <label for="fechaVentasInicio">Cliente</label>
                        <input type="text" list="listClientesMora" class="form-control" id="rutClienteMora" name="rutClienteMora" placeholder="Cliente"  required>
                        <!-- Lista de opciones -->
                        <datalist id="listClientesMora" class="datalist">
                          @foreach ($clientes as $cliente)
                            <tr>
                              <option value="{{ $cliente->rutCliente }}">{{ $cliente->nombreCliente }} {{ $cliente->apellidoPatCliente }} {{ $cliente->apellidoMatCliente }}</option>
                          @endforeach
                        </datalist>
                      </li>                      
                    </ul>
                    <div class="card-body">
                      <button type="submit" class="btn btn-primary" onclick="javascript:window.location.reload();">Descargar</button>
                    </div>
                  </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          

        </div>
        <!-- /.container-fluid -->
@endsection

@section('modals')


@endsection

@section('scripts')
  @parent
  <!-- Page level plugins -->
  <script src="{{asset("assets/$theme/vendor/datatables/jquery.dataTables.min.js")}}"></script>
  <script src="{{asset("assets/$theme/vendor/datatables/dataTables.bootstrap4.min.js")}}"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset("assets/$theme/js/demo/datatables-demo.js")}}"></script>
  <script src="{{asset("js/JQueryInformes.js")}}"></script>
@endsection

