# Panduan Manual Download Asset

Karena kendala teknis dalam pengunduhan otomatis, silakan ikuti langkah-langkah berikut untuk mengunduh asset secara manual:

## 1. Bootstrap (v5.3.0)
- Kunjungi: https://getbootstrap.com/docs/5.3/getting-started/download/
- Download file:
  - css/bootstrap.min.css → public/vendor/bootstrap/bootstrap.min.css
  - js/bootstrap.bundle.min.js → public/vendor/bootstrap/bootstrap.bundle.min.js

## 2. Font Awesome (v6.0.0)
- Kunjungi: https://fontawesome.com/download
- Download Free For Web
- Ekstrak dan pindahkan:
  - css/all.min.css → public/vendor/fontawesome/all.min.css
  - webfonts/* → public/vendor/fontawesome/webfonts/

## 3. jQuery (v3.7.1)
- Kunjungi: https://jquery.com/download/
- Download minified version
- Simpan sebagai: public/vendor/jquery/jquery.min.js

## 4. Chart.js (v4.4.1)
- Kunjungi: https://www.chartjs.org/docs/latest/getting-started/installation.html
- Download chart.umd.min.js
- Simpan sebagai: public/vendor/chartjs/chart.umd.min.js

## 5. Select2 (v4.1.0-rc.0)
- Kunjungi: https://select2.org/getting-started/installation
- Download:
  - select2.min.css → public/vendor/select2/select2.min.css
  - select2.min.js → public/vendor/select2/select2.min.js
- Untuk theme, kunjungi: https://github.com/apalfrey/select2-bootstrap-5-theme
  - select2-bootstrap-5-theme.min.css → public/vendor/select2/select2-bootstrap-5-theme.min.css

## 6. SweetAlert2 (v11)
- Kunjungi: https://sweetalert2.github.io/
- Download:
  - sweetalert2.min.css → public/vendor/sweetalert2/sweetalert2.min.css
  - sweetalert2.all.min.js → public/vendor/sweetalert2/sweetalert2.min.js

## Struktur Folder Akhir
```
public/
└── vendor/
    ├── bootstrap/
    │   ├── bootstrap.min.css
    │   └── bootstrap.bundle.min.js
    ├── fontawesome/
    │   ├── all.min.css
    │   └── webfonts/
    │       ├── fa-solid-900.woff2
    │       ├── fa-regular-400.woff2
    │       └── fa-brands-400.woff2
    ├── jquery/
    │   └── jquery.min.js
    ├── chartjs/
    │   └── chart.umd.min.js
    ├── select2/
    │   ├── select2.min.css
    │   ├── select2.min.js
    │   └── select2-bootstrap-5-theme.min.css
    └── sweetalert2/
        ├── sweetalert2.min.css
        └── sweetalert2.min.js
```

## Verifikasi
Setelah semua file diunduh dan ditempatkan:
1. Bersihkan cache browser
2. Restart server development
3. Periksa tidak ada error di console browser
4. Pastikan semua komponen visual berfungsi dengan baik