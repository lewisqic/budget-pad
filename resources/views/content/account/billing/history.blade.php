@extends('layouts.account')

@section('content')

	{!! Breadcrumbs::render('account/billing/history') !!}

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
					<a href="{{ url('account/billing/payment-methods') }}" class="nav-link" role="tab">Payment Methods</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" role="tab">Payment History</a>
				</li>
			</ul>

			<div class="tab-content page-tabs-content">
				<div class="tab-pane show active" role="tabpanel">

					@if ( $payments->isEmpty() )
						<div class="text-center"><em class="text-muted">no payments found</em></div>
					@else

						<table class="table table-striped">
							<thead>
								<tr>
									<th>Charge ID</th>
									<th>Amount</th>
									<th>Card</th>
									<th>Notes</th>
									<th>Status</th>
									<th>Date</th>
								</tr>
							</thead>
							@foreach ( $payments as $payment )

								<tr>
									<td>{{ $payment->stripe_charge_id }}</td>
									<td>{{ Format::currency($payment->amount) }}</td>
									<td>{!! Html::ccIcon($payment->paymentMethod->cc_type) !!} {{ $payment->paymentMethod->cc_last4 }}</td>
									<td>{{ $payment->notes }}</td>
									<td>
										<span class="{{ $payment->status == 'Complete' ? 'text-success' : 'text-warning' }}">{{ ucwords($payment->status) }}</span>
										<small class="text-muted">{{ $payment->refunded_at ? ' (' . $payment->refunded_at->format('M j') . ')' : '' }}</small>
									</td>
									<td>{{ $payment->created_at->toFormattedDateString() }}</td>
								</tr>

							@endforeach
						</table>

					@endif

				</div>
			</div>

		</div>
	</div>


@endsection

