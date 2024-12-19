# 📚 Sistem Pembayaran SPP Pesantren

> Sistem informasi untuk mengelola pembayaran SPP di Pesantren Jabal Nur Jadid.

## 🚀 Progress

### ✅ Fitur yang Sudah Selesai

#### Autentikasi & Otorisasi
- [x] Login multi user (Admin, Petugas, Wali Santri)
- [x] Role based access control
- [x] Logout dengan konfirmasi
- [x] Prevent back history setelah logout

#### Dashboard Admin
- [x] Statistik total santri
- [x] Statistik total penerimaan
- [x] Statistik total tunggakan
- [x] Daftar pembayaran terbaru
- [x] Daftar santri dengan tunggakan terbanyak

#### Manajemen Data Santri
- [x] CRUD data santri
- [x] Import data santri via Excel
- [x] Filter santri per kelas
- [x] Pencarian santri
- [x] Detail santri dengan riwayat pembayaran

#### Manajemen Kategori Santri
- [x] CRUD kategori santri
- [x] Setting tarif SPP per kategori
- [x] Riwayat perubahan tarif

#### Dashboard Wali Santri
- [x] Multi santri dalam satu akun wali
- [x] Informasi tagihan SPP
- [x] Riwayat pembayaran
- [x] Fitur hubungkan santri dengan wali

#### Laporan
- [x] Laporan pembayaran SPP
- [x] Laporan tunggakan
- [x] Filter laporan (periode/kelas)
- [x] Export laporan ke Excel

### 🚧 Fitur yang Sedang Dikerjakan

#### Pembayaran SPP
- [ ] Input pembayaran manual oleh admin/petugas
- [ ] Upload bukti pembayaran oleh wali
- [ ] Verifikasi pembayaran
- [ ] Notifikasi status pembayaran

#### Integrasi Payment Gateway
- [ ] Integrasi Midtrans
- [ ] Pembayaran online
- [ ] Callback pembayaran
- [ ] Status transaksi realtime

### 📋 Fitur yang Akan Datang

- [ ] Notifikasi WhatsApp
- [ ] Notifikasi Email
- [ ] Kartu SPP digital
- [ ] Grafik pembayaran
- [ ] Dashboard petugas
- [ ] Backup database
- [ ] Setting aplikasi
- [ ] Manajemen pengguna

## 💻 Tech Stack
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![jQuery](https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white)

## 🛠️ Instalasi

1. Clone repository
```bash
git clone https://github.com/username/repo.git
cd repo
```

2. Install dependencies
```bash
composer install
```
```bash
npm install
```

3. Setup environment
```bash
cp .env.example .env
```  

4. Konfigurasi database
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

5. Jalankan server
```bash
php artisan serve
```

6. Akses aplikasi
```bash
http://localhost:8000
```

## 📝 Keterangan Status
- ✅ Selesai
- 🚧 Sedang dikerjakan  
- 🚫 Belum dikerjakan

## 📄 Lisensi

Project ini dilisensikan dibawah [MIT License](LICENSE)

## 👥 Kontribusi

Kontribusi selalu diterima! Silakan buat pull request atau buka issue untuk diskusi.

## 📞 Kontak

Jika ada pertanyaan, silakan hubungi:
- Email: admin@example.com
- Website: https://example.com

---
Made with ❤️ by Tim Pengembang
