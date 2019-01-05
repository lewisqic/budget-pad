@extends('layouts.email')

@section('title', 'This is your test email')

@section('heading')
   Your Test Email Worked!
@endsection
@section('heading-color', '#4CAF50')

@section('content')

    <p>
        Your test email has been sent successfully, so your mail settings appear to be working properly.
    </p>

@endsection