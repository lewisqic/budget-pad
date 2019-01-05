@extends('layouts.email')

@section('title', 'Plan Change Confirmation')

@section('heading', 'Success! Your Plan Change Has Been ' . ($type == 'upgrade' ? 'Completed' : 'Scheduled') . '!')
@section('heading-color', '#4CAF50')

@section('content')

    <p>
        Your Tellerr subscription plan has been successfully {{ $type == 'upgrade' ? 'changed' : 'scheduled' }}, here are the details of your new subscription plan:
    </p>

    <p style="margin-top: 30px;">
        <strong>Selected Plan:</strong> {{ $plan }}
    </p>
    <p style="margin-bottom: {{ $type == 'upgrade' ? '30px' : '' }};">
        <strong>Subscription Fee:</strong> {{ $amount }}<small style="color: #777;">/{{ $term }}</small>
    </p>
    @if ( $type == 'downgrade' )
    <p style="margin-bottom: 30px;">
        <strong>Scheduled Change Date:</strong> {{ $next_billing_date }} <small style="color: #777;">(Since you are <em>downgrading</em> your subscription plan, we're going to keep you on your current plan until this date. At that point, the new plan that you have selected will go into effect and your subscription fee will be adjusted accordingly.)</small>
    </p>
    @endif

    <p>
        <span style="color: #4CAF50;">You can manage your subscription, update payment methods and view your billing history on the <a href="{{ url('account/billing/subscription') }}">My Subscription</a> page within your Tellerr account.</span>
    </p>

@endsection