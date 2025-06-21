{{-- <div>
    <!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
</div> --}}

@extends('layouts.mail')

@section('content')
    <!-- Salutation -->
    <h2>Dear {{ $certificationRequest?->full_name }},</h2>
    <p>
        Thanks for submitting your certification request! for
        {{ $certificationRequest?->certification->title }}
        {{ $certificationRequest?->certification->type }}
        certification.
    </p>
    <p>
        We want to let you know that we've received it and will be reviewing it within 3 working days.
    </p>
    <p>
        You'll get another email from us once the review is complete.
        In the meantime, you can always check the status of
        your request on your
        <a href="http://sdssn.org/auth/login" target="_blank" rel="noopener noreferrer">dashboard.</a>
    </p>
    <p>
        Thanks for your patience!
    </p>
@endsection
