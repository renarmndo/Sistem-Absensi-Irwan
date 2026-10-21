<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('leave-requests.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7l-7-7 7-7" />
                </svg>
            </a>
            <span>Buat Pengajuan Baru</span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <x-card class="stagger-1 animate-fade-in-up">
            <form method="POST" action="{{ route('leave-requests.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div class="form-group">
                    <x-input-label for="type" value="Jenis Pengajuan" />
                    <select id="type" name="type" required class="input mt-1 w-full">
                        <option value="">Pilih jenis pengajuan</option>
                        <option value="Izin" @selected(old('type') === 'Izin')>Izin</option>
                        <option value="Sakit" @selected(old('type') === 'Sakit')>Sakit</option>
                        <option value="Cuti" @selected(old('type') === 'Cuti')>Cuti</option>
                    </select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="form-group">
                        <x-input-label for="start_date" value="Tanggal Mulai" />
                        <x-text-input id="start_date" class="mt-1" type="date" name="start_date"
                            :value="old('start_date')" required />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>

                    <div class="form-group">
                        <x-input-label for="end_date" value="Tanggal Selesai" />
                        <x-text-input id="end_date" class="mt-1" type="date" name="end_date"
                            :value="old('end_date')" required />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                </div>

                <div class="form-group">
                    <x-input-label for="reason" value="Alasan" />
                    <textarea id="reason" name="reason" rows="4" required
                              class="input mt-1 w-full"
                              placeholder="Jelaskan alasan pengajuan Anda...">{{ old('reason') }}</textarea>
                    <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                </div>

                <div class="form-group">
                    <x-input-label for="attachment" value="Lampiran (opsional)" />
                    <div class="mt-1 flex items-center gap-4">
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            Pilih File
                            <input type="file" id="attachment" name="attachment" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        </label>
                        <span id="file-name" class="text-sm text-gray-400">Tidak ada file dipilih</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, PDF, DOC. Maksimal 2MB.</p>
                    <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('leave-requests.index') }}" class="btn-secondary flex-1 text-center text-sm">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary flex-1 text-sm">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    @push('scripts')
    <script>
        document.getElementById('attachment')?.addEventListener('change', function(e) {
            const name = e.target.files[0]?.name || 'Tidak ada file dipilih';
            document.getElementById('file-name').textContent = name;
        });
    </script>
    @endpush
</x-app-layout>
