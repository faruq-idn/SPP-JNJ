# BAB I
# PENDAHULUAN

## 1.1 Latar Belakang

Pondok Pesantren Jabal Nur Jadid merupakan sebuah lembaga pendidikan Islam yang terletak di Desa Meurandeh, Kecamatan Lembah Sabil, Kabupaten Aceh Barat Daya, Provinsi Aceh. Didirikan pada tanggal 19 Januari 2002 oleh Abuya Tgk. Nyak Diwan HS (Almarhum), seorang tokoh agama yang pernah mengaji di Pondok Darussalam Labuhan Haji Aceh Selatan. Model pendidikan Salafiyah yang diterapkan di Pondok Pesantren Jabal Nur Jadid telah berlangsung hingga tahun 2004.

Saat ini, pesantren mengelola pendidikan tingkat SMP dan SMA dengan total 12 rombongan belajar yang terdiri dari kelas A (putra) dengan rata-rata 22-26 santri dan kelas B dengan kisaran 25-32 santri per kelasnya. Dengan demikian, total santri yang harus dikelola pembayaran SPP-nya bisa mencapai lebih dari 300 santri setiap bulannya. Pengelolaan keuangan ini menjadi tantangan tersendiri mengingat jumlah santri yang cukup besar namun masih menggunakan sistem pencatatan manual.

Manajemen keuangan pondok pesantren saat ini dikelola oleh dua ustaz yang dibantu oleh tiga orang tenaga administrasi dari santriwati kelas 2 SMA. Proses pembukuan masih menggunakan cara konvensional dengan tiga media pencatatan terpisah:

1. Buku Identitas Santri
   - Berisi data lengkap santri dan wali
   - Informasi kategori pembayaran
   - Status aktif/non-aktif
   - Riwayat pembayaran awal masuk

2. Buku Iuran Bulanan Besar
   - Rekapitulasi pembayaran bulanan seluruh santri
   - Pencatatan tanggal dan nominal pembayaran
   - Status pembayaran per bulan
   - Perhitungan total tunggakan

3. Buku Iuran Bulanan Kecil
   - Buku pegangan masing-masing santri
   - Bukti pembayaran yang ditulis petugas
   - Catatan tanggal dan nominal pembayaran
   - Tanda tangan petugas sebagai validasi

Penggunaan tiga buku terpisah ini sering menimbulkan masalah dalam sinkronisasi data. Sebagai contoh, ketika santri melakukan pembayaran, petugas harus mencatat di buku kecil santri dan buku besar secara manual. Proses pencatatan ganda ini membutuhkan waktu sekitar 5-10 menit per transaksi dan rawan terjadi ketidakcocokan data. Saat rekapitulasi bulanan, petugas harus memeriksa ulang setiap transaksi di kedua buku yang memakan waktu 2-3 hari kerja.

Sistem pencatatan manual dengan tiga buku yang berbeda ini menimbulkan beberapa permasalahan serius:
1. Risiko ketidakcocokan data antar buku yang tinggi
2. Waktu yang terbuang untuk rekonsiliasi data
3. Kesulitan dalam pembuatan laporan dan rekapitulasi
4. Keluhan dari wali santri terkait transparansi pembayaran

Penelitian terdahulu oleh Ramadhana dan Fatmawati (2020) menunjukkan bahwa implementasi sistem informasi manajemen keuangan di Pondok Pesantren Adh-Dhuha berhasil meningkatkan efisiensi pengelolaan keuangan. Sejalan dengan itu, Awaludin dkk. (2021) juga membuktikan bahwa penggunaan sistem informasi dapat membantu lembaga pendidikan dalam mengelola keuangan secara lebih terstruktur.

Berdasarkan permasalahan tersebut, peneliti tertarik untuk melakukan penelitian dengan judul "Perancangan Sistem Informasi Manajemen Pembayaran SPP Berbasis Web di Pondok Pesantren Jabal Nur Jadid". Implementasi sistem ini diharapkan dapat meningkatkan efisiensi pengelolaan keuangan, mengurangi risiko kesalahan pencatatan, serta meningkatkan transparansi bagi wali santri.

## 1.2 Identifikasi Masalah

Berdasarkan observasi pada sistem pembayaran SPP yang berjalan di Pondok Pesantren Jabal Nur Jadid, dapat diidentifikasi beberapa permasalahan sebagai berikut:

1. Penggunaan tiga buku terpisah (buku iuran bulanan besar, buku iuran bulanan kecil, dan buku identitas santri) dalam pencatatan pembayaran SPP menyebabkan:
   - Tingginya risiko ketidaksesuaian data antar buku
   - Kesulitan dalam melacak riwayat pembayaran
   - Proses rekonsiliasi data yang memakan waktu

2. Sistem manual yang berjalan saat ini menimbulkan beberapa kendala operasional:
   - Pencarian data santri dan riwayat pembayaran membutuhkan waktu lama
   - Perhitungan tunggakan harus dilakukan secara manual
   - Pembuatan laporan keuangan membutuhkan waktu 2-3 hari kerja
   - Tidak ada backup data jika terjadi kerusakan atau kehilangan buku

3. Keterbatasan akses informasi bagi wali santri:
   - Wali santri harus datang langsung ke pesantren untuk mengecek status pembayaran
   - Sering terjadi perbedaan data antara bukti pembayaran santri dengan catatan administrasi
   - Tidak ada notifikasi untuk tagihan yang jatuh tempo

## 1.3 Batasan Masalah

Untuk memfokuskan pengembangan sistem dan memastikan hasil yang optimal, penelitian ini dibatasi pada hal-hal berikut:

1. Ruang Lingkup Sistem:
   - Pengelolaan data santri dan wali santri
   - Manajemen pembayaran SPP bulanan
   - Pencatatan dan pelacakan pembayaran
   - Pembuatan laporan keuangan

2. Fitur Teknis:
   - Sistem berbasis web menggunakan framework Laravel
   - Database MySQL untuk penyimpanan data
   - Integrasi dengan payment gateway untuk pembayaran online
   - Mendukung ekspor laporan dalam format PDF dan Excel

3. Hak Akses:
   - Admin: pengelolaan penuh sistem
   - Petugas: manajemen pembayaran dan laporan
   - Wali Santri: melihat tagihan dan melakukan pembayaran

4. Pembayaran:
   - Metode pembayaran manual (tunai/transfer)
   - Pembayaran online melalui payment gateway
   - Validasi dan konfirmasi pembayaran

## 1.4 Tujuan Penelitian

Berdasarkan identifikasi masalah yang telah dipaparkan, penelitian ini memiliki beberapa tujuan sebagai berikut:

1. Mengembangkan sistem informasi manajemen pembayaran SPP berbasis web yang:
   - Mengintegrasikan pencatatan pembayaran dalam satu sistem terpadu
   - Memiliki fitur pencarian dan pelacakan pembayaran yang cepat
   - Mendukung multiple user dengan hak akses berbeda

2. Mengimplementasikan sistem pembayaran yang:
   - Menyediakan opsi pembayaran manual dan online
   - Memiliki validasi otomatis untuk mengurangi kesalahan input
   - Menghasilkan bukti pembayaran yang terstandar

3. Membangun sistem pelaporan yang:
   - Mengotomatisasi perhitungan tunggakan
   - Menghasilkan laporan keuangan secara real-time
   - Mendukung ekspor data dalam berbagai format

## 1.5 Manfaat Penelitian

Penelitian ini diharapkan dapat memberikan manfaat sebagai berikut:

1. Bagi Pondok Pesantren:
   - Meningkatkan efisiensi pengelolaan pembayaran SPP
   - Mengurangi risiko kesalahan dalam pencatatan keuangan
   - Menyediakan sistem backup data yang aman
   - Memudahkan pembuatan laporan keuangan

2. Bagi Petugas Administrasi:
   - Mempercepat proses pencarian dan validasi data
   - Mengurangi beban kerja manual
   - Meningkatkan akurasi pencatatan
   - Memudahkan proses rekonsiliasi data

3. Bagi Wali Santri:
   - Memudahkan akses informasi pembayaran
   - Menyediakan berbagai opsi metode pembayaran
   - Memberikan transparansi status pembayaran
   - Menyediakan riwayat pembayaran yang jelas

## 1.6 Keaslian Penelitian (State of The Art)

### Penelitian Terkait

Tabel 1.1 Perbandingan dengan Penelitian Terdahulu

| No | Peneliti | Judul | Metode | Hasil | Perbedaan |
|----|----------|--------|---------|--------|------------|
| 1 | Ramadhana & Fatmawati (2020) | Sistem Informasi Manajemen Keuangan di Pondok Pesantren Adh-Dhuha | Waterfall | Menghasilkan sistem pengelolaan keuangan masuk dan keluar | • Fokus pada manajemen SPP<br>• Integrasi payment gateway<br>• Fitur notifikasi |
| 2 | Awaludin, Bahri, & Muslih (2021) | Penerapan Zachman Framework Dalam Perancangan Sistem Informasi Manajemen Keuangan Sekolah | Zachman Framework | Sistem sesuai dengan kebutuhan pemilik lembaga | • Framework Laravel<br>• Multi-user roles<br>• Mobile responsive |
| 3 | Widianto & Kurniadi (2021) | Rancang Bangun Sistem Informasi Manajemen Keuangan RT/RW Berbasis Web | Unified Approach (UA) | Sistem pengelolaan keuangan warga | • Fokus pendidikan<br>• Integrasi data santri<br>• Fitur tunggakan |

### Kebaruan Penelitian

Penelitian ini memiliki beberapa aspek kebaruan yang membedakannya dari penelitian sebelumnya:

1. Teknologi dan Arsitektur:
   - Penggunaan framework Laravel terbaru
   - Implementasi REST API untuk integrasi sistem
   - Penerapan payment gateway untuk pembayaran online

2. Fitur dan Fungsionalitas:
   - Sistem notifikasi otomatis via email dan WhatsApp
   - Dashboard analitik untuk monitoring pembayaran
   - Manajemen multi-tarif berdasarkan kategori santri

3. Aspek Keamanan:
   - Implementasi role-based access control

4. Antarmuka Pengguna:
   - Desain responsif untuk akses mobile
   - Interface yang mudah digunakan

Dengan demikian, meskipun terdapat beberapa penelitian serupa sebelumnya, penelitian ini memiliki keunikan dan nilai tambah tersendiri, terutama dalam konteks penerapannya di lingkungan pesantren dengan kebutuhan spesifik mereka.
