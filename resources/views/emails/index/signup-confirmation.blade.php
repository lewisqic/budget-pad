@extends('layouts.email')

@section('title', 'BudgetPad Sign Up Confirmation')

@section('heading', 'Thank You For Signing Up With BudgetPad!')
@section('heading-color', '#4CAF50')

@section('content')

    <p>
        Your BudgetPad account has been setup successfully, you're just about ready to start tracking calls.  Here are the details of your subscription:
    </p>

    <p style="margin-top: 30px;">
        <strong>Subscription Fee:</strong> {{ $amount }}<small>/{{ $term }}</small>
    </p>
    <p>
        <strong>Amount Charged Today:</strong> {{ $amount }}
    </p>
    <p>
        <strong>Next Billing Date:</strong> {{ $next_billing_date }}
    </p>

@endsection