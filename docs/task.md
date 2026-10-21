# Proyek Tracker: Sistem Presensi & Payroll PT. Pesona Putra Perkasa

> Dokumen terkait: flow.md · requirement.md · design.md · migration.md · absensi-geolocation.md
> Catatan: tabel pengaturan koordinat & radius kantor pakai nama `office_settings` secara konsisten di seluruh kode.

## Phase 1: Inisialisasi & Setup Arsitektur
- [X] Setup project Laravel baru & konfigurasi `.env`
- [X] Install dan konfigurasi Tailwind CSS melalui Vite
- [X] Setup database MySQL dan verifikasi koneksi data
- [X] Terapkan design token (CSS variables) di `app.css` (Tailwind v4 `@theme`) sesuai design.md
- [X] Membuat base layout admin & komponen UI reusable (card, badge status, tombol absen, sidebar role-based)
- [X] Implementasi sistem Autentikasi (Laravel Breeze Blade Stack)
- [X] `php artisan storage:link` (persiapan simpan foto selfie)

## Phase 2: Desain Database & Relasi (Migration & Models)
- [X] Migrasi & model `roles` dan `users` (kolom `role_id`, `employee_id`, `phone`, `position`)
- [X] Migrasi & model `office_settings` (`latitude`, `longitude`, `radius_meter`, `work_start_time`, `work_end_time`)
- [X] Migrasi & model `attendances` + kolom geofencing & selfie:
    - [X] `lat_in` / `long_in` / `distance_in` / `selfie_in`
    - [X] `lat_out` / `long_out` / `distance_out` / `selfie_out`
    - [X] `status` (Hadir/Terlambat), unique `(user_id, date)`
- [X] Migrasi & model `leave_requests` (type, start/end date, reason, attachment, status, `approved_by`)
- [X] Migrasi & model `salaries` & `payrolls` (unique `(user_id, period_month, period_year)`)
- [X] Definisikan relasi Eloquent (Role, User, Attendance, LeaveRequest, Salary, Payroll, OfficeSetting)
- [X] Database Seeding: role (Karyawan/HRD/Pimpinan), akun admin (hrd@example.com, direktur@example.com, karyawan@example.com), & koordinat kantor di `office_settings`

## Phase 3: Fitur Inti — Presensi, Geofencing & Selfie (Sprint 1)
- [X] Implementasi HTML5 Geolocation API pada frontend (Alpine.js → fetch API)
- [X] Implementasi kamera / Selfie Capture (MediaDevices getUserMedia → canvas → base64 JPEG)
- [X] Saat klik Absen Masuk/Pulang: ambil GPS DAN langsung buka kamera depan
- [X] Service `GeofenceService` untuk perhitungan Formula Haversine (jarak meter) di `app/Services/GeofenceService.php`
- [X] `AttendanceController@store`: validasi jarak karyawan vs koordinat kantor (tolak jika > radius)
- [X] Simpan koordinat + foto selfie + jarak ke tabel `attendances`
- [X] Logic status otomatis Hadir/Terlambat (bandingkan jam masuk vs `work_start_time`)
- [X] Cegah absen masuk ganda dalam 1 hari (unique constraint + logic check)
- [X] Handle kondisi error: GPS mati/ditolak, di luar radius, kamera diblokir, server error, akurasi rendah
- [X] HTTPS requirement (syarat Geolocation & kamera; handled browser-side)
- [X] Halaman presensi utama + riwayat presensi individu (30 hari terakhir & full history dengan filter bulan/tahun)

## Phase 4: Fitur Pengajuan Izin & Cuti (Sprint 2)
- [X] Form pengajuan izin/cuti/sakit (pilih jenis, tanggal, alasan, upload berkas pendukung)
- [X] Simpan ke `leave_requests` dengan status awal `Pending`
- [X] Halaman pantau status pengajuan untuk Karyawan (Pending/Approved/Rejected)
- [X] Dashboard review pengajuan untuk HRD
- [X] Fitur approval/rejection (set `status`, `approved_by`, `reviewed_at`, `rejection_reason`) dengan modal dialog
- [ ] Integrasi otomatis: tanggal Approved ditandai sebagai Izin/Cuti/Sakit (dikecualikan dari perhitungan Alfa)
- [ ] Notifikasi hasil pengajuan ke Karyawan

## Phase 5: Otomatisasi Kalkulasi Payroll (Sprint 3)
- [X] Halaman master data komponen gaji tetap karyawan / `salaries` — HRD CRUD (Create, Edit, Delete)
- [X] Pengaturan hari kerja efektif (`effective_work_days` di `office_settings`) & tabel `holidays`
- [X] Service `PayrollCalculator` — rekapitulasi kehadiran & hitung Alfa per bulan (perhitungan hari libur & izin approved)
- [X] Fungsi otomasi hitung potongan: Potongan = (Gaji Pokok / Hari Kerja Efektif) × Jumlah Alfa
- [X] Hitung gaji bersih: Gaji Pokok + Tunjangan Tetap − Potongan (dibungkus DB transaction via `updateOrCreate`)
- [X] Fitur "Generate Payroll" bulanan → simpan ke `payrolls` (unique per `user_id, period_month, period_year`)
- [X] Desain Slip Gaji digital (web view `payrolls.show`) & cetak PDF via DomPDF (`payrolls.pdf`)
- [X] Karyawan dapat melihat & mengunduh slip gaji sendiri (filter by role di `PayrollController@index`)

## Phase 6: Pelaporan & Dashboard Monitoring (Sprint 4)
- [X] Dashboard utama Pimpinan (`reports.dashboard`): ringkasan statistik real-time (hadir/terlambat/izin/alfa) + Chart.js bar chart grafik kehadiran bulanan
- [X] Grafik kehadiran harian menggunakan Chart.js (via npm, bundled dengan Vite)
- [X] Filter laporan absensi (karyawan, rentang tanggal, status) via JOIN `users` + `attendances` + filtering di `ReportController`
- [X] Ekspor laporan ke PDF (DomPDF) dan CSV/Excel (stream response dengan BOM UTF-8)
- [X] Fitur review & otorisasi payroll oleh Pimpinan (approve/reject dengan `approval_status`, `approved_by`, `approved_at`)

## Phase 7: Pengujian, Refactoring & Deployment
- [ ] Pengujian akurasi Geofencing langsung di lapangan dengan berbagai perangkat
- [ ] Pengujian unit & feature (auth, presensi, approval, payroll)
- [ ] Audit keamanan (CSRF, validasi input, RBAC Gates/Policies)
- [ ] Refactoring & optimasi query database + indexing; rapikan kode dengan Laravel Pint
- [ ] Simulasi penutupan buku payroll akhir bulan
- [ ] Setup backup database otomatis
- [ ] Deployment ke server production (Hosting / VPS) + `php artisan optimize`