## 4.2 Implementasi Antarmuka Pengguna

### 4.2.1 Implementasi Halaman Umum

**Gambar 4.1 Halaman Awal**
Halaman awal sistem menampilkan tampilan pembuka yang menarik dengan latar belakang bergradasi. Bagian atas memuat bilah navigasi dengan logo pesantren dan menu-menu penting. Bagian tengah menampilkan judul sistem disertai gambar ilustrasi yang bergerak saat disorot. Bagian bawah memuat informasi tentang pesantren dan keunggulan sistem pembayaran.

**Gambar 4.2 Halaman Login**
Halaman masuk menampilkan formulir dengan tata letak terpusat. Bagian atas memuat logo pesantren dan pesan selamat datang. Formulir dilengkapi kolom email dan kata sandi dengan tampilan kesalahan yang jelas. Tersedia pilihan "Ingat Saya" dan tautan untuk mengatur ulang kata sandi. Tampilan tombol masuk berubah saat proses autentikasi berlangsung.

### 4.2.2 Implementasi Halaman Admin

**Gambar 4.3 & 4.4 Halaman Utama Admin**
Halaman utama administrator menampilkan empat kotak ringkasan dengan ikon dan warna berbeda: jumlah santri, total penerimaan, total tunggakan, dan jumlah pengguna. Bagian bawah menampilkan statistik bulanan dengan bilah pilihan bulan dan tahun. Tersedia daftar pembayaran terbaru dan santri dengan tunggakan terbanyak.

**Gambar 4.5 Halaman Data Santri**
Halaman daftar santri menampilkan tabel dengan kemampuan mencari, menyaring, dan mengurutkan data. Setiap baris menampilkan nomor induk, nama, kelas, dan kategori santri. Tersedia tombol untuk menambah santri baru, mengunggah data massal, dan melakukan kenaikan kelas.

**Gambar 4.6 Halaman Tambah Data Santri**
Formulir penambahan santri dibagi menjadi beberapa bagian: data pribadi santri, data wali, dan informasi akademik. Setiap bagian ditata rapi dengan label yang jelas dan pemeriksaan data langsung. Sistem memastikan nomor induk yang dimasukkan belum terdaftar.

**Gambar 4.7 Halaman Ubah Data Santri**
Halaman pengubahan data santri menggunakan formulir yang sama dengan halaman tambah. Data yang ada ditampilkan dalam kolom yang sesuai dan dapat diubah. Sistem menyimpan riwayat perubahan data penting seperti kelas dan kategori.

**Gambar 4.8 Halaman Unggah Data Santri**
Halaman ini menyediakan area untuk mengunggah berkas Excel dengan contoh format yang dapat diunduh. Sistem memeriksa format data sebelum disimpan dan menampilkan pesan kesalahan yang informatif jika ditemukan ketidaksesuaian.

**Gambar 4.9 Halaman Kenaikan Kelas**
Halaman kenaikan kelas menampilkan daftar santri yang dapat dipilih dengan kotak centang. Tersedia pilihan penyaringan berdasarkan kelas dan konfirmasi sebelum proses kenaikan kelas dilakukan. Sistem mencatat riwayat kenaikan kelas setiap santri.

**Gambar 4.10 & 4.11 Halaman Detail Santri**
Halaman detail santri menampilkan informasi lengkap dalam beberapa bagian: data pribadi, data wali, riwayat pembayaran, dan status akademik. Status pembayaran ditampilkan dengan warna yang berbeda untuk memudahkan pemantauan.

### 4.2.3 Implementasi Pembayaran dan Tagihan

**Gambar 4.12 Halaman Detail Pembayaran**
Halaman ini menampilkan rincian transaksi pembayaran seperti tanggal, nominal, metode pembayaran, dan status. Bukti pembayaran dapat dilihat dan diunduh dalam format PDF.

**Gambar 4.13 Halaman Data Pembayaran**
Halaman ini menampilkan seluruh riwayat pembayaran dalam bentuk tabel. Petugas dapat menyaring data berdasarkan periode, status pembayaran, dan kategori santri.

**Gambar 4.14 Halaman Pembuatan Tagihan Massal**
Halaman ini menyediakan formulir untuk membuat tagihan untuk beberapa santri sekaligus. Petugas dapat memilih kelas atau kategori santri tertentu dan mengatur nominal tagihan.

### 4.2.4 Implementasi Laporan

**Gambar 4.15 Halaman Laporan**
Halaman utama laporan menampilkan ringkasan keuangan dalam bentuk statistik dan grafik. Terdapat informasi total pembayaran, jumlah santri yang sudah membayar, total tunggakan, dan perbandingan pembayaran antar periode.

**Gambar 4.16 Halaman Laporan Tunggakan**
Halaman ini menampilkan daftar tunggakan santri dengan berbagai pilihan penyaringan. Data yang ditampilkan mencakup informasi santri, wali, jumlah tunggakan, dan riwayat pembayaran.

**Gambar 4.17 Tampilan Laporan Tunggakan PDF**
Tampilan laporan dalam format PDF disusun dengan tata letak yang rapi, mencakup kop surat pesantren, data tunggakan yang terstruktur, dan keterangan yang diperlukan untuk keperluan arsip.

**Gambar 4.18 Tampilan Laporan Tunggakan Excel**
Format laporan dalam bentuk Excel disusun dengan kolom-kolom yang sesuai untuk pengolahan data lebih lanjut. Setiap sheet berisi informasi yang terpisah untuk memudahkan analisis data.

### 4.2.5 Implementasi Kategori dan Pengguna

**Gambar 4.19 Halaman Kategori Santri**
Halaman kategori santri menampilkan daftar kategori dengan rincian biaya masing-masing komponen SPP. Setiap kategori memiliki tombol untuk mengubah dan menghapus data.

**Gambar 4.20 & 4.21 Halaman Kategori**
Formulir kategori santri dirancang untuk mengelola komponen biaya SPP seperti biaya makan, asrama, listrik, dan kesehatan. Sistem memvalidasi perubahan tarif dan menyimpan riwayat perubahan.

**Gambar 4.22, 4.23 & 4.24 Halaman Pengelolaan Pengguna**
Halaman ini menampilkan daftar pengguna sistem beserta perannya. Formulir penambahan dan pengubahan pengguna mencakup pengaturan hak akses dan informasi kontak.

### 4.2.6 Implementasi Antarmuka Wali Santri

**Gambar 4.25 & 4.26 Halaman Dashboard Wali**
Dashboard wali santri menampilkan ringkasan informasi santri dan status pembayaran. Tersedia grafik dan indikator visual untuk memantau status pembayaran SPP.

**Gambar 4.27 Halaman Dashboard Wali - Tunggakan**
Bagian ini menampilkan daftar tunggakan dalam bentuk kartu yang informatif. Setiap kartu menampilkan periode, nominal, dan batas waktu pembayaran.

**Gambar 4.28 Halaman Tagihan**
Halaman tagihan menampilkan daftar tagihan aktif dengan status pembayaran. Wali santri dapat memilih tagihan untuk dibayar dan memilih metode pembayaran yang tersedia.

**Gambar 4.29 & 4.30 Detail Pembayaran SPP**
Halaman ini menampilkan rincian pembayaran dengan status yang berbeda untuk tagihan lunas dan belum lunas. Bukti pembayaran dapat diunduh untuk tagihan yang sudah dibayar.

**Gambar 4.31 & 4.32 Pembayaran Online**
Halaman pembayaran daring menampilkan pilihan metode pembayaran yang tersedia. Setelah memilih metode, sistem menampilkan petunjuk pembayaran yang jelas dan nomor pembayaran.

**Gambar 4.33 Pengaturan Profil Wali**
Halaman pengaturan profil memungkinkan wali santri mengubah informasi pribadi dan pengaturan pemberitahuan. Sistem memvalidasi perubahan data sebelum disimpan.
