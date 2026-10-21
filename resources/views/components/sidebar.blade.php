@props(['user' => Auth::user()])

@php
    $role = $user?->role?->name ?? 'karyawan';
    $roleLabel = match($role) { 'hrd' => 'HRD', 'pimpinan' => 'Direktur', default => 'Karyawan' };
    $menuItems = [
        'karyawan' => [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['label' => 'Presensi', 'route' => 'attendance.index', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6'],
            ['label' => 'Riwayat Presensi', 'route' => 'attendance.history', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Pengajuan', 'route' => 'leave-requests.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['label' => 'Slip Gaji', 'route' => 'payrolls.index', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z'],
        ],
        'hrd' => [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['label' => 'Karyawan', 'route' => 'users.index', 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
            ['label' => 'Gaji', 'route' => 'salaries.index', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm0 0h2v-2h-2v2zm0 0h2V9h-2v2z'],
            ['label' => 'Pengajuan', 'route' => 'leave-requests.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['label' => 'Absensi', 'route' => 'reports.index', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Payroll', 'route' => 'payrolls.index', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z'],
            ['label' => 'Laporan', 'route' => 'reports.dashboard', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['label' => 'Pengaturan Kantor', 'route' => 'office-settings.edit', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
        ],
        'pimpinan' => [
            ['label' => 'Dashboard', 'route' => 'reports.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['label' => 'Laporan', 'route' => 'reports.index', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Payroll', 'route' => 'payrolls.index', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z'],
            ['label' => 'Karyawan', 'route' => 'users.index', 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
            ['label' => 'Pengaturan Kantor', 'route' => 'office-settings.edit', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
        ],
    ];

    $currentMenu = $menuItems[$role] ?? $menuItems['karyawan'];

    if (!function_exists('routeOrHash')) {
        function routeOrHash($name) {
            if (\Illuminate\Support\Facades\Route::has($name)) {
                return route($name);
            }
            return '#';
        }
    }
@endphp

{{-- Mobile Overlay --}}
<div
    x-data
    x-show="$store.sidebar.open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="$store.sidebar.close()"
    class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
    x-cloak
></div>

{{-- Sidebar --}}
<aside
    x-data
    x-ref="sidebar"
    :class="$store.sidebar.open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 flex flex-col shadow-sidebar transition-transform duration-300 ease-in-out lg:z-40"
    style="width: 252px; background: linear-gradient(180deg, #16374D 0%, #102A3B 100%);"
>
    {{-- Logo --}}
    <div class="relative flex h-16 items-center gap-3 border-b border-white/10 px-5">
        <div class="flex h-9 w-9 items-center justify-center rounded-[10px] text-xs font-bold text-white shadow-lg"
             style="background: linear-gradient(135deg, #3B9AB8, #1E5F8C);">
            SA
        </div>
        <div class="flex-1">
            <span class="font-heading text-base font-semibold text-white tracking-tight">SisAbsen</span>
            <span class="block text-[10px] leading-none" style="color: #7E94A3;">Sistem Absensi</span>
        </div>
        {{-- Close button (mobile only) --}}
        <button
            @click="$store.sidebar.close()"
            class="flex h-8 w-8 items-center justify-center rounded-lg transition-colors lg:hidden"
            style="color: #7E94A3;"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Menu Items --}}
    <div class="flex-1 overflow-y-auto px-3 py-4 custom-scrollbar space-y-1">
        @foreach ($currentMenu as $item)
            @php $isActive = request()->routeIs($item['route']); @endphp
            <a href="{{ routeOrHash($item['route']) }}"
               @click="$store.sidebar.close()"
               class="sidebar-item {{ $isActive ? 'sidebar-item-active' : '' }}">
                <svg class="sidebar-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                </svg>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- User Info --}}
    <div class="border-t border-white/10 p-4">
        <div class="flex items-center gap-3 mb-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-full text-xs font-bold text-white shadow-lg"
                 style="background: linear-gradient(135deg, {{ match($role) { 'hrd' => '#8B6BAE, #5B4A8A', 'pimpinan' => '#B8762E, #8B5A1E', default => '#1E5F8C, #143B57' } }});">
                {{ $user ? substr($user->name, 0, 2) : '??' }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ $user->name ?? 'Guest' }}</p>
                <p class="text-[11px] truncate" style="color: #7E94A3;">{{ $user->position ?? $roleLabel }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200"
                    style="color: #7E94A3;"
                    onmouseover="this.style.background='rgba(255,255,255,0.08)';this.style.color='#AEBCC6'"
                    onmouseout="this.style.background='';this.style.color='#7E94A3'">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
