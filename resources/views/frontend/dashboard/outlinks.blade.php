@include('frontend.dashboard.common.header')
<?php 
      $currentUrl = url()->current();
      $ls = request()->segment(count(request()->segments()));
      $lastSegment = decryptData($ls);
?>
@if(count($outlink_data)>0)
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
         >
         <div>
            <h3 class="fw-bold mb-3">
               {{ $lastSegment }}
            </h3>
            @if (session('message_acceptedby_to_outlink_connection'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <i class="fa fa-check"></i> {{ session('message_acceptedby_to_outlink_connection') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session('message_acceptedby_from_outlink_connection'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <i class="fa fa-check"></i> {{ session('message_acceptedby_from_outlink_connection') }}
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
                     <div class="card-title">Outlink connections
                        <p>These are the websites you have to give a backlink to</p>
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
                       @foreach ($outlink_data as $mywebsite)
                        <tr>
                           <td>
                              {{ $mywebsite->website_url }}
                           </td>
                           <td>{{ $mywebsite->website_niche }}</td>
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
                              @if($mywebsite->status=="" || ($mywebsite->acceptedby_to=="" && $mywebsite->status=="pending"))
                              <a onclick="return confirm('Are you sure?')" href="/acceptedby-to-outlink-connection/{{ encrypt($mywebsite->id) }}" class="btn btn-success">Approve</a> | <a onclick="return confirm('Are you sure?')" href="/reject/{{encrypt($mywebsite->from_user_id)}}/{{encrypt($mywebsite->to_user_id)}}" class="btn btn-danger">Reject</a>

                              @elseif($mywebsite->status=="pending")
                              <a href="#" class="btn btn-warning">Waiting for Approval</a>

                              @elseif($mywebsite->status=="accepted")
                              <a href="#" class="btn btn-success">Go to chat</a>

                              @elseif($mywebsite->status=="rejected")
                              <a href="#" class="btn btn-danger">Rejected</a>
                              
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
   </div>
</div>
@else
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
         >
         <div>
            <h3 class="fw-bold mb-3">{{ $lastSegment }}</h3>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card card-round">
               <div class="card-header">
                  <div class="card-head-row card-tools-still-right">
                     <div class="card-title">Outlink connections
                        <p>These are the websites you have to give a backlink to</p>
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