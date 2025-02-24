# BAB IV
# HASIL DAN PEMBAHASAN

## 4.1 Pengujian Sistem

Pada tahap ini dilakukan pengujian black box terhadap Sistem Informasi Pembayaran SPP untuk memverifikasi bahwa seluruh fungsionalitas sistem telah berjalan sesuai dengan rancangan. Metode pengujian black box dipilih karena memungkinkan evaluasi sistem dari perspektif pengguna akhir dengan menguji berbagai skenario penggunaan tanpa perlu mengetahui struktur internal sistem. Pengujian dilaksanakan dengan memperhatikan aspek masukan, proses, dan keluaran dari setiap modul yang ada dalam sistem. Proses pengujian mencakup verifikasi antarmuka pengguna, validasi alur data, serta pemeriksaan keakuratan hasil pemrosesan pada setiap fungsi sistem. Berikut adalah hasil pengujian yang telah dilakukan terhadap masing-masing modul dalam sistem.

### 4.1.1 Pengujian Modul Autentikasi

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Proses Masuk Sistem | • Alamat surel benar<br>• Kata sandi benar | Pengguna masuk ke beranda sesuai peran | Sesuai |
| Validasi Kredensial | • Alamat surel/kata sandi salah | Sistem menampilkan pesan kesalahan | Sesuai |
| Keluar Sistem | Menekan tombol keluar | Pengguna kembali ke halaman masuk | Sesuai |

### 4.1.2 Pengujian Modul Santri

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Penambahan Data Santri | Mengisi formulir data santri | Data tersimpan dalam basis data | Sesuai |
| Kenaikan Kelas | Memilih santri dan kelas tujuan | Data kelas santri diperbarui | Sesuai |
| Pengubahan Data | Memperbarui data santri | Data berhasil diperbarui | Sesuai |
| Pencarian Data | Memasukkan nama/NIS | Menampilkan data yang sesuai | Sesuai |

### 4.1.3 Pengujian Modul Pembayaran

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Pembayaran Tunai | Memilih bulan dan nominal | Status pembayaran menjadi lunas | Sesuai |
| Pembayaran Daring | Memilih metode pembayaran | Teralihkan ke gerbang pembayaran | Sesuai |
| Riwayat Pembayaran | Memilih menu riwayat | Menampilkan daftar transaksi | Sesuai |
| Penyaringan Data | Memilih bulan dan tahun | Menampilkan data sesuai filter | Sesuai |

### 4.1.4 Pengujian Modul Wali Santri

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Melihat Tagihan | Membuka menu tagihan | Menampilkan daftar tagihan aktif | Sesuai |
| Melakukan Pembayaran | Memilih tagihan dan metode | Transaksi pembayaran berhasil | Sesuai |
| Melihat Riwayat | Membuka menu riwayat | Menampilkan riwayat transaksi | Sesuai |
| Memperbarui Profil | Mengubah data profil | Data profil diperbarui | Sesuai |

### 4.1.5 Pengujian Modul Laporan

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Laporan Harian | Memilih tanggal | Menampilkan transaksi harian | Sesuai |
| Laporan Bulanan | Memilih bulan dan tahun | Menampilkan rekapitulasi bulanan | Sesuai |
| Ekspor PDF | Menekan tombol PDF | Mengunduh berkas PDF | Sesuai |
| Ekspor Excel | Menekan tombol Excel | Mengunduh berkas Excel | Sesuai |

### 4.1.6 Pengujian Modul Pengguna

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Penambahan Pengguna | Mengisi formulir pengguna baru | Pengguna baru tersimpan | Sesuai |
| Pengubahan Peran | Mengubah peran pengguna | Peran pengguna diperbarui | Sesuai |
| Pengaturan Ulang Kata Sandi | Menekan tombol atur ulang | Kata sandi direset dan surel terkirim | Sesuai |
| Penonaktifan Pengguna | Menekan tombol nonaktif | Status pengguna dinonaktifkan | Sesuai |

## 4.2 Analisis Hasil Pengujian

Berdasarkan rangkaian pengujian yang telah dilaksanakan terhadap Sistem Informasi Pembayaran SPP, dapat disimpulkan bahwa sistem telah memenuhi seluruh kriteria fungsional yang diharapkan. Setiap modul dalam sistem mampu menjalankan fungsinya dengan baik, mulai dari proses autentikasi hingga pengelolaan laporan. Antarmuka pengguna dapat dioperasikan dengan mudah dan responsif, validasi data berjalan efektif dalam mencegah kesalahan input, serta pemrosesan transaksi pembayaran dan pelaporan menghasilkan data yang akurat. Dengan demikian, sistem ini telah siap untuk diimplementasikan dalam lingkungan operasional sebenarnya.
