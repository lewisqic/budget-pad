@extends('layouts.auth')

@section('subheading')
    Forgot your password? No worries, we can help you with that.
@endsection

@section('content')

    <h2 class="mb-4">Forgot Password</h2>
    <hr>
    <p class="text-muted mb-7 font-14"><i class="fal fa-lock"></i> Submit the secure form below and we'll email you a link allowing you to reset your password.</p>

    <form action="{{ url('auth/forgot') }}" method="post" class="validate" id="auth_form">
        {!! Html::hiddenInput(['ajax' => true]) !!}

        <div class="form-group floating">
            <input type="text" name="email" class="form-control" placeholder="Email Address" value="" data-fv-notempty="true" data-fv-emailaddress="false" autocomplete="off" autofocus>
        </div>

        <div class="form-group mt-6">
            <button type="submit" class="btn btn-primary btn-block" data-loading-text="<i class='fal fa-circle-notch fa-spin'></i> Sending..." data-success-text="<i class='fa fa-check'></i> Success!"><i class="fal fa-envelope mr-1"></i> Send Password Reset Link</button>
        </div>

        <div class="form-group mt-5 font-14">
            <a href="{{ url('auth/login') }}" class="text-muted"><i class="fal fa-arrow-left"></i> Back to login</a>
        </div>

    </form>

@endsection