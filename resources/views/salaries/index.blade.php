<x-app-layout>
    <x-slot name="header">Data Gaji Karyawan</x-slot>

    <div class="space-y-5">
        <div class="flex items-center justify-between stagger-1 animate-fade-in-up">
            <p class="text-sm" style="color: #8C97A1;">Kelola gaji pokok dan tunjangan tetap karyawan</p>
            <a href="{{ route('salaries.create') }}" class="btn-primary btn-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Gaji
            </a>
        </div>

        @if (session('success'))
            <div class="alert-success stagger-2 animate-fade-in-up">{{ session('success') }}</div>
        @endif

        <x-card class="stagger-3 animate-fade-in-up">
            @if ($salaries->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="empty-state-title">Belum Ada Data Gaji</p>
                    <p class="empty-state-description">Tambahkan data gaji pokok dan tunjangan untuk setiap karyawan.</p>
                    <a href="{{ route('salaries.create') }}" class="btn-primary mt-4">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Gaji
                    </a>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>Gaji Pokok</th>
                                <th>Tunjangan</th>
                                <th>Total</th>
                                <th>Terakhir Diubah</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salaries as $salary)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-[10px] font-bold text-white"
                                                 style="background: linear-gradient(135deg, #1E5F8C, #143B57);">
                                                {{ strtoupper(substr($salary->user->name, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-[13px] font-medium truncate" style="color: #16222E;">{{ $salary->user->name }}</p>
                                                <p class="text-[11px] truncate" style="color: #8C97A1;">{{ $salary->user->employee_id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-mono text-[13px]" style="color: #16222E;">Rp{{ number_format($salary->basic_salary, 0, ',', '.') }}</td>
                                    <td class="font-mono text-[13px]" style="color: #16222E;">Rp{{ number_format($salary->allowance, 0, ',', '.') }}</td>
                                    <td class="font-mono text-[13px] font-semibold" style="color: #1E5F8C;">Rp{{ number_format($salary->basic_salary + $salary->allowance, 0, ',', '.') }}</td>
                                    <td class="text-[12px]" style="color: #48555F;">{{ \Carbon\Carbon::parse($salary->effective_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('salaries.edit', $salary) }}" class="btn-ghost btn-sm">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('salaries.destroy', $salary) }}" method="POST"
                                                  onsubmit="return confirm('Hapus data gaji {{ $salary->user->name }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-ghost btn-sm text-red-600 hover:bg-red-50">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $salaries->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
