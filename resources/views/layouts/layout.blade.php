<!DOCTYPE html>
<html lang="en">

<head>
  <!--Custom Favicon --->
  <link rel="apple-touch-icon" sizes="114x114" href="{{asset("favicon/apple-touch-icon.png")}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{asset("favicon/favicon-32x32.png")}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset("favicon/favicon-16x16.png")}}">
  <link rel="manifest" href="{{asset("favicon/site.webmanifest")}}">
  <link rel="mask-icon" href="{{asset("favicon/safari-pinned-tab.svg")}}" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title> @yield('title') - Gato Negro </title>

  <!-- Custom fonts for this template-->
  <link href="{{asset("assets/$theme/vendor/fontawesome-free/css/all.min.css")}}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="{{asset("assets/$theme/css/sb-admin-2.min.css")}}" rel="stylesheet">

</head>
<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    @section('sidebar')
    <ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{URL('/')}}">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-cat"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Créditos Gato Negro</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Inicio -->
      <li class="nav-item {{ Request::is('/')  ? 'active' : null }}">
        <a class="nav-link" href="{{URL('/')}}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Inicio</span>
        </a>
      </li>

      <!-- Nav Item - Ventas -->
      <li class="nav-item {{ Request::is('ventas')  ? 'active' : null }}">
        <a class="nav-link collapsed" href="{{URL('ventas')}}">
          <i class="fas fa-fw fa-dollar-sign"></i>
          <span>Ventas</span>
        </a>
      </li>

      <!-- Nav Item - Clientes -->
      <li class="nav-item {{ Request::is('clientes') || Request::is('adicionales')  ? 'active' : null }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClientes" aria-expanded="true" aria-controls="collapseClientes">
          <i class="fas fa-fw fa-user"></i>
          <span>Clientes</span>
        </a>
        <div id="collapseClientes" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item {{ Request::is('clientes')  ? 'active' : null }}" href="{{URL('/clientes')}}">Titulares</a>
            <a class="collapse-item {{ Request::is('adicionales')  ? 'active' : null }}" href="{{URL('/adicionales')}}">Adicionales</a>
          </div>
        </div>
      </li>


      <!-- Nav Item - Cuentas -->
      <li class="nav-item {{ Request::is('cuentasTitulares') || Request::is('cuentasAdicionales')  ? 'active' : null }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCuentas" aria-expanded="true" aria-controls="collapseCuentas">
          <i class="fas fa-fw fa-credit-card"></i>
          <span>Cuentas</span>
        </a>
        <div id="collapseCuentas" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item {{ Request::is('cuentasTitulares')  ? 'active' : null }}" href="{{URL('/cuentasTitulares')}}"> Por Titular</a>
            <a class="collapse-item {{ Request::is('cuentasAdicionales')  ? 'active' : null }}" href="{{URL('/cuentasAdicionales')}}">Por Adicional</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Informes -->
      <li class="nav-item {{ Request::is('informes')  ? 'active' : null }}" >
        <a class="nav-link" href="{{URL('/informes')}}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Informes </span></a>
      </li>

      <!-- Nav Item - Vendedores -->
      <li class="nav-item {{ Request::is('vendedores')  ? 'active' : null }}">
        <a class="nav-link"  href="{{URL('/vendedores')}}">
          <i class="fas fa-fw fa-comment-dollar"></i>
          <span>Vendedores</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    @show
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Barra superior -->
        <nav class="h-100 navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        </nav>
        <!-- End of Barra Superior -->
        @yield('content')
      </div>
      <!-- End of Main Content -->
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Powered by @bassoldier</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Modals -->
  @yield('modals')

  @section('scripts')
  <!-- Bootstrap core JavaScript-->
  <script src="{{asset("assets/$theme/vendor/jquery/jquery.min.js")}}"></script>
  <script src="{{asset("assets/$theme/vendor/bootstrap/js/bootstrap.bundle.min.js")}}"></script>

  <!--Añadido por Diego Sanhueza -->
  <script src="{{asset("js/JQueryBootstrap.js")}}"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{asset("assets/$theme/vendor/jquery-easing/jquery.easing.min.js")}}"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{asset("assets/$theme/js/sb-admin-2.min.js")}}"></script>
  <script src="{{asset("js/funcionesJS.js")}}"></script>
  <script src="{{asset("js/evitarDobleEnvioFormularios.js")}}"></script>

  <!-- Datepicker Files -->
  <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">
  <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker.standalone.css')}}">
  <script src="{{asset('datePicker/js/bootstrap-datepicker.js')}}"></script>
  
  <!-- Languaje (DatePicker)-->
  <script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script>

  @show

</body>
</html>