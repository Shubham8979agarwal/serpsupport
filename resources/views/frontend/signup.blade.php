@include('frontend.common.header')
      <div class="d-lg-flex half">
         <div class="bg order-1 order-md-2 d-none d-lg-block" style="background-image: url('assets/images/PortalLogin.png');"></div>
         <div class="contents order-2 order-md-1">
            <div class="container">
               <div class="row align-items-center justify-content-center">
                  <div class="col-md-7">
                     <div class="mb-4">
                        <h3>Sign Up</h3>
                     </div>
                     <form action="{{ route('make.account') }}" method="post">
                        @csrf
                        <div class="social-login">
                           <a href="{{ route('google.redirect') }}" class="google btn d-flex justify-content-center align-items-center">
                           <span class="icon-google mr-3"></span> Sign Up with  Google
                           </a>
                        </div>
                        <span class="d-block text-center my-4 text-muted">&mdash; or &mdash;</span>

                        @if (session('register_success'))
                            <div class="alert alert-success">
                               {{ session('register_success') }}
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
                           <input type="text" class="form-control" name="name" placeholder="Enter Name" autocomplete="off" value="{{ old('name') }}">
                           @error('name')
                            <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
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
                        <div class="d-flex mb-3 align-items-center">
                           <span class="caption">Already have a account? <a href="{{ route('login') }}">Login</a></span>
                           <span class="ml-auto"><a href="{{ route('forget.password.get') }}" class="forgot-pass">Forgot Password</a></span> 
                        </div>
                        <button type="submit" class="btn btn-block btn-primary">Sign Up</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
@include('frontend.common.footer')
