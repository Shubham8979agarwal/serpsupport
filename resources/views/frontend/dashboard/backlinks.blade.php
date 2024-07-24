@include('frontend.dashboard.common.header')
@if(count($backlink_data)>0)
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
         >
         <div>
         	<h3 class="fw-bold mb-3">{{ $backlink_data[0]->forwhich_user_url }}</h3>
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
                     <tbody>
                        <?php $i=0; ?>
                       @foreach ($backlink_data as $mywebsite)
                        <tr>
                           <td>{{ $mywebsite->website_url }}</td>
                           <td>
                              <?php 
                                $getniche = DB::table('websites')->where('website_url', $mywebsite->website_url)->get()->toArray();
                              ?>
                              @foreach ($getniche as $niche)
                              {{ $niche->website_niche }}
                              @endforeach
                           </td>
                           <td>
                              <p> 
                                <a data-bs-toggle="collapse" href="#collapseExample<?php $i=$i+1; echo $i; ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $i; ?>">
                                  Show/Hide
                                </a>
                              </p>
                              <div class="collapse" id="collapseExample<?php echo $i; ?>">
                                <div class="card card-body">
                                  {{ $mywebsite->website_description }}
                                </div>
                              </div>
                           </td>
                           <td>
                              @if((Auth::user()->id==$mywebsite->from_user_id) && $mywebsite->status=="")
                              <a onclick="return confirm('Are you sure?')" href="/acceptedby-from-backlink-connection/{{ encrypt($mywebsite->id) }}" class="btn btn-success">Accept</a> | <a onclick="return confirm('Are you sure?')" href="#" class="btn btn-danger">Reject</a>

                              @elseif($mywebsite->status=="pending" && $mywebsite->acceptedby_from=="yes")
                              <a href="#" class="btn btn-warning">Waiting for Approval</a>

                              @elseif($mywebsite->acceptedby_to=="yes" && $mywebsite->acceptedby_from=="yes" && $mywebsite->status=="accepted")
                              <a href="#" class="btn btn-success">Go to chat</a>
                              @endif
                           </td>
                        </tr>
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
      <!--- chat connection requests --->
      <?php $data = DB::table('backlinks')->where('website_url',$backlink_data[0]->forwhich_user_url)->where('acceptedby_to','yes')->orwhere('acceptedby_from','yes')->where('status','pending')->orwhere('status','accepted')->get();
      
      $find_data = [];
      foreach ($data as $conn_req){
         if($backlink_data[0]->forwhich_user_url==$conn_req->website_url){
            $find_data[] = $conn_req;
         }
      }
      ?>
      <div class="row">
         <div class="col-md-12">
            <div class="card card-round">
               <div class="card-header">
                  <div class="card-head-row card-tools-still-right">
                     <div class="card-title">Chat connection requests
                        <!-- <p>These are the websites you have to give a backlink to</p> -->
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
                        <?php $i=0; ?>
                         @foreach ($find_data as $mywebsite)
                         @if(Auth::user()->id==$mywebsite->to_user_id)
                        <tr>
                           <td>
                              {{ $mywebsite->forwhich_user_url }}
                           </td>

                           <td>
                              <?php 
                                $getniche = DB::table('websites')->where('website_url', $mywebsite->forwhich_user_url)->get()->toArray();
                              ?>
                              @foreach ($getniche as $niche)
                              {{ $niche->website_niche }}
                              @endforeach
                           </td>
                           <td>
                              <?php 
                                $getdesc = DB::table('websites')->where('website_url', $mywebsite->forwhich_user_url)->get()->toArray();
                              ?>
                              @foreach ($getdesc as $desc)
                              <p> 
                                <a data-bs-toggle="collapse" href="#collapseExample<?php $i=$i+1; echo $i; ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $i; ?>">
                                  Show/Hide
                                </a>
                              </p>
                              <div class="collapse" id="collapseExample<?php echo $i; ?>">
                                <div class="card card-body">
                                  {{ $desc->website_description }}
                                </div>
                              </div>
                              @endforeach
                           </td>

                           <td>
                              @if($mywebsite->website_url==$backlink_data[0]->forwhich_user_url && $mywebsite->acceptedby_from=='yes' && $mywebsite->status=="pending")
                              <a onclick="return confirm('Are you sure?')" href="/acceptedby-to-backlink-connection/{{ encrypt($mywebsite->id) }}" class="btn btn-success">Approve</a> | <a onclick="return confirm('Are you sure?')" href="#" class="btn btn-danger">Reject</a>
                              @endif
                           </td>
                        </tr>
                        @else
                        <tr>
                           <td>There is no chat connection request yet...</td>
                           <td></td>
                           <td></td>
                           <td></td>
                        <tr>
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
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
         >
         <div>
            <h3 class="fw-bold mb-3"></h3>
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
                     <tbody>
                        <tr>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                        </tr>
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
                  <div class="card-title">
                     <p>No connection at the moment</p>
               </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endif
@include('frontend.dashboard.common.footer')