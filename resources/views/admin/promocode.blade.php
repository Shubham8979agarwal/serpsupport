@include('admin.common.header')
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2">
         <div>
            <h3 class="fw-bold mb-3">Create Promocode</h3>
            @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               {{ session('message') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
         </div>
      </div>
      <div class="page-header">
         <div class="col-md-12">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <form action="{{ route('create-promocode') }}" method="POST">
                        @csrf
                        <div class="form-group">
                           <label for="Promocode Name">Promocode Name</label>
                           <input type="text" class="form-control" name="promocode_name" placeholder="Enter Promocode Name" value="{{ old('promocode_name') }}">
                           @error('promocode_name')
                           <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                        <div class="form-group">
                           <label for="Enter Discount Value">Enter Discount Value</label>
                           <input type="number" class="form-control" name="discount" placeholder="Enter Discount Value" value="{{ old('discount') }}">
                           @error('discount')
                           <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                        <div class="form-group">
                              <label for="promocode_description">Enter Promocode Description(Max 250 words)</label>
                              <textarea class="form-control" name="promocode_description" placeholder="Enter Promocode Description" value="{{ old('promocode_description') }}" rows="10"></textarea>
                              @error('promocode_description')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                           </div>
                        <div class="form-group">
                           <button class="btn btn-primary">Add Promocode</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@include('admin.common.footer')