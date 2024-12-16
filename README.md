-- Active: 1718202689828@@127.0.0.1@3306
# Sistem Pembayaran SPP Pesantren

Sistem informasi untuk mengelola pembayaran SPP di Pesantren Jabal Nur Jadid.

## Status Progres

### Autentikasi & Otorisasi ✅
- [x] Login multi user (admin, petugas, wali santri)
- [x] Middleware role & permission
- [x] Redirect sesuai role setelah login
- [x] Logout

### Dashboard Admin ✅
- [x] Statistik total santri
- [x] Statistik total penerimaan
- [x] Statistik tunggakan
- [x] Statistik pembayaran hari ini
- [x] Daftar pembayaran terbaru
- [x] Notifikasi

### Manajemen Data Master ⚠️
- [x] CRUD Kategori Santri
- [x] Manajemen Tarif SPP
- [x] Riwayat perubahan tarif
- [ ] CRUD Data Santri
- [ ] CRUD Data Wali Santri
- [ ] CRUD Data Petugas
- [ ] Import Data Santri (Excel)

### Transaksi Pembayaran 🚫
- [ ] Input pembayaran SPP
- [ ] Cetak bukti pembayaran
- [ ] Riwayat pembayaran
- [ ] Pembayaran via payment gateway
- [ ] Notifikasi pembayaran

### Laporan 🚫
- [ ] Laporan penerimaan harian
- [ ] Laporan penerimaan bulanan
- [ ] Laporan tunggakan
- [ ] Export laporan (PDF/Excel)

### Fitur Wali Santri 🚫
- [ ] Lihat tagihan SPP
- [ ] Riwayat pembayaran
- [ ] Pembayaran online
- [ ] Cetak bukti pembayaran

### Lainnya 🚫
- [ ] Backup database
- [ ] Pengaturan aplikasi
- [ ] Logs aktivitas
- [ ] API untuk mobile app

Keterangan:
- ✅ Selesai
- ⚠️ Sedang dikerjakan
- 🚫 Belum dikerjakan

## Tech Stack
- Laravel 11
- MySQL
- Bootstrap 5
- SweetAlert2
- Font Awesome 6

## Fitur Utama

* **Manajemen Data Santri:**
    * Menambah, mengedit, dan menghapus data santri.
    * Data santri meliputi: nama, NISN, jenis kelamin, tanggal lahir, alamat, wali,  tanggal masuk, jenjang, kelas, status, dan kategori.
* **Pencatatan Pembayaran SPP:**
    * Wali santri dapat melihat dan membayar tagihan SPP secara online via Midtrans.
    * Admin dapat mencatat pembayaran SPP secara manual (dengan upload bukti bayar).
    * Status pembayaran tiap bulan tiap santri.
* **Pembuatan Laporan:**
    * Laporan penerimaan SPP per periode.
    * Laporan tunggakan SPP per santri dan per kelas.
    * Laporan rekap pembayaran per kategori santri.
    * Laporan detail pembayaran per santri.
    * Filter laporan: periode, kategori santri, kelas, status pembayaran.
    * Format laporan: PDF, Excel.
* **Manajemen Pengguna:**
    * Role pengguna: Admin, Petugas Keuangan, Wali Santri.
    * Hak akses yang berbeda untuk setiap role.
    * Sistem login yang aman.
* **Notifikasi:**
    * Notifikasi email ke wali santri (pembayaran berhasil, tagihan, dll.).
* **Manajemen Kategori Santri:**
    * Admin dapat menambah, mengedit, dan menghapus kategori santri.
    * Setiap kategori santri memiliki tarif SPP.
    * Sistem mencatat riwayat perubahan tarif SPP.

## Teknologi yang Digunakan

* **Backend:**  Laravel 11
* **Frontend:**  Blade Templating, Bootstrap 5
* **Database:**  MySQL 5.7+
* **Payment Gateway:**  Midtrans

## Instalasi

1.  Clone repository ini.
2.  Install dependency dengan `composer install`.
3.  Duplikasi file `.env.example` menjadi `.env` dan sesuaikan konfigurasinya (database, email, Midtrans).
4.  Jalankan migration dengan `php artisan migrate`.
5.  Jalankan seeder (jika ada) dengan `php artisan db:seed`.
6.  Jalankan aplikasi dengan `php artisan serve`.

## Kontribusi

Pull request sangat diterima. Untuk perubahan besar, silakan buka issue terlebih dahulu untuk mendiskusikan apa yang ingin Anda ubah.

## Lisensi

[MIT License]

## Progres Pengembangan

### Fitur yang Sudah Selesai
1. Autentikasi dan Autorisasi
   - Login untuk admin dan wali santri
   - Middleware role dan permission
   - Reset password

2. Manajemen Data Master
   - CRUD Kategori Santri
   - CRUD Data Santri
   - Riwayat perubahan tarif SPP
   - Validasi data terkait

3. Pencarian dan Filter
   - Pencarian santri (nama/NISN)
   - Filter berdasarkan jenjang
   - Filter berdasarkan kelas
   - Filter berdasarkan kategori
   - Filter berdasarkan status

4. Pembayaran SPP
   - Form pembayaran dengan validasi
   - Pencarian santri saat pembayaran
   - Preview data santri sebelum pembayaran
   - Riwayat pembayaran

### Fitur yang Sedang Dikembangkan
1. Dashboard
   - Statistik pembayaran
   - Grafik pendapatan
   - Notifikasi tunggakan

2. Laporan
   - Laporan pembayaran per periode
   - Laporan tunggakan
   - Export data ke Excel/PDF

3. Notifikasi
   - Email notifikasi pembayaran
   - Reminder tunggakan

### Tech Stack
- Laravel 10
- MySQL
- Bootstrap 5
- jQuery
- Select2
- DataTables
- SweetAlert2

### Instalasi dan Penggunaan
[Instruksi instalasi dan penggunaan akan ditambahkan]
