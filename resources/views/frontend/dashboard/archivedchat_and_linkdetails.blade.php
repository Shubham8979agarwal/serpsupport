@include('frontend.dashboard.common.header')
<div class="container">
   <div class="page-inner">
      <div
         class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
         >
         <div>
            <h3 class="fw-bold mb-3">Archived Chat & Link Details</h3>
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
                           <th>Outlink On</th>
                           <th>Backlink To</th>
                           <th>Anchor Text</th>
                           <th>Outlink Placed</th>
                           <!-- <th>Chat Status</th> -->
                           <th>Archive Chat</th>
                        </tr>
                     </thead>
                     <tbody>
                         @if(count($linkdetails) > 0)
                             @foreach($linkdetails as $index => $mywebsites)
                             <tr>
                                 <td>{{ $mywebsites->typeoflink }}</td>
                                 <td>{{ $mywebsites->outlink_on }}</td>
                                 <td>{{ $mywebsites->backlink_to }}</td>
                                 <td>{{ $mywebsites->anchor_text }}</td>
                                 <td>{{ $mywebsites->outlink_placed_on_your_website }}</td>
                                 <!-- <td>{{ $mywebsites->chat_status }}</td> -->
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
                           <th>Outlink On</th>
                           <th>Backlink To</th>
                           <th>Anchor Text</th>
                           <th>Outlink Placed</th>
                           <!-- <th>Chat Status</th> -->
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