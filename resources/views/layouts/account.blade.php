<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Account | BudgetPad v18</title>

    <link rel="stylesheet" id="adminly_css" href="{{ url('css/' . $css_file) }}">

</head>
<body class="adminly {{ $adminly_settings['layout']['header_style'] == 'sticky' ? 'allow-fixed-header' : '' }} {{ $adminly_settings['layout']['submenu_style'] == 'dropdown' ? 'submenu-dropdown-only' : '' }}">

<div class="header">
    <div class="bg-primary">
        <div class="container-fluid container-layout">
            <nav class="navbar navbar-expand-lg navbar-dark">

                <a class="navbar-brand" href="{{ url('account') }}"><i class="fas fa-coins"></i> BudgetPad</a>

                <div class="collapse navbar-collapse">

                    <ul class="navbar-nav ml-5 mr-auto">
                        <li class="nav-item dropdown dropdown-icon">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-heart-circle fa-lg"></i></a>
                            <div class="dropdown-menu dropdown-arrow animated fadeInUp favorites">
                                <div class="dropdown-header"><i class="fal fa-heart mr-1"></i> Favorites</div>
                                @foreach ( $adminly_settings['favorites'] ?? [] as $fav )
                                    @if ( !$loop->first )
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    <a href="{{ url($fav['path']) }}" class="dropdown-item"><i class="{{ $fav['icon'] }}"></i> {{ $fav['title'] }} <span class="delete-favorite text-danger"><i class="fal fa-trash-alt"></i></span></a>

                                @endforeach
                                <p class="text-muted {{ !empty($adminly_settings['favorites']) ? 'hide' : '' }}">To add a page to your favorites, hover over the page title then click on the <i class="fas fa-heart-circle"></i> icon.</p>
                            </div>
                        </li>
                        {{--<li class="nav-item dropdown dropdown-icon ml-4">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-bell fa-lg"></i></a>
                            <div class="dropdown-menu dropdown-menu dropdown-arrow animated fadeInUp" style="min-width: 180px;">
                                <div class="dropdown-header"><i class="fal fa-bell mr-1"></i> Notifications</div>
                                <p class="py-2 px-3 mb-0 text-muted"><small>You have <strong>0</strong> notifications.</small></p>
                            </div>
                        </li>--}}
                    </ul>
                    <ul class="navbar-nav mr-5">
                        <li class="nav-item dropdown dropdown-icon">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-question-circle fa-lg"></i></a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-arrow-right animated fadeInUp feedback">
                                <div class="dropdown-header"><i class="fal fa-question mr-1"></i> Help / Feedback</div>
                                <form action="{{ url('account/feedback') }}" method="post" class="validate" id="feedback_form">
                                    {!! Html::hiddenInput(['method' => 'post', 'ajax' => true]) !!}
                                    <textarea name="message" rows="4" class="form-control" placeholder="Enter your comments and/or feedback here..."></textarea>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-sm btn-primary" data-loading-text="<i class='fa fa-circle-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Submit</button>
                                    </div>
                                </form>
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle avatar-wrapper" data-toggle="dropdown">
                                <span class="name">Welcome, {{ $auth_user->first_name }}</span>
                                <span class="avatar">{!! $auth_user->avatar ? '<img src="' . Storage::url($auth_user->avatar) . '">' : '<i class="fal fa-user fa-lg"></i>' !!}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-arrow-right animated fadeInUp">
                                <div class="dropdown-header bg-white text-black">
                                    <strong>{{ $auth_user->name }}</strong><br>{{ $auth_user->email }}
                                </div>
                                <a class="dropdown-item" href="{{ url('account/profile') }}"><i class="fal fa-id-card fa-fw text-primary mr-1"></i> My Profile</a>
                                <a class="dropdown-item" href="{{ url('account/billing/subscription') }}"><i class="fal fa-credit-card fa-fw text-primary mr-1"></i> Billing & Subscription</a>
                                <div class="dropdown-footer">
                                    <a class="dropdown-item" href="{{ url('auth/logout') }}"><i class="far fa-power-off fa-fw text-danger mr-1"></i> Sign Out</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div class="bg-white">
        <div class="container-fluid container-layout">
            <div class="menu-wrapper">
                @php $has_submenu = false; @endphp
                <ul class="menu">

                    {{-- Dashboard --}}
                    <li class="{{ \Request::is('account') ? 'active' : '' }}"><a href="{{ url('account') }}">Dashboard</a></li>

                    {{-- Incomes --}}
                    <li class="{{ \Request::is('account/incomes*') ? 'active' : '' }}"><a href="{{ url('account/incomes') }}">Income</a></li>

                    {{-- Expenses --}}
                    <li class="{{ \Request::is('account/expenses*') ? 'active' : '' }}"><a href="{{ url('account/expenses') }}">Expenses</a></li>

                    {{-- Categories --}}
                    <li class="{{ \Request::is('account/categories*') ? 'active' : '' }}"><a href="{{ url('account/categories') }}">Categories</a></li>

                    {{-- Tags --}}
                    <li class="{{ \Request::is('account/tags*') ? 'active' : '' }}"><a href="{{ url('account/tags') }}">Tags</a></li>


                    {{-- System --}}
                    {{--@if ( \Request::is('account/billing*') || \Request::is('account/settings*') )
                        @php $has_submenu = true; $system_active = 'active'; @endphp
                    @endif--}}
                    {{--<li class="has-submenu {{ $system_active ?? '' }}">
                        <a href="#">System</a>
                        <ul class="submenu dropdown-arrow animated fadeInUp">

                            <li class="{{ \Request::is('account/billing*') ? 'active' : '' }}"><a href="{{ url('account/billing/subscription') }}"><i class="fal fa-credit-card fa-fw"></i> Billing & Subscription</a></li>

                            <li class="{{ \Request::is('account/settings*') ? 'active' : '' }}"><a href="{{ url('account/settings') }}"><i class="fal fa-cogs fa-fw"></i> Settings</a></li>

                        </ul>
                    </li>--}}

                    <li class="button">
                        <a href="{{ url('account/categories/create') }}" class="btn btn-sm btn-outline-secondary open-sidebar"><i class="fa fa-plus-circle"></i> Add Category</a>
                    </li>
                    <li class="button">
                        <a href="{{ url('account/expenses/create') }}" class="btn btn-sm btn-outline-secondary open-sidebar"><i class="fa fa-plus-circle"></i> Add Expense</a>
                    </li>
                    <li class="button">
                        <a href="{{ url('account/incomes/create') }}" class="btn btn-sm btn-outline-secondary open-sidebar"><i class="fa fa-plus-circle"></i> Add Income</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
<div class="submenu-bar-wrapper clearfix">
    <div class="container-fluid container-layout">
        <div class="submenu-bar-inner">
            @if ( $has_submenu )
                <div class="submenu-placeholder"></div>
            @endif
        </div>
    </div>
</div>

<div class="configurator">
    <a href="#" class="handle"><i class="fal fa-cog text-muted"></i></a>
    <div class="inner">
        <form action="{{ url('account/save-configurator') }}" method="POST" id="save_configurator">
            @csrf

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="#layout" class="nav-link active" data-toggle="tab" role="tab">Layout</a>
                </li>
                <li class="nav-item">
                    <a href="#colors" class="nav-link" data-toggle="tab" role="tab">Colors</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="layout" role="tabpanel">

                    <h6>Header Style</h6>

                    <div class="form-check abc-radio abc-radio-primary">
                        <input class="form-check-input" type="radio" name="adminly_settings[layout][header_style]" id="header_style_sticky" value="sticky" {{ $adminly_settings['layout']['header_style'] == 'sticky' ? 'checked' : '' }}>
                        <label class="form-check-label" for="header_style_sticky">Sticky</label>
                    </div>
                    <div class="form-check abc-radio abc-radio-primary">
                        <input class="form-check-input" type="radio" name="adminly_settings[layout][header_style]" id="header_style_normal" value="normal" {{ $adminly_settings['layout']['header_style'] == 'normal' ? 'checked' : '' }}>
                        <label class="form-check-label" for="header_style_normal">Normal</label>
                    </div>

                    <hr>

                    <h6>Submenu Style</h6>

                    <div class="form-check abc-radio abc-radio-primary">
                        <input class="form-check-input" type="radio" name="adminly_settings[layout][submenu_style]" id="submenu_style_bar" value="bar" {{ $adminly_settings['layout']['submenu_style'] == 'bar' ? 'checked' : '' }}>
                        <label class="form-check-label" for="submenu_style_bar">Bar</label>
                    </div>
                    <div class="form-check abc-radio abc-radio-primary">
                        <input class="form-check-input" type="radio" name="adminly_settings[layout][submenu_style]" id="submenu_style_dropdown" value="dropdown" {{ $adminly_settings['layout']['submenu_style'] == 'dropdown' ? 'checked' : '' }}>
                        <label class="form-check-label" for="submenu_style_dropdown">Dropdown</label>
                    </div>


                </div>
                <div class="tab-pane" id="colors" role="tabpanel">

                    @foreach ( \App\Models\User::$adminlyStyles as $style )
                        <div class="color-wrapper">
                            <h6><span class="toggle-colors text-{{ $adminly_settings['colors'][$style] }}">{{ ucwords($style) }} Color <i class="fal fa-angle-right"></i><i class="fal fa-angle-down"></i></span></h6>
                            <div class="color-list clearfix">
                                @foreach ( \App\Models\User::$adminlyColors as $color )
                                    <div class="form-check abc-radio color-{{ $color }}">
                                        <input class="form-check-input" type="radio" name="adminly_settings[colors][{{ $style }}]" id="{{ $style }}_color_{{ $color }}" value="{{ $color }}" {{ $adminly_settings['colors'][$style] == $color ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $style }}_color_{{ $color }}">{{ $color }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if ( !$loop->last )
                            <hr>
                        @endif
                    @endforeach

                </div>
            </div>
            <hr class="mt-4">
            <div class="text-center save-wrapper mb-1">
                <button type="submit" class="btn btn-primary btn-sm btn-block" data-loading-text='<i class="far fa-circle-notch fa-spin"></i> Saving...'><i class="far fa-check"></i> Save Settings</button>
            </div>
            <div class="building-alert-wrapper hide">
                <div class="alert alert-primary font-13 p-2 mt-3">
                    <i class="fal fa-info-circle"></i> Building your new styles can take up to 1 minute, so just hang tight!
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container-fluid container-layout content-wrapper">
    @yield('content')
</div>

<div class="footer">
    <div class="container-fluid text-center">
        Copyright &copy; {{ date('Y') }} BudgetPad
    </div>
</div>

<div id="datepicker-wrapper"></div>

<div class="sidebar-right" id="sidebar-right">
    <div class="cssload-container"><div class="cssload-whirlpool"></div></div>
    <div class="sidebar-wrapper"></div>
</div>
<a href="#" id="open-sidebar"></a>
<a href="#" id="close-sidebar" class="close-sidebar"></a>

{!! Js::config(true) !!}
<script src="{{ url('js/vendor.js') }}"></script>
<script src="{{ url('js/core.js') }}"></script>
<script>
    $(window).on('feedback_form.success', function(e, obj) {
        $('#feedback_form textarea').val('');
    });
</script>
@stack('scripts')
</body>
</html>
