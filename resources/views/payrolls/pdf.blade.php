<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Slip Gaji — {{ $payroll->user->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.5; color: #1e293b; margin: 0; padding: 40px; }
        h1 { font-size: 20px; font-weight: 700; text-align: center; margin: 0 0 4px; text-transform: uppercase; letter-spacing: 2px; }
        .subtitle { text-align: center; font-size: 13px; color: #64748b; margin-bottom: 30px; }
        .header-box { border-bottom: 2px solid #1e293b; padding-bottom: 16px; margin-bottom: 24px; }
        .info-grid { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; }
        .info-item { flex: 1 1 45%; }
        .info-label { font-size: 10px; color: #94a3b8; text-transform: uppercase; }
        .info-value { font-weight: 600; font-size: 13px; color: #1e293b; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { padding: 8px 12px; text-align: left; }
        th { border-bottom: 2px solid #cbd5e1; font-weight: 700; }
        td { border-bottom: 1px solid #e2e8f0; }
        .text-right { text-align: right; }
        .text-success { color: #059669; }
        .text-danger { color: #dc2626; }
        .text-primary { color: #2563eb; }
        .mono { font-family: 'Courier New', monospace; }
        .total-row td { border-top: 2px solid #1e293b; font-weight: 700; font-size: 14px; padding-top: 12px; }
        .strike td { border-top: 2px solid #fca5a5; }
        .footer { text-align: center; font-size: 10px; color: #94a3b8; margin-top: 40px; padding-top: 16px; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <h1>Slip Gaji</h1>
    <p class="subtitle">{{ \App\Services\PayrollCalculator::monthName($payroll->period_month) }} {{ $payroll->period_year }}</p>

    <div class="header-box">
        <table>
            <tr>
                <td style="width: 50%">
                    <div class="info-label">Nama Karyawan</div>
                    <div class="info-value">{{ $payroll->user->name }}</div>
                </td>
                <td>
                    <div class="info-label">NIK</div>
                    <div class="info-value">{{ $payroll->user->employee_id }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-label">Jabatan</div>
                    <div class="info-value">{{ $payroll->user->position ?? '-' }}</div>
                </td>
                <td>
                    <div class="info-label">Periode</div>
                    <div class="info-value">{{ \App\Services\PayrollCalculator::monthName($payroll->period_month) }} {{ $payroll->period_year }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td class="text-right mono">Rp{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan Tetap</td>
                <td class="text-right mono">Rp{{ number_format($payroll->allowance, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Kehadiran</td>
                <td class="text-right">{{ $payroll->total_attendance }} hari</td>
            </tr>
            <tr>
                <td>Total Alfa</td>
                <td class="text-right">{{ $payroll->total_absence_alfa }} hari</td>
            </tr>
            <tr class="strike">
                <td style="font-weight: 600; color: #dc2626;">Potongan Alfa</td>
                <td class="text-right mono" style="font-weight: 600; color: #dc2626;">(Rp{{ number_format($payroll->cuts, 0, ',', '.') }})</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td style="font-size: 14px;">Take Home Pay</td>
                <td class="text-right mono" style="font-size: 14px; color: #2563eb; font-weight: 700;">
                    Rp{{ number_format($payroll->take_home_pay, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Slip Gaji ini digenerate secara otomatis oleh Sistem Absensi Karyawan PT. Pesona Putra Perkasa</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
