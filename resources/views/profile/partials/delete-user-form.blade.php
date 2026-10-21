<section class="space-y-6">
    <header>
        <h2 class="font-heading text-lg font-semibold text-slate-900">Hapus Akun</h2>
        <p class="mt-1 text-sm text-slate-500">Setelah akun dihapus, semua data akan terhapus permanen. Harap unduh data yang ingin disimpan sebelumnya.</p>
    </header>

    <button type="button" x-data x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="btn-primary bg-status-alfa hover:bg-red-700">
        Hapus Akun
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-5">
            @csrf
            @method('delete')

            <h2 class="font-heading text-lg font-semibold text-slate-900">Apakah Anda yakin?</h2>
            <p class="text-sm text-slate-500">Masukkan kata sandi untuk konfirmasi penghapusan akun secara permanen.</p>

            <div>
                <x-input-label for="password" value="Kata Sandi" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4" placeholder="Kata Sandi" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" class="btn-secondary" x-on:click="$dispatch('close')">Batal</button>
                <button type="submit" class="btn-primary bg-status-alfa hover:bg-red-700">Hapus Akun</button>
            </div>
        </form>
    </x-modal>
</section>
