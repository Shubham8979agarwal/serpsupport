@include('frontend.dashboard.common.header')
<div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
         <div>
            <h3 class="fw-bold mb-3">Subscription Successful!</h3>
            <p>Your subscription has been activated.</p>
            <a href="{{ route('login') }}">Go to Login</a>
         </div>
      </div>
   </div>
</div>
@include('frontend.dashboard.common.footer')
