# Panduan Migrasi Asset dari CDN ke Local

## File yang Perlu Diunduh

### 1. Bootstrap
- Unduh dari: https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css
- Simpan ke: public/vendor/bootstrap/css/bootstrap.min.css
- Unduh dari: https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js
- Simpan ke: public/vendor/bootstrap/js/bootstrap.bundle.min.js

### 2. Font Awesome
- Unduh dari: https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css
- Simpan ke: public/vendor/fontawesome/css/all.min.css
- Unduh juga file font (.woff2) ke: public/vendor/fontawesome/webfonts/

### 3. Select2
- Unduh dari: https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css
- Simpan ke: public/vendor/select2/css/select2.min.css
- Unduh dari: https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css
- Simpan ke: public/vendor/select2/css/select2-bootstrap-5-theme.min.css
- Unduh dari: https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js
- Simpan ke: public/vendor/select2/js/select2.min.js

### 4. SweetAlert2
- Unduh dari: https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css
- Simpan ke: public/vendor/sweetalert2/sweetalert2.min.css
- Unduh dari: https://cdn.jsdelivr.net/npm/sweetalert2@11
- Simpan ke: public/vendor/sweetalert2/sweetalert2.min.js

### 5. jQuery
- Unduh dari: https://code.jquery.com/jquery-3.7.1.min.js
- Simpan ke: public/vendor/jquery/jquery.min.js

### 6. Chart.js
- Unduh dari: https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js
- Simpan ke: public/vendor/chartjs/chart.umd.min.js

## Langkah Verifikasi
1. Unduh semua file ke lokasi yang ditentukan
2. Pastikan struktur folder sesuai
3. Clear cache browser
4. Test aplikasi di local untuk memastikan semua berfungsi
5. Deploy ke server dan verifikasi tidak ada lagi error CSP