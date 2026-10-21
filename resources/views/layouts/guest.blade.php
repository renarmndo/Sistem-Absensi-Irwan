<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SisAbsen') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=poppins:400,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="guest-layout">
            <div class="guest-bg"></div>
            <div class="guest-blob-1"></div>
            <div class="guest-blob-2"></div>

            <div class="relative w-full max-w-md animate-fade-in-up">
                {{-- Logo --}}
                <div class="mb-8 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl text-white text-2xl font-bold shadow-lg ring-4 ring-white/50"
                         style="background: linear-gradient(135deg, #1E5F8C, #143B57); box-shadow: 0 8px 24px rgba(30, 95, 140, 0.25);">
                        SA
                    </div>
                    <h2 class="mt-4 font-heading text-xl font-bold" style="color: #16222E;">SisAbsen</h2>
                    <p class="text-sm" style="color: #8C97A1;">Sistem Absensi Karyawan</p>
                </div>

                {{-- Form Card --}}
                <div class="rounded-2xl bg-white p-8"
                     style="border: 1px solid #F0EADD; box-shadow: 0 4px 40px rgba(30, 95, 140, 0.08);">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <p class="mt-6 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} PT. Pesona Putra Perkasa. All rights reserved.
                </p>
            </div>
        </div>
    </body>
</html>
