# Panduan Deployment SPP JNJ

Projek ini mendukung deployment ke dua platform:
1. Shared Hosting (faruq.whoami.my.id)
2. Vercel (spp-jnj.vercel.app)

## Deployment ke Shared Hosting

### Persiapan
1. Pastikan server memenuhi requirements:
   - PHP >= 8.1
   - MySQL
   - Composer
   - Node.js & NPM
   - Extension PHP: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, XML

### Langkah Deployment
1. Jalankan script deployment:
   ```bash
   chmod +x deploy-hosting.sh
   ./deploy-hosting.sh
   ```

2. Upload file ke hosting menggunakan FTP:
   - Exclude folder yang tidak perlu: `/node_modules`, `/vendor`
   - Upload semua file lainnya

3. Setelah upload selesai, jalankan perintah berikut di server:
   ```bash
   # Migrasi database
   php artisan migrate --force

   # Link storage
   php artisan storage:link

   # Set permissions
   chmod -R 775 storage bootstrap/cache
   ```

## Deployment ke Vercel

### Persiapan
1. Buat akun Vercel dan install Vercel CLI:
   ```bash
   npm install -g vercel
   ```

2. Buat database PostgreSQL:
   - Bisa menggunakan Vercel Postgres
   - Atau layanan PostgreSQL lainnya

3. Siapkan environment variables berikut di dashboard Vercel:
   - `DATABASE_URL`: Connection string PostgreSQL
   - `POSTGRES_HOST`
   - `POSTGRES_DATABASE`
   - `POSTGRES_USER`
   - `POSTGRES_PASSWORD`
   - `APP_KEY`: Generate dengan `php artisan key:generate --show`
   - Salin semua variable dari `.env.vercel` ke Vercel dashboard

### Langkah Deployment
1. Jalankan script deployment:
   ```bash
   chmod +x deploy-vercel.sh
   ./deploy-vercel.sh
   ```

2. Deploy ke Vercel:
   ```bash
   vercel --prod
   ```

3. Setelah deployment selesai:
   - Jalankan migrasi database:
     ```bash
     vercel run php artisan migrate --force
     ```
   - Cek logs di Vercel dashboard untuk memastikan tidak ada error

## Maintenance

### Shared Hosting
- Untuk update aplikasi, jalankan `deploy-hosting.sh` lagi dan upload ulang file yang berubah
- Jalankan `php artisan migrate` jika ada perubahan database

### Vercel
- Push perubahan ke repository GitHub
- Vercel akan otomatis melakukan deployment
- Jika ada perubahan database, jalankan `vercel run php artisan migrate`

## Troubleshooting

### Shared Hosting
- Jika terjadi error 500:
  1. Cek error log di `/storage/logs/laravel.log`
  2. Pastikan permissions folder storage dan bootstrap/cache benar
  3. Clear cache dengan `php artisan cache:clear`

### Vercel
- Jika deployment gagal:
  1. Cek build logs di Vercel dashboard
  2. Pastikan semua environment variables sudah di-set
  3. Cek function logs untuk error runtime
  4. Pastikan versi PHP dan extensions yang dibutuhkan tersedia

## Panduan Switch Domain

### Shared Hosting ke Vercel (atau sebaliknya)
1. Update DNS records
2. Update konfigurasi Midtrans (webhook URL)
3. Update APP_URL di environment variables
4. Clear cache aplikasi
