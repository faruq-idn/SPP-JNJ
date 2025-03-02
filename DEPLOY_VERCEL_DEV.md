# Vercel Development Deployment Guide

## Persiapan Development Mode

1. Pastikan repository sudah di-push ke GitHub
2. Install Vercel CLI:
```bash
npm install -g vercel
```

3. Login ke Vercel:
```bash
vercel login
```

## Deployment untuk Testing

1. Deploy ke Vercel preview:
```bash
# Deploy tanpa production
vercel
```

2. Test PHP runtime:
- Buka URL yang diberikan + `/test`
- Contoh: `https://spp-jnj-git-main-username.vercel.app/test`
- Periksa output untuk informasi PHP dan extensions

3. Troubleshooting:
- Cek build logs: `vercel logs`
- Lihat environment info di dashboard Vercel
- Periksa `/tmp/php_errors.log` di serverless function

## Konfigurasi yang Digunakan

1. PHP Runtime: vercel-php@0.3.3
   - Versi yang lebih stabil untuk testing
   - Support untuk PostgreSQL

2. Environment Variables:
```env
APP_ENV=development
APP_DEBUG=true
LOG_LEVEL=debug
```

3. File Konfigurasi:
- `vercel.json` - Konfigurasi utama
- `vercel.build.sh` - Script build dengan debug info
- `api/test.php` - Test script untuk PHP runtime

## Known Issues & Solutions

1. SSL Library Issues:
- Menggunakan versi PHP runtime yang lebih lama (0.3.3)
- Minimal PHP extensions untuk testing

2. Composer Issues:
- Build process disederhanakan
- Dependencies minimal

3. Directory Permissions:
- Menggunakan /tmp untuk cache
- chmod 755 untuk direktori temporary

## Command References

1. Development Commands:
```bash
# Deploy preview
vercel

# Stream logs
vercel logs --follow

# Pull environment variables
vercel env pull

# Delete deployment
vercel remove spp-jnj
```

2. Testing Commands:
```bash
# Test PHP info
curl https://your-url.vercel.app/test

# Check logs
vercel logs your-deployment-url
```

## Penting Diingat

- Ini adalah konfigurasi untuk DEVELOPMENT/TESTING
- Jangan gunakan untuk production
- Debug mode diaktifkan
- Error reporting detail
- Database credentials tetap harus aman

## Next Steps

Setelah testing berhasil:
1. Review error logs
2. Test fitur-fitur utama
3. Persiapkan konfigurasi production
4. Setup PostgreSQL production
