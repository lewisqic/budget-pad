@extends('layouts.account')

@section('content')

	{!! Breadcrumbs::render('account/billing/payment-methods') !!}

	<h2>
		<span>{!! Html::pageIcon('fal fa-credit-card') !!} Billing & Subscription</span>
	</h2>

	<div class="content card">
		<div class="card-body">

			<ul class="nav nav-tabs page-tabs" role="tablist">
				<li class="nav-item">
					<a href="{{ url('account/billing/subscription') }}" class="nav-link" role="tab">My Subscription</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" role="tab">Payment Methods</a>
				</li>
				<li class="nav-item">
					<a href="{{ url('account/billing/history') }}" class="nav-link" role="tab">Payment History</a>
				</li>
			</ul>

			<div class="tab-content page-tabs-content">
				<div class="tab-pane show active" role="tabpanel">

					<div class="labels-right">

						<form action="{{ url('account/billing/payment-method-default') }}" method="post" class="validate d-inline" id="payment_method_default_form">
							{!! Html::hiddenInput(['method' => 'post']) !!}
							<input type="hidden" name="payment_method_id" value="">
						</form>

						@foreach ( $payment_methods as $payment_method )
							<div class="form-group row">
								<label class="col-form-label col-sm-3">{{ $payment_method->cc_type }} {!! Html::ccIcon($payment_method->cc_type) !!}</label>
								<div class="col-sm-9 form-control-static">

                                    <span class="d-inline-block" style="width: 200px;">
                                        XXXX-{{ $payment_method->cc_last4 }}, exp. {{ $payment_method->cc_expiration_month . '/' . $payment_method->cc_expiration_year }}
                                    </span>

									<div class="abc-radio abc-radio-primary form-check-inline">
										<input type="radio" name="default_payment_method" id="payment_method_{{ $payment_method->id }}" value="{{ $payment_method->id }}" {{ $payment_method->is_default ? 'checked' : '' }}>
										<label for="payment_method_{{ $payment_method->id }}" class="form-check-label">Default</label>
									</div>

									@if ( !$payment_method->is_default )
									<form action="{{ url('account/billing/payment-method/' . $payment_method->id) }}" method="post" class="d-inline">
										{!! Html::hiddenInput(['method' => 'delete']) !!}
										<a href="#" class="delete-payment-method text-danger submit-form confirm-click ml-3" data-title="Delete Payment Method" data-text="Are you sure you want to delete this payment method?"><i class="fa fa-trash-alt"></i></a>
									</form>
									@endif

								</div>
							</div>
						@endforeach
						<div class="row">
							<div class="col-sm-9 offset-sm-3">
								<a href="#" class="btn btn-sm btn-outline-success add-payment-method"><i class="fa fa-credit-card"></i> Add Payment Method</a>
							</div>
						</div>

						<div class="payment-method-wrapper ignore-validation hide">

							<form action="{{ url('account/billing/payment-method') }}" method="post" class="d-inline validate labels-right stripe-payment" id="add_payment_method">
								<input type="hidden" name="token" id="stripe_token" value="">
								{!! Html::hiddenInput(['method' => 'post']) !!}

								<div class="form-group row">
									<label class="col-form-label col-sm-3">
										Payment Method
									</label>
									<div class="col-sm-4">
										<div id="card_element">
											<!-- a Stripe Element will be inserted here. -->
										</div>
									</div>
                                    <div class="col-sm-5">
										<button type="submit" class="btn btn-sm btn-outline-success mt-1" data-loading-text="<i class='fa fa-circle-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save</button>
									</div>
								</div>
								<div class="form-group row mt-4 error-wrapper hide">
									<div class="col-sm-9 offset-sm-3">
										<div class="alert alert-alt alert-danger">
											<button type="button" class="close" data-hide="error-wrapper" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<i class="fal fa-exclamation-triangle"></i> <span class="error-message"></span>
										</div>
									</div>
								</div>

							</form>

						</div>

					</div>

				</div>
			</div>

		</div>
	</div>

@endsection

@push('scripts')
	{!! Js::stripeConfig() !!}
	<script src="https://js.stripe.com/v3/"></script>
	<script src="{{ url('assets/js/modules/stripe-payment.js') }}"></script>
	<script type="text/javascript">

		$('input[name="default_payment_method"]').on('change', function() {
			$('input[name="payment_method_id"]').val($(this).val());
			$('#payment_method_default_form').submit();
		});

		$('.add-payment-method').on('click', function(e) {
			e.preventDefault();
			$('.payment-method-wrapper').show();
			$(this).hide();
		});

	</script>
@endpush

