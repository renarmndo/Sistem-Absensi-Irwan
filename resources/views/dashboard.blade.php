<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    @php
        $greeting = now()->hour < 12 ? 'Selamat pagi' : (now()->hour < 18 ? 'Selamat siang' : 'Selamat malam');
        $officeName = $office?->office_name ?? 'Kantor Pusat';
        $workStart = $office?->work_start_time ? \Carbon\Carbon::parse($office->work_start_time)->format('H:i') : '08:00';
        $workEnd = $office?->work_end_time ? \Carbon\Carbon::parse($office->work_end_time)->format('H:i') : '17:00';

        $clockIn = $todayAttendance?->clock_in ? \Carbon\Carbon::parse($todayAttendance->clock_in) : null;
        $clockOut = $todayAttendance?->clock_out ? \Carbon\Carbon::parse($todayAttendance->clock_out) : null;
        $distance = $todayAttendance?->distance_in ?? 0;

        $startMin = $office ? (int)\Carbon\Carbon::parse($office->work_start_time)->format('H') * 60 + (int)\Carbon\Carbon::parse($office->work_start_time)->format('i') : 480;
        $endMin = $office ? (int)\Carbon\Carbon::parse($office->work_end_time)->format('H') * 60 + (int)\Carbon\Carbon::parse($office->work_end_time)->format('i') : 1020;
        $nowMin = now()->hour * 60 + now()->minute;
        $arcRange = max(1, $endMin - $startMin);

        $inPct = $clockIn ? min(100, max(0, (($clockIn->hour * 60 + $clockIn->minute) - $startMin) / $arcRange * 100)) : 0;
        $nowPct = min(100, max(0, (($nowMin) - $startMin) / $arcRange * 100));
        $outPct = $clockOut ? min(100, max(0, (($clockOut->hour * 60 + $clockOut->minute) - $startMin) / $arcRange * 100)) : 0;
        $fillPct = $clockOut ? $outPct : ($clockIn ? $nowPct : 0);
    @endphp

    <div class="space-y-5">
        {{-- Greeting Band --}}
        <div class="stagger-1 animate-fade-in-up overflow-hidden p-5 sm:p-6"
             style="background: linear-gradient(135deg, #E9F1F5 0%, #FAF7F1 100%); border-radius: 14px;">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-heading text-xl font-semibold sm:text-2xl" style="color: #16222E;">
                        {{ $greeting }}, {{ $user->name }} 👋
                    </h1>
                    <p class="mt-1 text-sm" style="color: #48555F;">
                        {{ now()->locale('id')->translatedFormat('l, d F Y') }} · {{ $officeName }}
                    </p>
                </div>
                <div class="flex items-center gap-2 text-xs" style="color: #8C97A1;">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Jam kerja {{ $workStart }} – {{ $workEnd }}
                </div>
            </div>
        </div>

        {{-- Presensi Hari Ini --}}
        <div class="card stagger-2 animate-fade-in-up">
            <div class="card-header">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #E9F1F5; color: #1E5F8C;">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-heading text-sm font-semibold" style="color: #16222E;">Presensi Hari Ini</h2>
                        @if ($clockIn)
                            <span class="text-[11px] font-mono" style="color: #3F7D58;">
                                {{ $distance }}m dari kantor
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Presence Arc --}}
            <div class="mt-4">
                <div class="presence-arc">
                    <div class="presence-arc-track"></div>
                    @if ($fillPct > 0)
                        <div class="presence-arc-fill" style="width: {{ $fillPct }}%;"></div>
                    @endif

                    @if ($clockIn)
                        <div class="presence-arc-dot presence-arc-dot-in" style="left: {{ $inPct }}%;"></div>
                        <span class="presence-arc-label" style="left: {{ $inPct }}%; transform: translateX(-50%);">
                            {{ $clockIn->format('H:i') }}
                        </span>
                    @endif

                    @if (! $clockOut && $clockIn && $nowPct > $inPct)
                        <div class="presence-arc-dot presence-arc-dot-now animate-pulse-dot" style="left: {{ $nowPct }}%;"></div>
                    @endif

                    @if ($clockOut)
                        <div class="presence-arc-dot presence-arc-dot-out done" style="left: {{ $outPct }}%;"></div>
                        <span class="presence-arc-label" style="left: {{ $outPct }}%; transform: translateX(-50%);">
                            {{ $clockOut->format('H:i') }}
                        </span>
                    @elseif (! $clockIn)
                        <div class="presence-arc-dot presence-arc-dot-out" style="left: 100%;"></div>
                    @endif
                </div>

                <div class="mt-8">
                    @if (! $clockIn)
                        <a href="{{ route('attendance.index') }}" class="btn-primary w-full justify-center">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Absen Masuk
                        </a>
                    @elseif (! $clockOut)
                        <a href="{{ route('attendance.index') }}" class="btn-success w-full justify-center">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                            </svg>
                            Absen Pulang
                        </a>
                    @else
                        <div class="flex items-center justify-center gap-2 text-sm font-medium" style="color: #3F7D58;">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Presensi selesai untuk hari ini
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Metric Strip --}}
        <div class="metric-strip stagger-3 animate-fade-in-up">
            <div class="metric">
                <div class="m-label">Hadir</div>
                <div class="m-value" style="color: #3F7D58;">{{ $monthSummary['hadir'] }}</div>
            </div>
            <div class="metric">
                <div class="m-label">Terlambat</div>
                <div class="m-value" style="color: #B8762E;">{{ $monthSummary['terlambat'] }}</div>
            </div>
            <div class="metric">
                <div class="m-label">Izin</div>
                <div class="m-value" style="color: #5B6FA8;">{{ $monthSummary['izin'] }}</div>
            </div>
            <div class="metric">
                <div class="m-label">Alfa</div>
                <div class="m-value" style="color: #B23A3A;">{{ $monthSummary['alfa'] }}</div>
            </div>
        </div>

        {{-- Riwayat 7 Hari --}}
        <div class="card stagger-4 animate-fade-in-up">
            <div class="card-header">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #E9F1F5; color: #1E5F8C;">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h2 class="font-heading text-sm font-semibold" style="color: #16222E;">Riwayat 7 Hari Terakhir</h2>
                </div>
                <a href="{{ route('attendance.history') }}" class="link-primary text-[11px]">Lihat semua →</a>
            </div>
            <div class="table-wrap mt-4">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Pulang</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentHistory as $att)
                            @php
                                $attDate = \Carbon\Carbon::parse($att->date);
                                $attIn = $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('H:i') : '--:--';
                                $attOut = $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('H:i') : '--:--';
                            @endphp
                            <tr>
                                <td class="font-medium" style="color: #16222E;">
                                    {{ $attDate->format('d/m/Y') }}
                                    <span class="block text-[11px] font-normal" style="color: #8C97A1;">{{ $attDate->locale('id')->translatedFormat('D') }}</span>
                                </td>
                                <td class="font-mono text-[13px]">{{ $attIn }}</td>
                                <td class="font-mono text-[13px]">{{ $attOut }}</td>
                                <td>
                                    @php $badge = 'badge-' . strtolower($att->status); @endphp
                                    <span class="badge {{ $badge }}">{{ $att->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-[12px]" style="color: #8C97A1;">Belum ada riwayat presensi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pengajuan + Slip Gaji --}}
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            {{-- Pengajuan Terakhir --}}
            <div class="card stagger-5 animate-fade-in-up">
                <div class="card-header">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #E9F1F5; color: #1E5F8C;">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="font-heading text-sm font-semibold" style="color: #16222E;">Pengajuan Terakhir</h2>
                    </div>
                    <a href="{{ route('leave-requests.create') }}" class="link-primary text-[11px]">Buat baru →</a>
                </div>
                @if ($recentLeave)
                    <div class="mt-4 flex items-center gap-3">
                        @php $leaveBadge = match(strtolower($recentLeave->type)) { 'izin' => 'badge-izin', 'sakit' => 'badge-sakit', 'cuti' => 'badge-cuti', default => 'badge-pending' }; @endphp
                        <x-dynamic-component :component="$leaveBadge" />
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-medium truncate" style="color: #16222E;">{{ $recentLeave->type }}</p>
                            <p class="text-[11px]" style="color: #8C97A1;">
                                {{ \Carbon\Carbon::parse($recentLeave->start_date)->format('d M') }} – {{ \Carbon\Carbon::parse($recentLeave->end_date)->format('d M Y') }}
                            </p>
                        </div>
                        @php
                            $statusBadge = match($recentLeave->status) { 'Approved' => 'badge-approved', 'Rejected' => 'badge-rejected', default => 'badge-pending' };
                            $statusLabel = match($recentLeave->status) { 'Approved' => 'Disetujui', 'Rejected' => 'Ditolak', default => 'Pending' };
                        @endphp
                        <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                    </div>
                @else
                    <p class="mt-4 text-center text-[12px]" style="color: #8C97A1;">Belum ada pengajuan</p>
                @endif
            </div>

            {{-- Slip Gaji --}}
            <div class="card stagger-6 animate-fade-in-up">
                <div class="card-header">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #E9F1F5; color: #1E5F8C;">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="font-heading text-sm font-semibold" style="color: #16222E;">Slip Gaji Terbaru</h2>
                    </div>
                </div>
                @if ($latestPayroll)
                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[13px] font-medium" style="color: #16222E;">
                                    {{ \App\Services\PayrollCalculator::monthName($latestPayroll->period_month) }} {{ $latestPayroll->period_year }}
                                </p>
                                @php
                                    $payBadge = match($latestPayroll->approval_status) { 'approved' => 'badge-approved', 'rejected' => 'badge-rejected', default => 'badge-pending' };
                                    $payLabel = match($latestPayroll->approval_status) { 'approved' => 'Disetujui', 'rejected' => 'Ditolak', default => 'Pending' };
                                @endphp
                                <span class="badge {{ $payBadge }}">{{ $payLabel }}</span>
                            </div>
                            <span class="font-mono text-lg font-semibold" style="color: #1E5F8C;">
                                Rp{{ number_format($latestPayroll->take_home_pay, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @else
                    <p class="mt-4 text-center text-[12px]" style="color: #8C97A1;">Belum ada slip gaji</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
