{{-- <div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
</div> --}}


@extends('layouts.mail')

@section('content')
    <!-- Salutation -->
    <h2>Dear {{ $membership?->full_name }},</h2>
    <p>
        Payment for your certificate has been confirmed!
    </p>
    <p>
    <blockquote>
        <strong>Name: </strong>
        {{ $membership?->full_name }}
        <br>
        <strong>Email: </strong>
        {{ $membership?->user?->email }}
        <br>
        <strong>Serial No: </strong>
        {{ $membership?->serial_no }}
        <br>
        <strong>Membership Code: </strong>
        {{ $membership?->membership_code }}
        <br>
        <strong>Issued On: </strong>
        {{ $membership?->issued_on }}
        {{-- ({{ $membership?->issued_on->diffForHumans  }}) --}}
        <br>
        <strong>Expires On: </strong>
        {{ $membership?->expires_on }}
        {{-- ({{ $membership?->expires_on->diffForHumans  }}) --}}
        <br>
        <strong>Status: </strong>
        {{ $membership?->status }}
        <br>
    </blockquote>

    </p>
    <p>
        Please log in to your
        <a href="http://sdssn.org/auth/login" target="_blank" rel="noopener noreferrer">dashboard</a>
        to download your certificate.
    </p>
    <p>
        Congratulations again, and we look forward to your continued success!
    </p>
@endsection
