# System Requirements Specification (SRS)

## 1. Spesifikasi Teknologi (Tech Stack)
*   **Backend Framework:** Laravel 13 (PHP >= 8.3)
*   **Database:** MySQL 8.x
*   **Frontend Styling:** Tailwind CSS v4 / v3 (via Vite)
*   **Libraries Tambahan:**
    *   `dompdf/dompdf`: Untuk kebutuhan cetak laporan dan slip gaji ke PDF.
    *   `maatwebsite/excel`: Untuk ekspor laporan absensi dan penggajian ke format Excel.
    *   `Chart.js`: Untuk visualisasi data kehadiran (grafik lingkaran/batang) di dashboard pimpinan.
    *   HTML5 Geolocation API & `Webcam.js`: Untuk menangkap data lokasi dan foto wajah dari browser pengguna.

## 2. Kebutuhan Fungsional (Functional Requirements)

### A. Peran: Karyawan (Staff)
*   **FR-01:** Karyawan wajib melakukan login untuk mengakses sistem.
*   **FR-02:** Karyawan dapat melakukan Absen Masuk & Absen Pulang dengan syarat membagikan lokasi GPS dan mengizinkan akses kamera wajah.
*   **FR-03:** Karyawan dapat melihat status kehadiran pribadi secara real-time pada dashboard.
*   **FR-04:** Karyawan dapat mengajukan surat Izin, Sakit, atau Cuti dengan mengisi form dan mengunggah dokumen/foto bukti pendukung.
*   **FR-05:** Karyawan dapat melihat riwayat pengajuan dan mengunduh slip gaji bulanan mereka sendiri.

### B. Peran: HRD / Admin
*   **FR-06:** HRD dapat mengelola data karyawan (CRUD Data Karyawan, Jabatan, Gaji Pokok).
*   **FR-07:** HRD dapat mengubah parameter koordinat kantor (Latitude, Longitude) dan batas radius (dalam meter).
*   **FR-08:** HRD berhak menyetujui (Approve) atau menolak (Reject) pengajuan izin/cuti karyawan.
*   **FR-09:** HRD dapat melakukan trigger kalkulasi payroll bulanan yang datanya ditarik otomatis dari rekap absensi.
*   **FR-10:** HRD dapat mengunduh seluruh laporan absensi dan penggajian dalam bentuk PDF/Excel.

### C. Peran: Pimpinan (Management)
*   **FR-11:** Pimpinan mendapatkan akses visualisasi data kehadiran karyawan hari ini secara real-time (Grafik lingkaran/batang).
*   **FR-12:** Pimpinan dapat meninjau rekapitulasi pengeluaran gaji bulanan perusahaan.

## 3. Kebutuhan Non-Fungsional (Non-Functional Requirements)
*   **Security:** Proteksi pencatatan presensi dari manipulasi lokasi (*GPS Spoofing*) dengan melakukan validasi sisi server menggunakan backend Laravel, bukan sekadar validasi JavaScript di frontend.
*   **Usability:** Antarmuka aplikasi harus menggunakan pendekatan *Mobile-First Design* untuk halaman karyawan (karena mayoritas absen via HP) dan *Desktop-Friendly* untuk halaman HRD/Pimpinan.
*   **Performance:** Proses kalkulasi payroll bulanan untuk seluruh karyawan tidak boleh menyebabkan *timeout* pada server (Optimasi Eloquent query dengan *eager loading*).