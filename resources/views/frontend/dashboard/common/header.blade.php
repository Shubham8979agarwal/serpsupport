<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <title>SerpSupport Portal</title>
      <meta
         content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
         name="viewport"
         />
      <link rel="icon" href="{{ url('dashboard_assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon"
         />
      <!-- Fonts and icons -->
      <script src="{{ url('dashboard_assets/js/plugin/webfont/webfont.min.js') }}"></script>
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
             urls: ["{{ url('dashboard_assets/css/fonts.min.css') }}"],
           },
           active: function () {
             sessionStorage.fonts = true;
           },
         });
      </script>
      <!-- CSS Files -->
      <link rel="stylesheet" href="{{ url('dashboard_assets/css/bootstrap.min.css') }}" />
      <link rel="stylesheet" href="{{ url('dashboard_assets/css/plugins.min.css') }}" />
      <link rel="stylesheet" href="{{ url('dashboard_assets/css/kaiadmin.min.css') }}" />
      <!-- CSS Just for demo purpose, don't include it in your project -->
      <link rel="stylesheet" href="{{ url('dashboard_assets/css/demo.css') }}" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
      <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" />

      <style type="text/css">
         li.nav-item.account-settings{
         position: fixed;
         bottom: 30px;
         left: 0;
         width:265px;
         }
         @media screen and (max-width: 991.5px) {
         .topbar_open .navbar-header {
         transform: translate3d(0, 0, 0) !important;
         background: #fff;
         }
         }
         @media(max-width:884px){
         .main-header{
         background: transparent!important;
         }
         }
         .changebackground{
            background-color: white!important;
            margin: 2px!important;
         }
         .changebackground span{
            color:#000!important;
         }
         .changebackground span:before{
            background: #000!important;
         }
      </style>
   </head>
   <body>
      <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
         <div class="sidebar-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
               <a href="#" class="logo" style="color:#FFF!important">
                  SerpSupport Portal
               </a>
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
         <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
               <ul class="nav nav-secondary">
                  <li class="nav-item">
                     <a href="#">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                     </a>
                  </li>
                  <?php
                     $currentUrl = url()->current();
                     $getwebsites = DB::table('websites')->where('website_uploader_email', Auth::user()->email)->get();
                     //$lastSegment = \Crypt::decrypt(request()->segment(count(request()->segments())));
                     ?>
                  @if(count($getwebsites) > 0)
                  <ul class="nav">
                     @foreach($getwebsites as $websites)
                     <?php
                        use Illuminate\Contracts\Encryption\DecryptException;
                        use Illuminate\Support\Facades\Crypt;
                        try {
                              $lastSegment = Crypt::decryptString(request()->segment(count(request()->segments())));
                           } catch (DecryptException $e) {
                              //
                           }
                        $websiteId = $websites->id;
                        $encryptedUrl = encrypt($websites->website_url);
                        $backlinkUrl = url("/backlinks/" . $encryptedUrl);
                        $outlinkUrl = url("/outlinks/" . $encryptedUrl);
                        $isActive = $lastSegment == $websites->website_url;
                        $submenuId = "submenu" . $loop->iteration;
                        ?>
                     <li class="nav-item @if($isActive) active submenu @endif" id="item{{ $websiteId }}">
                        <a data-bs-toggle="collapse" href="#{{ $submenuId }}">
                           <i class="fas fa-bars"></i>
                           <p>{{ $websites->website_url }}</p>
                           <span class="caret"></span>
                        </a>
                        <div class="collapse @if($isActive) show @endif" id="{{ $submenuId }}">
                           <ul class="nav nav-collapse">
                              <li class="@if(strpos($currentUrl,'backlinks') && ($isActive)) changebackground @endif">
                                 <a href="{{ $backlinkUrl }}">
                                 <span class="sub-item">Backlinks</span>
                                 </a>
                              </li>
                              <li class="@if(strpos($currentUrl,'outlinks') && ($isActive)) changebackground @endif">
                                 <a href="{{ $outlinkUrl }}">
                                 <span class="sub-item">Outlinks</span>
                                 </a>
                              </li>
                           </ul>
                        </div>
                     </li>
                     @endforeach
                  </ul>
                  @endif
                  <li class="nav-item account-settings">
                     <a href="{{ route('account-settings') }}">
                        <i class="fas fa-gear"></i>
                        <p>Account Settings</p>
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
         <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
            >
            <div class="container-fluid">
               <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                  <li class="nav-item topbar-user dropdown hidden-caret">
                     <a
                        class="dropdown-toggle profile-pic"
                        data-bs-toggle="dropdown"
                        href="#"
                        aria-expanded="false"
                        >
                        <div class="avatar-sm">
                           <img
                              src="{{ url('dashboard_assets/img/profile.jpg') }}"
                              alt="..."
                              class="avatar-img rounded-circle"
                              />
                        </div>
                        <span class="profile-username">
                        <span class="op-7">Hi,</span>
                        <span class="fw-bold">{{ $data->email }} </span>
                        </span>
                     </a>
                     <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                           <li>
                              <div class="user-box">
                                 <div class="avatar-lg">
                                    <img
                                       src="{{ url('dashboard_assets/img/profile.jpg') }}"
                                       alt="image profile"
                                       class="avatar-img rounded"
                                       />
                                 </div>
                                 <div class="u-text">
                                    <h4>{{ $data->first_name }} {{ $data->last_name }}</h4>
                                    <p class="text-muted">{{ $data->email }}</p>
                                 </div>
                              </div>
                           </li>
                           <li>
                              <div class="dropdown-divider"></div>
                              <!-- <a class="dropdown-item" href="#">My Profile</a>
                                 <div class="dropdown-divider"></div> -->
                              <!-- <a class="dropdown-item" href="#">My Balance</a> -->
                              <!-- <a class="dropdown-item" href="#">Inbox</a>
                                 <div class="dropdown-divider"></div> -->
                              <a class="dropdown-item" href="{{ route('account-settings') }}">Account Setting</a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="{{ route('signout') }}">Signout</a>
                           </li>
                        </div>
                     </ul>
                  </li>
               </ul>
            </div>
         </nav>
         <!-- End Navbar -->
      </div>