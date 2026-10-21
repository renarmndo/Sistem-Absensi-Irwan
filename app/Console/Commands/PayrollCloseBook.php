<?php

namespace App\Console\Commands;

use App\Models\Payroll;
use Illuminate\Console\Command;

class PayrollCloseBook extends Command
{
    protected $signature = 'payroll:close-book
                            {--month= : Bulan (1-12, default: bulan sebelumnya)}
                            {--year= : Tahun (default: tahun sebelumnya jika bulan=1)}';

    protected $description = 'Finalisasi dan kunci periode payroll (set semua payroll pending ke approved)';

    public function handle(): int
    {
        $month = (int) ($this->option('month') ?? now()->subMonth()->month);
        $year = (int) ($this->option('year')
            ?? ($month === 12 ? now()->year - 1 : now()->year));

        if ($month < 1 || $month > 12) {
            $this->error('Bulan harus antara 1-12.');

            return self::FAILURE;
        }

        $count = Payroll::where('period_month', $month)
            ->where('period_year', $year)
            ->where('approval_status', 'pending')
            ->count();

        if ($count === 0) {
            $this->info("Tidak ada payroll pending untuk periode {$month}/{$year}.");

            return self::SUCCESS;
        }

        if (! $this->confirm("Finalisasi {$count} payroll periode {$month}/{$year}? Semua akan di-approve.", true)) {
            $this->info('Dibatalkan.');

            return self::SUCCESS;
        }

        Payroll::where('period_month', $month)
            ->where('period_year', $year)
            ->where('approval_status', 'pending')
            ->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]);

        $this->info("{$count} payroll periode {$month}/{$year} berhasil difinalisasi.");

        return self::SUCCESS;
    }
}
