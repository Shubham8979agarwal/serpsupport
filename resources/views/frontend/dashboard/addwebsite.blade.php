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
                  <div class="card-title"><a href="{{ route('account-settings') }}" style="color:blue"><i class="fas fa-home"></i> Account Settings</a>  <i class="fa fa-angle-right"></i>  Add Website</div>
                  @if (session('error_message'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     <i class="fa fa-warning"></i> {{ session('error_message') }}
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  @endif
               </div>
               @if($check==0)
               <div class="card-body">
                  <div class="row">
                     <form action="{{ route('push-website') }}" method="POST">
                      @csrf   
                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="addwebsite">Select website Niche</label>
                              <select name="website_niche" class="form-select" value="{{ old('website_niche') }}">
                                 <option value="" selected>---Select---</option>
                                 <option value="Health">Health</option>
                                 <option value="Finance">Finance</option>
                                 <option value="Technology">Technology</option>
                                 <option value="Travel">Travel</option>
                                 <option value="Food">Food</option>
                                 <option value="Fashion">Fashion</option>
                                 <option value="Parenting">Parenting</option>
                                 <option value="Education">Education</option>

                                 <option value="Home">Home</option>
                                 <option value="Fitness">Fitness</option>
                                 <option value="Gaming">Gaming</option>
                                 <option value="Music">Music</option>
                                 <option value="Business">Business</option>
                                 <option value="Marketing">Marketing</option>
                                 <option value="Pets">Pets</option>
                                 <option value="Photography">Photography</option>

                                 <option value="Development">Development</option>
                                 <option value="Lifestyle">Lifestyle</option>
                                 <option value="RealEstate">RealEstate</option>
                                 <option value="News">News</option>
                                 <option value="Automobiles">Automobiles</option>
                                 <option value="Sustainability">Sustainability</option>
                                 <option value="Science">Science</option>
                                 <option value="Sports">Sports</option>

                                 <option value="Crafts">Crafts</option>
                                 <option value="MentalHealth">MentalHealth</option>
                                 <option value="Blogging">Blogging</option>
                                 <option value="E-commerce">E-commerce</option>
                                 <option value="History">History</option>
                                 <option value="Politics">Politics</option>
                                 <option value="Career">Career</option>
                                 <option value="Spirituality">Spirituality</option>

                                 <option value="Relationships">Relationships</option>
                                 <option value="Movies">Movies</option>
                                 <option value="Art">Art</option>
                                 <option value="Gardening">Gardening</option>
                                 <option value="Environment">Environment</option>
                                 <option value="Legal">Legal</option>
                                 <option value="Cryptocurrency">Cryptocurrency</option>
                                 <option value="Collectibles">Collectibles</option>

                                 <option value="Events">Events</option>
                                 <option value="Skills">Skills</option>
                                 <option value="WebDesign">WebDesign</option>
                                 <option value="Investment">Investment</option>
                                 <option value="Literature">Literature</option>
                                 <option value="Interior">Interior</option>
                                 <option value="Wedding">Wedding</option>
                                 <option value="Charity">Charity</option>

                                 <option value="Alternative">Alternative</option>
                                 <option value="DIY">DIY</option>
                                 <option value="Other">Other</option>
                              </select>
                              @error('website_niche')
                                 <span class="text-danger">{{ $message }}</span>
                              @enderror
                           </div>
                           <div class="form-group">
                              <label for="addwebsite">Enter Website URL</label>
                              <input type="text" class="form-control" name="website_url" placeholder="Enter Website URL" value="{{ old('website_url') }}">
                              @error('website_url')
                                 <span class="text-danger">{{ $message }}</span>
                              @enderror
                           </div>
                           <div class="form-group">
                              <label for="addwebsite">Enter Website Description(Max 250 words)</label>
                              <textarea class="form-control" name="website_description" placeholder="Enter Website Description" value="{{ old('website_description') }}" rows="10"></textarea>
                              @error('website_description')
                                 <span class="text-danger">{{ $message }}</span>
                              @enderror
                           </div>
                           <div class="form-group">
                              <button class="btn btn-primary">Add Website</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
               @else
               <div class="card-body">
                  <div class="row">
                     <p>You have already added 1 website.</p>
                  </div>
               </div>   
               @endif
            </div>
         </div>
      </div>
   </div>
</div>
@include('frontend.dashboard.common.footer')