{{-- @extends('mail.layouts.mail') --}}
@extends('layouts.mail')

@section('content')
    <!-- Salutation -->
    <h2>Dear {{ $user?->first_name }},</h2>
    <p>
        You requested for an OTP because you forgot your password.
        Use the below OTP to setup a new password.
    </p>
    <p>
        OTP:
    <div class="button">{{ $otp }}</div>
    <p>
        If you didn't request for it ignore this email and no action will take place.
    </p>
    </p>
    <p>
        Thanks for!
    </p>
@endsection
