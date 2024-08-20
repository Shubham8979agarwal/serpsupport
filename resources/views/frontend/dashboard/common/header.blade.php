<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <title>SERPsupport</title>
      <meta
         content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
         name="viewport"
         />
      <link rel="icon" href="{{ url('dashboard_dashboard_assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon"
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
               SERPsupport
               </a>
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
                     $ls = request()->segment(count(request()->segments()));
                     $lastSegment = decryptData($ls);
                     ?>
                  @if(count($getwebsites) > 0)
                  <ul class="nav">
                     @foreach($getwebsites as $websites)
                     <?php
                        $websiteId = $websites->id;
                        $encryptedUrl = encrypt($websites->website_url);
                        $backlinkUrl = url("/backlinks/" . $encryptedUrl);
                        $outlinkUrl = url("/outlinks/" . $encryptedUrl);
                        $isActive = $lastSegment == $websites->website_url;
                        $submenuId = "submenu" . $loop->iteration;
                        
                        // Fetch the counts of unseen backlinks
                        $unread_outlink_count = DB::table('outlinks')
                            ->where('website_url', $websites->website_url)
                            ->where('seenby_ol_user', 0)
                            ->count();
                        
                        $unread_backlink_count = DB::table('backlinks')
                            ->where('forwhich_user_url', $websites->website_url)
                            ->where('seenby_ol_user', 0)
                            ->count();
                        
                        $backlink_count = $unread_outlink_count + $unread_backlink_count  ;
                        
                        // Fetch the counts of unseen outlinks
                        $unread_outlink_count_oc = DB::table('outlinks')
                            ->where('forwhich_user_url', $websites->website_url)
                            ->where('seenby_ol_user', 0)
                            ->count();
                        $unread_backlink_count_bc = DB::table('backlinks')
                            ->where('website_url', $websites->website_url)
                            ->where('seenby_ol_user', 0)
                            ->count();
                        $outlink_count =  $unread_outlink_count_oc + $unread_backlink_count_bc;
                        
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
                                 <span class="sub-item">
                                 Backlinks
                                 <span class='flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20'>
                                 {{ $backlink_count }} 
                                 </span>
                                 </span>
                                 </a>
                              </li>
                              <li class="@if(strpos($currentUrl,'outlinks') && ($isActive)) changebackground @endif">
                                 <a href="{{ $outlinkUrl }}">
                                 <span class="sub-item">
                                 Outlinks
                                 <span class='flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20'>
                                 {{ $outlink_count }} 
                                 </span>
                                 </span>
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
                  <li class="nav-item topbar-icon dropdown hidden-caret">
                     <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        id="notifDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        >
                     <i class="fa fa-bell"></i>
                     <span class="notification">1</span>
                     </a>
                     <ul
                        class="dropdown-menu notif-box animated fadeIn"
                        aria-labelledby="notifDropdown"
                        >
                        <li>
                           <div class="dropdown-title">
                              You have new notifications
                           </div>
                        </li>
                        <li>
                           <div class="notif-scroll scrollbar-outer">
                              <div class="notif-center">
                                 <a href="#">
                                    <div class="notif-img">
                                       <img
                                          src="{{ url('dashboard_assets/img/profile2.jpg') }}"
                                          alt="Img Profile"
                                          />
                                    </div>
                                    <div class="notif-content">
                                       <span class="block">
                                       Reza send messages to you
                                       </span>
                                       <span class="time">12 minutes ago</span>
                                    </div>
                                 </a>
                              </div>
                           </div>
                        </li>
                        <li>
                           <a class="see-all" href="javascript:void(0);"
                              >See all notifications<i class="fa fa-angle-right"></i>
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li class="nav-item topbar-user dropdown hidden-caret">
                     <a
                        class="dropdown-toggle profile-pic"
                        data-bs-toggle="dropdown"
                        href="#"
                        aria-expanded="false"
                        >
                     <span class="fw-bold">{{ Auth::user()->email }} <i class="fa fa-caret-down"></i> </span>
                     </span>
                     </a>
                     <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                           <li>
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