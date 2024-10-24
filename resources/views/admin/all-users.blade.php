@include('admin.common.header')
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2">
         <div class="container">
            <h3 class="fw-bold mb-3">All Users</h3>
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
                  <table id="all_users" class="table" style="width:100%">
                     <thead>
                        <tr>
                           <th>Name</th>
                           <th>Email</th>
                           <th>Email Verified</th>
                           <th>Is Subscrbed</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(count($allusers)>0)
                        @foreach($allusers as $au)
                        <tr>
                           <td>{{ $au->name }}</td>
                           <td>{{ $au->email }}</td>
                           <td>
                              @if($au->is_email_verified==1)
                              <p>Yes</p>
                              @elseif($au->is_email_verified==0)
                              <p>No</p>
                              @endif
                           </td>
                           <td>
                              @if($au->is_subscribed=='true')
                              <span>Yes</span>
                              @else
                              <span></span>
                              @endif
                           </td>
                           <td>{{ $au->status }}</td>
                           <td>@if($au->status=="active")
                              <a onclick="return confirm('Are you sure?')" href="block-user/{{ encrypt($au->id) }}" class="mb-2 btn btn-secondary"><i class="fa fa-ban" aria-hidden="true"></i> Block</a>
                              @elseif($au->status=="blocked")
                              <a onclick="return confirm('Are you sure?')" href="unblock-user/{{ encrypt($au->id) }}" class="mb-2 btn btn-secondary"><i class="fa fa-unlock" aria-hidden="true"></i> Unblock</a>
                              @endif
                              <a onclick="return confirm('Are you sure?')" href="delete-user/{{ encrypt($au->id) }}" class="mb-2 btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                              @if($au->is_email_verified==0)
                              <a onclick="return confirm('Are you sure?')" href="verify-email/{{ encrypt($au->id) }}" class="btn btn-info mb-2">Verify Email</a>
                              @endif
                           </td>
                        </tr>
                        @endforeach
                        @endif
                     <tbody>
                     <tfoot>
                        <tr>
                           <th>Name</th>
                           <th>Email</th>
                           <th>Email Verified</th>
                           <th>Is Subscrbed</th>
                           <th>Status</th>
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
   $('#all_users').DataTable({
      "scrollX": true,
      layout: {
        top: ['search'],
          topEnd: {
              buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
          }
      }
   });
</script>