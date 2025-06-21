{{-- <div>
    <!-- It is never too late to be what you might have been. - George Eliot -->
</div> --}}

@extends('layouts.mail')

@section('content')


        <!-- Salutation -->
        <h2>Dear {{ $certificationRequest?->full_name }},</h2>
        <p>
            We are thrilled to inform you that your application for the 
            {{ $certificationRequest?->certification->title }}
            {{ $certificationRequest?->certification->type }}
            certification has been approved! Congratulations on 
            successfully meeting all the necessary requirements.
        </p>
        <p>
            To complete your certification and receive your certificate, 
            please log in to your 
            <a href="http://sdssn.org/auth/login" target="_blank" rel="noopener noreferrer">dashboard</a>
             to make the payment.
        </p>
        <p>
            {{-- quoted word --}}
            <blockquote>
                {{ $certificationRequest?->management_note }}
            </blockquote>
        </p>
        <p>
            Congratulations again, and we look forward to your continued success!
        </p>

@endsection

