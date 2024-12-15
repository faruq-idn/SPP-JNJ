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
