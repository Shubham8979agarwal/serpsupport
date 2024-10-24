@include('frontend.dashboard.common.header')
<div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
         <div>
            <h3 class="fw-bold mb-3">Subscription Canceled</h3>
            <p>Your subscription has been canceled.</p>
            <a href="{{ route('subscription') }}">Go back to Pricing</a>
         </div>
      </div>
   </div>
</div>
@include('frontend.dashboard.common.footer')
