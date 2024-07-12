@include('frontend.dashboard.common.header')
@if(count($outlink_data)>0)
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
         >
         <div>
            
            <h3 class="fw-bold mb-3">{{ $outlink_data[0]->forwhich_user_url }}</h3>
            
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
                           <td>{{ $mywebsite->website_url }}</td>
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
                              @if($mywebsite->status=="not_accepted_yet")
                              <a onclick="return confirm('Are you sure?')" href="#" class="btn btn-success">Accept</a> | <a onclick="return confirm('Are you sure?')" href="#" class="btn btn-danger">Reject</a>
                              @elseif($mywebsite->status=="accepted")
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