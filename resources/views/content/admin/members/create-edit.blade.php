@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')

@if ( isset($user) )
    {!! Breadcrumbs::render('admin/members/edit', $user) !!}
@else
    {!! Breadcrumbs::render('admin/members/create') !!}
@endif

<h2>
    <span>{!! Html::pageIcon('fal fa-users') !!} {{ $title }} Member <small>{{ $user->name ?? '' }}</small></span>
</h2>

<div class="content card">
    <div class="card-body">

        <form action="{{ url('admin/members' . (isset($user) ? '/' . $user->id : '')) }}" method="post" class="validate tabs labels-right authorizenet-payment" id="create_edit_member_form">
            <input type="hidden" name="user[ignore_permissions]" value="1">
            <input type="hidden" name="user[id]" value="{{ $user->id ?? '' }}">
            <input type="hidden" name="payment_methods[dataDescriptor]" id="dataDescriptor" value="">
            <input type="hidden" name="payment_methods[dataValue]" id="dataValue" value="">
            {!! Html::hiddenInput(['method' => isset($user) ? 'put' : 'post']) !!}

            <ul class="nav nav-tabs page-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#member" role="tab">Member</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#company" role="tab">Company</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#subscription" role="tab">Subscription</a>
                </li>
                @if ( isset($user) && !empty($company->customer_profile_id) )
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#payment_methods" role="tab">Payment Methods</a>
                </li>
                @endif
            </ul>

            <div class="tab-content page-tabs-content">
                <div class="tab-pane fade show active" id="member" role="tabpanel">

                    <div class="form-group row {{ isset($user) ? 'hide' : '' }}">
                        <label class="col-form-label col-sm-3">
                            Company
                        </label>
                        <div class="col-sm-9">
                            <select name="user[company_id]" class="form-control" data-fv-notempty="true" {{ isset($user) ? 'disabled' : '' }}>
                                <option value="new">- Create New Company -</option>
                                @foreach ( $companies as $c )
                                    <option value="{{ $c->id }}" {{ isset($user) && $user->company_id == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->users->first()->name }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">First Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="user[first_name]" class="form-control" placeholder="First Name" value="{{ $user->first_name ?? old('first_name') }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="2" data-fv-stringlength-max="80" autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Last Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="user[last_name]" class="form-control" placeholder="Last Name" value="{{ $user->last_name ?? old('last_name') }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="2" data-fv-stringlength-max="80">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Email</label>
                        <div class="col-sm-9">
                            <input type="text" name="user[email]" class="form-control" placeholder="Email" value="{{ $user->email ?? old('email') }}" data-fv-notempty="true" data-fv-emailaddress="true">
                        </div>
                    </div>

                    @if ( isset($user) )
                        <div class="form-group row">
                            <div class="col-sm-9 ml-auto">
                                <div class="abc-checkbox abc-checkbox-primary form-check form-check-inline">
                                    <input type="checkbox" class="toggle-content" id="change_password" data-toggle=".password-fields">
                                    <label class="form-check-label" for="change_password">Change Password</label>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="password-fields ignore-validation {{ isset($user) ? 'hide' : '' }}">

                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Password</label>
                            <div class="col-sm-9">
                                <input type="password" name="user[password]" id="user_password" class="form-control" placeholder="Password" value="" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Confirm Password</label>
                            <div class="col-sm-9">
                                <input type="password" name="user[password_confirmation]" class="form-control" placeholder="Confirm Password" value="" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" data-fv-identical="true" data-fv-identical-field="password" autocomplete="off">
                            </div>
                        </div>

                    </div>

                </div>
                <div class="tab-pane fade" id="company" role="tabpanel">

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="company[name]" class="form-control" placeholder="Company Name" value="{{ $company->name ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Email</label>
                        <div class="col-sm-9">
                            <input type="text" name="company[email]" class="form-control" placeholder="Company Email" data-fv-notempty="true" data-fv-emailaddress="true" value="{{ isset($company) ? $company->email : '' }}">
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="subscription" role="tabpanel">

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Status</label>
                        <div class="col-sm-9 form-control-static">
                            <div class="abc-radio abc-radio-primary form-check-inline">
                                <input type="radio" name="subscription[status]" id="status_active" value="Active" {{ !isset($subscription) || $subscription->status == 'Active' ? 'checked' : '' }}>
                                <label for="status_active" class="form-check-label text-success">Active</label>
                            </div>
                            <div class="abc-radio abc-radio-primary form-check-inline">
                                <input type="radio" name="subscription[status]" id="status_pending_cancellation" value="Pending Cancelation" {{ isset($subscription) && $subscription->status == 'Pending Cancelation' ? 'checked' : '' }}>
                                <label for="status_pending_cancellation" class="form-check-label text-warning">Pending Cancelation</label>
                            </div>
                            <div class="abc-radio abc-radio-primary form-check-inline">
                                <input type="radio" name="subscription[status]" id="status_canceled" value="Canceled" {{ isset($subscription) && $subscription->status == 'Canceled' ? 'checked' : '' }}>
                                <label for="status_canceled" class="form-check-label text-danger">Canceled</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Billing Term</label>
                        <div class="col-sm-9 form-control-static">
                            <div class="abc-radio abc-radio-primary form-check-inline">
                                <input type="radio" name="subscription[term]" id="term_month" value="month" class="toggle-content" data-hide=".free-wrapper" data-show=".paid-wrapper" {{ !isset($subscription) || $subscription->term == 'month' ? 'checked' : '' }}>
                                <label for="term_month" class="form-check-label">Monthly</label>
                            </div>
                            <div class="abc-radio abc-radio-primary form-check-inline">
                                <input type="radio" name="subscription[term]" id="term_year" value="year" class="toggle-content" data-hide=".free-wrapper" data-show=".paid-wrapper" {{ isset($subscription) && $subscription->term == 'year' ? 'checked' : '' }}>
                                <label for="term_year" class="form-check-label">Yearly</label>
                            </div>
                            <div class="abc-radio abc-radio-primary form-check-inline">
                                <input type="radio" name="subscription[term]" id="term_free" value="" class="toggle-content" data-show=".free-wrapper" data-hide=".paid-wrapper" {{ isset($subscription) && empty($subscription->term) ? 'checked' : '' }}>
                                <label for="term_free" class="form-check-label">FREE</label>
                            </div>
                        </div>
                    </div>

                    <div class="free-wrapper {{ isset($subscription) && empty($subscription->term) ? '' : 'hide' }}">
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Expiration Date</label>
                            <div class="col-sm-9">
                                <input type="text" name="subscription[expires_at]" class="form-control datepicker" placeholder="mm/dd/yyyy" data-date-start-date="{{ date('c') }}" value="{{ isset($subscription) && $subscription->expires_at ? $subscription->expires_at->format('m/d/Y') : '' }}">
                                <div class="form-text text-muted font-13">Set date when subscription will expire.  Leave empty for no expiration.</div>
                            </div>
                        </div>
                    </div>

                    <div class="paid-wrapper {{ !isset($subscription) || !empty($subscription->term) ? '' : 'hide' }}" data-ignore-validation="true">

                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Amount</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fal fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="text" name="subscription[amount]" class="form-control" placeholder="0.00" value="{{ $subscription->amount ?? '' }}" data-fv-notempty="true" data-fv-numeric="true">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Billing Date</label>
                            <div class="col-sm-9">
                                <input type="text" name="subscription[next_billing_at]" class="form-control datepicker" placeholder="mm/dd/yyyy" value="{{ isset($subscription) && $subscription->next_billing_at ? $subscription->next_billing_at->format('m/d/Y') : '' }}" data-fv-notempty="true">
                                <div class="form-text text-muted font-13">Set the date when billing should begin. Enter a future date to allow free trial.</div>
                            </div>
                        </div>

                        @if ( !isset($subscription) || empty($company->customer_profile_id) )
                            @include('partials.admin.payment-fields')
                        @endif

                    </div>

                </div>
                <div class="tab-pane fade" id="payment_methods" role="tabpanel">

                    @if ( isset($payment_methods) )
                        @foreach ( $payment_methods as $payment_method )
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3">{{ $payment_method->cc_type }} {!! Html::ccIcon($payment_method->cc_type) !!}</label>
                                <div class="col-sm-9 form-control-static">
                                    <span class="d-inline-block" style="width: 200px;">
                                        XXXX-{{ $payment_method->cc_last4 }}, exp. {{ $payment_method->cc_expiration_month . '/' . $payment_method->cc_expiration_year }}
                                    </span>
                                    <div class="abc-radio abc-radio-primary form-check-inline">
                                        <input type="radio" name="payment_methods[default]" id="payment_method_{{ $payment_method->id }}" value="{{ $payment_method->id }}" {{ $payment_method->is_default ? 'checked' : '' }}>
                                        <label for="payment_method_{{ $payment_method->id }}" class="form-check-label">Default</label>
                                    </div>
                                    @if ( !$payment_method->is_default )
                                        <a href="#" class="delete-payment-method text-danger ml-3" data-id="{{ $payment_method->id }}"><i class="fa fa-trash-alt"></i></a>
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
                            @include('partials.admin.payment-fields')
                        </div>

                    @endif

                </div>
            </div>

            <div class="form-group row mt-5">
                <div class="col-sm-9 ml-auto">
                    <button type="submit" class="btn btn-primary " data-loading-text="<i class='fa fa-circle-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save</button>
                    <a href="#" class="btn btn-secondary close-sidebar">Cancel</a>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection


@push('scripts')

    @if ( !\Request::ajax() )
        {!! Js::authorizeNetConfig() !!}
        <script type="text/javascript" src="{{ env('APP_ENV') == 'production' ? 'https://js.authorize.net/v1/Accept.js' : 'https://jstest.authorize.net/v1/Accept.js' }}" charset="utf-8"></script>
    @endif

    <script src="{{ url('assets/js/modules/authorizenet-payment.js') }}"></script>
    <script type="text/javascript">

        $('select[name="user[company_id]"]').on('change', function() {
            if ( $(this).val() == 'new' ) {
                $('.company-wrapper, .card-body .nav-link[href!="#member"]').show();
                $('.paid-wrapper').removeClass('ignore-validation');
            } else {
                $('.company-wrapper, .card-body .nav-link[href!="#member"]').hide();
                $('.paid-wrapper').addClass('ignore-validation');
            }
        });
        
        $('input[name="user[email]"]').on('blur', function() {
            if ( $('input[name="company[email]"]').val() === '' ) {
                $('input[name="company[email]"]').val($('input[name="user[email]"]').val());
            } 
        });

        $('.add-payment-method').on('click', function(e) {
            e.preventDefault();
            $('.payment-method-wrapper').show();
            $(this).hide();
        });

        $('.delete-payment-method').on('click', function(e) {
            e.preventDefault();
            if ( $(this).closest('.form-group').find('input[type="radio"]').prop('checked') ) {
                Core.notify('danger', 'Cannot delete default payment method');
                return false;
            }
            $('#create_edit_member_form').append('<input type="hidden" name="payment_methods[delete][]" value="' + $(this).attr('data-id') + '">');
            $(this).closest('.form-group').remove();
        });

    </script>
@endpush