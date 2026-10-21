<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('salaries.index') }}" class="transition-colors" style="color: #8C97A1;" onmouseover="this.style.color='#1E5F8C'" onmouseout="this.style.color='#8C97A1'">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7" />
                </svg>
            </a>
            <span>Tambah Data Gaji</span>
        </div>
    </x-slot>

    <div class="space-y-5">
        <x-card class="stagger-1 animate-fade-in-up">
            @if ($employees->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="empty-state-title">Semua Karyawan Sudah Punya Gaji</p>
                    <p class="empty-state-description">Tidak ada karyawan yang perlu ditambahkan gajinya. Edit gaji yang sudah ada jika perlu perubahan.</p>
                    <a href="{{ route('salaries.index') }}" class="btn-primary mt-4">Lihat Data Gaji</a>
                </div>
            @else
                <form method="POST" action="{{ route('salaries.store') }}" class="space-y-5">
                    @csrf

                    <div class="form-group">
                        <x-input-label for="user_id" value="Karyawan" />
                        <select id="user_id" name="user_id" required class="input mt-1 w-full">
                            <option value="">Pilih karyawan</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}" @selected(old('user_id') == $emp->id)>
                                    {{ $emp->employee_id }} — {{ $emp->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="form-group">
                            <x-input-label for="basic_salary" value="Gaji Pokok" />
                            <div class="relative mt-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm" style="color: #8C97A1;">Rp</span>
                                <input id="basic_salary" type="number" name="basic_salary" step="0.01" min="0"
                                       value="{{ old('basic_salary') }}" required
                                       class="input w-full pl-10" placeholder="0">
                            </div>
                            <x-input-error :messages="$errors->get('basic_salary')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <x-input-label for="allowance" value="Tunjangan Tetap" />
                            <div class="relative mt-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm" style="color: #8C97A1;">Rp</span>
                                <input id="allowance" type="number" name="allowance" step="0.01" min="0"
                                       value="{{ old('allowance', 0) }}" required
                                       class="input w-full pl-10" placeholder="0">
                            </div>
                            <x-input-error :messages="$errors->get('allowance')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <a href="{{ route('salaries.index') }}" class="btn-secondary flex-1 text-center text-sm">Batal</a>
                        <button type="submit" class="btn-primary flex-1 text-sm">Simpan</button>
                    </div>
                </form>
            @endif
        </x-card>
    </div>
</x-app-layout>
