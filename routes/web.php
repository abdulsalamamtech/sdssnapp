<?php

use App\Mail\CertificationRequestRejectedMail;
use App\Mail\NotifyAdminAboutCertificateRequestMail;
use App\Models\Api\CertificationRequest;
use App\Models\Api\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;




Route::get('/', function () {

    return redirect()->away(config('app.frontend_url'));
});


// For Auth endpoints
require __DIR__ . '/auth.php';


Route::get('/mail', function (Request $request) {

    $certificationRequest = CertificationRequest::find(1); // Assuming you have a certification request with ID 1
    // Mail::to('abdulsalamamtech@gmail.com')->send(new NotifyAdminAboutCertificateRequestMail($certificationRequest));
    Mail::to('abdulsalamamtech@gmail.com')->send(new CertificationRequestRejectedMail($certificationRequest));

    return "DONE";

    // $send = Mail::raw('This is a test email, from: SDSSN', function ($message) {
    //     $message->to('abdulsalamamtech@gmail.com')->subject('Test Email: ' . now());
    // });
    // return $send ? "done" : "fail";
});

// /home/amtech/Desktop/projects/sdssnapp/resources/views/custom/tawk/index.blade.php
// Route::get('/tawk', function () {
//     return view('custom.tawk.index');
// });




Route::get('/run', function () {

    // return date('s');
    // $membership_id = 1;
    // $cert_type = Membership::where('id', $membership_id)?->first();
    // $cert_type = $cert_type->certificationRequest?->certification?->type;
    // // get the first 3 letters
    // $cer_abbr = substr($cert_type, 0, 3);
    // $uniqid = strtoupper($cer_abbr) . date('y') . '00' . date('s') . $membership_id;
    // while(App\Models\Api\Membership::where('membership_code', $uniqid)?->exists()){
    //     $uniqid = strtoupper(uniqid('TIC'));
    // }

    // return $uniqid;


    // Using rand
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersNumber = strlen($characters);
    $codeLength = 6;

    $code = '';

    while (strlen($code) < $codeLength) {
        $position = rand(0, $charactersNumber - 1);
        $character = $characters[$position];
        $code = $code . $character;
    }


    // Using time
    $timestamp = Carbon::now()->timestamp;
    $ref_id = Str::random(18);
    $random_string = Str::random(32);
    $combine = md5($timestamp . $ref_id . $random_string);
    $unique_reference = uniqid($combine);
    $transactionReference = Str::uuid($unique_reference);


    // Rand Value
    $randomString = random_int(0, 3);
    $randomVal = uniqid('TIC');
    // $time = time();
    $randValue = $randomVal  . $randomString;
    $res = (object) [
        'code' => $code,
        'uniqid' => strtoupper(uniqid('SDSSN')),
        'rand_value' => $randValue,
        'ref' => $transactionReference,
        'random' => Str::random(40),
        'str' => Str::random(18),
        'uuid' => Str::uuid(),
        'uuid7' => Str::uuid7(),
        'uuid77' => Str::uuid7(now()),
        'today' => Carbon::today(),
        'timing' => date('Y-m-d', strtotime('+ 10month')),
        'now' => time(),
        // 'str_to_date' => Carbon::crea('today' . 20 . 'weeks to date'),
    ];

    // return $res->code;
    return $res;
});
