@extends('layouts.auth')

@section('subheading')
    Regain account access by resetting your password.
@endsection

@section('content')

    <h2 class="mb-4">Reset Password</h2>
    <hr>
    <p class="text-muted mb-7 font-14"><i class="fal fa-lock"></i> Submit the secure form below to reset your password.</p>

    <form action="{{ url('auth/reset') }}" method="post" class="validate" id="auth_form">
        <input type="hidden" name="token" value="{{ $token }}">
        {!! Html::hiddenInput(['ajax' => true]) !!}

        <div class="form-group floating">
            <input type="text" name="email" class="form-control" placeholder="Email Address" value="{{ $email }}" readonly>
        </div>

        <div class="form-group floating">
            <input type="password" name="password" class="form-control" placeholder="New Password" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" autocomplete="off" autofocus>
        </div>

        <div class="form-group floating">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" data-fv-identical="true" data-fv-identical-field="password" autocomplete="off">
        </div>

        <div class="form-group mt-6">
            <button type="submit" class="btn btn-primary btn-block" data-loading-text="<i class='fal fa-circle-notch fa-spin'></i> Sending..." data-success-text="<i class='fa fa-check'></i> Success!"><i class="fal fa-check mr-1"></i> Reset Password</button>
        </div>

        <div class="form-group mt-5 font-14">
            <a href="{{ url('auth/login') }}" class="text-muted"><i class="fal fa-arrow-left"></i> Back to login</a>
        </div>

    </form>

@endsection