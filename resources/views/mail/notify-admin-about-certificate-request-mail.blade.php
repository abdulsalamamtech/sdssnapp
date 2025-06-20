@extends('layouts.mail')

@section('content')
    <div>
        <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
        Hello Admin
        <p>
            {{-- {{ json_encode($certificationRequest) }} has requested for certification. --}}
            {{ $certificationRequest?->full_name ?? 'A user' }} has requested for certification.

        </p>
        {{-- reason_for_certification --}}
        <p>
            <strong>Reason for applying for certification:</strong>
            <br>
            {{ $certificationRequest?->reason_for_certification }}
        </p>

        {{-- Regards,<br>
        {{ config('app.name') ?? 'SDSSN' }} <br>
        {{ config('app.frontend_url') ?? 'sdssn.org' }} <br> --}}
    </div>
@endsection
