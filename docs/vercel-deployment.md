# Panduan Development Deployment ke Vercel

## Persiapan

1. Install Vercel CLI:
```bash
npm install -g vercel
```

2. Pastikan repository sudah ada di GitHub

3. Setup PostgreSQL:
- Buat database PostgreSQL (bisa menggunakan Vercel Postgres)
- Catat credentials database untuk konfigurasi nanti

## Langkah Deployment untuk Testing

1. Jalankan script deployment:
```bash
chmod +x deploy-vercel.sh
./deploy-vercel.sh
```

2. Deploy ke Vercel:
```bash
vercel
```
Note: Jangan gunakan `vercel --prod` karena ini untuk testing

3. Atur environment variables di Vercel dashboard:
```
DATABASE_URL=<PostgreSQL connection string>
POSTGRES_HOST=<host>
POSTGRES_DATABASE=<database>
POSTGRES_USER=<user>
POSTGRES_PASSWORD=<password>
APP_KEY=<hasil dari 'php artisan key:generate --show'>
```

4. Jalankan migrasi database:
```bash
vercel run php artisan migrate
```

## Debugging

1. Debug mode sudah aktif di environment development
2. Error logs bisa dilihat di:
   - Vercel dashboard -> Deployments -> Deployment yang dipilih -> Functions
   - Terminal dengan perintah: `vercel logs`

3. Jika ada error saat build:
   - Cek build logs di Vercel dashboard
   - Coba build manual dengan `npm run build`
   - Pastikan tidak ada error di log Laravel (`storage/logs/laravel.log`)

## Fitur Development Mode

- APP_DEBUG=true untuk error reporting detail
- No caching untuk view dan config
- Running di preview URLs (*.vercel.app)
- Bisa deploy ulang kapan saja untuk testing
- Bisa rollback ke deployment sebelumnya

## Update & Testing

1. Setelah ada perubahan code:
```bash
./deploy-vercel.sh
vercel
```

2. Test fitur baru di URL preview yang diberikan

3. Cek logs jika ada error:
```bash
vercel logs
```

## Penting Diingat

- Ini adalah setup untuk development/testing
- Jangan gunakan untuk production
- Database credentials aman karena di environment variables
- File system bersifat read-only, gunakan /tmp untuk cache
- Session menggunakan cookie
- Cache menggunakan array driver
