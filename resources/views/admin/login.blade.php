@include('frontend.common.header')
<div class="d-lg-flex half">
   <div class="bg order-1 order-md-2" style="background-image: url('assets/images/PortalLogin.png');"></div>
   <div class="contents order-2 order-md-1">
      <div class="container">
         <div class="row align-items-center justify-content-center">
            <div class="col-md-7">
               <div class="mb-4">
                  <h3>Admin Login</h3>
                  <!-- <p class="mb-4">Lorem ipsum dolor sit amet elit. Sapiente sit aut eos consectetur adipisicing.</p> -->
               </div>
               <form action="{{ route('make-admin-login') }}" method="post">
                  @csrf
                  @if (session('message'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                     {{ session('message') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                  </div>
                  @endif
                  @if (session('message_cp'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('message_cp') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </div>
                  @endif
                  @if (session('status_signin_failed'))
                  <div class="alert alert-danger">
                  {{ session('status_signin_failed') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </div>
                  @endif
                  <div class="form-group">
                  <input type="email" class="form-control" name="email" placeholder="Enter Email" autocomplete="off" value="{{ old('email') }}">
                  @error('email')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
                  </div>
                  <div class="form-group position-relative">
                   <input type="password" id="password" class="form-control" name="password" placeholder="Enter Password" autocomplete="off" value="{{ old('password') }}">

                   <!-- Eye icon to toggle password visibility -->
                   <span toggle="#password" class="fa fa-fw fa-eye password-toggle-icon"></span>

                   <!-- Error message for password field -->
                   @error('password')
                       <span class="text-danger password-error">{{ $message }}</span>
                   @enderror
                  </div>
                  <!-- <div class="d-flex mb-3 align-items-center">
                     <span class="caption">Don't have a account? <a href="{{ route('signup')}}">Sign Up</a></span>
                     <span class="ml-auto"><a href="{{ route('forget.password.get') }}" class="forgot-pass">Forgot Password</a></span> 
                     </div> -->
                  <button type="submit" class="btn btn-block btn-primary">Login</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@include('frontend.common.footer')