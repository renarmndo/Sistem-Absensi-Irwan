<x-app-layout>
    <x-slot name="header">Laporan Absensi</x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="alert-success stagger-1 animate-fade-in-up">{{ session('success') }}</div>
        @endif

        <x-card class="stagger-2 animate-fade-in-up">
            <x-slot name="header">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </div>
                    <h2 class="font-heading text-base font-semibold text-gray-900">Filter Laporan</h2>
                </div>
            </x-slot>
            <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <div class="form-group">
                    <x-input-label for="user_id" value="Karyawan" />
                    <select id="user_id" name="user_id" class="input mt-1">
                        <option value="">Semua Karyawan</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}" @selected(request('user_id') == $emp->id)>
                                {{ $emp->employee_id }} — {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <x-input-label for="date_from" value="Dari Tanggal" />
                    <x-text-input id="date_from" class="mt-1" type="date" name="date_from" :value="request('date_from')" />
                </div>

                <div class="form-group">
                    <x-input-label for="date_to" value="Sampai Tanggal" />
                    <x-text-input id="date_to" class="mt-1" type="date" name="date_to" :value="request('date_to')" />
                </div>

                <div class="form-group">
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="input mt-1">
                        <option value="">Semua Status</option>
                        <option value="Hadir" @selected(request('status') === 'Hadir')>Hadir</option>
                        <option value="Terlambat" @selected(request('status') === 'Terlambat')>Terlambat</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn-secondary px-3">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>
            </form>
        </x-card>

        @if (request()->hasAny(['user_id', 'date_from', 'date_to', 'status']))
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between stagger-3 animate-fade-in-up">
                <p class="text-sm text-gray-500">
                    Menampilkan {{ $attendances->firstItem() }}–{{ $attendances->lastItem() }} dari {{ $attendances->total() }} data
                </p>
                <div class="flex gap-2">
                    <a href="{{ route('reports.pdf', request()->query()) }}" target="_blank" class="btn-secondary btn-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        PDF
                    </a>
                    <a href="{{ route('reports.excel', request()->query()) }}" class="btn-secondary btn-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Excel
                    </a>
                </div>
            </div>
        @endif

        <x-card class="stagger-3 animate-fade-in-up">
            @if ($attendances->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="empty-state-title">Tidak Ada Data</p>
                    <p class="empty-state-description">Gunakan filter untuk menampilkan data absensi.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td class="font-mono text-xs text-gray-500">{{ $attendance->user->employee_id }}</td>
                                    <td class="font-medium text-gray-900">{{ $attendance->user->name }}</td>
                                    <td class="text-gray-600">{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                                    <td>{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>
                                    <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                                    <td>
                                        @php
                                            $badge = 'badge-' . strtolower($attendance->status);
                                        @endphp
                                        <span class="badge {{ $badge }}">{{ $attendance->status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $attendances->withQueryString()->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
