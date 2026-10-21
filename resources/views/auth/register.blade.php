<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="font-heading text-2xl font-bold" style="color: #16222E;">Daftar Akun</h1>
        <p class="mt-1 text-sm" style="color: #8C97A1;">Buat akun baru untuk memulai</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="form-group">
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input
                id="name"
                class="mt-1"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Nama Anda"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="mt-1"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
                placeholder="nama@perusahaan.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-group">
            <x-input-label for="password" :value="__('Kata Sandi')" />
            <x-text-input
                id="password"
                class="mt-1"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
            <x-text-input
                id="password_confirmation"
                class="mt-1"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Ulangi kata sandi"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center mt-6">
            Daftar
        </x-primary-button>

        <p class="text-center text-sm" style="color: #8C97A1;">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="link-primary font-semibold">Masuk</a>
        </p>
    </form>
</x-guest-layout>
