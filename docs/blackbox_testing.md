# Pengujian Black Box - Sistem Pembayaran SPP

## 1. Login & Autentikasi

| Test Case | Input | Yang Diharapkan | Status |
|-----------|-------|-----------------|--------|
| Login Sukses | • Email benar<br>• Password benar | Masuk ke dashboard sesuai role | ✅ |
| Login Gagal | • Email/password salah | Tampil pesan error | ✅ |
| Logout | Klik tombol logout | Kembali ke halaman login | ✅ |

## 2. Pengelolaan Santri

| Test Case | Input | Yang Diharapkan | Status |
|-----------|-------|-----------------|--------|
| Tambah Santri | Form data santri lengkap | Data tersimpan & tampil di daftar | ✅ |
| Naik Kelas | Pilih santri & kelas tujuan | Kelas santri terupdate | ✅ |
| Edit Data | Ubah data santri yang ada | Data berhasil diperbarui | ✅ |
| Cari Santri | Ketik nama/NIS di pencarian | Tampil data yang sesuai | ✅ |

## 3. Pembayaran SPP

| Test Case | Input | Yang Diharapkan | Status |
|-----------|-------|-----------------|--------|
| Bayar Tunai | Pilih bulan & input nominal | Status bayar jadi lunas | ✅ |
| Bayar Online | Pilih metode & proses bayar | Redirect ke payment gateway | ✅ |
| Lihat History | Klik menu riwayat | Tampil daftar pembayaran | ✅ |
| Filter Laporan | Pilih bulan & tahun | Tampil data sesuai filter | ✅ |

## 4. Wali Santri

| Test Case | Input | Yang Diharapkan | Status |
|-----------|-------|-----------------|--------|
| Lihat Tagihan | Buka menu tagihan | Tampil daftar tagihan aktif | ✅ |
| Bayar SPP | Pilih tagihan & metode bayar | Proses pembayaran berhasil | ✅ |
| Lihat Riwayat | Buka menu riwayat | Tampil history pembayaran | ✅ |
| Update Profil | Edit data profil | Data profil terupdate | ✅ |

## 5. Laporan

| Test Case | Input | Yang Diharapkan | Status |
|-----------|-------|-----------------|--------|
| Laporan Harian | Pilih tanggal | Tampil transaksi per hari | ✅ |
| Laporan Bulanan | Pilih bulan & tahun | Tampil rekap bulanan | ✅ |
| Export PDF | Klik tombol PDF | Download file PDF | ✅ |
| Export Excel | Klik tombol Excel | Download file Excel | ✅ |

## 6. Pengelolaan User

| Test Case | Input | Yang Diharapkan | Status |
|-----------|-------|-----------------|--------|
| Tambah User | Isi form user baru | User baru tersimpan | ✅ |
| Edit Role | Ubah role user | Role terupdate | ✅ |
| Reset Password | Klik reset password | Password direset & email terkirim | ✅ |
| Nonaktifkan User | Klik nonaktifkan | Status user jadi nonaktif | ✅ |
