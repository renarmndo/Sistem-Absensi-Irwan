<x-app-layout>
    <x-slot name="header">Dashboard HRD</x-slot>

    @php
        $greeting = now()->hour < 12 ? 'Selamat pagi' : (now()->hour < 18 ? 'Selamat siang' : 'Selamat malam');
    @endphp

    <div class="space-y-5">
        {{-- Greeting --}}
        <div class="stagger-1 animate-fade-in-up overflow-hidden p-5 sm:p-6"
             style="background: linear-gradient(135deg, #E9F1F5 0%, #FAF7F1 100%); border-radius: 14px;">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-heading text-xl font-semibold sm:text-2xl" style="color: #16222E;">
                        {{ $greeting }}, {{ $user->name }} 👋
                    </h1>
                    <p class="mt-1 text-sm" style="color: #48555F;">
                        Kelola kehadiran dan persetujuan tim hari ini
                    </p>
                </div>
                <div class="flex items-center gap-2 text-xs" style="color: #8C97A1;">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>

        {{-- Metric Strip --}}
        <div class="metric-strip stagger-2 animate-fade-in-up">
            <div class="metric">
                <div class="m-label">Total Karyawan</div>
                <div class="m-value" style="color: #16222E;">{{ $totalKaryawan }}</div>
            </div>
            <div class="metric">
                <div class="m-label">Pengajuan Menunggu</div>
                <div class="m-value" style="color: #B8762E;">{{ $pendingCount }}</div>
            </div>
            <div class="metric">
                <div class="m-label">Disetujui Hari Ini</div>
                <div class="m-value" style="color: #3F7D58;">{{ $approvedToday }}</div>
            </div>
            <div class="metric">
                <div class="m-label">Ditolak Hari Ini</div>
                <div class="m-value" style="color: #B23A3A;">{{ $rejectedToday }}</div>
            </div>
        </div>

        {{-- Pengajuan Menunggu --}}
        <div class="card stagger-3 animate-fade-in-up">
            <div class="card-header">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #FAF1E5; color: #B8762E;">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="font-heading text-sm font-semibold" style="color: #16222E;">Pengajuan Menunggu</h2>
                    @if ($pendingCount > 0)
                        <span class="inline-flex items-center justify-center rounded-full px-2 py-0.5 text-[10px] font-bold text-white" style="background: #B8762E;">{{ $pendingCount }}</span>
                    @endif
                </div>
                <a href="{{ route('leave-requests.index') }}" class="link-primary text-[11px]">Lihat semua →</a>
            </div>

            <div class="table-wrap mt-4">
                <table>
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Jenis</th>
                            <th>Tanggal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingLeaves as $leave)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-[10px] font-bold text-white"
                                             style="background: linear-gradient(135deg, {{ match(substr($leave->type, 0, 1)) { 'S' => '#A4548A, #7A3A62', 'C' => '#8B6BAE, #5B4A8A', default => '#5B6FA8, #3A4A6E' } }});">
                                            {{ strtoupper(substr($leave->user->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[13px] font-medium truncate" style="color: #16222E;">{{ $leave->user->name }}</p>
                                            <p class="text-[11px] truncate" style="color: #8C97A1;">{{ $leave->user->employee_id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php $typeBadge = match(strtolower($leave->type)) { 'izin' => 'badge-izin', 'sakit' => 'badge-sakit', 'cuti' => 'badge-cuti', default => 'badge-pending' }; @endphp
                                    <x-dynamic-component :component="$typeBadge">{{ ucfirst($leave->type) }}</x-dynamic-component>
                                </td>
                                <td class="text-[12px]" style="color: #48555F;">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d/m') }} – {{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('leave-requests.approve', $leave) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-approve btn-sm">Setuju</button>
                                        </form>
                                        <button type="button" @click="$refs.reject{{ $leave->id }}.showModal()" class="btn-reject btn-sm">Tolak</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-[12px]" style="color: #8C97A1;">Tidak ada pengajuan yang menunggu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Karyawan Hari Ini --}}
        <div class="card stagger-4 animate-fade-in-up">
            <div class="card-header">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #E9F1F5; color: #1E5F8C;">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h2 class="font-heading text-sm font-semibold" style="color: #16222E;">Karyawan Hari Ini</h2>
                </div>
            </div>

            {{-- Roster Pulse --}}
            <div class="mt-4 mb-4">
                <div class="roster-pulse">
                    @php
                        $karyawanIds = \App\Models\User::whereHas('role', fn ($q) => $q->where('name', 'karyawan'))->pluck('id');
                        $todayAttends = \App\Models\Attendance::whereIn('user_id', $karyawanIds)->whereDate('date', now()->format('Y-m-d'))->get();
                        $attendMap = $todayAttends->pluck('status', 'user_id');
                    @endphp
                    @foreach ($karyawanIds as $kid)
                        <div class="roster-dot {{ match($attendMap->get($kid)) { 'Hadir' => 'roster-dot-hadir', 'Terlambat' => 'roster-dot-terlambat', default => 'roster-dot-alfa' } }}"></div>
                    @endforeach
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($todayAttendances as $att)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-[10px] font-bold text-white"
                                             style="background: linear-gradient(135deg, #1E5F8C, #143B57);">
                                            {{ strtoupper(substr($att->user->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[13px] font-medium truncate" style="color: #16222E;">{{ $att->user->name }}</p>
                                            <p class="text-[11px] truncate" style="color: #8C97A1;">{{ $att->user->employee_id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-mono text-[13px]" style="color: #48555F;">
                                    {{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('H:i') : '--:--' }}
                                </td>
                                <td>
                                    @php $badge = 'badge-' . strtolower($att->status); @endphp
                                    <span class="badge {{ $badge }}">{{ $att->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-[12px]" style="color: #8C97A1;">Belum ada data kehadiran hari ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Aksi Cepat --}}
        <div class="stagger-5 animate-fade-in-up">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <a href="{{ route('users.index') }}" class="flex items-center gap-4 rounded-card bg-white p-5 transition-all duration-200 hover:shadow-md"
                   style="border: 1px solid #F0EADD;">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background: #E9F1F5; color: #1E5F8C;">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[13px] font-semibold" style="color: #16222E;">Kelola Karyawan</p>
                        <p class="text-[11px]" style="color: #8C97A1;">Tambah, edit, hapus data karyawan</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 shrink-0" style="color: #C9D5DC;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('payrolls.index') }}" class="flex items-center gap-4 rounded-card bg-white p-5 transition-all duration-200 hover:shadow-md"
                   style="border: 1px solid #F0EADD;">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background: #EAF3EC; color: #3F7D58;">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[13px] font-semibold" style="color: #16222E;">Proses Payroll</p>
                        <p class="text-[11px]" style="color: #8C97A1;">Generate slip gaji karyawan</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 shrink-0" style="color: #C9D5DC;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('reports.index') }}" class="flex items-center gap-4 rounded-card bg-white p-5 transition-all duration-200 hover:shadow-md"
                   style="border: 1px solid #F0EADD;">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background: #EEF0F8; color: #5B6FA8;">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[13px] font-semibold" style="color: #16222E;">Unduh Laporan</p>
                        <p class="text-[11px]" style="color: #8C97A1;">Ekspor data absensi & payroll</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 shrink-0" style="color: #C9D5DC;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
