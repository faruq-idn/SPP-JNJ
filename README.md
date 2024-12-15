-- Active: 1718202689828@@127.0.0.1@3306
# Sistem Informasi Manajemen Keuangan SPP Pesantren Jabal Nur Jadid

Sistem informasi berbasis website untuk memudahkan pengelolaan keuangan SPP di Pesantren Jabal Nur Jadid.

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