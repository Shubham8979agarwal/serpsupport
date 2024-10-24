@include('admin.common.header')
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2">
         <div class="container">
            <h3 class="fw-bold mb-3">All Websites</h3>
            @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <i class="fa fa-check-circle" aria-hidden="true"></i>
               {{ session('message') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card card-round">
               <div class="card-body p-4">
                  <table id="all_websites" class="table" style="width:100%">
                     <thead>
                        <tr>
                           <th>Website URL</th>
                           <th>Website Niche</th>
                           <th>Website Description</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(count($allwebsites)>0)
                        @foreach($allwebsites as $aw)
                        <tr>
                           <td>{{ $aw->website_url }}</td>
                           <td>{{ $aw->website_niche }}</td>
                           <td>{{ $aw->website_description }}</td>
                           <td>
                              <a onclick="return confirm('Are you sure?')" href="delete-website/{{ encrypt($aw->id) }}" class="mb-2 btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                           </td>
                        </tr>
                        @endforeach
                        @endif
                     <tbody>
                     <tfoot>
                        <tr>
                           <th>Website URL</th>
                           <th>Website Niche</th>
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
@include('admin.common.footer')
<script type="text/javascript">
   $('#all_websites').DataTable({
      "scrollX": true,
      layout: {
        top: ['search'],
          topEnd: {
              buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
          }
      }
   });
</script>