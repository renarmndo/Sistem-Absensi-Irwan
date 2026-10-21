<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="font-heading text-2xl font-bold" style="color: #16222E;">Masuk</h1>
        <p class="mt-1 text-sm" style="color: #8C97A1;">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="mt-1"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
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
                autocomplete="current-password"
                placeholder="Masukkan kata sandi"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="h-4 w-4 rounded border-line text-primary focus:ring-primary cursor-pointer"
                    name="remember"
                >
                <span class="text-sm" style="color: #48555F;">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="link-primary" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center">
            Masuk
        </x-primary-button>

        <p class="text-center text-sm" style="color: #8C97A1;">
            Belum punya akun?
            <a href="{{ route('register') }}" class="link-primary font-semibold">Daftar</a>
        </p>
    </form>
</x-guest-layout>
