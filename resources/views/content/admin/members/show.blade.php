@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')

{!! Breadcrumbs::render('admin/members/show', $user) !!}

<div class="float-right">
    @if ( has_permission('admin.members.edit') )
    <a href="{{ url('admin/members/' . $user->id . '/edit?_ajax=false&_redir=' . urlencode(url('admin/members/' . $user->id))) }}" class="btn btn-primary open-sidebar"><i class="fa fa-edit"></i> Edit</a>
    @endif
    @if ( has_permission('admin.members.destroy') )
    <form action="{{ url('admin/members/' . $user->id) }}" method="post" class="validate d-inline ml-2" id="delete_form">
        {!! \Html::hiddenInput(['method' => 'delete']) !!}
        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
    </form>
    @endif
</div>

<h2>
    <span>{!! Html::pageIcon('fal fa-users') !!} {{ $user->name }} <small>Member</small></span>
</h2>

<div class="content card">
    <div class="card-body labels-right">


        <ul class="nav nav-tabs hash-tabs page-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#show_details" role="tab">Member</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#show_company" role="tab">Company</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#show_subscription" role="tab">Subscription</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#show_payment_methods" role="tab">Payment Methods</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#show_payment_history" role="tab">Payment History</a>
            </li>
        </ul>

        <div class="tab-content page-tabs-content">
            <div class="tab-pane fade show active" id="show_details" role="tabpanel">

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">First Name:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $user->first_name }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Last Name:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $user->last_name }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Email:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $user->email }}
                    </div>
                </div>

                <br>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Last Login:</label>
                    <div class="col-sm-10 form-control-static">
                        {!! $user->last_login ? $user->last_login->toDayDateTimeString() : '<em>no logins</em>' !!}
                    </div>
                </div>

                @if ( $user->updated_at )
                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Last Updated:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $user->updated_at->toDayDateTimeString() }}
                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Date Created:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $user->created_at->toDayDateTimeString() }}
                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="show_company" role="tabpanel">

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Name:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $company->name }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Email:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $company->email }}
                    </div>
                </div>

                @if ( $company->customer_profile_id )
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Authorize.Net Customer ID:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $company->customer_profile_id }}
                    </div>
                </div>
                @endif

            </div>
            <div class="tab-pane fade" id="show_subscription" role="tabpanel">

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Status:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $subscription->status }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Billing Term:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $subscription->term ? ucwords($subscription->term) : 'FREE' }}
                    </div>
                </div>

                @if ( $subscription->term )

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Amount:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ Format::currency($subscription->amount) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Billing Date:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $subscription->next_billing_at ? $subscription->next_billing_at->format('M j, Y') : '' }}
                        </div>
                    </div>

                @else

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Expiration Date:</label>
                        <div class="col-sm-10 form-control-static">
                            {!! $subscription->expires_at ? $subscription->expires_at->format('M j, Y') : '<em class="text-muted">no expiration</em>' !!}
                        </div>
                    </div>

                @endif

            </div>
            <div class="tab-pane fade" id="show_payment_methods" role="tabpanel">

                @if ( $payment_methods->isEmpty() )
                    <div class="text-center"><em class="text-muted">no payment methods</em></div>
                @else

                    @foreach ( $payment_methods as $payment_method )
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">{{ $payment_method->cc_type }} {!! Html::ccIcon($payment_method->cc_type) !!}</label>
                            <div class="col-sm-10 form-control-static">
                                XXXX-{{ $payment_method->cc_last4 }}, exp. {{ $payment_method->cc_expiration_month . '/' . $payment_method->cc_expiration_year }}
                                @if ( $payment_method->is_default )
                                    <span class="badge badge-primary ml-3">default</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                @endif

            </div>
            <div class="tab-pane fade" id="show_payment_history" role="tabpanel">

                @if ( $payments->isEmpty() )
                    <div class="text-center"><em class="text-muted">no payment history</em></div>
                @else

                    <table class="table table-striped">
                        <thead>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Card</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th></th>
                        </thead>
                        @foreach ( $payments as $payment )

                            <tr>
                                <td>{{ $payment->transaction_id }}</td>
                                <td>{{ Format::currency($payment->amount) }}</td>
                                <td>{!! Html::ccIcon($payment->paymentMethod->cc_type) !!} {{ $payment->paymentMethod->cc_last4 }}</td>
                                <td>{{ $payment->notes }}</td>
                                <td>
                                    <span class="{{ $payment->status == 'Complete' ? 'text-success' : 'text-warning' }}">{{ ucwords($payment->status) }}</span>
                                    <small class="text-muted">{{ $payment->refunded_at ? ' (' . $payment->refunded_at->format('M j') . ')' : '' }}</small>
                                </td>
                                <td>{{ $payment->created_at->toFormattedDateString() }}</td>
                                <td>
                                    @if ( is_null($payment->refunded_at) )
                                        <form action="{{ url('admin/members/refund-payment') }}" method="post" class="validate d-inline">
                                            {!! Html::hiddenInput(['method' => 'post']) !!}
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="hidden" name="id" value="{{ $payment->id }}">
                                            <a href="#" class="btn btn-sm btn-outline-secondary submit-form confirm-click" data-id="cancelRefund" data-title="Issue Refund For {{ Format::currency($payment->amount) }}" data-text="Are you sure you want to refund this payment?" data-button-class="btn-success"><i class="fa fa-undo text-warning"></i> Refund</a>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                    </table>

                @endif

            </div>
        </div>


    </div>

</div>

@endsection

@push('scripts')
    {!! Js::authorizeNetConfig() !!}
    <script type="text/javascript" src="{{ env('APP_ENV') == 'production' ? 'https://js.authorize.net/v1/Accept.js' : 'https://jstest.authorize.net/v1/Accept.js' }}" charset="utf-8"></script>
@endpush