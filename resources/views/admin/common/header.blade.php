<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <title>SERPsupport Admin</title>
      <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
         name="viewport"/>
      <link rel="icon" href="{{ url('admin_assets/dashboard_assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon"/>
      <!-- Fonts and icons -->
      <script src="{{ url('admin_assets/dashboard_assets/js/plugin/webfont/webfont.min.js') }}"></script>
      <script>
         WebFont.load({
           google: { families: ["Public Sans:300,400,500,600,700"] },
           custom: {
             families: [
               "Font Awesome 5 Solid",
               "Font Awesome 5 Regular",
               "Font Awesome 5 Brands",
               "simple-line-icons",
             ],
             urls: ["{{ url('admin_assets/dashboard_assets/css/fonts.min.css') }}"],
           },
           active: function () {
             sessionStorage.fonts = true;
           },
         });
      </script>
      <!-- CSS Files -->
      <link rel="stylesheet" href="{{ url('admin_assets/dashboard_assets/css/bootstrap.min.css') }}" />
      <link rel="stylesheet" href="{{ url('admin_assets/dashboard_assets/css/plugins.min.css') }}" />
      <link rel="stylesheet" href="{{ url('admin_assets/dashboard_assets/css/kaiadmin.min.css') }}" />
      <!-- CSS Just for demo purpose, don't include it in your project -->
      <link rel="stylesheet" href="{{ url('admin_assets/dashboard_assets/css/demo.css') }}" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
      <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css"/>
      <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css"/>
   </head>
   <body>
      <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
         <div class="sidebar-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
               <a href="#" class="logo" style="color:#FFF!important">
                SERPsupport Admin
               </a>
            </div>
            <!-- End Logo Header -->
         </div>
         <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
               <ul class="nav nav-secondary">
                  <li class="nav-item @if(Route::currentRouteName() == 'admin-dashboard') active @endif">
                     <a href="{{ route('admin-dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Admin Dashboard</p>
                     </a>
                  </li>
                  <li class="nav-item @if(Route::currentRouteName() == 'all-users') active @endif">
                     <a href="{{ route('all-users') }}">
                        <i class="fas fa-users"></i>
                        <p>All User(s)</p>
                     </a>
                  </li>
                  <li class="nav-item @if(Route::currentRouteName() == 'all-websites') active @endif">
                     <a href="{{ route('all-websites') }}">
                        <i class="fas fa-globe"></i>
                        <p>All Website(s)</p>
                     </a>
                  </li>
                  <li class="nav-item @if(Route::currentRouteName() == 'connections') active @endif">
                     <a href="{{ route('connections') }}">
                        <i class="fa fa-link"></i>
                        <p>Connection(s)</p>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
      <!-- End Sidebar -->
      <div class="main-panel">
      <div class="main-header">
         <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
               <div class="nav-toggle">
                  <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                  </button>
                  <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                  </button>
               </div>
               <button class="topbar-toggler more">
               <i class="gg-more-vertical-alt"></i>
               </button>
            </div>
            <!-- End Logo Header -->
         </div>
         <!-- Navbar Header -->
         <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
               <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                  <li class="nav-item topbar-user dropdown hidden-caret">
                     <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                     <span class="fw-bold">{{ auth('admin')->user()->email }} <i class="fa fa-caret-down"></i></span>
                     </span>
                     </a>
                     <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                           <li>
                              <a class="dropdown-item" href="{{ route('admin-signout') }}">Signout</a>
                           </li>
                        </div>
                     </ul>
                  </li>
               </ul>
            </div>
         </nav>
      </div>