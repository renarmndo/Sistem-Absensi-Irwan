<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="font-heading text-2xl font-bold text-slate-900">Lupa Kata Sandi</h1>
        <p class="mt-1 text-sm text-slate-500">Masukkan email Anda, kami akan kirim tautan reset</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@perusahaan.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button>
            Kirim Tautan Reset
        </x-primary-button>

        <p class="text-center text-sm text-slate-500">
            <a href="{{ route('login') }}" class="link-primary">Kembali ke masuk</a>
        </p>
    </form>
</x-guest-layout>
