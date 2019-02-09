@extends('layouts.index')

@section('title', 'BudgetPad | Pricing & Sign Up')

@section('content')

    <h1 class="my-7 text-center">Let's get you signed up!</h1>

    <form action="{{ url('signup') }}" method="post" class="validate labels-right stripe-payment" id="signup_form">
        <input type="hidden" name="token" id="stripe_token" value="">
        {!! Html::hiddenInput(['method' => 'post', 'ajax' => true]) !!}

        <div class="row">
            <div class="col-sm-6">

                <div class="form-group row">
                    <label class="col-form-label col-sm-4">First Name</label>
                    <div class="col-sm-8">
                        <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ old('first_name') }}" data-fv-notempty="true" autofocus>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-4">Last Name</label>
                    <div class="col-sm-8">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ old('last_name') }}" data-fv-notempty="true">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-4">Email</label>
                    <div class="col-sm-8">
                        <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="5" data-fv-stringlength-max="64" data-fv-emailaddress="true">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-4">Password</label>
                    <div class="col-sm-8">
                        <input type="password" name="password" class="form-control" placeholder="Password" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-sm-4">Confirm Password</label>
                    <div class="col-sm-8">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" data-fv-identical="true" data-fv-identical-field="password" autocomplete="off">
                    </div>
                </div>


            </div>
            <div class="col-sm-6">
                <div class="ml-5">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="mt-2 mb-3">
                                <span class="mr-2">Plan:</span>
                                <strong class="text-primary">{{ Format::currency($amount) }}</strong><small class="text-dark">/month</small>
                            </h3>
                            @if ( $trial )
                                <h3 class="mt-2 mb-3">
                                    <span class="mr-2">Trial:</span>
                                    <strong class="text-primary">{{ $trial }} Day</strong><small class="text-dark"> free trial</small>
                                </h3>
                                <p class="mb-0 text-muted font-14">
                                    Your card won't be charged until your free trial ends on {{ date('M jS', strtotime('+' . $trial . ' days')) }}.
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 44px;">
                        <img src="{{ url('images/credit-cards.jpg') }}" class="float-right">
                        <label for="exampleInputPassword1">Credit/Debit Card</label>
                        <div id="card_element" style="border-bottom: 1px solid #ced4da; padding-bottom: 5px;">
                            <!-- a Stripe Element will be inserted here. -->
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-sm-4 mx-auto">
                <button type="submit" class="btn btn-lg btn-success btn-block" data-loading-text="<i class='fa fa-circle-notch fa-spin fa-lg'></i>" disabled><i class="fa fa-check"></i> Complete Sign Up</button>
            </div>
        </div>

        <div class="form-group row mt-4 error-wrapper hide">
            <div class="col-sm-4 mx-auto">
                <div class="alert alert-alt alert-danger">
                    <button type="button" class="close" data-hide="error-wrapper" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="fal fa-exclamation-triangle"></i> <span class="error-message"></span>
                </div>
            </div>
        </div>

    </form>


@endsection

@push('scripts')
    {!! Js::stripeConfig() !!}
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ url('assets/js/modules/stripe-payment.js') }}"></script>
    <script>
        // handle form success action
        $(window).on('signup_form.success', function(e, obj) {
            window.location = obj.data.route;
        });
    </script>
@endpush