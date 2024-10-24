@include('frontend.dashboard.common.header')
<div class="container">
   <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2">
         <div>
            <h3 class="fw-bold mb-3">Subscription Details</h3>
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <i class="fa fa-check"></i> {{ session('success') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
         </div>
      </div>
   </div>
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="card card-round">
               <div class="card-body p-4">
                  <table id="sd" class="table" style="width:100%">
                     <thead>
                        <tr>
                           <th>Subscription ID</th>
                           <th>Status</th>
                           <th>Current Period Start</th>
                           <th>Current Period End</th>
                           <th>Plan Name</th>
                           <th>Amount</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>{{ $subscription->id }}</td>
                           <td>{{ ucfirst($subscription->status) }}</td>
                           <td>{{ \Carbon\Carbon::createFromTimestamp($subscription->current_period_start)->toFormattedDateString() }}</td>
                           <td>{{ \Carbon\Carbon::createFromTimestamp($subscription->current_period_end)->toFormattedDateString() }}</td>

                           <td>
                              @if($subscription->status == 'trialing')
                              <span>Trail Period</span>
                              @else
                              {{ $subscription->items->data[0]->plan->nickname }}
                              @endif
                           </td>
                           <td>{{ number_format($subscription->items->data[0]->plan->amount / 100, 2) }} {{ strtoupper($subscription->items->data[0]->plan->currency) }}</td>
                           <td>
                              <!-- Cancel or Resume Subscription Buttons -->
                              @if($subscription->status == 'active' && !$subscription->cancel_at_period_end)
                              <!-- If the subscription is active, show a cancel button -->
                              <form action="{{ route('subscription.cancel', $subscription->id) }}" method="POST">
                                 @csrf
                                 <button type="submit">Cancel Subscription</button>
                              </form>
                              @elseif($subscription->status == 'canceled' || $subscription->cancel_at_period_end)
                              <!-- If the subscription is canceled or set to cancel at the end of the period, show a resume button -->
                              <form action="{{ route('subscription.resume', $subscription->id) }}" method="POST">
                                 @csrf
                                 <button type="submit">Restart Subscription</button>
                              </form>
                              @elseif($subscription->status == 'trialing')
                              <span class="btn btn-info">Trail</span>
                              @endif
                           </td>
                        </tr>
                     <tbody>
                     <tfoot>
                        <tr>
                           <th>Subscription ID</th>
                           <th>Status</th>
                           <th>Current Period Start</th>
                           <th>Current Period End</th>
                           <th>Plan Name</th>
                           <th>Amount</th>
                           <th>Action</th>
                        </tr>
                     </tfoot>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@include('frontend.dashboard.common.footer')