# Pengujian Black Box Sistem Informasi Pembayaran SPP

## 1. Pengujian Modul Autentikasi

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Proses Masuk Sistem | • Alamat surel benar<br>• Kata sandi benar | Pengguna masuk ke beranda sesuai peran | Sesuai |
| Validasi Kredensial | • Alamat surel/kata sandi salah | Sistem menampilkan pesan kesalahan | Sesuai |
| Keluar Sistem | Menekan tombol keluar | Pengguna kembali ke halaman masuk | Sesuai |

## 2. Pengujian Modul Santri

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Penambahan Data Santri | Mengisi formulir data santri | Data tersimpan dalam basis data | Sesuai |
| Kenaikan Kelas | Memilih santri dan kelas tujuan | Data kelas santri diperbarui | Sesuai |
| Pengubahan Data | Memperbarui data santri | Data berhasil diperbarui | Sesuai |
| Pencarian Data | Memasukkan nama/NIS | Menampilkan data yang sesuai | Sesuai |

## 3. Pengujian Modul Pembayaran

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Pembayaran Tunai | Memilih bulan dan nominal | Status pembayaran menjadi lunas | Sesuai |
| Pembayaran Daring | Memilih metode pembayaran | Teralihkan ke gerbang pembayaran | Sesuai |
| Riwayat Pembayaran | Memilih menu riwayat | Menampilkan daftar transaksi | Sesuai |
| Penyaringan Data | Memilih bulan dan tahun | Menampilkan data sesuai filter | Sesuai |

## 4. Pengujian Modul Wali Santri

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Melihat Tagihan | Membuka menu tagihan | Menampilkan daftar tagihan aktif | Sesuai |
| Melakukan Pembayaran | Memilih tagihan dan metode | Transaksi pembayaran berhasil | Sesuai |
| Melihat Riwayat | Membuka menu riwayat | Menampilkan riwayat transaksi | Sesuai |
| Memperbarui Profil | Mengubah data profil | Data profil diperbarui | Sesuai |

## 5. Pengujian Modul Laporan

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Laporan Harian | Memilih tanggal | Menampilkan transaksi harian | Sesuai |
| Laporan Bulanan | Memilih bulan dan tahun | Menampilkan rekapitulasi bulanan | Sesuai |
| Ekspor PDF | Menekan tombol PDF | Mengunduh berkas PDF | Sesuai |
| Ekspor Excel | Menekan tombol Excel | Mengunduh berkas Excel | Sesuai |

## 6. Pengujian Modul Pengguna

| Kasus Uji | Masukan | Hasil yang Diharapkan | Kesesuaian |
|-----------|---------|----------------------|------------|
| Penambahan Pengguna | Mengisi formulir pengguna baru | Pengguna baru tersimpan | Sesuai |
| Pengubahan Peran | Mengubah peran pengguna | Peran pengguna diperbarui | Sesuai |
| Pengaturan Ulang Kata Sandi | Menekan tombol atur ulang | Kata sandi direset dan surel terkirim | Sesuai |
| Penonaktifan Pengguna | Menekan tombol nonaktif | Status pengguna dinonaktifkan | Sesuai |
