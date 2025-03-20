<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return redirect()->away(config('app.frontend_url'));

    $data = [
        'Laravel' => app()->version(),
        'project' => 'SDSSN',
        'developers' => [
            'backend' =>[
                'name' => 'Abdulsalam Abdulrahman',
                'email' => 'abdulsalamamtech@gmail.com',
            ],
            'frontend' =>[
                'name' => 'Mayowa Sanusi',
                'email' => 'mayowa@gmail.com',
            ]
        ],
    ];
    return $data;
});


// For Auth endpoints
require __DIR__.'/auth.php';


Route::get('/mail', function (Request $request){
    
    $send = Mail::raw('This is a test email, from: SDSSN', function ($message) {
        $message->to('abdulsalamamtech@gmail.com')->subject('Test Email: ' . now());
    });
    return $send? "done": "fail";
});

// /home/amtech/Desktop/projects/sdssnapp/resources/views/custom/tawk/index.blade.php
// Route::get('/tawk', function () {
//     return view('custom.tawk.index');
// });




