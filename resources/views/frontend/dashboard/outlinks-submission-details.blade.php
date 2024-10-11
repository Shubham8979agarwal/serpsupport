@include('frontend.dashboard.common.header')
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
         <div>
            <h3 class="fw-bold mb-3">SERPsupport Portal Stats</h3>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
               <div class="card-body">
                  <div class="row align-items-center">
                     <div class="col-icon">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </div>
                     </div>
                     <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                           <p class="card-category">Inbound Links</p>
                           <h4 class="card-title">{{ $confirmed_backlinks }}</h4>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
               <div class="card-body">
                  <div class="row align-items-center">
                     <div class="col-icon">
                        <div class="icon-big text-center icon-info bubble-shadow-small">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        </div>
                     </div>
                     <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                           <p class="card-category">Outbound Links</p>
                           <h4 class="card-title">{{ $confirmed_outlinks }}</h4>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
               <div class="card-body">
                  <div class="row align-items-center">
                     <div class="col-icon">
                        <div class="icon-big text-center icon-success bubble-shadow-small">
                           <i class="fa fa-globe"></i>
                        </div>
                     </div>
                     <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                           <p class="card-category">Total Website(s)</p>
                           <h4 class="card-title">{{ $total_websites }}</h4>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
               <div class="card-body">
                  <div class="row align-items-center">
                     <div class="col-icon">
                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                           <i class="fa fa-link"></i>
                        </div>
                     </div>
                     <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                           <p class="card-category">Total Connects</p>
                           <h4 class="card-title">{{ $connections }}</h4>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
         <div>
            <h3 class="fw-bold mb-3">Outlink(s) Submission Details</h3>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card card-round">
               <div class="card-body p-4">
                  <table id="acld" class="table" style="width:100%">
                     <thead>
                        <tr>
                           <th>Type Of Link</th>
                           <th>Outbound Link Page</th>
                           <!-- <th>Inbound Link Page</th> -->
                           <th>Anchor Text</th>
                           <!-- <th>Outlink Placed</th> -->
                           <th>Archive Chat</th>
                        </tr>
                     </thead>
                     <tbody>
                         @if(count($linkdetails) > 0)
                             @foreach($linkdetails as $index => $mywebsites)
                             <tr>
                                 <td>{{ $mywebsites->typeoflink }}</td>
                                 <td><a href="https://{{ $mywebsites->outlink_on }}">{{ $mywebsites->outlink_on }}</a></td>
                                 <!-- <td><a href="https://{{ $mywebsites->backlink_to }}">{{ $mywebsites->backlink_to }}</a></td> -->
                                 <td>{{ $mywebsites->anchor_text }}</td>
                                 <td>
                                     <?php 
                                         $chatid = $mywebsites->chat_id; 
                                         $chatidParts = explode('_', $chatid);
                                         $beforeUnderscore = $chatidParts[0];
                                         $afterUnderscore = $chatidParts[1];
                                     ?>
                                     @if($beforeUnderscore == Auth::user()->id)
                                         <!-- Display this button if the part before the underscore matches the authenticated user's ID -->
                                         <a class="btn btn-info" href="/chat/{{ $afterUnderscore }}"><i class="fa fa-eye"></i> View</a>
                                     @elseif($afterUnderscore == Auth::user()->id)
                                         <!-- Display this button if the part after the underscore matches the authenticated user's ID -->
                                         <a class="btn btn-info" href="/chat/{{ $beforeUnderscore }}"><i class="fa fa-eye"></i> View</a>
                                     @endif
                                 </td>
                             </tr>
                             @endforeach  
                         @endif
                     </tbody>
                     <tfoot>
                        <tr>
                           <th>Type Of Link</th>
                           <th>Outbound Link Page</th>
                           <!-- <th>Inbound Link Page</th> -->
                           <th>Anchor Text</th>
                           <!-- <th>Outlink Placed</th> -->
                           <th>Archive Chat</th>
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