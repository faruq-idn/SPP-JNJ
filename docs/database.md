# Perancangan Database Sistem Pembayaran SPP

## Deskripsi Sistem
Sistem ini dirancang untuk mengelola pembayaran SPP di pesantren dengan fitur manajemen santri, pencatatan pembayaran, dan pelaporan keuangan. Database mendukung multi-role user (admin, petugas, wali) dan dapat menangani berbagai metode pembayaran.

## Struktur Tabel

### 1. Tabel users
Tabel sentral yang menyimpan data semua pengguna sistem termasuk admin, petugas, dan wali santri. Tabel ini menangani autentikasi dan otorisasi sistem.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| name | VARCHAR(255) | Nama pengguna |
| email | VARCHAR(255) | Email untuk login |
| email_verified_at | TIMESTAMP | Waktu verifikasi email |
| password | VARCHAR(255) | Password terenkripsi |
| remember_token | VARCHAR(100) | Token "remember me" |
| role | ENUM | Role pengguna |
| no_hp | VARCHAR(255) | Nomor HP untuk notifikasi |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir diupdate |

### 2. Tabel santri
Tabel utama yang menyimpan data santri sebagai subjek utama dalam sistem pembayaran SPP. Mencakup informasi pribadi, akademik, dan status pembayaran.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| nisn | VARCHAR(255) | Nomor Induk Siswa Nasional - identifikasi unik |
| nama | VARCHAR(255) | Nama lengkap santri |
| jenis_kelamin | ENUM | Jenis kelamin (L/P) |
| tanggal_lahir | DATE | Tanggal lahir |
| alamat | TEXT | Alamat lengkap |
| wali_id | BIGINT(20) | ID wali (FK ke users) |
| nama_wali | VARCHAR(255) | Nama wali santri |
| tanggal_masuk | DATE | Tanggal mulai masuk pesantren |
| jenjang | ENUM | Jenjang pendidikan (SMP/SMA) |
| kelas | VARCHAR(255) | Kelas saat ini |
| status | ENUM | Status santri (aktif/non-aktif/lulus/keluar) |
| tahun_tamat | YEAR(4) | Tahun kelulusan/tamat |
| kategori_id | BIGINT(20) | ID kategori santri (FK) |
| status_spp | ENUM | Status pembayaran SPP |
| created_at | TIMESTAMP | Waktu pembuatan record |
| updated_at | TIMESTAMP | Waktu terakhir update |

### 3. Tabel pembayaran_spp
Tabel transaksi yang mencatat seluruh pembayaran SPP. Menyimpan detail lengkap setiap transaksi termasuk integrasi dengan payment gateway.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| santri_id | BIGINT(20) | ID santri pembayar (FK) |
| metode_pembayaran_id | BIGINT(20) | ID metode pembayaran (FK) |
| bulan | VARCHAR(255) | Bulan yang dibayar |
| tahun | YEAR(4) | Tahun pembayaran |
| nominal | DECIMAL(10,0) | Jumlah pembayaran |
| tanggal_bayar | TIMESTAMP | Waktu pembayaran dilakukan |
| keterangan | VARCHAR(255) | Catatan tambahan |
| snap_token | VARCHAR(255) | Token Midtrans untuk pembayaran online |
| order_id | CHAR(36) | ID unik order Midtrans |
| payment_type | VARCHAR(255) | Metode pembayaran yang digunakan |
| transaction_id | VARCHAR(255) | ID transaksi dari Midtrans |
| payment_details | JSON | Detail lengkap pembayaran |
| status | VARCHAR(255) | Status transaksi |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir update |

### 4. Tabel kategori_santri
Tabel master untuk mengkategorikan santri berdasarkan berbagai kriteria yang mempengaruhi tarif SPP.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| nama | VARCHAR(255) | Nama kategori (mis: Reguler, Beasiswa) |
| keterangan | TEXT | Penjelasan lengkap kategori |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir update |

### 5. Tabel riwayat_tarif_spp
Tabel yang mencatat sejarah perubahan tarif SPP untuk setiap kategori santri, memungkinkan pelacakan perubahan tarif dari waktu ke waktu.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| kategori_id | BIGINT(20) | ID kategori santri (FK) |
| nominal | DECIMAL(10,2) | Besaran tarif SPP |
| biaya_makan | DECIMAL(10,2) | Biaya Makan Santri |
| biaya_asrama | DECIMAL(10,2) | Biaya Asrama Santri |
| biaya_listrik | DECIMAL(10,2) | Biaya listrik Santri |
| biaya_kesehatan | DECIMAL(10,2) | Biaya kesehatan Santri |
| berlaku_mulai | DATE | Tanggal mulai berlaku |
| berlaku_sampai | DATE | Tanggal akhir berlaku |
| keterangan | TEXT | Alasan perubahan tarif |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir update |

### 6. Tabel metode_pembayaran
Tabel master yang mendefinisikan metode pembayaran yang tersedia dalam sistem.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| nama | VARCHAR(255) | Nama metode pembayaran |
| kode | VARCHAR(255) | Kode unik metode |
| status | ENUM | Status aktif/nonaktif |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir update |

### 7. Tabel kenaikan_kelas_history
Tabel yang mencatat riwayat akademik santri terkait kenaikan kelas, memberikan visibilitas terhadap progress pendidikan.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| santri_id | BIGINT(20) | ID santri (FK) |
| kelas_sebelum | VARCHAR(255) | Kelas asal |
| kelas_sesudah | VARCHAR(255) | Kelas tujuan |
| status | ENUM | Status kenaikan/kelulusan |
| created_by | BIGINT(20) | ID user pencatat (FK) |
| created_at | TIMESTAMP | Waktu pencatatan |
| updated_at | TIMESTAMP | Waktu terakhir update |

## Enumerasi yang Digunakan

### Role Pengguna
```sql
ENUM('admin', 'petugas', 'wali')
```
- `admin`: Akses penuh ke sistem
- `petugas`: Mengelola pembayaran dan data santri
- `wali`: Melihat dan membayar SPP

### Status Santri
```sql
ENUM('aktif', 'non-aktif', 'lulus', 'keluar')
```
- `aktif`: Santri masih bersekolah
- `non-aktif`: Santri sedang cuti/ditangguhkan
- `lulus`: Telah menyelesaikan pendidikan
- `keluar`: Berhenti sebelum lulus

### Status SPP
```sql
ENUM('Lunas', 'Belum Lunas')
```
Menandakan status pembayaran SPP periode berjalan

### Status Metode Pembayaran
```sql
ENUM('aktif', 'nonaktif')
```
Mengontrol ketersediaan metode pembayaran

### Jenis Kelamin
```sql
ENUM('L', 'P')
```
L: Laki-laki, P: Perempuan

### Jenjang Pendidikan
```sql
ENUM('SMP', 'SMA')
```
Tingkat pendidikan santri

## Relasi Antar Tabel

1. **Santri dengan Users (Wali)**
   - `santri.wali_id` → `users.id`
   - ON DELETE SET NULL: Jika wali dihapus, santri tetap ada

2. **Santri dengan Kategori**
   - `santri.kategori_id` → `kategori_santri.id`
   - Menentukan tarif SPP yang berlaku

3. **Pembayaran SPP dengan Santri**
   - `pembayaran_spp.santri_id` → `santri.id`
   - ON DELETE CASCADE: Pembayaran terhapus jika santri dihapus

4. **Pembayaran SPP dengan Metode Pembayaran**
   - `pembayaran_spp.metode_pembayaran_id` → `metode_pembayaran.id`
   - ON DELETE SET NULL: Histori pembayaran tetap ada

5. **Riwayat Tarif dengan Kategori**
   - `riwayat_tarif_spp.kategori_id` → `kategori_santri.id`
   - Mencatat perubahan tarif per kategori

6. **Kenaikan Kelas dengan Santri**
   - `kenaikan_kelas_history.santri_id` → `santri.id`
   - ON DELETE CASCADE: Riwayat terhapus jika santri dihapus

7. **Kenaikan Kelas dengan Users**
   - `kenaikan_kelas_history.created_by` → `users.id`
   - Mencatat petugas yang melakukan pencatatan