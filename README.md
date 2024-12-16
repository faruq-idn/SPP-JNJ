# 📚 Sistem Pembayaran SPP Pesantren

> Sistem informasi untuk mengelola pembayaran SPP di Pesantren Jabal Nur Jadid.

## 🚀 Fitur Utama

### Fitur Admin ⚡
- ✅ Kelola data santri
    - ✅ Import data santri dari Excel
    - ✅ Tambah, edit, hapus data santri
    - ✅ Lihat detail santri
    - ✅ Filter santri per kelas
- ✅ Kelola data wali santri
    - ✅ Tambah, edit, hapus data wali
    - ✅ Reset password wali
- ⚠️ Kelola pembayaran SPP
    - ✅ Input pembayaran SPP
    - ⚠️ Cetak bukti pembayaran
    - ⚠️ Laporan pembayaran
- ✅ Kelola master data
    - ✅ Kategori santri
    - ✅ Tarif SPP

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
- ⚠️ Sedang dikerjakan  
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
