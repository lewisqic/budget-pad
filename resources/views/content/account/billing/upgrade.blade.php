@extends('layouts.account')

@section('content')

	{!! Breadcrumbs::render('account/billing/upgrade') !!}

	<h1>{{ $subscription->status == 'trial' ? 'Complete' : 'Resume' }} Your Subscription</h1>

	<div class="page-content container-fluid">

		<form action="{{ url('account/billing/upgrade') }}" method="post" class="labels-right mb20 payment-form" id="upgrade_form">
			<input type="hidden" name="token" value="">
			{!! Html::hiddenInput(['method' => 'post']) !!}

			<div class="row mt-3">
				<div class="col-sm-6">

					<div class="form-group row mb-4">
						<div class="col-sm-9 ml-auto">
							<h3 class="upgrade-step">
								<span class="number-badge">1</span>
								Choose Your Plan
							</h3>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-form-label col-sm-3">Plan</label>
						<div class="col-sm-9 font-18">
							<select name="plan_id" class="form-control">
								@foreach ( $plans as $plan )
									<option value="{{ $plan->id }}" data-price-month="{{ Format::currency($plan->price_month) }}" data-price-year="{{ Format::currency($plan->price_year) }}" {{ $plan->id == $default_plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
								@endforeach
							</select>
							<div class="form-text font-13">
								Select the plan that you would like to subscribe to.  <a href="{{ url('pricing') }}" target="_blank">Compare Plans <i class="fa fa-external-link"></i></a>
							</div>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-form-label col-sm-3">Billing Period</label>
						<div class="col-sm-9 font-18 mt-1">
							<div class="abc-radio abc-radio-primary radio-inline">
								<input type="radio" name="term" id="term_monthly" value="month" data-term="month" data-price="{{ Format::currency($default_plan->price_month) }}" {{ is_null($subscription->term) || $subscription->term == 'month' ? 'checked' : '' }}>
								<label for="term_monthly"><strong class="text-success plan-price" data-term="month">{{ Format::currency($default_plan->price_month) }}</strong>/month</label>
							</div>
							<div class="abc-radio abc-radio-primary radio-inline ml20">
								<input type="radio" name="term" id="term_yearly" value="year" data-term="year" data-price="{{ Format::currency($default_plan->price_year) }}" {{ $subscription->term == 'year' ? 'checked' : '' }}>
								<label for="term_yearly"><strong class="text-success plan-price" data-term="year">{{ Format::currency($default_plan->price_year) }}</strong>/year</label>
							</div>
							<div class="form-text font-13">
								Select your preferred billing period. <span class="text-muted">(In it for the long haul? Go all in with annual billing and save 20% off the monthly rate!)</span>
							</div>
						</div>
					</div>

					<div class="form-group row mt-4">
						<div class="col-sm-9 ml-auto">
							<div class="alert alert-alt alert-primary mt-1 pr-0">

								<h4 class="mt-2 mb-3">You're Almost There!</h4>

								<ul class="mt-2 pl-0 font-14 list-style-none text-dark">
									<li><i class="fa fa-check text-success"></i> 100% satisfaction guaranteed.</li>
									<li><i class="fa fa-check text-success"></i> No hidden fees, no contracts, no surprises.</li>
									<li><i class="fa fa-check text-success"></i> Cancel any time, for any reason, no questions asked!</li>
									<li><i class="fa fa-check text-success"></i> PCI SAQ-A compliant and 2048-bit encryption security.</li>
								</ul>

							</div>
						</div>
					</div>


				</div>
				<div class="col-sm-6">

					<div class="form-group row mb-4">
						<div class="col-sm-9 ml-auto">
							<img src="{{ url('assets/images/credit-cards.jpg') }}" class="float-right mt-2">
							<h3 class="upgrade-step">
								<span class="number-badge">2</span>
								Enter Payment
							</h3>
						</div>
					</div>

					@if ( !empty($payment_methods) )

						<div class="form-group row mt15neg">
							<label class="col-form-label col-sm-3"></label>
							<div class="col-sm-9">
								<div class="abc-radio abc-radio-primary radio-inline">
									<input type="radio" name="payment_method" id="existing" value="existing" checked>
									<label for="existing">Use Existing Card</label>
								</div>
								<div class="abc-radio abc-radio-primary radio-inline">
									<input type="radio" name="payment_method" id="new" value="new">
									<label for="new">Enter New Card</label>
								</div>
							</div>
						</div>

						<div class="payment-methods-wrapper" data-method="existing">
							<div class="form-group row">
								<label class="col-form-label col-sm-3">Payment Method</label>
								<div class="col-sm-9">
									<select name="company_payment_method_id" class="form-control">
										@foreach ( $payment_methods as $pm )
											<option value="{{ $pm->id }}" {{ $pm->is_default ? 'selected' : '' }}>
												{{ $pm->cc_type }}, XXXX-{{ $pm->cc_last4 }}, {{ $pm->cc_expiration_month . '/' . $pm->cc_expiration_year }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>

					@endif

					<div class="payment-methods-wrapper {{ $payment_methods ? 'display-none' : '' }}" data-method="new">

						<div class="form-group row">
							<label class="col-form-label col-sm-3"  for="card_element">Credit/Debit Card</label>
							<div class="col-sm-9">
								<div id="card_element">
									<!-- a Stripe Element will be inserted here. -->
								</div>
							</div>
						</div>

					</div>

					<div class="form-group row my-4">
						<div class="col-sm-9 ml-auto">
							<div class="card">
								<div class="card-header font-18">

									<div class="row mb20">
										<div class="col-sm-6 text-right">
											Total Due Today:
										</div>
										<div class="col-sm-6">
											<strong class="text-success term-price">{{ Format::currency($default_plan->price_month) }}</strong>
										</div>
									</div>
									<div class="row mb10">
										<div class="col-sm-6 text-right">
											Next Billing Date:
										</div>
										<div class="col-sm-6">
											<strong class="text-success term-term" data-term="month">{{ Carbon::now()->addMonth()->toFormattedDateString() }}</strong>
											<strong class="text-success term-term display-none" data-term="year">{{ Carbon::now()->addYear()->toFormattedDateString() }}</strong>
										</div>
									</div>
									<small class="text-muted font-13">
										Your card will be charged <span class="term-price">{{ Format::currency($default_plan->price_month) }}</span> each
										<span class="term-term" data-term="month">month</span>
										<span class="term-term display-none" data-term="year">year</span>
										while your subscription is active.  Feel free to cancel at anytime!
									</small>

								</div>
							</div>
						</div>
					</div>

					{{--<div class="form-group row">
						<div class="col-sm-9 ml-auto">
							<a href="#" class="text-muted font-14 toggle-content" data-toggle=".coupon-wrapper">Do you have a coupon code?</a>
						</div>
					</div>

					<div class="form-group row coupon-wrapper mb-4">
						<div class="col-sm-9 ml-auto">
							<div class="input-group">
								<input type="text" class="form-control" name="coupon" placeholder="Coupon Code...">
								<span class="input-group-btn">
									<button type="submit" class="btn btn-primary apply-coupon">Apply</button>
									<button type="submit" class="btn btn-primary remove-coupon display-none">Remove</button>
								</span>
							</div>
							<div class="form-text font-13 coupon-message"></div>
						</div>
					</div>--}}

					<div class="form-group row mt-4">
						<div class="col-sm-9 ml-auto">
							<button type="submit" class="btn btn-lg btn-success btn-block submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>" disabled><i class="fa fa-check"></i> Submit Payment</button>
						</div>
					</div>

					<div class="form-group row mt10 error-wrapper display-none">
						<div class="col-sm-9 ml-auto">
							<div class="alert alert-alt alert-danger">
								<button type="button" class="close" data-hide="error-wrapper" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<div class="mb10">
									<strong><i class="fa fa-exclamation-triangle"></i> Oh snap! Something went wrong...</strong>
								</div>
								<em class="error-message"></em>
							</div>
						</div>
					</div>

				</div>
			</div>

		</form>

	</div>

@endsection

@push('scripts')
{!! Js::stripeConfig() !!}
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ url('assets/js/modules/upgrade.js') }}"></script>
@endpush