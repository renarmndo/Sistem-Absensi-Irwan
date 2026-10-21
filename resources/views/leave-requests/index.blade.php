<x-app-layout>
    <x-slot name="header">{{ auth()->user()->role?->name === 'hrd' ? 'Semua Pengajuan' : 'Pengajuan Saya' }}</x-slot>

    <div class="space-y-6">
        @if (auth()->user()->role?->name !== 'hrd')
            <div class="flex justify-end stagger-1 animate-fade-in-up">
                <a href="{{ route('leave-requests.create') }}" class="btn-primary btn-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Pengajuan
                </a>
            </div>
        @endif

        @if (session('success'))
            <div class="alert-success stagger-1 animate-fade-in-up">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <x-card class="stagger-2 animate-fade-in-up">
            @if ($leaveRequests->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="empty-state-title">Belum Ada Pengajuan</p>
                    <p class="empty-state-description">
                        @if (auth()->user()->role?->name === 'hrd')
                            Belum ada pengajuan izin, sakit, atau cuti dari karyawan.
                        @else
                            Anda belum membuat pengajuan izin, sakit, atau cuti.
                        @endif
                    </p>
                    @if (auth()->user()->role?->name !== 'hrd')
                        <a href="{{ route('leave-requests.create') }}" class="btn-primary mt-4">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Pengajuan
                        </a>
                    @endif
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                @if (auth()->user()->role?->name === 'hrd')
                                    <th>Karyawan</th>
                                @endif
                                <th>Jenis</th>
                                <th>Tanggal</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                @if (auth()->user()->role?->name === 'hrd')
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaveRequests as $request)
                                <tr>
                                    @if (auth()->user()->role?->name === 'hrd')
                                        <td>
                                            <div style="color: #16222E;" class="font-medium">{{ $request->user->name }}</div>
                                            <div class="text-xs" style="color: #8C97A1;">{{ $request->user->employee_id ?? '-' }}</div>
                                        </td>
                                    @endif
                                    <td>
                                        @php
                                            $badgeComponent = match(strtolower($request->type)) {
                                                'izin' => 'badge-izin',
                                                'sakit' => 'badge-sakit',
                                                'cuti' => 'badge-cuti',
                                                default => 'badge-pending',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeComponent }}">{{ ucfirst($request->type) }}</span>
                                    </td>
                                    <td style="color: #48555F;">
                                        {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }}
                                        @if ($request->start_date != $request->end_date)
                                            — {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="max-w-xs truncate" style="color: #8C97A1;">{{ $request->reason }}</td>
                                    <td>
                                        @php
                                            $statusBadge = match ($request->status) {
                                                'Approved' => 'badge-approved',
                                                'Rejected' => 'badge-rejected',
                                                default => 'badge-pending',
                                            };
                                            $statusLabel = match ($request->status) {
                                                'Approved' => 'Disetujui',
                                                'Rejected' => 'Ditolak',
                                                default => 'Pending',
                                            };
                                        @endphp
                                        <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                                    </td>
                                    @if (auth()->user()->role?->name === 'hrd')
                                        <td>
                                            <div class="flex items-center gap-2">
                                                @if ($request->status === 'Pending')
                                                    <form action="{{ route('leave-requests.approve', $request) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn-approve btn-sm">Setujui</button>
                                                    </form>
                                                    <button type="button" @click="$refs.reject{{ $request->id }}.showModal()" class="btn-reject btn-sm">Tolak</button>
                                                @elseif ($request->status === 'Rejected' && $request->rejection_reason)
                                                    <button type="button" @click="$refs.reason{{ $request->id }}.showModal()" class="link-primary text-xs">
                                                        Lihat Alasan
                                                    </button>
                                                @elseif ($request->status === 'Approved' && $request->approver)
                                                    <span class="text-xs" style="color: #8C97A1;">Oleh: {{ $request->approver->name }}</span>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $leaveRequests->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
