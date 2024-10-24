<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
      <title>SERPsupport</title>
      <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
         name="viewport"/>
      <link rel="icon" href="{{ url('dashboard_assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon"/>
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
      <link rel="stylesheet" href="{{ url('dashboard_assets/css/bootstrap.min.css') }}"/>
      <link rel="stylesheet" href="{{ url('assets/css/accordian.css') }}"/>
      <link rel="stylesheet" href="{{ url('dashboard_assets/css/plugins.min.css') }}"/>
      <link rel="stylesheet" href="{{ url('dashboard_assets/css/kaiadmin.min.css') }}"/>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"/>
      <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" />
      <style type="text/css">
         ul.nav {
            position: relative; /* Ensure parent respects child positioning */
         }
         li.nav-item.faqs {
             position: fixed; /* Keep fixed positioning */
             bottom: 50px; /* Adjust as needed */
             left: 0; /* Stick to the left */
             width: 265px; /* Adjust width */
             z-index: 0; /* Lower the z-index to avoid overlap */
             padding-top: 10px;
         }
         li.nav-item.account-settings {
             position: fixed; /* Keep fixed positioning */
             bottom: 10px; /* Adjust distance from the bottom */
             left: 0; /* Stick to the left */
             width: 265px; /* Adjust width */
             z-index: 0; /* Lower z-index to avoid overlap */
             padding-top: 10px;
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
         .messages-notif-box .notif-center a .notif-content, .notif-box .notif-center a .notif-content{
         padding: 10px 15px 10px 15px!important; 
         }
         .notif-content {
         padding: 5px!important;
         }
         .activeStatus{
         z-index: 9!important;
         }
         .block {
         font-size: 12px!important;
         }
         form#submitlinkdetails .form-group label{
         white-space: normal!important;
         }
         /* Hide by default (for larger screens like desktop) */
         .logo-header .nav-toggle {
         display: none!important;
         }
         /* Show on mobile (small screens) */
         @media (max-width: 884px){
         .logo-header .nav-toggle {
         display: block!important; /* Makes it visible on mobile screens */
         }
         }
         .sidebar .nav-collapse li a .sub-item:before, .sidebar[data-background-color=white] .nav-collapse li a .sub-item:before{
            display: none!important;
         }
         .sidebar .nav-collapse li a .sub-item, .sidebar[data-background-color=white] .nav-collapse li a .sub-item{
            margin-left: 0!important;
         }
         .sidebar .nav-collapse, .sidebar[data-background-color=white] .nav-collapse{
            padding-top: 0!important;
            padding-bottom: 0!important;
         }
         .sidebar .nav-collapse li a, .sidebar[data-background-color=white] .nav-collapse li a{
            padding: 5px 10px 10px 32px !important;
         }
         span.sub-item {
            margin-top: 5px!important;
         }
         .message-card.mc-sender .message a.hiw{
            color: #FFF;
            text-decoration: underline !important;
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
                  <?php
                     $currentUrl = url()->current();
                     $getwebsites = DB::table('websites')->where('website_uploader_email', Auth::user()->email)->get();
                     $ls = request()->segment(count(request()->segments()));
                     $lastSegment = decryptData($ls);
                     ?>
                  <li class="nav-item @if(Route::currentRouteName() == 'backlinks-submission-details' || Route::currentRouteName() == 'outlinks-submission-details') active submenu @endif">
                     <a data-bs-toggle="collapse" href="#dashboard">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                        <i class="fas fa-caret-up"></i>
                     </a>
                     <div class="collapse show" id="dashboard">
                        <!-- 'show' class to make it expanded by default -->
                        <ul class="nav nav-collapse">
                           <li class="@if(strpos($currentUrl,'backlinks-submission-details')) changebackground @endif">
                              <a href="{{ route('backlinks-submission-details') }}">
                              <span class="sub-item">Incoming Links</span>
                              </a>
                           </li>
                           <li class="@if(strpos($currentUrl,'outlinks-submission-details')) changebackground @endif">
                              <a href="{{ route('outlinks-submission-details') }}">
                              <span class="sub-item">Outgoing Links</span>
                              </a>
                           </li>
                        </ul>
                     </div>
                  </li>
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
                            ->where('website_url', $websites->website_url)->where('chat_status',"=",NULL)->count();
                            
                        $unread_backlink_count = DB::table('backlinks')
                            ->where('forwhich_user_url', $websites->website_url)->where('chat_status',"=",NULL)->count();
                        
                        $backlink_count = $unread_outlink_count + $unread_backlink_count;
                        
                        // Fetch the counts of unseen outlinks
                        $unread_outlink_count_oc = DB::table('outlinks')
                            ->where('forwhich_user_url', $websites->website_url)->where('chat_status',"=",NULL)->count();
                          
                        $unread_backlink_count_bc = DB::table('backlinks')
                            ->where('website_url', $websites->website_url)->where('chat_status',"=",NULL)->count();
                        $outlink_count =  $unread_outlink_count_oc + $unread_backlink_count_bc;
                        // If backlink_count is greater than 1, set it to 1
                        if ($backlink_count > 1) {
                            $backlink_count = 1;
                        }

                        // If outlink_count is greater than 1, set it to 1
                        if ($outlink_count > 1) {
                            $outlink_count = 1;
                        }
                        ?>
                     <li class="nav-item @if($isActive) active submenu @endif" id="item{{ $websiteId }}">
                        <a data-bs-toggle="collapse" href="#{{ $submenuId }}">
                           <i class="fas fa-bars"></i>
                           <p>{{ $websites->website_url }}</p>
                           <!-- <span class="caret"> -->
                           <i class="fas fa-caret-up"></i> <!-- Always show caret up since it is open by default -->
                           <!-- </span> -->
                        </a>
                        <div class="collapse show" id="{{ $submenuId }}">
                           <!-- 'show' class to keep it expanded -->
                           <ul class="nav nav-collapse">
                              <li class="@if(strpos($currentUrl,'backlinks') && ($isActive)) changebackground @endif">
                                 <a href="{{ $backlinkUrl }}">
                                 <span class="sub-item">
                                 Incoming Link Connection(s)
                                 <span class='flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20'>
                                 {{ $backlink_count }}
                                 </span>
                                 </span>
                                 </a>
                              </li>
                              <li class="@if(strpos($currentUrl,'outlinks') && ($isActive)) changebackground @endif">
                                 <a href="{{ $outlinkUrl }}">
                                 <span class="sub-item">
                                 Outgoing Link Connection(s)
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
                  <li class="nav-item faqs @if(Route::currentRouteName() == 'faqs') active @endif">
                     <a href="{{ route('faqs') }}">
                        <i class="fas fa-question-circle"></i>
                        <p>FAQ(s)</p>
                     </a>
                  </li>
                  <li class="nav-item account-settings @if(Route::currentRouteName() == 'account-settings') active @endif">
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
         </div>
         <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
               <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                  <li class="nav-item topbar-icon dropdown hidden-caret">
                     <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-envelope"></i>
                        <?php
                           // Get the authenticated user's ID
                           $userId = Auth::id();
                           
                           // Count the number of unread messages (seen = 0) where the logged-in user is the recipient (to_id)
                           $unreadCount = DB::table('ch_messages')
                              ->where('to_id', $userId)
                              ->where('seen', 0)
                              ->count();
                        ?>
                        @if($unreadCount > 0)
                        <!-- Display the number of unread messages -->
                        <span class="notification">{{ $unreadCount }}</span>
                        @endif
                     </a>
                     <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="messageDropdown">
                        <li>
                           <div class="dropdown-title">
                              You have {{ $unreadCount }} new message{{ $unreadCount != 1 ? 's' : '' }}
                           </div>
                        </li>
                        <li>
                           <div class="notif-scroll scrollbar-outer">
                              <div class="notif-center">
                                 <?php
                                    // Fetch the unseen messages for the user, limiting the result to 5
                                    $unseenMessages = DB::table('ch_messages')
                                       ->where('to_id', $userId)
                                       ->where('seen', 0)
                                       ->orderBy('created_at', 'desc')
                                       ->limit(5)  // Limit to 5 notifications
                                       ->get();
                                 ?>
                                 @forelse($unseenMessages as $message)
                                 <?php
                                    // Get the sender's name (assuming there is a 'users' table)
                                    $senderEmail = DB::table('users')
                                       ->where('id', $message->from_id)
                                       ->pluck('email')
                                       ->first();
                                    
                                    $websiteName = DB::table('websites')
                                       ->where('website_uploader_email', $senderEmail)
                                       ->pluck('website_url')
                                       ->first();   
                                    ?>
                                 <div class="col-md-12 d-flex align-items-center">
                                    <a href="/chat/{{$message->from_id}}">
                                       <div class="notif-content">
                                          <span class="block">
                                          {{$websiteName}} sent you a message
                                          </span>
                                          <span class="time">
                                          {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                                          </span>
                                       </div>
                                    </a>
                                    <a href="/seen-message/{{encrypt($message->id)}}" class="ms-2">
                                       <!-- Added ms-2 for margin start -->
                                       <span class='flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20'>
                                       Mark seen
                                       </span>
                                    </a>
                                 </div>
                                 @empty
                                 <div class="notif-content">
                                    <span class="block">No new messages</span>
                                 </div>
                                 @endforelse
                              </div>
                           </div>
                        </li>
                        <!-- <li class="dropdown-footer">
                           <a href="#">View All Notifications</a>
                           </li> -->
                     </ul>
                  </li>
                  <?php
                     $notifications = [];
                     // Check if websites data is available
                     $getwebsites = DB::table('websites')->where('website_uploader_email', Auth::user()->email)->get(); 
                     $backlink_count = 0;
                     $outlink_count = 0;
                     $notifications_count = 0;
                     
                     // Loop through the websites to calculate backlink and outlink counts
                     if (count($getwebsites) > 0) {
                         foreach ($getwebsites as $websites) {
                             $unread_outlink_count = DB::table('outlinks')
                                 ->where('website_url', $websites->website_url)
                                 ->where('chat_status', '=', NULL)
                                 ->count();
                                 
                             $unread_backlink_count = DB::table('backlinks')
                                 ->where('forwhich_user_url', $websites->website_url)
                                 ->where('chat_status', '=', NULL)
                                 ->count();
                                 
                             $backlink_count += $unread_outlink_count + $unread_backlink_count;
                     
                             $unread_outlink_count_oc = DB::table('outlinks')
                                 ->where('forwhich_user_url', $websites->website_url)
                                 ->where('chat_status', '=', NULL)
                                 ->count();
                                 
                             $unread_backlink_count_bc = DB::table('backlinks')
                                 ->where('website_url', $websites->website_url)
                                 ->where('chat_status', '=', NULL)
                                 ->count();
                     
                          // Get the domain from the website URL
                          $domain = parse_url($websites->website_url, PHP_URL_HOST) ?: $websites->website_url;
                     
                          // Get the authenticated user's ID
                          $currentUserId = Auth::user()->id;
                     
                          // Count the notifications based on the criteria used for fetching them
                          $notifications_count = DB::table('notifications')
                              ->where(function ($query) use ($domain) {
                                  $query->where('forwhich_user_url', 'LIKE', "$domain%")
                                        ->orWhere('website_url', 'LIKE', "$domain%");
                              })
                              ->where('seen', false)
                              ->where('from_user_id', '!=', $currentUserId)  // Exclude notifications from the logged-in user
                              ->where('to_user_id', '!=', $currentUserId)    // Exclude notifications to the logged-in user
                              ->count(); 
                     
                          // Now, fetch the notifications
                          $notifications = array_merge($notifications, DB::table('notifications')
                              ->where(function ($query) use ($websites) {
                                  $domain = parse_url($websites->website_url, PHP_URL_HOST) ?: $websites->website_url;
                     
                                  $query->where('forwhich_user_url', 'LIKE', "$domain%")
                                        ->orWhere('website_url', 'LIKE', "$domain%");
                              })
                              ->where('seen', false)
                              ->where('from_user_id', '!=', $currentUserId)  // Exclude notifications from the logged-in user
                              ->where('to_user_id', '!=', $currentUserId)    // Exclude notifications to the logged-in user
                              ->orderBy('created_at', 'desc')
                              ->get()
                              ->toArray());
                     
                             $outlink_count += $unread_outlink_count_oc + $unread_backlink_count_bc;

                             // If backlink_count is greater than 1, set it to 1
                        if ($backlink_count > 1) {
                            $backlink_count = 1;
                        }

                        // If outlink_count is greater than 1, set it to 1
                        if ($outlink_count > 1) {
                            $outlink_count = 1;
                        }
                     }
                     }
                     ?>
                  <li class="nav-item topbar-icon dropdown hidden-caret">
                     <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="fa fa-bell"></i>
                     @if($backlink_count > 0 || $outlink_count > 0 || $notifications_count>0)
                     <span class="notification">{{ $backlink_count + $outlink_count + $notifications_count }}</span>
                     @endif
                     </a>
                     <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                           <div class="dropdown-title">
                              Incoming & Outgoing Link Connection Notifications
                           </div>
                        </li>
                        <li>
                           <div class="notif-scroll scrollbar-outer">
                              <div class="notif-center">
                                 <div class="notif-content">
                                    <span class="block">Received {{ $backlink_count }} Incoming link connection(s)</span>
                                 </div>
                                 <div class="notif-content">
                                    <span class="block">Received {{ $outlink_count }} Outgoing link connection(s)</span>
                                 </div>
                                 @if(count($notifications) > 0)
                                 @foreach($notifications as $notification)
                                 @if($notification->from_user_id!=Auth::user()->id && $notification->to_user_id!=Auth::user()->id)
                                 <div class="notif-content">
                                    <span class="block">
                                    <a href="/seen-notification/{{encrypt($notification->id)}}">{{ $notification->connnection_text }}<span class='flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20'>
                                    Mark seen
                                    </span></a>
                                    </span>
                                 </div>
                                 @endif
                                 @endforeach
                                 @endif
                              </div>
                           </div>
                        </li>
                     </ul>
                  </li>
                  <li class="nav-item topbar-user dropdown hidden-caret">
                     <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                           <img src="{{ url('admin_assets/dashboard_assets/img/profile.png') }}" alt="..." class="avatar-img rounded-circle"/>
                        </div>
                        <span class="fw-bold">&nbsp;{{ Auth::user()->email }} <i class="fa fa-caret-down"></i></span>
                        </span>
                     </a>
                     <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                           <li>
                              <a class="dropdown-item" href="{{ route('account-settings') }}">Account Setting</a>
                              <div class="dropdown-divider"></div>
                              <?php 
                               $userid = Auth::user()->id;
                               $getsubscription_id = DB::table('subscriptions')->where('user_id',$userid)->value('stripe_subscription_id');
                              ?>
                              <a class="dropdown-item" href="{{ route('show-subscription',['subscription_id'=>$getsubscription_id]) }}">Subscription details</a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="{{ route('signout') }}">Signout</a>
                           </li>
                        </div>
                     </ul>
                  </li>
               </ul>
            </div>
         </nav>
      </div>