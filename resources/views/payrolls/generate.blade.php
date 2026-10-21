<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('payrolls.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7" />
                </svg>
            </a>
            <span>Generate Payroll</span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <x-card class="stagger-1 animate-fade-in-up">
            <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50/50 p-4 text-sm text-blue-700">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-100">
                        <svg class="h-3 w-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium">Perhatian:</p>
                        <ul class="mt-1 list-disc list-inside text-blue-600">
                            <li>Payroll yang sudah digenerate dapat diperbarui dengan meng-generate ulang periode yang sama.</li>
                            <li>Pastikan data absensi, izin/cuti, dan hari libur sudah lengkap sebelum generate.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('payrolls.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <x-input-label for="month" value="Bulan" />
                        <select id="month" name="month" required class="input mt-1 w-full">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" @selected(old('month', $suggestedMonth) == $m)>
                                    {{ \App\Services\PayrollCalculator::monthName($m) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('month')" class="mt-2" />
                    </div>
                    <div class="form-group">
                        <x-input-label for="year" value="Tahun" />
                        <select id="year" name="year" required class="input mt-1 w-full">
                            @foreach (range(now()->year - 1, now()->year + 1) as $y)
                                <option value="{{ $y }}" @selected(old('year', $suggestedYear) == $y)>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('year')" class="mt-2" />
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Generate & Simpan Payroll
                </button>
            </form>
        </x-card>
    </div>
</x-app-layout>
