@extends('layouts.auth')

@section('subheading')
    To begin, please sign in on the right <i class="fal fa-long-arrow-alt-right"></i>
@endsection

@section('content')

    <h2 class="mb-4">Sign In</h2>
    <hr>
    <p class="text-muted mb-7 font-14"><i class="fal fa-lock"></i> Login secured with 256-bit encryption.</p>

    <form action="{{ url('auth/login') }}" method="post" class="validate" id="auth_form">
        {!! Html::hiddenInput(['ajax' => true]) !!}

        <div class="form-group floating">
            <input type="text" name="email" class="form-control" placeholder="Email Address" value="" data-fv-notempty="true" data-fv-emailaddress="true" autocomplete="off" autofocus>
        </div>

        <div class="form-group floating">
            <input type="password" name="password" class="form-control" placeholder="Password" data-fv-notempty="true" autocomplete="off">
        </div>

        <div class="form-group mt-4 font-14">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check abc-checkbox abc-checkbox-primary">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember" value="1">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ url('auth/forgot') }}">Forgot Password?</a>
                </div>
            </div>
        </div>

        <div class="form-group mt-6">
            <button type="submit" class="btn btn-primary btn-block" data-loading-text="<i class='fal fa-circle-notch fa-spin'></i> Signing in..." data-success-text="<i class='fa fa-check'></i> Success!"><i class="fal fa-sign-in mr-1"></i> Sign In</button>
        </div>

    </form>

@endsection