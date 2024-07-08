@include('frontend.dashboard.common.header')
<div class="container">
   <div class="page-inner">
      <div
         class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
         >
         <div>
            <h3 class="fw-bold mb-3">Account Settings</h3>
            @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               {{ session('message') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
         </div>
         <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('addwebsite') }}" class="btn btn-primary btn-round"><i class="fa fa-plus"></i> Add Website</a>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card card-round">
               <div class="card-header">
                  <div class="card-head-row card-tools-still-right">
                     <div class="card-title">Your Websites(10 Max)</div>
                  </div>
               </div>
               <div class="card-body p-4">
                  <table id="websites" class="table" style="width:100%">
                     <thead>
                        <tr>
                           <th>Website Niche</th>
                           <th>Website URL</th>
                           <th>Website Description</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php $i=0; ?> 
                        @if(count($pushedwebsites)>0)
                        @foreach($pushedwebsites as $mywebsites)
                        <tr>
                           <td>{{ $mywebsites->website_niche }}</td>
                           <td>{{ $mywebsites->website_url }}</td>
                           <td>
                              <p> 
                                <a data-bs-toggle="collapse" href="#collapseExample<?php $i=$i+1; echo $i; ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $i; ?>">
                                  Show/Hide
                                </a>
                              </p>
                              <div class="collapse" id="collapseExample<?php echo $i; ?>">
                                <div class="card card-body">
                                  {{ $mywebsites->website_description }}
                                </div>
                              </div>
                           </td>
                           <td><a onclick="return confirm('Are you sure?')" href="delete-website/{{ encrypt($mywebsites->website_id) }}">Delete</a></td>
                        </tr>
                        @endforeach  
                        @endif
                     </tbody>
                     <tfoot>
                        <tr>
                           <th>Website Niche</th>
                           <th>Website URL</th>
                           <th>Website Description</th>
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
@include('frontend.dashboard.common.footer')