@extends('layouts.email')

@section('title', 'Subscription Payment Failed')

@section('heading', 'Oh Snap! Your Subscription Payment Failed.')
@section('heading-color', '#F44336')

@section('content')

    <p>
        We tried to process your subscription fee payment, but the payment was unsuccessful.  Here are the details of the attempted transaction:
    </p>

    <p style="margin-top: 30px;">
        <strong>Amount:</strong> {{ $amount }}
    </p>
    <p style="margin-bottom: 30px;">
        <strong>Date:</strong> {{ $date }}
    </p>

    <p>
        <strong style="color: #F44336;">Due to the payment failure, your BudgetPad subscription has been canceled.</strong>
    </p>

    {{--<p>
        <span style="color: #4CAF50;">To resume your subscription, please visit the <a href="{{ url('account/billing/subscription') }}">My Subscription</a> page within your BudgetPad account where you can submit payment for the overdue subscription fee.</span>
    </p>--}}

@endsection