<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="font-heading text-2xl font-bold text-slate-900">Verifikasi Email</h1>
        <p class="mt-1 text-sm text-slate-500">Terima kasih telah mendaftar! Sebelum memulai, verifikasi email Anda dengan mengklik tautan yang kami kirim.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-3 text-sm text-status-hadir">
            Tautan verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                Kirim Ulang Email Verifikasi
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-center text-sm font-medium text-slate-500 hover:text-slate-700">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
