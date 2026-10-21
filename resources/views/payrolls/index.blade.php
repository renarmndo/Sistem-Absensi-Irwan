<x-app-layout>
    <x-slot name="header">{{ auth()->user()->role?->name === 'karyawan' ? 'Slip Gaji Saya' : 'Payroll' }}</x-slot>

    <div class="space-y-6">
        @if (auth()->user()->role?->name === 'hrd')
            <div class="flex justify-end stagger-1 animate-fade-in-up">
                <a href="{{ route('payrolls.create') }}" class="btn-primary btn-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Generate Payroll
                </a>
            </div>
        @endif

        @if (session('success'))
            <div class="alert-success stagger-1 animate-fade-in-up">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <x-card class="stagger-2 animate-fade-in-up">
            @if ($payrolls->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="empty-state-title">Belum Ada Data Payroll</p>
                    <p class="empty-state-description">
                        @if (auth()->user()->role?->name === 'hrd')
                            Generate payroll untuk periode tertentu.
                        @else
                            Belum ada slip gaji yang tersedia.
                        @endif
                    </p>
                    @if (auth()->user()->role?->name === 'hrd')
                        <a href="{{ route('payrolls.create') }}" class="btn-primary mt-4">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Generate Payroll
                        </a>
                    @endif
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                @if (auth()->user()->role?->name !== 'karyawan')
                                    <th>Karyawan</th>
                                @endif
                                <th>Periode</th>
                                <th>Hadir</th>
                                <th>Alfa</th>
                                <th>Potongan</th>
                                <th class="text-right">Take Home Pay</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($payrolls as $payroll)
                                <tr>
                                    @if (auth()->user()->role?->name !== 'karyawan')
                                        <td>
                                            <span style="color: #16222E;" class="font-medium">{{ $payroll->user->name }}</span>
                                            <span class="ml-1 text-xs" style="color: #8C97A1;">{{ $payroll->user->employee_id }}</span>
                                        </td>
                                    @endif
                                    <td style="color: #48555F;">
                                        {{ \App\Services\PayrollCalculator::monthName($payroll->period_month) }}
                                        {{ $payroll->period_year }}
                                    </td>
                                    <td class="font-mono">{{ $payroll->total_attendance }} hari</td>
                                    <td class="font-mono">{{ $payroll->total_absence_alfa }} hari</td>
                                    <td class="font-mono" style="color: #B23A3A;">(Rp{{ number_format($payroll->cuts, 0, ',', '.') }})</td>
                                    <td class="font-mono font-semibold text-right" style="color: #3F7D58;">Rp{{ number_format($payroll->take_home_pay, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('payrolls.show', $payroll) }}" class="btn-ghost btn-sm">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Detail
                                            </a>
                                            <a href="{{ route('payrolls.pdf', $payroll) }}" class="btn-ghost btn-sm text-primary hover:bg-primary/10">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $payrolls->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
