<!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->


@extends('layouts.mail')

@section('content')
    <div>

        <!-- Salutation -->
        <h2>Hello Admin</h2>
        <p>
            A new certificate and membership request for
            {{ $certificationRequest?->certification->title }}
            {{ $certificationRequest?->certification->type }}
            certification has been submitted.
        </p>
        <p>
            <strong>Name:</strong> {{ $certificationRequest?->full_name }}
            <br>
            <strong>Email:</strong> {{ $certificationRequest?->user?->email }}
        </p>

        {{-- reason_for_certification --}}
        <p>
            <strong>Reason for applying for certification:</strong>
            <br>
            {{-- quoted word --}}
        <blockquote>
            {{ $certificationRequest?->reason_for_certification }}
        </blockquote>
        </p>

        {{-- this document was submitted along with this mail --}}
        <p>
            <strong>Document:</strong>
            <br>
            <a href="{{ $certificationRequest?->credential?->url }}" target="_blank" rel="noopener noreferrer">
                View Document
            </a>
        </p>

        <p>
            Please log in to the
            <a href="http://sdssn.org/admin/login" target="_blank" rel="noopener noreferrer">dashboard</a>
            to view the full details and take the necessary action.
        </p>

    </div>
@endsection
