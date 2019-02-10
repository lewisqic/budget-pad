<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Auth | BudgetPad v18</title>

    <link rel="stylesheet" href="{{ url('css/core.css') }}">

</head>
<body class="auth">

<div class="container-fluid">
    <div class="row bg-wrapper">
        <div class="col-sm-8">

            <div class="auth-content">

                <div class="version-wrapper">
                    <a href="{{ url('') }}"><span class="square">BP</span></a>
                    <span class="version">v1.0.0</span>
                </div>

                <h2>
                    Welcome to BudgetPad
                </h2>

                <p class="mt-3">
                    @yield('subheading')
                </p>

            </div>

        </div>
        <div class="col-sm-4 form-wrapper">
            <div class="form-inner">
                @yield('content')
                <div class="danger-wrapper mt-5 hide">
                    <div class="alert alert-alt alert-danger">
                        <button type="button" class="close" data-hide="danger-wrapper"><span>&times;</span></button>
                        <i class="fa fa-exclamation-circle"></i> <span class="message"></span>
                    </div>
                </div>
                <div class="success-wrapper mt-5 hide">
                    <div class="alert alert-alt alert-success">
                        <button type="button" class="close" data-hide="success-wrapper"><span>&times;</span></button>
                        <i class="fa fa-check"></i> <span class="message"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{!! Js::config(true) !!}
<script src="{{ url('js/vendor.js') }}"></script>
<script src="{{ url('js/core.js') }}"></script>
<script src="{{ url('assets/js/modules/auth.js') }}"></script>
@stack('scripts')
</body>
</html>
