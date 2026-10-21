<section>
    <header class="mb-6">
        <h2 class="font-heading text-lg font-semibold text-slate-900">Informasi Profil</h2>
        <p class="mt-1 text-sm text-slate-500">Perbarui informasi profil dan email akun Anda.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-lg bg-amber-50 border border-amber-200 p-3 text-sm text-amber-700">
                    Email Anda belum diverifikasi.
                    <button form="send-verification" class="link-primary underline">Klik untuk kirim ulang verifikasi.</button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm text-status-hadir">Tautan verifikasi baru telah dikirim ke email Anda.</p>
                @endif
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">Simpan</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-status-hadir">
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>
