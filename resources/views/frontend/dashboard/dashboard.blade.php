@include('frontend.dashboard.common.header')
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
         <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
         </div>
         <?php 
            $websiteCount = DB::table('websites')->where('user_id', Auth::user()->id)->count();
             ?>
         @if($websiteCount >= 1)
         <p></p>
         @else
         <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('addwebsite') }}" class="btn btn-primary btn-round"><i class="fa fa-plus"></i> Add Website</a>
         </div>
         @endif
      </div>
      <div class="row">
         <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
               <div class="card-body">
                  <div class="row align-items-center">
                     <div class="col-icon">
                        <div
                           class="icon-big text-center icon-primary bubble-shadow-small"
                           >
                           <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </div>
                     </div>
                     <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                           <p class="card-category">Backlink(s)</p>
                           <h4 class="card-title">{{ $confirmed_backlinks }}</h4>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
               <div class="card-body">
                  <div class="row align-items-center">
                     <div class="col-icon">
                        <div
                           class="icon-big text-center icon-info bubble-shadow-small"
                           >
                           <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        </div>
                     </div>
                     <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                           <p class="card-category">Outlink(s)</p>
                           <h4 class="card-title">{{ $confirmed_outlinks }}</h4>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
               <div class="card-body">
                  <div class="row align-items-center">
                     <div class="col-icon">
                        <div
                           class="icon-big text-center icon-success bubble-shadow-small"
                           >
                           <i class="fa fa-globe"></i>
                        </div>
                     </div>
                     <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                           <p class="card-category">Website(s) in portal</p>
                           <h4 class="card-title">{{ $total_websites }}</h4>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
               <div class="card-body">
                  <div class="row align-items-center">
                     <div class="col-icon">
                        <div
                           class="icon-big text-center icon-secondary bubble-shadow-small"
                           >
                           <i class="fa fa-link"></i>
                        </div>
                     </div>
                     <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                           <p class="card-category">Connection(s)</p>
                           <h4 class="card-title">{{ $connections }}</h4>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@include('frontend.dashboard.common.footer')