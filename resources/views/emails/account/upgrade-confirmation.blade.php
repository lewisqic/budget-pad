@extends('layouts.email')

@section('title', 'Account Upgrade Confirmation')

@section('heading', 'Thank You For ' . ($type == 'new' ? 'Upgrading to a Paid Account!' : 'Resuming' . ' Your Tellerr Subscription!'))
@section('heading-color', '#4CAF50')

@section('content')

    <p>
        @if ( $type == 'new' )
        Your Tellerr account has been successfully upgraded to a paid account, here are the details of your subscription:
        @else
        Your existing subscription has been resumed successfully, here are the details of your subscription:
        @endif
    </p>

    <p style="margin-top: 30px;">
        <strong>Selected Plan:</strong> {{ $plan }}
    </p>
    <p>
        <strong>Subscription Fee:</strong> {{ $amount }}<small style="color: #777;">/{{ $term }}</small>
    </p>
    <p style="margin-bottom: 30px;">
        <strong>Next Billing Date:</strong> {{ $next_billing_date }} <small style="color: #777;">(your initial payment made today was successful - this is the date of your next subscription payment)</small>
    </p>

    <p>
        <span style="color: #4CAF50;">You can manage your subscription, update payment methods and view your billing history on the <a href="{{ url('account/billing/subscription') }}">My Subscription</a> page within your Tellerr account.</span>
    </p>

@endsection