@component('mail::message')
## Feedback from {{ $user->name }} ({{ $user->email }})

Message: {{ $message }}

@endcomponent