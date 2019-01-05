@extends('layouts.email')

@section('title', 'Account Cancelation Confirmation')

@section('heading')
    We're Sad to See You Go :(
@endsection
@section('heading-color', '#FF9800')

@section('content')

    <p>
        Your BudgetPad account has been successfully canceled, effective immediately.
    </p>

    <p style="margin: 30px 0;">
        <strong>Cancelation Date:</strong> {{ $cancelation_date }}
    </p>

    <p>
        <strong style="color: #4CAF50;">Feel free to come back anytime, our door is always open!</strong>
    </p>

@endsection