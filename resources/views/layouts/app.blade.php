<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SisAbsen') }} — {{ $header ?? 'Dashboard' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=poppins:400,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=jetbrains-mono:500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans text-ink antialiased" x-data x-init="
        Alpine.store('sidebar', {
            open: false,
            toggle() { this.open = !this.open },
            close() { this.open = false },
            openMobile() { this.open = true }
        });
    ">
        <div class="min-h-screen" style="background: #FAF7F1;">
            <x-sidebar />

            <div class="transition-all duration-300 lg:ml-[252px]">
                {{-- Topbar --}}
                <header class="sticky top-0 z-30 flex items-center gap-3 bg-white px-4 sm:px-6 no-print"
                        style="height: 68px; border-bottom: 1px solid #F0EADD;">
                    {{-- Hamburger Button (mobile + tablet) --}}
                    <button
                        type="button"
                        @click="$store.sidebar.toggle()"
                        class="flex h-9 w-9 items-center justify-center rounded-lg transition-colors lg:hidden"
                        style="color: #8C97A1;"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    {{-- Left: Eyebrow + Title --}}
                    <div class="flex flex-col">
                        <span class="text-[11px] font-semibold uppercase tracking-wider" style="color: #8C97A1;">
                            {{ Auth::user()->role?->display_name ?? 'Karyawan' }} / {{ $header ?? 'Dashboard' }}
                        </span>
                        <h1 class="font-heading text-[19px] font-semibold" style="color: #16222E;">{{ $header ?? 'Dashboard' }}</h1>
                    </div>

                    {{-- Right side --}}
                    <div class="ml-auto flex items-center gap-3">
                        {{-- User Identity --}}
                        <div class="hidden text-right text-sm leading-tight sm:block">
                            <p class="font-medium" style="color: #16222E;">{{ Auth::user()->name }}</p>
                            <p class="text-[11px]" style="color: #8C97A1;">{{ Auth::user()->position ?? Auth::user()->employee_id }}</p>
                        </div>

                        {{-- Profile Dropdown --}}
                        <div class="relative" x-data="{ profileOpen: false }">
                            <button
                                @click="profileOpen = !profileOpen"
                                @click.outside="profileOpen = false"
                                class="flex h-9 w-9 items-center justify-center rounded-full text-xs font-bold text-white shadow-md ring-2 ring-white transition-all duration-200 hover:shadow-lg"
                                style="background: linear-gradient(135deg, {{ match(Auth::user()->role?->name ?? 'karyawan') { 'hrd' => '#8B6BAE, #5B4A8A', 'pimpinan' => '#B8762E, #8B5A1E', default => '#1E5F8C, #143B57' } }});"
                            >
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </button>

                            <div
                                x-show="profileOpen"
                                x-cloak
                                @click.outside="profileOpen = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="dropdown-menu right-0 mt-2"
                            >
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    Profil Saya
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="color: #B23A3A;">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Main Content --}}
                <main class="p-4 sm:p-6 page-enter">
                    {{ $slot }}
                </main>

                {{-- Footer --}}
                <footer class="px-4 py-4 sm:px-6 no-print" style="border-top: 1px solid #F0EADD;">
                    <p class="text-center text-[11px]" style="color: #8C97A1;">
                        &copy; {{ date('Y') }} PT. Pesona Putra Perkasa. All rights reserved.
                    </p>
                </footer>
            </div>
        </div>

        <div class="toast-container" id="toast-container"></div>

        @stack('scripts')
    </body>
</html>
