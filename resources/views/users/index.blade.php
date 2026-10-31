<x-app-layout>
    <x-slot name="header">Data Karyawan</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between stagger-1 animate-fade-in-up">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, NIK..."
                       class="input w-full sm:w-64">
                <select name="role" class="input">
                    <option value="">Semua Role</option>
                    <option value="karyawan" @selected(request('role') === 'karyawan')>Karyawan</option>
                    <option value="hrd" @selected(request('role') === 'hrd')>HRD</option>
                </select>
                <button type="submit" class="btn-primary btn-sm">Filter</button>
            </form>
            @can('hrd')
            <a href="{{ route('users.create') }}" class="btn-primary btn-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Karyawan
            </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert-success stagger-2 animate-fade-in-up">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert-error stagger-2 animate-fade-in-up">{{ session('error') }}</div>
        @endif

        <x-card class="stagger-3 animate-fade-in-up">
            @if ($users->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <p class="empty-state-title">Belum Ada Karyawan</p>
                    <p class="empty-state-description">Tambahkan karyawan baru untuk mulai mengelola data.</p>
                    @can('hrd')
                    <a href="{{ route('users.create') }}" class="btn-primary mt-4">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Karyawan
                    </a>
                    @endcan
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Jabatan</th>
                                <th>Role</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="font-mono text-xs text-gray-500">{{ $user->employee_id }}</td>
                                    <td class="font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="text-gray-600">{{ $user->email }}</td>
                                    <td class="text-gray-600">{{ $user->position ?? '-' }}</td>
                                    <td>
                                        @php
                                            $roleBadge = match($user->role?->name) {
                                                'hrd' => 'badge-pending',
                                                'pimpinan' => 'badge-approved',
                                                default => 'badge-hadir',
                                            };
                                        @endphp
                                        <span class="badge {{ $roleBadge }}">{{ $user->role?->display_name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @can('hrd')
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('users.edit', $user) }}" class="btn-ghost btn-sm">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                            @if ($user->id !== auth()->id())
                                                <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                      onsubmit="return confirm('Hapus karyawan {{ $user->name }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-ghost btn-sm text-red-600 hover:bg-red-50">
                                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $users->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
