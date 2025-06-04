<?php


use App\Mail\NotifyAdminAboutCertificateRequestMail;
use App\Models\Api\CertificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {

    return redirect()->away(config('app.frontend_url'));

});


// For Auth endpoints
require __DIR__.'/auth.php';


Route::get('/mail', function (Request $request){
    
    // $certificationRequest = CertificationRequest::find(1); // Assuming you have a certification request with ID 1
    // Mail::to('abdulsalamamtech@gmail.com')->send(new NotifyAdminAboutCertificateRequestMail($certificationRequest));
    
    $send = Mail::raw('This is a test email, from: SDSSN', function ($message) {
        $message->to('abdulsalamamtech@gmail.com')->subject('Test Email: ' . now());
    });
    return $send? "done": "fail";
});

// /home/amtech/Desktop/projects/sdssnapp/resources/views/custom/tawk/index.blade.php
// Route::get('/tawk', function () {
//     return view('custom.tawk.index');
// });




