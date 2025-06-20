@extends('layouts.mail')

@section('content')


        <!-- Salutation -->
        <h2>Dear {{ $certificationRequest?->full_name }},</h2>
        <p>
            Thank you for your application for the
            {{ $certificationRequest?->certification->title }}
            {{ $certificationRequest?->certification->type }}
            certification.
        </p>
        <p>
            After review, we regret to inform you that we are unable
            to approve your certification at this time.
            Your application did not meet the necessary requirements.
        </p>
        <p>
            {{-- quoted word --}}
        <blockquote>
            {{ $certificationRequest?->management_note }}
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit tempore ratione consequatur, exercitationem
            quidem saepe culpa mollitia cum dolorem vel.
        </blockquote>
        </p>
        <p>
            We encourage you to review our certification requirements and consider reapplying.
        </p>

@endsection
