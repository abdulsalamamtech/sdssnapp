<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* General Body Styles */
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .header {
            /* Primary brand color */
            /* background-color: #4caf50;  */
            background-color: rgb(5, 35, 62);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }

        .header .logo img {
            width: 70px;
            height: 70px;
            text-align: center;
            margin: 0 auto;
            padding-bottom: 10px;
        }

        /* .header .logo img{
                width: 100%;
                height: 100%;
                text-align: center;
            } */
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 18px;
            opacity: 0.9;
        }

        /* Main */
        .main {
            padding: 30px 0px 10px;
        }

        /* Content Area */
        .content {
            color: #333333;
            line-height: 1.6;
            font-size: 16px;
        }


        .content h2 {
            color: #2c3e50;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 15px;
        }

        .content a {
            /* color: #4caf50; */
            color: rgb(5, 35, 62);
            text-decoration: none;
            font-weight: bold;
        }

        /* Call to Action Button */
        .button-container {
            text-align: center;
            padding: 20px 30px;
        }

        .button {
            display: inline-block;
            /* background-color: #4caf50; */
            background-color: rgb(5, 35, 62);
            color: #ffffff;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        /* Footer */
        .footer {
            /* background-color: #333333; */
            background-color: rgb(5, 35, 62);
            color: #cccccc;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }

        .footer p {
            margin: 0;
        }

        .footer a {
            color: #4caf50;
            text-decoration: none;
        }

        /* Responsive Styles */
        @media only screen and (max-width: 620px) {
            .email-container {
                margin: 0 !important;
                border-radius: 0 !important;
            }

            .header,
            .content,
            .footer {
                padding: 20px !important;
            }

            .header h1 {
                font-size: 28px !important;
            }

            .header p {
                font-size: 16px !important;
            }

            .content h2 {
                font-size: 20px !important;
            }
        }
    </style>        
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <a href="/">
                    {{-- <x-application-logo class="w-20 h-20 fill-current text-gray-500" /> --}}
                </a>
                <!-- Header -->
                <div class="header px-6 md:px-10">
                    <div class="logo">
                        <img src="https://sdssn.org/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Flogo-new-white.897aba2b.png&w=1920&q=75"
                            alt="" />
                    </div>
                    <p>
                        Spatial Data Science Society Of Nigeria (SDSSN) is a
                        collaborative, practical and interactive platform, which
                        demonstrates collective and shared vision of the Goespatial
                        and data science communities.
                    </p>
                </div>                
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
