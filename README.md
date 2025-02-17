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

### 🚀 Deployment ke Shared Hosting

#### Persiapan
1. Pastikan shared hosting memenuhi requirements:
   - PHP >= 8.1
   - MySQL/MariaDB
   - Ekstensi PHP yang diperlukan (BCMath, Ctype, dll)
   - Composer (jika tersedia)
   
2. Siapkan subdomain atau domain yang akan digunakan

#### Langkah Deployment

1. **Persiapkan File Upload:**
   ```bash
   # Build assets produksi
   npm run build
   
   # Optimize autoloader composer
   composer install --optimize-autoloader --no-dev
   ```

2. **Upload File ke Hosting:**
   - Upload semua file proyek ke hosting KECUALI:
     - folder `node_modules/`
     - folder `vendor/` (akan diinstall ulang)
     - file `.env`
     - folder `.git/`
   - File dapat diupload via FTP atau File Manager hosting

3. **Setup Direktori Publik:**
   - Pindahkan semua isi folder `public/` ke `public_html/` atau root folder yang ditentukan hosting
   - Edit file `index.php` yang dipindah, sesuaikan path ke `bootstrap/app.php`:
   ```php
   require __DIR__.'/../bootstrap/app.php';
   // menjadi (sesuaikan dengan struktur folder):
   require __DIR__.'/../[nama-folder-aplikasi]/bootstrap/app.php';
   ```

4. **Install Dependencies:**
   - Jika hosting mendukung SSH:
     ```bash
     composer install --optimize-autoloader --no-dev
     ```
   - Jika tidak ada SSH:
     - Upload folder `vendor/` yang sudah di-generate lokal
     
5. **Setup Database:**
   - Buat database baru di hosting
   - Import database dari backup lokal atau
   - Jalankan migration (jika hosting mendukung SSH):
     ```bash
     php artisan migrate --seed
     ```

6. **Konfigurasi Aplikasi:**
   - Copy `.env.example` menjadi `.env`
   - Sesuaikan konfigurasi di `.env`:
     ```env
     APP_ENV=production
     APP_DEBUG=false
     APP_URL=https://domain-anda.com
     
     DB_HOST=localhost
     DB_DATABASE=nama_database
     DB_USERNAME=username_database
     DB_PASSWORD=password_database
     
     MIDTRANS_IS_PRODUCTION=true # jika sudah siap live
     MIDTRANS_NOTIFICATION_URL=https://domain-anda.com/wali/pembayaran/notification
     ```
   - Generate app key baru:
     ```bash
     php artisan key:generate
     ```

7. **Optimasi Production:**
   ```bash
   # Clear & cache konfigurasi
   php artisan config:cache
   
   # Cache routes
   php artisan route:cache
   
   # Cache views
   php artisan view:cache
   ```

8. **Pengaturan Permission:**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

9. **Setup Cronjob** (opsional, jika hosting mendukung):
   - Tambahkan cronjob untuk menjalankan scheduler Laravel:
     ```
     * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
     ```

10. **Verifikasi Deployment:**
    - Buka domain/subdomain yang sudah dikonfigurasi
    - Test login dengan akun default
    - Test fitur pembayaran dengan akun Midtrans Sandbox
    - Periksa error log di `storage/logs/laravel.log`

#### Troubleshooting

1. **Halaman 500 Internal Server Error:**
   - Periksa permission folder `storage/` dan `bootstrap/cache/`
   - Periksa error log di `storage/logs/laravel.log`
   - Pastikan `.env` sudah ada dan terkonfigurasi dengan benar

2. **Assets (CSS/JS) Tidak Loading:**
   - Pastikan path di `APP_URL` sudah benar
   - Periksa file `.htaccess` di public folder
   - Jalankan `npm run build` ulang jika perlu

3. **Midtrans Callback Tidak Berfungsi:**
   - Pastikan URL callback di `.env` dan dashboard Midtrans sudah benar
   - Periksa firewall/security hosting tidak memblokir request dari Midtrans

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
