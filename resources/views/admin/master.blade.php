<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>sofra | @yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url(route('home'))}}" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url(route('contact.index'))}}" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }} <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url(route('home'))}}" class="brand-link">
      <img src="{{asset('dist/img/AdminLTELogo.png')}}"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Sofra Adminstration</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Israa Mohamed</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                          Restaurants
                      <i class="right fas fa-angle-left"></i>
                  </p>
              </a>
            
            <ul class="nav nav-treeview">
             @if(Laratrust::can('show_restaurants'))
              <li class="nav-item">
                <a href="{{url(route('restaurant.index'))}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Restaurants</p>
                </a>
              </li>
              @endif

              @if(Laratrust::can('show_categories'))
                <li class="nav-item">
                      <a href="{{url(route('category.index'))}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Categories</p>
                      </a>
                </li>
              @endif
            </ul>
          </li>
          @if(Laratrust::can('show_clients'))
            <li class="nav-item">
              <a href="{{url(route('client.index'))}}" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                Clients
                </p>
              </a>
            </li>
          @endif
          @if(Laratrust::can('show_paymentMethods'))
            <li class="nav-item">
                <a href="{{url(route('paymentMethod.index'))}}" class="nav-link">
                  <i class="nav-icon fas fa-dollar-sign"></i>
                  <p>
                    Payment Methods
                  </p>
                </a>
            </li>
          @endif

          @if(Laratrust::can('show_cities'))
            <li class="nav-item">
                <a href="{{url(route('city.index'))}}" class="nav-link">
                  <i class="nav-icon fas fa-archway"></i>
                  <p>
                    Cities
                  </p>
                </a>
            </li>
          @endif

          @if(Laratrust::can('show_regions'))
            <li class="nav-item">
                <a href="{{url(route('region.index'))}}" class="nav-link">
                  <i class="nav-icon fas fa-project-diagram"></i>
                  <p>
                    Regions
                  </p>
                </a>
            </li>
          @endif

          @if(Laratrust::can('show_contacts'))
            <li class="nav-item">
              <a href="{{url(route('contact.index'))}}" class="nav-link">
                <i class="nav-icon fas fa-address-book"></i>
                <p>
                  Contacts
                </p>
              </a>
            </li>
          @endif

          @if(Laratrust::can('show_users'))
            <li class="nav-item">
              <a href="{{url(route('user.index'))}}" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                Users
                </p>
              </a>
            </li>
          @endif

          @if(Laratrust::can('show_roles'))
          <li class="nav-item">
            <a href="{{url(route('role.index'))}}" class="nav-link">
              <i class="nav-icon fas fa-user-tag"></i>
              <p>
                Roles
              </p>
            </a>
          </li>
          @endif

          @if(Laratrust::can('show_payments'))
          <li class="nav-item">
            <a href="{{url(route('payment.index'))}}" class="nav-link">
              <i class="nav-icon fas fa-dollar-sign"></i>
              <p>
                Payments
              </p>
            </a>
          </li>
          @endif

          
          @if(Laratrust::can('edit_settings'))
          <li class="nav-item">
            <a href="{{url(route('setting.edit'))}}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Settings
              </p>
            </a>
          </li>
          @endif
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            @yield('header')
          </div><!-- /.container-fluid -->
        </section>
    
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
        <!-- /.content -->
    
        <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
          <i class="fas fa-chevron-up"></i>
        </a>
      </div>
      <!-- /.content-wrapper -->
    
      <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
          <b>Version</b> 3.0.0
        </div>
        <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
        reserved.
      </footer>
    
      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('dist/js/demo.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{asset('js/custom.js')}}"></script>
    @stack('script')
    </body>
</html>
    
