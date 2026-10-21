<x-app-layout>
    <x-slot name="header">Riwayat Presensi</x-slot>

    <div class="space-y-6">
        <div class="metric-strip">
            <div class="metric stagger-1 animate-fade-in-up">
                <div class="m-label">Total Kehadiran</div>
                <div class="m-value" style="color: #16222E;">{{ $summary->total }}</div>
            </div>
            <div class="metric stagger-2 animate-fade-in-up">
                <div class="m-label">Hadir</div>
                <div class="m-value" style="color: #3F7D58;">{{ $summary->hadir }}</div>
            </div>
            <div class="metric stagger-3 animate-fade-in-up">
                <div class="m-label">Terlambat</div>
                <div class="m-value" style="color: #B8762E;">{{ $summary->terlambat }}</div>
            </div>
        </div>

        <x-card class="stagger-4 animate-fade-in-up">
            <x-slot name="header">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="font-heading text-[14.5px] font-semibold" style="color: #16222E;">Daftar Presensi</h2>
                    <form method="GET" class="flex gap-2">
                        <select name="month" class="input text-sm">
                            <option value="">Semua Bulan</option>
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                                </option>
                            @endforeach
                        </select>
                        <select name="year" class="input text-sm">
                            <option value="">Semua Tahun</option>
                            @foreach (range(now()->year, now()->year - 2, -1) as $y)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-primary btn-sm">Filter</button>
                    </form>
                </div>
            </x-slot>

            @if ($attendances->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="empty-state-title">Belum Ada Data</p>
                    <p class="empty-state-description">Belum ada data presensi untuk periode ini.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Lokasi Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td style="color: #16222E;" class="font-medium">
                                        {{ \Carbon\Carbon::parse($attendance->date)->locale('id')->translatedFormat('l, d M Y') }}
                                    </td>
                                    <td class="font-mono">
                                        {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i:s') : '-' }}
                                    </td>
                                    <td class="font-mono">
                                        {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i:s') : '-' }}
                                    </td>
                                    <td>
                                        @php
                                            $badge = 'badge-' . strtolower($attendance->status);
                                        @endphp
                                        <span class="badge {{ $badge }}">{{ $attendance->status }}</span>
                                    </td>
                                    <td class="text-xs" style="color: #8C97A1;">
                                        <span title="Lat: {{ $attendance->lat_in }}, Lon: {{ $attendance->long_in }}">
                                            {{ number_format($attendance->distance_in, 0, ',', '.') }}m
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
