@include('frontend.common.header')
<!-- <script async src="https://js.stripe.com/v3/pricing-table.js"></script>
<h3 class="text-center mt-5">Select a Subscription Plan</h3>
<stripe-pricing-table
pricing-table-id="prctbl_1QCzkuKrXu1HpmMk8LXwXpMJ"
publishable-key="{{ env('STRIPE_KEY') }}"
customer-email="{{ $email }}"
>
</stripe-pricing-table> -->

<!-- live script --->

<script async src="https://js.stripe.com/v3/pricing-table.js"></script>
<h3 class="text-center mt-5">Select a Subscription Plan</h3>
<stripe-pricing-table pricing-table-id="prctbl_1QDMygKrXu1HpmMkhxC4YS5H"
publishable-key="{{ env('STRIPE_KEY') }}"
customer-email="{{ $email }}"
>
</stripe-pricing-table>

<!-- ends --->
@include('frontend.common.footer')
