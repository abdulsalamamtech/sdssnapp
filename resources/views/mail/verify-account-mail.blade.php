{{-- @extends('mail.layouts.mail') --}}
@extends('layouts.mail')

@section('content')
    <!-- Salutation -->
    <h2>Dear {{ $user?->first_name }},</h2>
    <p>
        You are welcome to SDSSN.
        Please verify your account.
    </p>
    <p>
        OTP:
    <div class="button">{{ $otp }}</div>
    </p>
    <p>
        This OTP code expires in 10 minutes.
    </p>
    <p>
        You are welcome on board.
    </p>
    <p>
        Thanks for!
    </p>
@endsection
