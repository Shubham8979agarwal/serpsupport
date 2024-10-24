@include('admin.common.header')
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2">
         <div> 
            <h3 class="fw-bold mb-3">Add Plan(s)</h3>
            @if (session('error_message'))
               <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="fa fa-warning"></i> {{ session('error_message') }}
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
                     <form action="{{ route('add-plans') }}" method="POST">
                        @csrf   
                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="plan_name">Plan Name</label>
                              <input type="text" class="form-control" name="plan_name" placeholder="Enter Plan Name" value="{{ old('plan_name') }}">
                              @error('plan_name')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                           </div>
                           <div class="form-group">
                              <label for="plan_pricing">Plan Pricing (in USD)</label>
                              <input type="number" class="form-control" name="plan_pricing" placeholder="Eg: $9.99" value="{{ old('plan_pricing') }}">
                              @error('plan_pricing')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                           </div>
                           <div class="form-group">
                              <label for="plan_type">Plan Type</label>
                              <select name="plan_type" class="form-select" value="{{ old('plan_type') }}">
                                 <option value="" selected>---Select---</option>
                                 <option value="Weekly">Weekly</option>
                                 <option value="Monthly">Monthly</option>
                                 <option value="Yearly">Yearly</option>
                              </select>
                              @error('plan_type')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                           </div>
                           <div class="form-group">
                              <label for="plan_description">Enter Plan Description(Max 250 words)</label>
                              <textarea class="form-control" name="plan_description" placeholder="Enter Plan Description" value="{{ old('plan_description') }}" rows="10"></textarea>
                              @error('plan_description')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                           </div>
                           <div class="form-group">
                              <button class="btn btn-primary">Add Plan</button>
                           </div>
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