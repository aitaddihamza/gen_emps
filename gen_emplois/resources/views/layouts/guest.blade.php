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
            body {
                background-color: #f2f4f7;
                position: relative;
                overflow: hidden;
            }
            .ellipse {
                position: absolute;
                border-radius: 50%;
                background-color: #A7DBE8;
                opacity: 0.6;
                z-index: 0;
            }
            .ellipse1 { width: 300px; height: 300px; top: -200px; left: -200px; }
            .ellipse2 { width: 250px; height: 250px; top: -80px; right: -60px; }
            .ellipse3 { width: 250px; height: 250px; top: 720px; left: -210px; }
            .ellipse4 { width: 300px; height: 300px; bottom: -200px; right: -95px; }

            .auth-card {
                background: white;
                border-radius: 6px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                z-index: 1;
                position: relative;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Decorative ellipses -->
        <div class="ellipse ellipse1"></div>
        <div class="ellipse ellipse2"></div>
        <div class="ellipse ellipse3"></div>
        <div class="ellipse ellipse4"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
