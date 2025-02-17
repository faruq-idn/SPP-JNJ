# Perancangan Database

## Daftar Enum

### Status Kenaikan Kelas
```sql
ENUM('aktif', 'lulus')
```
Menandakan status kenaikan kelas santri:
- `aktif`: Santri naik ke kelas berikutnya
- `lulus`: Santri telah menyelesaikan pendidikan

### Status Metode Pembayaran
```sql
ENUM('aktif', 'nonaktif')
```
Status ketersediaan metode pembayaran:
- `aktif`: Metode pembayaran dapat digunakan
- `nonaktif`: Metode pembayaran tidak dapat digunakan

### Jenis Kelamin Santri
```sql
ENUM('L', 'P')
```
- `L`: Laki-laki
- `P`: Perempuan

### Jenjang Pendidikan
```sql
ENUM('SMP', 'SMA')
```
Jenjang pendidikan santri

### Status Santri
```sql
ENUM('aktif', 'non-aktif', 'lulus', 'keluar')
```
Status keaktifan santri:
- `aktif`: Santri masih aktif bersekolah
- `non-aktif`: Santri sedang tidak aktif
- `lulus`: Santri telah lulus
- `keluar`: Santri keluar sebelum lulus

### Status SPP Santri
```sql
ENUM('Lunas', 'Belum Lunas')
```
Status pembayaran SPP santri

### Role Pengguna
```sql
ENUM('admin', 'petugas', 'wali')
```
Peran pengguna dalam sistem:
- `admin`: Administrator sistem
- `petugas`: Petugas administrasi
- `wali`: Wali santri

## Struktur Tabel

### Tabel kategori_santri
Menyimpan data kategori/tingkatan santri.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| nama | VARCHAR(255) | Nama kategori |
| keterangan | TEXT | Keterangan tambahan |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir diupdate |

### Tabel kenaikan_kelas_history
Mencatat riwayat kenaikan kelas santri.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| santri_id | BIGINT(20) | ID santri (FK) |
| kelas_sebelum | VARCHAR(255) | Kelas sebelum kenaikan |
| kelas_sesudah | VARCHAR(255) | Kelas setelah kenaikan |
| status | ENUM | Status kenaikan |
| created_by | BIGINT(20) | ID user pembuat |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir diupdate |

### Tabel metode_pembayaran
Menyimpan data metode pembayaran yang tersedia.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| nama | VARCHAR(255) | Nama metode pembayaran |
| kode | VARCHAR(255) | Kode unik metode |
| status | ENUM | Status keaktifan |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir diupdate |

### Tabel pembayaran_spp
Mencatat transaksi pembayaran SPP.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| santri_id | BIGINT(20) | ID santri (FK) |
| metode_pembayaran_id | BIGINT(20) | ID metode pembayaran (FK) |
| bulan | VARCHAR(255) | Bulan pembayaran |
| tahun | YEAR(4) | Tahun pembayaran |
| nominal | DECIMAL(10,0) | Nominal pembayaran |
| tanggal_bayar | TIMESTAMP | Tanggal pembayaran |
| keterangan | VARCHAR(255) | Keterangan tambahan |
| snap_token | VARCHAR(255) | Token Midtrans |
| order_id | CHAR(36) | ID order Midtrans |
| payment_type | VARCHAR(255) | Jenis pembayaran |
| transaction_id | VARCHAR(255) | ID transaksi Midtrans |
| payment_details | JSON | Detail pembayaran |
| status | VARCHAR(255) | Status pembayaran |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir diupdate |

### Tabel riwayat_tarif_spp
Mencatat riwayat perubahan tarif SPP.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| kategori_id | BIGINT(20) | ID kategori santri (FK) |
| nominal | DECIMAL(10,2) | Nominal tarif |
| berlaku_mulai | DATE | Tanggal mulai berlaku |
| berlaku_sampai | DATE | Tanggal akhir berlaku |
| keterangan | TEXT | Keterangan tambahan |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir diupdate |

### Tabel santri
Menyimpan data santri.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| nisn | VARCHAR(255) | Nomor Induk Siswa Nasional |
| nama | VARCHAR(255) | Nama santri |
| jenis_kelamin | ENUM | Jenis kelamin |
| tanggal_lahir | DATE | Tanggal lahir |
| alamat | TEXT | Alamat |
| wali_id | BIGINT(20) | ID wali (FK) |
| nama_wali | VARCHAR(255) | Nama wali |
| tanggal_masuk | DATE | Tanggal masuk |
| jenjang | ENUM | Jenjang pendidikan |
| kelas | VARCHAR(255) | Kelas |
| status | ENUM | Status santri |
| tahun_tamat | YEAR(4) | Tahun tamat |
| kategori_id | BIGINT(20) | ID kategori (FK) |
| status_spp | ENUM | Status pembayaran SPP |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir diupdate |

### Tabel users
Menyimpan data pengguna sistem.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | BIGINT(20) | Primary key |
| name | VARCHAR(255) | Nama pengguna |
| email | VARCHAR(255) | Email |
| email_verified_at | TIMESTAMP | Waktu verifikasi email |
| password | VARCHAR(255) | Password |
| remember_token | VARCHAR(100) | Token "remember me" |
| role | ENUM | Role pengguna |
| no_hp | VARCHAR(255) | Nomor HP |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu terakhir diupdate |

## Relasi Antar Tabel

1. **Santri - Kategori**
   - Tabel `santri` memiliki foreign key `kategori_id` yang merujuk ke `kategori_santri.id`
   - Saat kategori dihapus, data santri tetap ada

2. **Santri - Users (Wali)**
   - Tabel `santri` memiliki foreign key `wali_id` yang merujuk ke `users.id`
   - Saat user dihapus, field `wali_id` di tabel santri akan di-set NULL

3. **Pembayaran SPP - Santri**
   - Tabel `pembayaran_spp` memiliki foreign key `santri_id` yang merujuk ke `santri.id`
   - Saat santri dihapus, semua data pembayaran terkait akan ikut terhapus (cascade)

4. **Pembayaran SPP - Metode Pembayaran**
   - Tabel `pembayaran_spp` memiliki foreign key `metode_pembayaran_id` yang merujuk ke `metode_pembayaran.id`
   - Saat metode pembayaran dihapus, field tersebut di tabel pembayaran akan di-set NULL

5. **Riwayat Tarif - Kategori**
   - Tabel `riwayat_tarif_spp` memiliki foreign key `kategori_id` yang merujuk ke `kategori_santri.id`
   - Saat kategori dihapus, data riwayat tarif tetap ada

6. **Kenaikan Kelas - Santri**
   - Tabel `kenaikan_kelas_history` memiliki foreign key `santri_id` yang merujuk ke `santri.id`
   - Saat santri dihapus, semua data kenaikan kelas terkait akan ikut terhapus (cascade)

7. **Kenaikan Kelas - Users**
   - Tabel `kenaikan_kelas_history` memiliki foreign key `created_by` yang merujuk ke `users.id`
   - Saat user dihapus, data kenaikan kelas tetap ada