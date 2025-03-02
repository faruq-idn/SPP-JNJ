## 3.4 Perancangan Antarmuka Pengguna

Berikut ini adalah penjelasan rancangan antarmuka pengguna yang akan dikembangkan dalam sistem informasi manajemen pembayaran SPP:

### 3.4.1 Antarmuka Umum

**Gambar 3.11 Rancangan Halaman Awal**
Rancangan halaman awal didesain sebagai landing page yang akan menampilkan informasi umum tentang Pondok Pesantren Jabal Nur Jadid. Pada halaman ini akan ditampilkan logo pesantren, menu navigasi utama, serta tombol login untuk mengakses sistem. Tata letak dirancang dengan menerapkan prinsip minimalis namun tetap memberikan informasi yang relevan bagi pengunjung.

**Gambar 3.12 Rancangan Halaman Login**
Antarmuka halaman login dirancang dengan mempertimbangkan aspek keamanan dan kemudahan penggunaan. Form login akan terdiri dari field username dan password dengan validasi input yang ketat. Sistem akan memberikan feedback yang informatif ketika terjadi kesalahan login untuk memudahkan pengguna mengidentifikasi masalah.

### 3.4.2 Antarmuka Administrator

**Gambar 3.13 Rancangan Halaman Dashboard Admin**
Dashboard administrator dirancang untuk memberikan gambaran komprehensif tentang status keuangan pesantren. Beberapa elemen yang akan ditampilkan meliputi:
- Grafik tren pembayaran SPP bulanan
- Statistik total pembayaran bulan berjalan
- Persentase tunggakan per kelas
- Notifikasi pembayaran terbaru
- Ringkasan jumlah santri aktif

**Gambar 3.14 Rancangan Halaman Menu Data Santri**
Halaman pengelolaan data santri dirancang dengan fokus pada efisiensi pencarian dan pengelolaan informasi. Fitur-fitur yang akan disediakan meliputi:
- Tabel data santri dengan paginasi
- Filter berdasarkan kelas, kategori, dan status
- Kolom pencarian dengan auto-suggest
- Tombol aksi untuk melihat detail, edit, dan hapus data

**Gambar 3.15 Rancangan Halaman Detail Santri**
Halaman detail santri akan menampilkan informasi komprehensif dalam layout yang terstruktur, terdiri dari:
- Data pribadi santri
- Informasi wali santri
- Status akademik
- Riwayat pembayaran
- Statistik keaktifan pembayaran

**Gambar 3.16 Rancangan Halaman Detail Pembayaran Santri**
Antarmuka ini fokus pada visualisasi data pembayaran santri secara detail, menampilkan:
- Timeline pembayaran
- Status pembayaran per bulan
- Total tunggakan terkini
- Riwayat transaksi lengkap
- Opsi cetak bukti pembayaran

### 3.4.3 Antarmuka Pengelolaan Data

**Gambar 3.17 Rancangan Halaman Form Tambah/Edit Santri**
Form pengelolaan data santri dirancang dengan pendekatan user-friendly:
- Grouped form fields berdasarkan kategori informasi
- Validasi real-time untuk mengurangi kesalahan input
- Auto-save draft untuk mencegah kehilangan data
- Preview data sebelum penyimpanan final

**Gambar 3.18 Rancangan Halaman Import Data Santri**
Halaman import data dirancang untuk memudahkan migrasi data dalam jumlah besar:
- Template Excel yang dapat diunduh
- Validasi format sebelum import
- Preview data yang akan diimport
- Log hasil import dengan detail sukses/gagal

**Gambar 3.19 Rancangan Halaman Proses Kenaikan Kelas**
Antarmuka kenaikan kelas dirancang untuk memudahkan proses di akhir tahun ajaran:
- Daftar santri dengan checkbox untuk seleksi massal
- Filter berdasarkan kelas asal
- Preview perubahan kelas
- Konfirmasi sebelum eksekusi

### 3.4.4 Antarmuka Keuangan

**Gambar 3.20 Rancangan Halaman Menu Pembayaran**
Halaman transaksi pembayaran dirancang dengan prioritas pada kecepatan dan akurasi:
- Form pembayaran dengan auto-complete nama santri
- Kalkulasi otomatis jumlah yang harus dibayar
- Opsi pembayaran parsial
- Preview bukti pembayaran

**Gambar 3.21 Rancangan Halaman Pembuatan Tagihan Masal**
Antarmuka pembuatan tagihan massal didesain untuk efisiensi operasional:
- Filter berdasarkan kelas/kategori
- Setting periode tagihan
- Preview tagihan sebelum dibuat
- Notifikasi hasil pembuatan tagihan

**Gambar 3.22 Rancangan Halaman Menu Kategori**
Pengelolaan kategori santri dirancang dengan tampilan yang informatif:
- Tabel daftar kategori
- Informasi jumlah santri per kategori
- Tarif SPP terkait
- Opsi edit dan hapus kategori

**Gambar 3.23 Rancangan Halaman Tambah Kategori**
Form penambahan kategori baru dirancang simpel namun lengkap:
- Field nama kategori
- Input tarif SPP
- Deskripsi kategori
- Status aktif/non-aktif

**Gambar 3.24 Rancangan Halaman Edit Kategori**
Form edit kategori dirancang mirip dengan form tambah dengan tambahan:
- Data existing yang dapat diubah
- History perubahan tarif
- Opsi pengaktifan/penonaktifan

### 3.4.5 Antarmuka Pelaporan

**Gambar 3.25 Rancangan Halaman Menu Laporan**
Halaman utama laporan dirancang untuk memudahkan akses ke berbagai jenis laporan:
- Menu pemilihan jenis laporan
- Filter periode laporan
- Preview laporan sebelum ekspor
- Opsi format ekspor

**Gambar 3.26 Rancangan Halaman Laporan Tunggakan**
Antarmuka laporan tunggakan dirancang dengan fokus pada detail informasi:
- Filter berdasarkan kelas/kategori
- Periode tunggakan
- Total tunggakan per santri
- Opsi ekspor data

**Gambar 3.27 Rancangan Tampilan Export Laporan Tunggakan PDF**
Template laporan PDF dirancang profesional dengan:
- Kop surat pesantren
- Tabel data yang terstruktur
- Footer dengan informasi pembuat laporan
- Nomor halaman dan timestamp

**Gambar 3.28 Rancangan Tampilan Export Laporan Tunggakan Excel**
Format laporan Excel dirancang untuk kemudahan analisis:
- Header yang jelas
- Format sel yang konsisten
- Formula untuk perhitungan otomatis
- Worksheet yang terorganisir

### 3.4.6 Antarmuka Wali Santri

**Gambar 3.29 Rancangan Halaman Dashboard Wali**
Dashboard wali santri dirancang informatif dan mudah dipahami:
- Status pembayaran terkini
- Notifikasi tagihan
- Riwayat pembayaran terakhir
- Informasi kontak pesantren

**Gambar 3.30 Rancangan Halaman Tagihan dan Riwayat Pembayaran**
Halaman ini dirancang untuk memberikan transparansi penuh kepada wali santri:
- Daftar tagihan aktif
- Status pembayaran per bulan
- History pembayaran lengkap
- Detail tunggakan

**Gambar 3.31 Rancangan Halaman Detail Pembayaran**
Tampilan detail transaksi dirancang komprehensif:
- Informasi transaksi lengkap
- Status pembayaran
- Bukti pembayaran
- Opsi cetak bukti pembayaran

**Gambar 3.32 Rancangan Halaman Pembayaran Online**
Antarmuka pembayaran online dirancang dengan prioritas keamanan dan kemudahan:
- Pilihan metode pembayaran
- Form pembayaran yang aman
- Instruksi pembayaran detail
- Konfirmasi status pembayaran

**Gambar 3.33 Rancangan Halaman Hubungkan Santri**
Halaman penghubung akun wali dengan santri dirancang simpel:
- Form input NIS/nama santri
- Verifikasi data santri
- Konfirmasi hubungan wali-santri
- Notifikasi hasil penghubungan

Semua rancangan antarmuka di atas dikembangkan dengan memperhatikan prinsip-prinsip desain responsif, konsistensi visual, dan kemudahan penggunaan. Penggunaan elemen visual seperti warna, tipografi, dan spacing disesuaikan dengan identitas visual Pondok Pesantren Jabal Nur Jadid serta mengikuti best practices dalam pengembangan aplikasi web modern.
