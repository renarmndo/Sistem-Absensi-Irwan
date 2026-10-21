<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('payrolls.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7" />
                </svg>
            </a>
            <span>Slip Gaji — {{ $payroll->user->name }}</span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <x-card class="stagger-1 animate-fade-in-up">
            <div class="pb-6 text-center" style="border-bottom: 1px solid #F0EADD;">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full" style="background: #E9F1F5; color: #1E5F8C;">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="font-heading text-xl font-bold" style="color: #16222E;">SLIP GAJI</h1>
                <p class="mt-1 text-sm" style="color: #8C97A1;">
                    {{ \App\Services\PayrollCalculator::monthName($payroll->period_month) }} {{ $payroll->period_year }}
                </p>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-medium uppercase" style="color: #8C97A1;">Nama Karyawan</p>
                    <p class="mt-0.5 font-medium" style="color: #16222E;">{{ $payroll->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase" style="color: #8C97A1;">NIK</p>
                    <p class="mt-0.5 font-medium" style="color: #16222E;">{{ $payroll->user->employee_id }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase" style="color: #8C97A1;">Jabatan</p>
                    <p class="mt-0.5 font-medium" style="color: #16222E;">{{ $payroll->user->position ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase" style="color: #8C97A1;">Periode</p>
                    <p class="mt-0.5 font-medium" style="color: #16222E;">
                        {{ \App\Services\PayrollCalculator::monthName($payroll->period_month) }} {{ $payroll->period_year }}
                    </p>
                </div>
            </div>

            <table class="mt-6 w-full text-sm">
                <thead>
                    <tr style="border-top: 2px solid #E7E0D2; border-bottom: 2px solid #E7E0D2;">
                        <th class="py-2 text-left font-semibold" style="color: #48555F;">Keterangan</th>
                        <th class="py-2 text-right font-semibold" style="color: #48555F;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #F0EADD;">
                        <td class="py-3" style="color: #48555F;">Gaji Pokok</td>
                        <td class="py-3 text-right font-mono" style="color: #16222E;">Rp{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #F0EADD;">
                        <td class="py-3" style="color: #48555F;">Tunjangan Tetap</td>
                        <td class="py-3 text-right font-mono" style="color: #16222E;">Rp{{ number_format($payroll->allowance, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #F0EADD;">
                        <td class="py-3" style="color: #48555F;">Total Kehadiran</td>
                        <td class="py-3 text-right" style="color: #48555F;">{{ $payroll->total_attendance }} hari</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #F0EADD;">
                        <td class="py-3" style="color: #48555F;">Total Alfa</td>
                        <td class="py-3 text-right" style="color: #48555F;">{{ $payroll->total_absence_alfa }} hari</td>
                    </tr>
                    <tr style="border-top: 2px solid #FAEDED;">
                        <td class="py-3 font-medium" style="color: #B23A3A;">Potongan Alfa</td>
                        <td class="py-3 text-right font-mono font-medium" style="color: #B23A3A;">(Rp{{ number_format($payroll->cuts, 0, ',', '.') }})</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr style="border-top: 2px solid #16222E;">
                        <td class="py-4 font-heading text-lg font-bold" style="color: #16222E;">Take Home Pay</td>
                        <td class="py-4 text-right font-heading text-lg font-bold" style="color: #1E5F8C;">Rp{{ number_format($payroll->take_home_pay, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="flex justify-center gap-3 pt-4" style="border-top: 1px solid #F0EADD;">
                <a href="{{ route('payrolls.index') }}" class="btn-secondary">Kembali</a>
                <a href="{{ route('payrolls.pdf', $payroll) }}" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download PDF
                </a>
            </div>
        </x-card>
    </div>
</x-app-layout>
