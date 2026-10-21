<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; line-height: 1.5; color: #1e293b; margin: 0; padding: 30px; }
        h1 { font-size: 18px; font-weight: 700; text-align: center; margin: 0 0 4px; text-transform: uppercase; letter-spacing: 1px; }
        .subtitle { text-align: center; font-size: 12px; color: #64748b; margin-bottom: 24px; }
        .filter-info { font-size: 10px; color: #64748b; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { padding: 6px 8px; text-align: left; border: 1px solid #e2e8f0; }
        th { background: #f1f5f9; font-weight: 700; font-size: 9px; text-transform: uppercase; }
        .text-center { text-align: center; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 24px; padding-top: 12px; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <h1>Laporan Absensi</h1>
    <p class="subtitle">PT. Pesona Putra Perkasa</p>

    <div class="filter-info">
        <p>
            @if (!empty($params['date_from']))
                Dari: {{ \Carbon\Carbon::parse($params['date_from'])->format('d/m/Y') }}
            @endif
            @if (!empty($params['date_to']))
                Sampai: {{ \Carbon\Carbon::parse($params['date_to'])->format('d/m/Y') }}
            @endif
            @if (!empty($params['status']))
                | Status: {{ $params['status'] }}
            @endif
            @if (empty($params['date_from']) && empty($params['date_to']) && empty($params['status']))
                Semua data
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Masuk</th>
                <th>Pulang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $i => $attendance)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $attendance->user->employee_id }}</td>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                    <td>{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }} | Sistem Absensi Karyawan PT. Pesona Putra Perkasa</p>
    </div>
</body>
</html>
