@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/billing/subscription') !!}

    <h2>
        <span>{!! Html::pageIcon('fal fa-credit-card') !!} Billing & Subscription</span>
    </h2>

    <div class="content card">
        <div class="card-body">

            <ul class="nav nav-tabs page-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" role="tab">My Subscription</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('account/billing/payment-methods') }}" class="nav-link" role="tab">Payment Methods</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('account/billing/history') }}" class="nav-link" role="tab">Payment History</a>
                </li>
            </ul>

            <div class="tab-content page-tabs-content">
                <div class="tab-pane show active" role="tabpanel">

                    <div class="labels-right">

                        @if ( $subscription->status == 'Pending Cancelation' )
                            <div class="alert alert-alt alert-warning">
                                <strong class="font-18"><i class="fa fa-info-circle"></i> Your subscription is <em>Pending Cancelation</em>, you will not be charged again.</strong><br>
                                You still have full access until the end of your current billing cycle on <strong>{{ $subscription->next_billing_at->toFormattedDateString() }}</strong>, at which point your subscription will be canceled.
                            </div>
                        @endif

                        @if ( $subscription->status == 'Canceled' )
                            <div class="alert alert-alt alert-danger">
                                <strong class="font-18"><i class="fa fa-info-circle"></i> Your subscription has been <em>Canceled</em>.</strong>
                                <p class="mt-1 mb-0">
                                    Status notes: <em>{{ $subscription->status_notes }}</em><br>
                                    <small>If you would like to resume your subscription, please contact us.</small>
                                </p>
                            </div>
                        @endif

                        @if ( $subscription->amount )
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3">Subscription Fee:</label>
                                <div class="col-sm-9 form-control-static">
                                    {{ Format::currency($subscription->amount) }}<small>/{{ $subscription->term }}</small>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Subscription Status:</label>
                            <div class="col-sm-9 form-control-static">
                                {{ ucwords($subscription->status) }}
                                {!! is_null($subscription->amount) ? ' <small class="text-muted">(free account)</small>' : '' !!}
                                @if ( $subscription->amount )
                                    @if ( $subscription->status == 'Active' )
                                        <form action="{{ url('account/billing/cancel-subscription') }}" method="post" class="validate d-inline ml-5">
                                            {!! Html::hiddenInput(['method' => 'post']) !!}
                                            <button type="submit" class="btn btn-sm btn-outline-danger confirm-click" data-text="By canceling your subscription, you will retain access until your next billing date. You will not be charged again." data-button-text="Yes, cancel my subscription"><i class="fa fa-ban"></i> Cancel Subscription</button>
                                        </form>
                                    @endif
                                    @if ( $subscription->status == 'Pending Cancelation' )
                                        <form action="{{ url('account/billing/resume-subscription') }}" method="post" class="validate d-inline ml-5">
                                            {!! Html::hiddenInput(['method' => 'post']) !!}
                                            <button type="submit" class="btn btn-sm btn-outline-success confirm-click" data-title="Great! Here are the details..." data-text="By resuming your subscription, you will retain full account access.  Billing will resume and you will be charged {{ Format::currency($subscription->amount) }} on your next billing date." data-type="info" data-button-text="Resume Subscription" data-button-class="btn-success"><i class="fa fa-undo"></i> Resume Subscription</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>

                        @if ( $subscription->next_billing_at && !$subscription->canceled_at )
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3">{{ $subscription->status == 'Pending Cancelation' ? 'Last Access Date' : 'Next Billing Date' }}:</label>
                                <div class="col-sm-9 form-control-static">
                                    {{ $subscription->status == 'Pending Cancelation' ? $subscription->next_billing_at->subDay()->toFormattedDateString() : $subscription->next_billing_at->toFormattedDateString() }}
                                </div>
                            </div>
                        @endif

                        @if ( $subscription->canceled_at )
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3">Cancelation Date:</label>
                                <div class="col-sm-9 form-control-static">
                                    {{ $subscription->canceled_at->toFormattedDateString() }}
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Sign Up Date:</label>
                            <div class="col-sm-9 form-control-static">
                                {{ $subscription->created_at->toFormattedDateString() }}
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
