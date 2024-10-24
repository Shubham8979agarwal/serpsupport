@include('frontend.dashboard.common.header')

<?php 
  $currentUrl = url()->current();
  $ls = request()->segment(count(request()->segments()));
  $lastSegment = decryptData($ls);
?>

@if(count($backlink_data) > 0)
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
         <div>
            <h3 class="fw-bold mb-3">{{ $lastSegment }}</h3>
            @if (session('message_acceptedby_to_backlink_connection'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <i class="fa fa-check"></i> {{ session('message_acceptedby_to_backlink_connection') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session('message_acceptedby_from_backlink_connection'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <i class="fa fa-check"></i> {{ session('message_acceptedby_from_backlink_connection') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <i class="fa fa-check"></i> {{ session('message') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session('reject_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <i class="fa fa-check"></i> {{ session('reject_message') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card card-round">
               <div class="card-header">
                  <div class="card-head-row card-tools-still-right">
                     <div class="card-title">Incoming Link Connection(s)
                        <p>These are the links you receive to your website</p>
                     </div>
                  </div>
               </div>
               <div class="card-body p-4">
                  <table id="websites" class="table" style="width: 100%;">
                     <thead>
                        <tr>
                           <th>Website</th>
                           <th>Niche</th>
                           <th>Description</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php $i = 0; $showNextRow = true; ?>
                        @foreach ($backlink_data as $mywebsite)
                           @if($showNextRow && $mywebsite->chat_status !== 'closed')
                              <tr>
                                 <td>
                                    @if($mywebsite->website_url == $lastSegment)
                                       {{ $mywebsite->forwhich_user_url }}
                                    @else
                                       <?php 
                                       $getid = DB::table('websites')->where('website_url', $mywebsite->website_url)->select('user_id')->pluck('user_id')->first();
                                       ?>
                                       {{ $mywebsite->website_url }}
                                    @endif
                                 </td>
                                 <td>
                                    @if($mywebsite->website_url == $lastSegment)
                                       <?php 
                                       $getniche = DB::table('websites')->where('website_url', $mywebsite->forwhich_user_url)->select('website_niche')->pluck('website_niche')->first();
                                       ?>
                                       {{ $getniche }}
                                    @else
                                       <?php 
                                       $getniche = DB::table('websites')->where('website_url', $mywebsite->website_url)->select('website_niche')->pluck('website_niche')->first();
                                       ?>
                                       {{ $getniche }}
                                    @endif
                                 </td>
                                 <td>
                                    @if($mywebsite->website_url == $lastSegment)
                                       <?php 
                                       $get_website_description = DB::table('websites')->where('website_url', $mywebsite->forwhich_user_url)->select('website_description')->pluck('website_description')->first();
                                       ?>
                                       <p> 
                                          <a data-bs-toggle="collapse" href="#collapseExample<?php $i = $i + 1; echo $i; ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $i; ?>">
                                             Show/Hide
                                          </a>
                                       </p>
                                       <div class="collapse" id="collapseExample<?php echo $i; ?>">
                                          <div class="card card-body">
                                             {{ $get_website_description }}
                                          </div>
                                       </div>
                                    @else
                                       <?php 
                                       $get_website_description = DB::table('websites')->where('website_url', $mywebsite->website_url)->select('website_description')->pluck('website_description')->first();
                                       ?>
                                       <p> 
                                          <a data-bs-toggle="collapse" href="#collapseExample<?php $i = $i + 1; echo $i; ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $i; ?>">
                                             Show/Hide
                                          </a>
                                       </p>
                                       <div class="collapse" id="collapseExample<?php echo $i; ?>">
                                          <div class="card card-body">
                                             {{ $get_website_description }}
                                          </div>
                                       </div>
                                    @endif
                                 </td>
                                 <td>
                                    @if($mywebsite->status == "" || ($mywebsite->acceptedby_from == "" && $mywebsite->status == "pending")) 
                                       <a onclick="return confirm('Are you sure?')" href="/acceptedby-from-backlink-connection/{{ encrypt($mywebsite->id) }}/{{ encrypt($mywebsite->forwhich_user_url) }}/{{ encrypt($mywebsite->website_url) }}" class="btn btn-success mb-2">Accept</a>&nbsp;
                                       <a onclick="return confirm('Are you sure?')" href="/reject/{{ encrypt($mywebsite->forwhich_user_url) }}/{{ encrypt($mywebsite->website_url) }}" class="btn btn-danger mb-2">Reject</a>
                                    @elseif($mywebsite->status == "pending")
                                       <a href="#" class="btn btn-warning">Waiting for Approval</a>
                                    @elseif($mywebsite->status == "accepted")
                                       @if($mywebsite->website_url == $lastSegment)
                                          <?php 
                                          $getid = DB::table('websites')->where('website_url', $mywebsite->forwhich_user_url)->select('user_id')->pluck('user_id')->first();
                                          ?>
                                          <a href="{{ route('chat', ['id' => $getid]) }}" class="btn btn-success">Go to chat</a>
                                       @else
                                          <?php 
                                          $getid = DB::table('websites')->where('website_url', $mywebsite->website_url)->select('user_id')->pluck('user_id')->first();
                                          ?>
                                          <a href="{{ route('chat', ['id' => $getid]) }}" class="btn btn-success">Go to chat</a>
                                       @endif
                                    @elseif($mywebsite->status == "rejected")
                                       <a href="#" class="btn btn-danger">Rejected</a>
                                    @endif
                                 </td>
                              </tr>
                              <?php $showNextRow = false; ?>
                           @elseif($mywebsite->chat_status == 'closed')
                              <?php $showNextRow = true; ?>
                           @endif
                        @endforeach
                     </tbody>
                     <tfoot>
                        <tr>
                           <th>Website</th>
                           <th>Niche</th>
                           <th>Description</th>
                           <th>Action</th>
                        </tr>
                     </tfoot>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@else
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
         <div>
            <h3 class="fw-bold mb-3">{{ $lastSegment }}</h3>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card card-round">
               <div class="card-header">
                  <div class="card-head-row card-tools-still-right">
                     <div class="card-title">Backlink connections
                        <p>These are the websites you get a backlink from</p>
                     </div>
                  </div>
               </div>
               <div class="card-body p-4">
                  <table id="websites" class="table" style="width: 100%;">
                     <thead>
                        <tr>
                           <th>Website</th>
                           <th>Niche</th>
                           <th>Description</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody></tbody>
                     <tfoot>
                        <tr>
                           <th>Website</th>
                           <th>Niche</th>
                           <th>Description</th>
                           <th>Action</th>
                        </tr>
                     </tfoot>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endif

@include('frontend.dashboard.common.footer')
