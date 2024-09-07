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
               <div class="card-header">
                  <div class="card-head-row card-tools-still-right">
                     <div class="card-title">Archived Chat & Link Details</div>
                  </div>
               </div>
               <div class="card-body p-4">
                  <table id="websites" class="table" style="width:100%">
                     <thead>
                        <tr>
                           <th>Type Of Link</th>
                           <th>Outlink On</th>
                           <th>Backlink On</th>
                           <th>Anchor Text</th>
                           <th>Outlink Placed On Your Website</th>
                           <th>Chat Status</th>
                        </tr>
                     </thead>
                     <tbody>
                         @if(count($linkdetails) > 0)
                             @foreach($linkdetails as $index => $mywebsites)
                             <tr>
                                 <td>{{ $mywebsites->typeoflink }}</td>
                                 <td>{{ $mywebsites->outlink_on }}</td>
                                 <td>{{ $mywebsites->backlink_on }}</td>
                                 <td>{{ $mywebsites->anchor_text }}</td>
                                 <td>{{ $mywebsites->outlink_placed_on_your_website }}</td>
                                 <td>{{ $mywebsites->chat_status }}</td>
                             </tr>
                             @endforeach  
                         @endif
                     </tbody>
                     <tfoot>
                        <tr>
                           <th>Type Of Link</th>
                           <th>Outlink On</th>
                           <th>Backlink On</th>
                           <th>Anchor Text</th>
                           <th>Outlink Placed On Your Website</th>
                           <th>Chat Status</th>
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