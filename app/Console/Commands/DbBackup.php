<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DbBackup extends Command
{
    protected $signature = 'db:backup
                            {--database=mysql : Nama koneksi database}
                            {--path= : Direktori tujuan backup (default: storage/app/backups)}';

    protected $description = 'Backup database menggunakan mysqldump (MySQL) atau sqlite3 (SQLite)';

    public function handle(): int
    {
        $connection = config("database.connections.{$this->option('database')}");

        if (! $connection) {
            $this->error("Koneksi database '{$this->option('database')}' tidak ditemukan.");

            return self::FAILURE;
        }

        $path = $this->option('path') ?? storage_path('app/backups');
        $filename = 'backup-'.now()->format('Y-m-d_H-i-s').'.sql';
        $fullPath = $path.DIRECTORY_SEPARATOR.$filename;

        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $driver = $connection['driver'] ?? 'mysql';

        if ($driver === 'mysql') {
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
                escapeshellarg($connection['host'] ?? '127.0.0.1'),
                escapeshellarg($connection['port'] ?? '3306'),
                escapeshellarg($connection['username'] ?? 'root'),
                escapeshellarg($connection['password'] ?? ''),
                escapeshellarg($connection['database']),
                escapeshellarg($fullPath),
            );

            system($command, $exitCode);
        } elseif ($driver === 'sqlite') {
            $dbPath = $connection['database'];
            $command = sprintf('sqlite3 %s ".dump" > %s', escapeshellarg($dbPath), escapeshellarg($fullPath));
            system($command, $exitCode);
        } else {
            $this->error("Driver '{$driver}' belum didukung.");

            return self::FAILURE;
        }

        if ($exitCode === 0) {
            $this->info("Backup berhasil: {$fullPath}");
            $this->info('Ukuran: '.$this->formatBytes(filesize($fullPath)));

            return self::SUCCESS;
        }

        $this->error('Backup gagal. Pastikan mysqldump/sqlite3 tersedia di PATH.');

        return self::FAILURE;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}
