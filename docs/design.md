# Design System — SisAbsen v2

> Redesign Dashboard per Peran (Karyawan, HRD, Direktur)
> Gaya: Soft Modern, Warm Neutral · Aksen Biru Laut Pekat
> PT. Pesona Putra Perkasa

---

## 1. Filosofi & Apa yang Berubah dari Versi Sebelumnya

Versi sebelumnya sudah punya fondasi yang benar (warm neutral, biru laut, soft modern) tapi eksekusinya masih jatuh ke pola dashboard admin generik: empat kartu stat dengan ikon kotak, palet biru Tailwind standar, dan background abu dingin yang sebenarnya bertentangan dengan klaim "warm neutral" di judul. Versi ini menajamkan tiga hal:

| Area | Sebelumnya | Sekarang | Alasan |
|------|-----------|----------|--------|
| Primary color | `#2563EB` (Blue 600, default Tailwind) | `#1E5F8C` (Ocean, dikustom) | Lebih dalam dan less-saturated, terasa seperti pilihan sadar, bukan default framework |
| Surface | `#FAFBFC` (abu dingin) | `#FAF7F1` (krem hangat) | Baru benar-benar warm neutral, bukan abu yang dilabeli warm |
| Status colors | Saturasi penuh (Emerald 600, Amber 600, Red 600 dari Tailwind) | Diredupkan 10–15% | Elegan, tidak berteriak di layar yang dilihat berjam-jam tiap hari |
| Stat display | 4 kartu kotak + ikon dalam lingkaran berwarna | Metric strip horizontal, angka mono dipisah garis tipis | Pola kartu-ikon-stat adalah ciri paling umum dashboard hasil generate AI; metric strip lebih editorial dan hemat ruang |
| Elemen identitas | Tidak ada elemen unik — semua kartu, badge, tabel standar | Presence Arc (karyawan), Roster Pulse (HRD), Ritme Kehadiran (direktur) | Tiga visual yang berasal dari data presensi itu sendiri, bukan dekorasi tempelan |
| Angka & waktu | Font body biasa | `JetBrains Mono` untuk jam, nominal, statistik | Sistem absensi & payroll sangat angka-sentris — tipografi mono memberi kesan presisi |

Prinsip inti tetap sama: **Soft & Warm**, **Calm Confidence**, **Clear Hierarchy**, **Mobile-First**, **Accessible (WCAG AA, kontras ≥ 4.5:1)**. Yang berubah adalah seberapa serius prinsip-prinsip itu dieksekusi.

---

## 2. Design Tokens

### Warna

```
┌──────────────────────────────────────────────────────────────┐
│  PALETTE                                                     │
│                                                               │
│  Ink (teks utama):    #16222E                                │
│  Ink Soft (sekunder): #48555F                                │
│  Ink Mute (caption):  #8C97A1                                │
│                                                               │
│  Ocean (primary):     #1E5F8C   → Aksi utama, link, fokus    │
│  Ocean Deep:          #143B57   → Hover, sidebar gradient    │
│  Ocean Pale:          #E9F1F5   → Background aksen, greeting │
│  Sea (accent):        #3B9AB8   → Highlight, indikator aktif │
│                                                               │
│  Sand (surface):      #FAF7F1   → Background halaman         │
│  Sand Deep:           #F2ECE0   → Background hover, row alt  │
│  Card:                #FFFFFF                                │
│  Line:                #E7E0D2   → Border kartu, input        │
│  Line Soft:           #F0EADD   → Divider dalam kartu        │
│                                                               │
│  Success:    #3F7D58  bg #EAF3EC   → Hadir, disetujui        │
│  Warning:    #B8762E  bg #FAF1E5   → Terlambat, pending      │
│  Danger:     #B23A3A  bg #FAEDED   → Alfa, ditolak            │
│  Info:       #5B6FA8  bg #EEF0F8   → Izin                    │
│  Violet:     #8B6BAE  bg #F3EFF8   → Cuti                    │
│  Plum:       #A4548A  bg #F8EFF4   → Sakit                   │
│                                                               │
│  Sidebar BG: linear-gradient(180deg, #16374D 0%, #102A3B 100%)│
│  Sidebar Text:        #C9D5DC                                │
│  Sidebar Text Dim:    #7E94A3                                │
│  Sidebar Active BG:   rgba(59,154,184,.16)                   │
│  Sidebar Active Icon: #5DC2E0                                │
└──────────────────────────────────────────────────────────────┘
```

### Tipografi

```
Display:    Poppins 600–700, 19–21px   → Judul halaman, greeting
Heading 2:  Poppins 600, 14.5px        → Judul kartu/section
Body:       Inter 400–500, 13.5–14px   → Teks default
Small:      Inter 400–500, 11–12.5px   → Caption, meta, label
Data/Mono:  JetBrains Mono 500–600     → Jam, tanggal singkat, nominal, statistik
```

Aturan pemakaian mono: setiap angka yang **dibaca cepat dan dibandingkan** (08.02, Rp 4.850.000, 18 hari hadir) pakai `JetBrains Mono` dengan `font-variant-numeric: tabular-nums` supaya digit sejajar. Angka naratif di dalam kalimat tetap pakai Inter.

### Radius, Shadow, Spacing

| Token | Nilai | Pemakaian |
|-------|-------|-----------|
| `--r-card` | 14px | Kartu, kartu kartu presensi/payroll |
| `--r-btn` | 9px | Tombol |
| `--r-pill` | 999px | Badge, chip, segmented control |
| Shadow kartu | `0 1px 2px rgba(22,34,46,.04), 0 10px 28px -16px rgba(22,34,46,.16)` | Default semua `.card` |
| Shadow pop | `0 8px 24px -8px rgba(22,34,46,.18)` | Elemen yang sedang aktif/terangkat (tombol switch aktif) |

---

## 3. Shell Aplikasi (berlaku untuk semua peran)

Satu shell yang sama dipakai oleh ketiga peran — yang berubah hanya isi menu sidebar, identitas pengguna, dan konten halaman. Ini yang menjaga sistem terasa sebagai satu produk, bukan tiga aplikasi terpisah.

```
┌────────────┬──────────────────────────────────────────────┐
│            │  Karyawan / Dashboard          🔍  🔔  ●  ▾  │
│  SisAbsen  │  Dashboard                                    │
│            ├──────────────────────────────────────────────┤
│  Dashboard │                                                │
│  Presensi  │            (konten berbeda per peran)          │
│  Pengajuan │                                                │
│  Slip Gaji │                                                │
│            │                                                │
│  ──────    │                                                │
│  DP  Dimas │                                                │
│  Staff Op. │                                                │
│  Keluar    │                                                │
└────────────┴──────────────────────────────────────────────┘
```

### Sidebar

- Lebar 252px, gradient `#16374D → #102A3B`, padding 22px 16px
- Logo "SA" 36×36px, radius 10px, gradient `#3B9AB8 → #1E5F8C`
- Menu per peran (lihat tabel di bawah). Item aktif: background `rgba(59,154,184,.16)`, indikator garis kiri 3px warna `#5DC2E0`
- Kartu user di bagian bawah: avatar 36×36px bergradasi unik per peran, nama, jabatan
- Tinggi minimum setiap item nav 40px (touch target), kontras teks `#AEBCC6` di atas `#16374D` ≈ 7.2:1 — lolos AA

| Peran | Menu Sidebar |
|-------|-------------|
| Karyawan | Dashboard · Presensi · Pengajuan · Slip Gaji |
| HRD | Dashboard · Karyawan · Pengajuan · Absensi · Payroll · Laporan |
| Direktur | Dashboard · Laporan · Payroll · Karyawan |

### Topbar

- Tinggi 68px, background putih, border bottom `--line`
- Kiri: eyebrow kecil ("Karyawan / Dashboard") + judul halaman (Poppins 600, 19px)
- Kanan: ikon pencarian, ikon notifikasi (dot merah jika ada item baru), pemisah vertikal, identitas user (nama + jabatan + avatar + chevron)

---

## 4. Dashboard Karyawan

```
┌─────────────────────────────────────────────────────────────┐
│  Selamat pagi, Dimas 👋                                      │
│  Rabu, 25 Juni 2026 · Kantor Pusat Jakarta                  │
├─────────────────────────────────────────────────────────────┤
│  PRESENSI HARI INI                  23 m dari kantor ✓      │
│  Jam kerja 08.00–17.00                                       │
│                                                               │
│  ●Masuk 08.02 ─────●(now)─ ─ ─ ─○ Pulang --:--               │
│                                                               │
│  ● Tepat waktu                          [ Absen Pulang ]    │
│  ───────────────────────────────────────────────────────    │
│  18 Hadir │ 2 Terlambat │ 0 Izin │ 0 Alfa                    │
├─────────────────────────────────────────────────────────────┤
│  Pengajuan terakhir                        Buat baru →      │
│  ● Sakit · 20–21 Jun 2026                    Disetujui      │
└─────────────────────────────────────────────────────────────┘
┌──────────────────────────┐  ┌──────────────────────────┐
│ Riwayat 7 hari            │  │ Slip gaji terbaru         │
│ 25/06  08.02–--:--  Hadir │  │ Mei 2026     Rp 4.850.000 │
│ 24/06  07.58–17.04  Hadir │  │ Disetujui                 │
│ 23/06  08.34–17.10  Terlmb│  │                            │
│ 22/06  07.55–17.02  Hadir │  │                            │
│ 19/06  08.01–17.00  Hadir │  │                            │
└──────────────────────────┘  └──────────────────────────┘
```

### Elemen Khas: Presence Arc

Pengganti pola lama "tiga kotak terpisah (Masuk / Panah / Pulang)". Satu garis horizontal merepresentasikan rentang jam kerja:

- Track abu `--line`, tinggi 5px, radius penuh
- Fill gradient `--ocean → --sea` dari titik masuk sampai waktu saat ini (atau sampai titik pulang jika sudah absen pulang)
- Titik **masuk**: lingkaran terisi warna ocean, ring pucat di sekelilingnya, label jam di atasnya
- Titik **sekarang**: lingkaran sea-color dengan animasi pulse halus (satu-satunya animasi looping di seluruh dashboard — sengaja dibatasi satu agar tidak ramai)
- Titik **pulang**: lingkaran kosong dengan border putus-putus selama belum diisi; berubah jadi solid begitu karyawan absen pulang

Spesifikasi: tinggi container 54px, margin horizontal 4px agar label jam di ujung kiri/kanan tidak terpotong kartu.

### Spesifikasi Lain

- **Greeting band**: gradient halus `--ocean-pale → --sand`, radius 14px, padding 22–26px
- **Recap strip**: 4 angka mono dipisah garis vertikal tipis, bukan 4 kartu terpisah — satu baris ringkas di bawah kartu presensi
- **Riwayat 7 hari**: list dengan kolom tanggal mono (lebar tetap 64px) agar rapi sejajar, lalu jam masuk–pulang, lalu badge status
- **CTA**: tombol penuh warna `Absen Pulang` muncul kontekstual — berubah jadi `Absen Masuk` sebelum karyawan check-in

---

## 5. Dashboard HRD

```
┌─────────────────────────────────────────────────────────────┐
│  Dashboard HRD                                               │
│  Kelola kehadiran dan persetujuan tim hari ini               │
├──────────┬──────────┬──────────┬──────────┤
│ 45       │ 3        │ 2        │ 1        │
│ Total    │ Pengajuan│ Disetujui│ Ditolak  │
│ Karyawan │ Menunggu │ Hari Ini │ Hari Ini │
└──────────┴──────────┴──────────┴──────────┘
┌────────────────────────────┐ ┌────────────────────────────┐
│ Pengajuan menunggu          │ │ Karyawan hari ini           │
│                              │ │ ■■■■■■■■■■■■■■ (roster pulse)│
│ AF Ahmad Fauzi — Sakit       │ │ Budi Santoso         08.00  │
│    25–26 Jun  [Tolak][Setuju]│ │ Sari Wulandari       07.55  │
│ SN Siti Nuraini — Izin       │ │ Joko Wibowo        Terlambat│
│    27–28 Jun  [Tolak][Setuju]│ │ Andi Saputra            Alfa│
│ RH Rendra H. — Cuti          │ │         Lihat semua →       │
│    1–3 Jul    [Tolak][Setuju]│ │                              │
└────────────────────────────┘ └────────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│  Proses Payroll  │  Kelola Data Karyawan  │  Unduh Laporan   │
└─────────────────────────────────────────────────────────────┘
```

### Elemen Khas: Roster Pulse

Sebelum daftar nama, ada gugusan kotak kecil 10×10px (1 kotak = 1 karyawan, warna sesuai status hari itu) yang memberi gambaran sekilas komposisi tim — penuh hijau berarti hari yang tenang, ada beberapa merah berarti perlu perhatian. Ini elemen agregat dari Presence Arc milik karyawan: sama-sama "presensi sebagai bentuk visual", hanya beda skala (individu vs. tim).

### Spesifikasi Lain

- **Metric strip**: 4 angka dalam satu baris, bukan 4 kartu kotak. Warna angka mengikuti makna (pending = warning, ditolak = danger), bukan didekorasi ikon
- **Approve row**: avatar inisial bergradasi unik, nama, jenis & tanggal pengajuan, dua tombol kecil — `Tolak` (background merah pucat) dan `Setuju` (background hijau pucat), berubah solid saat hover. Warna tombol langsung menunjukkan konsekuensi aksi tanpa perlu membaca label
- **Aksi cepat**: bar horizontal tipis berisi 3 tautan teks+ikon, bukan 3 kartu besar bergambar — HRD memakai ini berkali-kali sehari, jadi harus cepat dipindai, bukan "didekorasi"

---

## 6. Dashboard Direktur

```
┌─────────────────────────────────────────────────────────────┐
│  Dashboard Direktur                                          │
│  Ringkasan kehadiran perusahaan dan persetujuan payroll      │
├──────────┬──────────┬──────────┬──────────┤
│ 45       │ 40       │ 3        │ 2        │
│ Total    │ Hadir    │ Terlambat│ Payroll  │
│ Karyawan │ Hari Ini │          │ Menunggu │
└──────────┴──────────┴──────────┴──────────┘
┌─────────────────────────────────────────────────────────────┐
│  RITME KEHADIRAN — Juni 2026      ● Hadir ● Terlambat ● Izin │
│                                                                │
│      ╱╲    ╱╲      ╱╲    ╱╲                                  │
│  ╱╲ ╱  ╲  ╱  ╲  ╱╲ ╱  ╲  ╱  ╲  ╱╲                            │
│ ╱  ╲╱    ╲╱    ╲╱  ╲╱    ╲╱    ╲╱                            │
│  1 Jun    8 Jun    15 Jun    22 Jun    30 Jun                │
└─────────────────────────────────────────────────────────────┘
┌────────────────────────────┐ ┌────────────────────────────┐
│ Payroll menunggu persetujuan│ │ Laporan terbaru             │
│ ┃ Juni 2026 · 45 karyawan   │ │ 📊 Laporan Absensi          │
│ ┃ Rp 250.000.000 [Tolak][Setuju]│ Juni 2026         [Unduh] │
│ ┃ Mei 2026 · 45 karyawan    │ │ 🏦 Laporan Payroll          │
│ ┃ Rp 245.000.000 [Tolak][Setuju]│ Mei 2026          [Unduh] │
└────────────────────────────┘ └────────────────────────────┘
```

### Elemen Khas: Ritme Kehadiran

Grafik area tipis, full-width, menyatu dengan kartu (tanpa border tebal mengelilingi seperti widget chart pada umumnya). Garis `--ocean` 2.5px dengan fill gradient transparan ke bawah. Label tanggal pakai mono, jarak antar titik proporsional terhadap tanggal. Ini satu-satunya grafik di seluruh sistem — direktur perlu satu gambaran tren, bukan banyak chart kecil yang saling bersaing.

### Spesifikasi Lain

- **Payroll row**: border kiri 4px warna `--ocean` (menandakan "butuh keputusan", beda dari warna status presensi). Nominal dalam mono, ukuran lebih besar dari teks lain di kartu — ini angka paling penting di baris tersebut
- **Laporan terbaru**: ikon kecil dalam kotak `--ocean-pale`, nama laporan, tombol unduh ghost yang hanya muncul untuk laporan yang sudah final

---

## 7. Component Library

### Tombol

```css
.btn-primary {
  background: linear-gradient(135deg, #2A6E9C, #143B57);
  color: #fff;
  min-height: 40px;
  padding: 0 18px;
  border-radius: 9px;
  font-weight: 600;
  box-shadow: 0 3px 10px rgba(20, 59, 87, .28);
  transition: all .18s ease;
}
.btn-primary:hover { box-shadow: 0 6px 16px rgba(20, 59, 87, .36); transform: translateY(-1px); }
.btn-primary:active { transform: scale(.98); }

.btn-secondary {
  background: #fff;
  border: 1.5px solid #E7E0D2;
  color: #48555F;
  min-height: 40px;
  border-radius: 9px;
}
.btn-secondary:hover { border-color: #D7CDB8; background: #FAF7F1; }

.btn-approve { background: #EAF3EC; color: #3F7D58; }
.btn-approve:hover { background: #3F7D58; color: #fff; }

.btn-reject { background: #FAEDED; color: #B23A3A; }
.btn-reject:hover { background: #B23A3A; color: #fff; }
```

### Kartu

```css
.card {
  background: #fff;
  border: 1px solid #F0EADD;
  border-radius: 14px;
  box-shadow: 0 1px 2px rgba(22,34,46,.04), 0 10px 28px -16px rgba(22,34,46,.16);
}
```

### Badge Status

```css
.badge {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 3.5px 10px; border-radius: 999px;
  font-size: 11.5px; font-weight: 600;
}
.badge::before { content:''; width: 6px; height: 6px; border-radius: 50%; }

.b-hadir      { background:#EAF3EC; color:#3F7D58; } .b-hadir::before      { background:#3F7D58; }
.b-terlambat  { background:#FAF1E5; color:#B8762E; } .b-terlambat::before  { background:#B8762E; }
.b-alfa       { background:#FAEDED; color:#B23A3A; } .b-alfa::before       { background:#B23A3A; }
.b-izin       { background:#EEF0F8; color:#5B6FA8; } .b-izin::before       { background:#5B6FA8; }
.b-cuti       { background:#F3EFF8; color:#8B6BAE; } .b-cuti::before       { background:#8B6BAE; }
.b-sakit      { background:#F8EFF4; color:#A4548A; } .b-sakit::before      { background:#A4548A; }
.b-disetujui  { background:#EAF3EC; color:#3F7D58; } .b-disetujui::before  { background:#3F7D58; }
```

### Metric Strip

```css
.metric-strip { display:flex; background:#fff; border:1px solid #F0EADD; border-radius:14px; overflow:hidden; }
.metric { flex:1; padding:18px 22px; border-right:1px solid #F0EADD; }
.metric:last-child { border-right:none; }
.metric .m-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#8C97A1; margin-bottom:8px; }
.metric .m-value { font-family:'JetBrains Mono',monospace; font-size:27px; font-weight:600; }
```

---

## 8. Responsive

| Breakpoint | Perilaku |
|-----------|----------|
| < 640px | Sidebar disembunyikan (akses via menu hamburger), dua kolom jadi satu kolom, metric strip jadi 2×2 |
| 640–1024px | Sidebar tetap, grid dua kolom menyempit jadi satu kolom pada kartu yang lebih kompleks (Pengajuan Menunggu, Payroll) |
| > 1024px | Layout penuh seperti wireframe di atas |

Touch target minimum 36–40px untuk semua elemen interaktif di seluruh breakpoint, termasuk di dalam sidebar dan baris approve/reject — ini memperbaiki temuan aksesibilitas dari audit versi sebelumnya.

---

## 9. Referensi Implementasi

Mockup interaktif (HTML, dengan pengalih peran) tersedia terpisah: `sisabsen-dashboards.html`. File tersebut adalah sumber kebenaran visual; dokumen ini adalah catatan keputusan dan token yang bisa langsung dipetakan ke `tailwind.config.js` serta komponen Blade (`dashboard.blade.php` per peran, `components/sidebar.blade.php`, `components/topbar.blade.php`).

Saran urutan kerja:

1. Tanam token warna & tipografi di `tailwind.config.js`
2. Bangun shell (`layouts/app.blade.php`) dengan sidebar & topbar yang menyesuaikan peran lewat middleware/role check
3. Bangun komponen reusable: `<x-metric-strip>`, `<x-badge>`, `<x-presence-arc>`, `<x-payroll-row>`
4. Susun tiga dashboard di atas komponen yang sama, isi data sesuai query per peran