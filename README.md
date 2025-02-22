# 📚 Sistem Informasi Pembayaran SPP

Aplikasi manajemen pembayaran SPP berbasis web dengan integrasi payment gateway Midtrans. Dibuat menggunakan Laravel dan MySQL.

## ✨ Fitur Utama

### 🔐 Autentikasi & Otorisasi
- [x] Login multi user (Admin, Petugas, Wali Santri)
- [x] Role based access control
- [x] Logout dengan konfirmasi
- [x] Prevent back history setelah logout

### 📊 Dashboard Admin
- [x] Statistik total santri
- [x] Statistik total penerimaan
- [x] Statistik total tunggakan
- [x] Daftar pembayaran terbaru
- [x] Daftar santri dengan tunggakan terbanyak

### 👥 Manajemen Data Santri
- [x] CRUD data santri
- [x] Import data santri via Excel
- [x] Filter santri per kelas
- [x] Pencarian santri
- [x] Detail santri dengan riwayat pembayaran
- [x] Status santri (aktif/non-aktif)
- [x] Kenaikan kelas santri
- [x] Riwayat kenaikan kelas
- [x] Pencatatan tahun tamat

### 📋 Manajemen Kategori Santri
- [x] CRUD kategori santri
- [x] Setting tarif SPP per kategori
- [x] Riwayat perubahan tarif

### 💳 Pembayaran SPP
- [x] Input pembayaran manual oleh admin/petugas
- [x] Pembayaran online via Midtrans
- [x] Multiple payment methods (VA, QRIS, dll)
- [x] Status transaksi realtime
- [x] Cetak bukti pembayaran
- [x] Riwayat pembayaran

### 📱 Dashboard Wali Santri
- [x] Multi santri dalam satu akun wali
- [x] Informasi tagihan SPP
- [x] Riwayat pembayaran
- [x] Fitur hubungkan santri dengan wali

### 📈 Laporan
- [x] Laporan penerimaan 
- [x] Laporan tunggakan
- [x] Filter berdasarkan periode
- [x] Export PDF & Excel

## 💻 Tech Stack
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![jQuery](https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white)

### 🛠️ Instalasi Lokal

#### Prasyarat untuk Development
- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM
- Git
- Ekstensi PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

#### Langkah Instalasi

1.  **Clone repository dari GitHub:**
    ```bash
    git clone [URL REPOSITORY GITHUB]
    cd [NAMA DIREKTORI REPOSITORY]
    ```
    *Ganti `[URL REPOSITORY GITHUB]` dan `[NAMA DIREKTORI REPOSITORY]` dengan URL dan nama direktori repository proyek Anda.*

2.  **Konfigurasi Aplikasi:**
    *   Copy file `.env.example` menjadi `.env`:
        ```bash
        cp .env.example .env
        ```
    *   Generate application key:
        ```bash
        php artisan key:generate
        ```
    *   Buka file `.env` dan konfigurasi pengaturan database, Midtrans, dan lainnya sesuai kebutuhan.

3.  **Konfigurasi Database:**
    *   Pastikan server MySQL/MariaDB sudah berjalan.
    *   Buat database baru dengan nama `spp_jnj` (atau nama lain sesuai konfigurasi `.env`).
    *   Sesuaikan konfigurasi database di file `.env`:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=spp_jnj
        DB_USERNAME=root
        DB_PASSWORD=
        ```
        *Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan kredensial database Anda.*

4.  **Konfigurasi Midtrans:**
    *   Dapatkan `MIDTRANS_MERCHANT_ID`, `MIDTRANS_CLIENT_KEY`, dan `MIDTRANS_SERVER_KEY` dari dashboard Midtrans Sandbox (untuk testing) atau Production (untuk aplikasi live).
    *   Konfigurasi Midtrans di file `.env`:
        ```env
        MIDTRANS_MERCHANT_ID=your-merchant-id
        MIDTRANS_CLIENT_KEY=your-client-key
        MIDTRANS_SERVER_KEY=your-server-key
        MIDTRANS_IS_PRODUCTION=false # Set to true for production
        ```
        *Ganti `your-merchant-id`, `your-client-key`, dan `your-server-key` dengan kredensial Midtrans Anda.*

5.  **Install Dependencies PHP:**
    ```bash
    composer install
    ```

6.  **Install Dependencies JavaScript dan Build Assets:**
    ```bash
    npm install
    npm run build
    ```

7.  **Migrasi dan Seeding Database:**
    ```bash
    php artisan migrate --seed
    ```

8.  **Jalankan Development Server:**
    ```bash
    php artisan serve
    ```

9.  **Akses Aplikasi di Browser:**
    Buka browser dan akses aplikasi di URL yang diberikan oleh `php artisan serve` (biasanya `http://localhost:8000`).

#### Akun Default
| Role    | Email               | Password   |
|---------|---------------------|------------|
| Admin   | admin@example.com   | password   |
| Petugas | petugas@example.com | password   |
| Wali    | wali@example.com    | password   |

### 🚀 Deployment untuk Testing di Shared Hosting

#### Persiapan dan Backup
1. **Backup Database Lokal:**
   ```bash
   # Export database
   mysqldump -u root -p spp_jnj > backup_local.sql
   
   # Export dengan data sensitif yang sudah dimodifikasi (opsional)
   mysqldump -u root -p --replace --skip-extended-insert \
   --replace-regex="s/nomor_hp_asli/08123456789/g" \
   spp_jnj > backup_modified.sql
   ```

2. **Pastikan Hosting Mendukung:**
   - PHP >= 8.1
   - MySQL/MariaDB
   - Ekstensi PHP: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, XML
   - File Manager untuk upload
   - phpMyAdmin atau tools database serupa
   
3. **Persiapkan Domain Testing:**
   - Gunakan subdomain khusus untuk testing (misal: test.domain.com)
   - Pastikan SSL tersedia untuk testing pembayaran

#### Langkah Deployment Testing

1. **Build dan Persiapan File:**
   ```bash
   # Install dependencies
   composer install
   npm install
   
   # Build assets
   npm run build
   ```

2. **Upload File ke Hosting:**
   - Upload semua file proyek KECUALI:
     - `node_modules/`
     - `vendor/` (install manual di hosting)
     - `.env`
     - `.git/`
     - `tests/`
   - Gunakan File Manager hosting atau FTP

3. **Setup Database:**
   - Buat database baru di hosting
   - Import `backup_local.sql` atau `backup_modified.sql` via phpMyAdmin
   - Catat kredensial database untuk konfigurasi

4. **Konfigurasi untuk Testing:**
   - Copy `.env.example` ke `.env` di hosting
   - Sesuaikan konfigurasi testing:
     ```env
     APP_ENV=local
     APP_DEBUG=true
     APP_URL=https://test.domain.com
     
     DB_HOST=localhost
     DB_DATABASE=nama_database_test
     DB_USERNAME=user_test
     DB_PASSWORD=pass_test
     
     # Tetap gunakan Sandbox Midtrans
     MIDTRANS_IS_PRODUCTION=false
     MIDTRANS_NOTIFICATION_URL=https://test.domain.com/wali/pembayaran/notification
     ```

5. **Setup Folder dan Permission:**
   ```bash
   # Buat folder yang diperlukan
   mkdir -p storage/framework/{sessions,views,cache}
   mkdir -p storage/logs
   
   # Set permission
   chmod -R 755 storage bootstrap/cache
   ```

#### Panduan Testing & Troubleshooting

1. **Cek Konfigurasi:**
   - Buka `phpinfo.php` untuk verifikasi ekstensi PHP
   - Test koneksi database melalui phpMyAdmin
   - Pastikan semua folder memiliki permission yang benar

2. **Log dan Debugging:**
   - Error log Laravel: `storage/logs/laravel.log`
   - Error log PHP: cek di cPanel atau direktori log hosting
   - Aktifkan APP_DEBUG=true untuk melihat error detail

3. **Masalah Umum dan Solusi:**
   
   a. **500 Internal Server Error:**
   - Periksa permission storage & cache
   - Cek error di laravel.log
   - Validasi format .env (tidak boleh ada spasi setelah value)

   b. **Database Error:**
   - Verifikasi kredensial database
   - Cek prefix table di .env
   - Pastikan user database punya priviledge yang cukup

   c. **Asset Tidak Loading:**
   - Periksa path di APP_URL
   - Clear cache browser
   - Rebuild asset jika perlu

4. **Prosedur Rollback:**
   - Backup file dan database sebelum setiap perubahan besar
   - Simpan file .env original
   - Catat setiap perubahan untuk mudah di-rollback

5. **Tips Tambahan:**
   - Gunakan subdomain terpisah untuk setiap fitur besar
   - Test pembayaran dengan akun Midtrans Sandbox
   - Monitor penggunaan disk space dan database
   - Catat semua error dan solusinya untuk referensi

6. **Keamanan Testing:**
   - Gunakan password yang kuat meski untuk testing
   - Batasi akses IP jika memungkinkan
   - Hindari menyimpan data sensitif di environment testing
   - Regular backup untuk mencegah kehilangan data test

7. **Komunikasi Issue:**
   - Screenshot error yang muncul
   - Copy error log yang relevan
   - Catat langkah-langkah reproduksi masalah
   - Dokumentasikan solusi yang sudah dicoba

PENTING: Environment ini untuk testing, hindari:
- Menggunakan data produksi yang sensitif
- Mengaktifkan fitur notifikasi ke user real
- Mengubah konfigurasi yang bisa mempengaruhi sistem lain di hosting

## 📋 Todo

### 📱 Notifikasi
- [ ] Notifikasi WhatsApp
- [ ] Notifikasi Email
- [ ] Kartu SPP digital
- [ ] Pembayaran cicilan

### 📊 Laporan & Statistik
- [ ] Grafik pembayaran
- [ ] Dashboard petugas
- [ ] Laporan per kategori
- [ ] Statistik tunggakan

### ⚙️ Sistem
- [ ] Backup database otomatis
- [ ] Setting aplikasi
- [ ] Manajemen pengguna
- [ ] Activity log

## 🤝 Kontribusi
Kontribusi dan saran sangat diterima.

1. Fork repository ini
2. Buat branch baru (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m 'Menambah fitur'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## 📝 Lisensi
[MIT License](LICENSE)

## 📧 Kontak
- Email: admin@example.com
- Website: https://example.com

## 🙏 Credit
Terima kasih kepada semua kontributor yang telah membantu project ini.

---
Made with ❤️ by Tim Pengembang
