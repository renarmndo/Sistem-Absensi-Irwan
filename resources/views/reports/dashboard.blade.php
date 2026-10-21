<x-app-layout>
    <x-slot name="header">Dashboard Direktur</x-slot>

    @php
        $user = auth()->user();
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
                        Ringkasan kehadiran dan persetujuan payroll
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
                <div class="m-value" style="color: #16222E;">{{ $todayStats['total'] }}</div>
            </div>
            <div class="metric">
                <div class="m-label">Hadir Hari Ini</div>
                <div class="m-value" style="color: #3F7D58;">{{ $todayStats['hadir'] }}</div>
            </div>
            <div class="metric">
                <div class="m-label">Terlambat</div>
                <div class="m-value" style="color: #B8762E;">{{ $todayStats['terlambat'] }}</div>
            </div>
            <div class="metric">
                <div class="m-label">Payroll Menunggu</div>
                <div class="m-value" style="color: #5B6FA8;">{{ $latestPayrolls->count() }}</div>
            </div>
        </div>

        {{-- Ritme Kehadiran --}}
        <div class="card stagger-3 animate-fade-in-up">
            <div class="card-header">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #E9F1F5; color: #1E5F8C;">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h2 class="font-heading text-sm font-semibold" style="color: #16222E;">
                        Ritme Kehadiran — {{ \App\Services\PayrollCalculator::monthName(now()->month) }} {{ now()->year }}
                    </h2>
                </div>
                <div class="flex items-center gap-4 text-[11px] font-semibold" style="color: #8C97A1;">
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block h-2 w-2 rounded-full" style="background: #3F7D58;"></span> Hadir
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block h-2 w-2 rounded-full" style="background: #B8762E;"></span> Terlambat
                    </span>
                </div>
            </div>
            <div class="mt-4">
                <canvas id="ritmeChart" height="200"></canvas>
            </div>
        </div>

        {{-- Payroll Menunggu --}}
        <div class="card stagger-4 animate-fade-in-up">
            <div class="card-header">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: #FAF1E5; color: #B8762E;">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="font-heading text-sm font-semibold" style="color: #16222E;">Payroll Menunggu Persetujuan</h2>
                    @if ($latestPayrolls->count() > 0)
                        <span class="inline-flex items-center justify-center rounded-full px-2 py-0.5 text-[10px] font-bold text-white" style="background: #B8762E;">{{ $latestPayrolls->count() }}</span>
                    @endif
                </div>
            </div>

            <div class="table-wrap mt-4">
                <table>
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Karyawan</th>
                            <th class="text-right">THP</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestPayrolls as $payroll)
                            <tr>
                                <td class="font-medium" style="color: #16222E;">
                                    {{ \App\Services\PayrollCalculator::monthName($payroll->period_month) }} {{ $payroll->period_year }}
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-[9px] font-bold text-white"
                                             style="background: linear-gradient(135deg, #1E5F8C, #143B57);">
                                            {{ strtoupper(substr($payroll->user->name, 0, 2)) }}
                                        </div>
                                        <span class="text-[13px]" style="color: #48555F;">{{ $payroll->user->name }}</span>
                                    </div>
                                </td>
                                <td class="text-right font-mono text-[13px] font-semibold" style="color: #1E5F8C;">
                                    Rp{{ number_format($payroll->take_home_pay, 0, ',', '.') }}
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('reports.approve-payroll', $payroll) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-approve btn-sm">Setujui</button>
                                        </form>
                                        <form action="{{ route('reports.reject-payroll', $payroll) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-reject btn-sm">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-[12px]" style="color: #8C97A1;">Tidak ada payroll yang menunggu persetujuan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Akses Cepat --}}
        <div class="stagger-5 animate-fade-in-up">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <a href="{{ route('reports.index') }}" class="flex items-center gap-4 rounded-card bg-white p-5 transition-all duration-200 hover:shadow-md"
                   style="border: 1px solid #F0EADD;">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background: #E9F1F5; color: #1E5F8C;">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[13px] font-semibold" style="color: #16222E;">Laporan Absensi</p>
                        <p class="text-[11px]" style="color: #8C97A1;">{{ now()->locale('id')->translatedFormat('F Y') }}</p>
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
                        <p class="text-[13px] font-semibold" style="color: #16222E;">Rekap Payroll</p>
                        <p class="text-[11px]" style="color: #8C97A1;">{{ now()->locale('id')->translatedFormat('F Y') }}</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 shrink-0" style="color: #C9D5DC;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('users.index') }}" class="flex items-center gap-4 rounded-card bg-white p-5 transition-all duration-200 hover:shadow-md"
                   style="border: 1px solid #F0EADD;">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background: #EEF0F8; color: #5B6FA8;">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[13px] font-semibold" style="color: #16222E;">Data Karyawan</p>
                        <p class="text-[11px]" style="color: #8C97A1;">Lihat data seluruh karyawan</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 shrink-0" style="color: #C9D5DC;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('ritmeChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json(array_column($monthlyStats, 'date')),
                    datasets: [
                        {
                            label: 'Hadir',
                            data: @json(array_column($monthlyStats, 'hadir')),
                            borderColor: '#3F7D58',
                            backgroundColor: 'rgba(63, 125, 88, 0.08)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: '#3F7D58',
                        },
                        {
                            label: 'Terlambat',
                            data: @json(array_column($monthlyStats, 'terlambat')),
                            borderColor: '#B8762E',
                            backgroundColor: 'rgba(184, 118, 46, 0.06)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: '#B8762E',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { family: 'JetBrains Mono', size: 11 }
                            },
                            grid: { color: '#F0EADD' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'JetBrains Mono', size: 11 } }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
