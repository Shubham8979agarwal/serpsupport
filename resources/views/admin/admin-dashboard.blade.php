@include('admin.common.header')
<div class="container">
	<div class="page-inner">
	  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2">
	     <div>
	        <h3 class="fw-bold mb-3">Admin Dashboard</h3>
	     </div>
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
	                       <i class="fas fa-users"></i>
	                    </div>
	                 </div>
	                 <div class="col col-stats ms-3 ms-sm-0">
	                    <div class="numbers">
	                       <p class="card-category">Total Users</p>
	                       <h4 class="card-title">{{ $count_all_users }}</h4>
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
	                       <i class="fas fa-globe"></i>
	                    </div>
	                 </div>
	                 <div class="col col-stats ms-3 ms-sm-0">
	                    <div class="numbers">
	                       <p class="card-category">Total Websites</p>
	                       <h4 class="card-title">{{ $count_total_websites }}</h4>
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
	                       <i class="fa fa-money"></i>
	                    </div>
	                 </div>
	                 <div class="col col-stats ms-3 ms-sm-0">
	                    <div class="numbers">
	                       <p class="card-category">Total Plans</p>
	                       <h4 class="card-title">{{ $count_total_plans }}</h4>
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
	                       <p class="card-category">Total Connections</p>
	                       <h4 class="card-title">{{ $count_total_connections }}</h4>
	                    </div>
	                 </div>
	              </div>
	           </div>
	        </div>
	     </div>
	  </div>
	</div>
 </div>
@include('admin.common.footer')