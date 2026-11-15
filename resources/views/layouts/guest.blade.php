<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Panggil CSS Anda -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body style="margin: 0; background-color: #f0f2f5; font-family: 'Inter', sans-serif;">
        
        <!-- Container Utama (Tengah Layar) -->
        <div style="min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px;">
            
            <!-- Logo (Ukuran disesuaikan manual) -->
            <div style="margin-bottom: 20px;">
                <a href="/">
                    <!-- Jika logo SVG bawaan Breeze masih raksasa, kita paksa ukurannya di sini -->
                    <x-application-logo style="width: 80px; height: 80px; color: #6c757d; fill: currentColor;" />
                </a>
            </div>

            <!-- Kotak Putih Form -->
            <div style="width: 100%; max-width: 400px; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                {{ $slot }}
            </div>

        </div>
    </body>
</html>