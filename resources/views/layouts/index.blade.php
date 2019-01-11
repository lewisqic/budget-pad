<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BudgetPad | Budget Tracking Software</title>

    <link rel="stylesheet" href="{{ url('css/core.css') }}">

</head>

<body class="frontend">


<div class="header">

    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a href="{{ url('') }}" class="navbar-brand">BudgetPad Logo Here</a>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mr-3">
                        <a href="{{ url('signup') }}" class="nav-link">Pricing/Signup</a>
                    </li>
                    @if ( $user = \Auth::check() && !empty($user) )
                        <li class="nav-item dropdown ml-3">
                            <a href="#" class="nav-link dropdown-toggle" id="user_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Welcome, {{ $user->first_name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="user_menu">
                                <a href="{{ url($user->type == 1 ? 'admin' : 'account') }}" class="dropdown-item"><i class="fa fa-user text-primary"></i> My Account</a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ url('auth/logout') }}" class="dropdown-item text-danger"><i class="fa fa-power-off"></i> Logout</a>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ url('auth/login') }}" class="nav-link">Login</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

</div>

@if ( Request::path() == '/' )

    <div class="container">{!! \Msg::show() !!}</div>

    <div class="home-wrapper">
        @yield('content')
    </div>

@else

    <div class="container mt-5">

        @if ( isset($errors) && count($errors) > 0 )
            <div class="alert alert-alt alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                <strong>Oops!</strong> We encountered the following error(s) when trying to process your request:
                <ul>
                    @foreach ( $errors->all() as $error )
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @else
            {!! \Msg::show() !!}
        @endif

        @yield('content')

    </div>

@endif

<div class="footer mt-7">

    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                Copyright &copy; {{ date('Y') }} BudgetPad
            </div>
        </div>
    </div>

</div>


{!! Js::config(true) !!}
<script src="{{ url('js/vendor.js') }}"></script>
<script src="{{ url('js/core.js') }}"></script>
@stack('scripts')

</body>
</html>