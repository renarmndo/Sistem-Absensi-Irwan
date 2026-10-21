# Database Architecture & UI Components Design

## 1. Skema Database MySQL (Relasional)

### Tabel: `office_settings`
Mencatat data acuan geofencing kantor. Hanya berisi 1 baris data aktif.
*   `id` (INT, Primary Key, Auto Increment)
*   `office_name` (VARCHAR)
*   `latitude` (DOUBLE / VARCHAR)
*   `longitude` (DOUBLE / VARCHAR)
*   `radius_meter` (INT) - *Default: 50*
*   `work_start_time` (TIME) - *Jam masuk kerja, contoh: 08:00:00*
*   `work_end_time` (TIME) - *Jam pulang kerja, contoh: 17:00:00*

### Tabel: `attendances`
Mencatat log absensi harian karyawan.
*   `id` (BIGINT, Primary Key, Auto Increment)
*   `user_id` (FOREIGN KEY -> `users.id`, Cascade)
*   `date` (DATE) - *Tanggal presensi*
*   `clock_in` (TIME, Nullable)
*   `clock_out` (TIME, Nullable)
*   `lat_in` (VARCHAR) - *Koordinat latitude saat absen masuk*
*   `long_in` (VARCHAR) - *Koordinat longitude saat absen masuk*
*   `distance_in` (INT) - *Jarak dari kantor saat absen masuk (meter)*
*   `selfie_in` (VARCHAR) - *Nama file gambar selfie masuk*
*   `lat_out` (VARCHAR, Nullable) - *Koordinat latitude saat absen pulang*
*   `long_out` (VARCHAR, Nullable) - *Koordinat longitude saat absen pulang*
*   `distance_out` (INT, Nullable) - *Jarak dari kantor saat absen pulang (meter)*
*   `selfie_out` (VARCHAR, Nullable) - *Nama file gambar selfie pulang*
*   `status` (ENUM: 'Hadir', 'Terlambat')
*   `created_at` / `updated_at`
*   UNIQUE (`user_id`, `date`)

### Tabel: `leave_requests`
Mencatat pengajuan tidak masuk kerja.
*   `id` (BIGINT, Primary Key, Auto Increment)
*   `user_id` (FOREIGN KEY -> `users.id`)
*   `type` (ENUM: 'Izin', 'Sakit', 'Cuti')
*   `start_date` (DATE)
*   `end_date` (DATE)
*   `reason` (TEXT)
*   `attachment` (VARCHAR, Nullable) - *File dokumen pendukung*
*   `status` (ENUM: 'Pending', 'Approved', 'Rejected')
*   `approved_by` (FOREIGN KEY -> `users.id`, Nullable) - *HRD yang menyetujui/menolak*

### Tabel: `salaries`
Mencatat master data gaji tetap per karyawan.
*   `id` (BIGINT, Primary Key, Auto Increment)
*   `user_id` (FOREIGN KEY -> `users.id`)
*   `basic_salary` (DECIMAL 15,2) - *Gaji pokok*
*   `allowance` (DECIMAL 15,2) - *Tunjangan tetap*
*   `effective_at` (DATE) - *Tanggal berlakunya gaji ini*
*   `created_at` / `updated_at`

### Tabel: `payrolls`
Mencatat hasil kalkulasi gaji bulanan.
*   `id` (BIGINT, Primary Key, Auto Increment)
*   `user_id` (FOREIGN KEY -> `users.id`)
*   `period_month` (TINYINT) - *Contoh: 06*
*   `period_year` (YEAR) - *Contoh: 2026*
*   `total_attendance` (INT)
*   `total_absence_alfa` (INT)
*   `basic_salary` (DECIMAL 15,2)
*   `allowance` (DECIMAL 15,2)
*   `cuts` (DECIMAL 15,2) - *Total potongan akibat alfa*
*   `take_home_pay` (DECIMAL 15,2) - *Gaji bersih akhir*
*   unique(`user_id`, `period_month`, `period_year`) - *Satu payroll per karyawan per bulan*

---

## 2. Panduan UI/UX (Tailwind CSS Component Structure)

### Halaman Presensi Karyawan (Mobile Responsive View)
*   **Header Card:** Menampilkan sisa waktu sebelum jam kerja dimulai menggunakan utility class Tailwind (`bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-2xl p-6 shadow-lg`).
*   **Kamera & Maps Preview Box:** Kotak aspek rasio 1:1 (`aspect-square w-full max-w-sm rounded-xl overflow-hidden border-2 border-dashed border-gray-300`) untuk menampilkan preview live webcam wajah karyawan sebelum tombol submit aktif.
*   **Action Button:** Tombol interaktif besar yang berubah status (`w-full py-4 rounded-xl text-lg font-bold transition-all`). Jika di luar radius, tombol otomatis terkunci (`bg-gray-400 cursor-not-allowed`), jika aman berubah menjadi hijau (`bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-200`).

### Dashboard Monitoring HRD & Pimpinan (Workspace View)
*   **Grid Statistik:** Menggunakan layout grid (`grid grid-cols-1 md:grid-cols-4 gap-6`) untuk menampilkan kotak indikator ringkas: Total Hadir (Hijau), Terlambat (Kuning), Izin/Cuti (Biru), Belum Absen/Alfa (Merah).
*   **Tabel Riwayat Modern:** Desain tabel clean menggunakan kelas utility Tailwind (`w-full text-left border-collapse`), baris data zebra-striping (`odd:bg-white even:bg-slate-50`), serta badge status yang dinamis menggunakan utility `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium`.