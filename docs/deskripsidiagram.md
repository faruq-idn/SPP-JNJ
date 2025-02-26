# Deskripsi Diagram Sistem Pembayaran SPP

## Activity Diagram

### Proses Autentikasi
Diagram aktivitas autentikasi menggambarkan proses masuk pengguna ke dalam sistem. Alur dimulai saat pengguna mengakses halaman login dan memasukkan kredensial. Sistem kemudian memvalidasi input dan mengecek role pengguna untuk mengarahkan ke dashboard yang sesuai. Jika terjadi kesalahan, sistem akan menampilkan pesan error yang relevan.

### Pengelolaan Kategori Santri
Dalam pengelolaan kategori santri, diagram aktivitas menjelaskan bagaimana admin dapat melihat, menambah, mengubah, dan menghapus kategori. Setiap kategori memiliki informasi seperti nama, tarif SPP, dan jumlah santri yang terdaftar. Setiap perubahan akan divalidasi dan disimpan oleh sistem.

### Manajemen Kenaikan Kelas
Proses kenaikan kelas dijelaskan dalam diagram aktivitas khusus yang menunjukkan bagaimana admin dapat melihat daftar santri per kelas dan melakukan kenaikan kelas baik secara masal maupun individual. Sistem mencatat setiap perpindahan kelas dalam riwayat untuk keperluan tracking dan pelaporan.

### Sistem Pelaporan
Diagram aktivitas pelaporan menunjukkan proses pembuatan laporan oleh admin. Dashboard laporan menampilkan ringkasan informasi penting, dan admin dapat memilih jenis laporan serta mengatur filter berdasarkan berbagai parameter. Laporan dapat diunduh dalam format PDF atau Excel sesuai kebutuhan.

### Proses Pembayaran SPP
Proses pembayaran SPP memiliki diagram aktivitas yang mencakup dua jalur pembayaran: online melalui payment gateway dan offline melalui petugas. Untuk pembayaran online, sistem terintegrasi dengan layanan pembayaran dan menangani callback secara otomatis. Pembayaran offline melibatkan verifikasi oleh petugas dan pencetakan kuitansi.

### Pendaftaran Santri
Diagram aktivitas penambahan santri memperlihatkan dua metode: input manual dan import Excel. Untuk input manual, admin mengisi form lengkap data santri. Sedangkan untuk import Excel, sistem memvalidasi format dan data sebelum menyimpannya. Dalam kedua kasus, sistem akan membuat akun wali jika diperlukan.

### Manajemen Pengguna
Pengelolaan pengguna sistem dijelaskan dalam diagram aktivitas yang menunjukkan bagaimana admin dapat melihat daftar pengguna, menambah pengguna baru, mengedit data, dan mereset password. Setiap perubahan dicatat dan divalidasi oleh sistem untuk menjaga keamanan.

### Aktivitas Wali Santri
Diagram aktivitas wali santri menggambarkan interaksi wali dengan sistem. Setelah login, wali dapat melihat data santri, tagihan SPP, dan melakukan pembayaran. Untuk wali dengan multiple santri, tersedia fitur pemilihan santri yang akan dilihat informasinya.

## Class Diagram

### Struktur Berorientasi Objek
Diagram kelas memberikan gambaran struktur berorientasi objek dari sistem. Diagram ini menunjukkan kelas-kelas utama seperti User, Santri, KategoriSantri, PembayaranSPP, dan hubungan antar kelas tersebut. Service layer seperti PaymentService menangani logika bisnis kompleks seperti integrasi pembayaran, sementara Controller bertanggung jawab atas alur aplikasi dan respons terhadap request pengguna.

## Entity Relationship Diagram

### Struktur Database
Diagram Entity Relationship menggambarkan struktur database sistem dengan detail relasi antar tabel. Tabel utama seperti users, santri, dan pembayaran_spp memiliki relasi yang jelas dan terstruktur. Tabel kategori_santri terhubung dengan riwayat_tarif_spp untuk mencatat perubahan tarif, sementara tabel kenaikan_kelas_history menyimpan riwayat perpindahan kelas santri. Relasi ini dirancang untuk mendukung integritas data dan kemudahan query.

## Sequence Diagram

### Alur Pembayaran Online
Diagram sequence untuk proses pembayaran mendetailkan interaksi antara komponen sistem dalam proses pembayaran online. Diagram ini menunjukkan alur dari request pembayaran oleh wali santri, pemrosesan oleh sistem, interaksi dengan payment gateway, hingga penanganan callback dan pembaruan status pembayaran. Diagram ini sangat penting untuk memahami flow pembayaran dan penanganan berbagai skenario (sukses/gagal).

## Use Case Diagram

### Fungsionalitas Sistem
Diagram use case memberikan gambaran fungsionalitas sistem dari perspektif pengguna. Sistem memiliki tiga aktor utama: Admin sebagai pengelola utama, Petugas sebagai operator harian, dan Wali Santri sebagai pengguna eksternal. Setiap aktor memiliki hak akses ke fitur-fitur tertentu yang relevan dengan peran mereka. Hubungan include dan extend menunjukkan ketergantungan dan perluasan fungsi dalam sistem.