@if(Auth::check() && auth()->user()->is_email_verified==1)
@include('frontend.dashboard.common.header')
@else
@include('frontend.common.header')
@endif
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
         <div>
            <h3 class="fw-bold mb-3">FAQ(s)</h3>
         </div>
      </div>
      <div class="accordion">
         <div class="accordion__item">
            <div class="accordion__header" data-toggle="#faq1">Watch our video: How it works?</div>
            <div class="accordion__content" id="faq1">
               <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
               <iframe width="100%" height="350px" src="https://www.youtube.com/embed/tgbNymZ7vqY">
               </iframe>
            </div>
         </div>
         <div class="accordion__item">
            <div class="accordion__header" data-toggle="#faq2">But I must explain to you how all this mistaken idea?</div>
            <div class="accordion__content" id="faq2">
               <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beata</p>
            </div>
         </div>
         <div class="accordion__item">
            <div class="accordion__header" data-toggle="#faq3">At vero eos et accusamus et iusto odio?</div>
            <div class="accordion__content" id="faq3">
               <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful</p>
            </div>
         </div>
      </div>
   </div>
</div>
@if(Auth::check() && auth()->user()->is_email_verified==1)
@include('frontend.dashboard.common.footer')
@else
@include('frontend.common.footer')
@endif