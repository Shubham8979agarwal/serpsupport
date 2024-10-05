@include('admin.common.header')
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
         <div class="container">
            <h3 class="fw-bold mb-3">Connection(s)</h3>
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
                  <table id="show_connections" class="table">
                     <thead>
                        <tr>
                           <th>Connection Type</th>
                           <th>Outlink On</th>
                           <th>Backlink To</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(count($connections)>0)
                        @foreach($connections as $connection)
                        <tr>
                           <td>{{ $connection->connection_type }}</td>
                           <td>{{ $connection->outlink_on }}</td>
                           <td>{{ $connection->backlink_to}}</td>
                           <td>
                              <a onclick="return confirm('Are you sure?')" href="delete-connection/{{ encrypt($connection->chat_id) }}" class="mb-2 btn btn-danger"><i class="fa fa-trash"></i> Delete Connection</a>
                           </td>
                        </tr>
                        @endforeach
                        @endif
                     <tbody>
                     <tfoot>
                        <tr>
                           <th>Connection Type</th>
                           <th>Outlink On</th>
                           <th>Backlink To</th>
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
   $('#show_connections').DataTable({
      "scrollX": true,
      layout: {
        top: ['search'],
          topEnd: {
              buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
          }
      }
   });
</script>