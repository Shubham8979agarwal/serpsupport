@include('frontend.dashboard.common.header')
<div class="container">
   <div class="page-inner">
      @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
         <i class="fa fa-warning"></i> {{ session('error') }}
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif
      <div class="page-header">
         <div class="col-md-12">
            <div class="card mt-4">
               <div class="card-header">
                  <div class="card-title">
                     <a href="{{ route('account-settings') }}" style="color:blue"><i class="fas fa-home"></i> Account Settings</a> 
                     <i class="fa fa-angle-right"></i> Edit Website
                  </div>
                  @if (session('error_message'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     <i class="fa fa-warning"></i> {{ session('error_message') }}
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  @endif
               </div>
               <div class="card-body">
                  <div class="row">
                     <form action="{{ route('edit-website', ['website_id' => $website->website_id]) }}" method="POST">
                        @csrf
                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="editwebsite">Select Website Niche</label>
                              <select name="website_niche" class="form-select" required>
                                 <option value="" disabled>---Select---</option>
                                 <option value="Health" {{ $website->website_niche == 'Health' ? 'selected' : '' }}>Health</option>
                                 <option value="Finance" {{ $website->website_niche == 'Finance' ? 'selected' : '' }}>Finance</option>
                                 <option value="Technology" {{ $website->website_niche == 'Technology' ? 'selected' : '' }}>Technology</option>
                                 <option value="Travel" {{ $website->website_niche == 'Travel' ? 'selected' : '' }}>Travel</option>
                                 <option value="Food" {{ $website->website_niche == 'Food' ? 'selected' : '' }}>Food</option>
                                 <option value="Fashion" {{ $website->website_niche == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                                 <option value="Parenting" {{ $website->website_niche == 'Parenting' ? 'selected' : '' }}>Parenting</option>
                                 <option value="Education" {{ $website->website_niche == 'Education' ? 'selected' : '' }}>Education</option>
                                 <option value="Home" {{ $website->website_niche == 'Home' ? 'selected' : '' }}>Home</option>
                                 <option value="Fitness" {{ $website->website_niche == 'Fitness' ? 'selected' : '' }}>Fitness</option>
                                 <option value="Gaming" {{ $website->website_niche == 'Gaming' ? 'selected' : '' }}>Gaming</option>
                                 <option value="Music" {{ $website->website_niche == 'Music' ? 'selected' : '' }}>Music</option>
                                 <option value="Business" {{ $website->website_niche == 'Business' ? 'selected' : '' }}>Business</option>
                                 <option value="Marketing" {{ $website->website_niche == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                 <!-- Add other options here following the same structure -->
                              </select>
                              @error('website_niche')
                                 <span class="text-danger">{{ $message }}</span>
                              @enderror
                           </div>

                           <div class="form-group">
                              <label for="editwebsite">Enter Website Description (Max 250 words)</label>
                              <textarea class="form-control" name="website_description" placeholder="Enter Website Description" rows="10" required>{{ old('website_description', $website->website_description) }}</textarea>
                              @error('website_description')
                                 <span class="text-danger">{{ $message }}</span>
                              @enderror
                           </div>

                           <div class="form-group">
                              <button class="btn btn-primary">Update Website</button>
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
@include('frontend.dashboard.common.footer')
