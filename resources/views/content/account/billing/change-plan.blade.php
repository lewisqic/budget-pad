@extends('layouts.account')

@section('content')

	{!! Breadcrumbs::render('account/billing/change-plan') !!}

	<h1>Change Subscription Plan</h1>

	<div class="page-content container-fluid">


		<form action="{{ url('account/billing/change-plan') }}" method="post" class="labels-right mb-5" id="change_plan_form">
			<input type="hidden" class="current-amount" value="{{ $subscription->amount }}">
			{!! Html::hiddenInput(['method' => 'post']) !!}

			<div class="row mt-3">
				<div class="col-sm-6">

					<div class="form-group row mb-4">
						<div class="col-sm-9 ml-auto">
							<h3 class="upgrade-step">
								<span class="number-badge">1</span>
								Choose a New Plan
							</h3>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-form-label col-sm-3">Current Plan</label>
						<div class="col-sm-9 form-control-static">
							{{ $subscription->plan_name }} <small class="text-muted">({{ Format::currency($subscription->amount) }}/{{ $subscription->term }})</small>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-form-label col-sm-3">New Plan</label>
						<div class="col-sm-9">
							<select name="plan_id" class="form-control new-plan">
								<option value="">-- select plan --</option>
								@foreach ( $plans as $plan )
									<option value="{{ $plan->id }}" data-price="{{ Format::currency($plan->{'price_' . $subscription->term}) }}" {{ $current_plan && $current_plan-> id == $plan->id ? 'disabled' : '' }}>{{ $plan->name }} {{ $current_plan && $current_plan-> id == $plan->id ? '(this is your current plan)' : '(' . Format::currency($plan->{'price_' . $subscription->term}) . '/' . $subscription->term . ')' }}</option>
								@endforeach
							</select>
							<div class="form-text font-13">
								Select the new plan that you would like to subscribe to.  <a href="{{ url('pricing') }}" target="_blank">Compare Plans <i class="fa fa-external-link"></i></a>
							</div>
						</div>
					</div>


					<div class="col-sm-9 ml-auto mt-4 display-none payment-required">
						<div class="alert alert-info alert-alt">
							<strong><i class="fa fa-info-circle"></i> Heads Up!</strong><br>
							Your current subscription plan has a {{ $subscription->term }}ly fee of <strong>{{ Format::currency($subscription->amount) }}</strong>. The new subscription plan you have selected has a {{ $subscription->term }}ly fee of <strong class="new-plan-price"></strong>. In order to upgrade your subscription plan immediately, payment is due for the difference between subscription plan fees.
						</div>
					</div>


				</div>
				<div class="col-sm-6">

					<div class="payment-required display-none">

						<div class="form-group row mb-4">
							<div class="col-sm-9 ml-auto">
								<img src="{{ url('assets/images/credit-cards.jpg') }}" class="float-right mt-2">
								<h3 class="upgrade-step">
									<span class="number-badge">2</span>
									Select Payment
								</h3>
							</div>
						</div>

						@if ( !empty($payment_methods) )

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
										<div class="form-text text-muted font-13">If you'd like to pay with a different card, please add it within your <a href="{{ url('account/billing/payment-methods') }}">payment methods</a> first.</div>
									</div>
								</div>
							</div>

						@else

							<div class="alert alert-alt alert-warning">Please add a valid payment method before changing your subscription plan.</div>

						@endif

						<div class="form-group row my-4">
							<div class="col-sm-9 ml-auto">
								<div class="card">
									<div class="card-header font-18">

										<div class="row mb20">
											<div class="col-sm-6 text-right">
												Total Due Today:
											</div>
											<div class="col-sm-6">
												<strong class="text-success total-due-today"></strong>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>

					</div>

					<div class="no-payment-required display-none">

						<div class="form-group row mb-4">
							<div class="col-sm-9 ml-auto">
								<h3 class="upgrade-step">
									<span class="number-badge">2</span>
									Review & Submit
								</h3>
							</div>
						</div>

						<div class="col-sm-9 ml-auto">
							<div class="alert alert-info alert-alt">
								<strong><i class="fa fa-info-circle"></i> Heads Up!</strong><br>
								Since you are <em>downgrading</em> your subscription plan, we will keep you on your current plan until your next billing date on <strong>{{ $subscription->next_billing_at->toFormattedDateString() }}</strong>. At that point, the new plan that you have selected will go into effect and your subscription fee will be adjusted to <strong class="new-plan-price"></strong><small>/{{ $subscription->term }}</small>.
							</div>
						</div>

					</div>

					@if ( !empty($payment_methods) )

						<div class="form-group row mt-5 display-none payment-required no-payment-required">
							<div class="col-sm-9 ml-auto">
								<button type="submit" class="btn btn-lg btn-success btn-block submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Submit Plan Change</button>
							</div>
						</div>

					@endif

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