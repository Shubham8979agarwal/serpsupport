@include('admin.common.header')
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2">
         <div> 
            <h3 class="fw-bold mb-3">Show Promocode</h3>
            @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               {{ session('message') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
         </div>
         <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('promocode') }}" class="btn btn-primary btn-round"><i class="fa fa-plus"></i> Add Promocode</a>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card card-round">
               <div class="card-body p-4">
                  <table id="showpromocode" class="table" style="width:100%">
                     <thead>
                        <tr>
                           <th>Promocode Name</th>
                           <th>Discount</th>
                           <th>Description</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(count($promocode) > 0)
                        @foreach($promocode as $index => $pc)
                        <tr>
                           <td>{{ $pc->promocode_name }}</td>
                           <td class="text-center">{{ $pc->discount }}%</td>
                           <td>
                              <p> 
                                 <a data-bs-toggle="collapse" href="#collapseExample{{ $index + 1 }}" role="button" aria-expanded="false" aria-controls="collapseExample{{ $index + 1 }}">
                                 Show/Hide
                                 </a>
                              </p>
                              <div class="collapse" id="collapseExample{{ $index + 1 }}">
                                 <div class="card card-body">
                                    {{ $pc->promocode_description  }}
                                 </div>
                              </div>
                           </td>
                           <td>
                              @if($pc->status=="1")
                              <span class="btn btn-success">Activated</span>
                              @elseif($pc->status=="0")
                              <span class="btn btn-danger">Deactivated</span>
                              @endif
                           </td>
                           <td><a class="btn btn-danger" onclick="return confirm('Are you sure?')" href="delete-promocode/{{ encrypt($pc->id) }}"><i class="fa fa-trash"></i> Delete</a> | 
                           @if($pc->status == "0")
                           <a class="btn btn-success" onclick="return confirm('Are you sure?')" href="turn-on-promocode/{{ encrypt($pc->id) }}"><i class="fa fa-toggle-off" aria-hidden="true"></i> Activate</a>
                           @elseif($pc->status == "1")
                           <a class="btn btn-danger" onclick="return confirm('Are you sure?')" href="turn-off-promocode/{{ encrypt($pc->id) }}"><i class="fa fa-toggle-on" aria-hidden="true"></i> Deactivate</a>
                           @endif
                          </td>
                        </tr>
                        @endforeach
                        @endif
                     </tbody>
                     <tfoot>
                        <tr>
                           <th>Promocode Name</th>
                           <th>Discount</th>
                           <th>Description</th>
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
   $('#showpromocode').DataTable({
    "scrollX": true,  
    layout: {
      top: ['search'],
        topEnd: {
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        }
    }
   });
</script>