@include('frontend.common.header')
<div class="container">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Forgot Password -->
          <div class="card">
            <div class="card-body">
              @if(session('message'))
              <div class="alert alert-success mb-2">
                 {{ session('message') }}
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
              </div>
              <br>
              @endif
              <h4 class="mb-2">Forgot Password? 🔒</h4>
              <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
              <form class="mb-3" action="{{ route('forget.password.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="text" class="form-control" name="email" placeholder="Enter your email"/>
                  @if ($errors->has('email'))
                      <span class="text-danger">{{ $errors->first('email') }}</span>
                  @endif
                </div>
                <button class="btn btn-primary d-grid">Send Reset Link</button>
              </form>
              <div class="text-center">
                <a href="{{ route('login') }}">
                  <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                  Back to login
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('frontend.common.footer')