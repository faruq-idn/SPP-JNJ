# Diagram UML dan ERD Sistem Pembayaran SPP

Dokumentasi visual untuk Sistem Pembayaran SPP yang mencakup berbagai aspek perancangan sistem.

## Activity Diagrams

### 1. Proses Login (activity_login.puml)
Menggambarkan alur autentikasi pengguna:
- Validasi input
- Pengecekan kredensial
- Pengarahan berdasarkan role
- Penanganan error

### 2. Pengelolaan Santri (activity_tambah_santri.puml)
Menunjukkan proses pengelolaan data santri:
- Input manual santri
- Import massal via Excel
- Validasi data
- Pembuatan akun wali
- Penanganan error dan duplikasi

### 3. Pengelolaan Kategori (activity_kategori.puml)
Visualisasi manajemen kategori santri:
- Pembuatan kategori baru
- Edit kategori
- Pengelolaan tarif SPP
- Riwayat perubahan tarif
- Validasi penggunaan kategori

### 4. Proses Pembayaran (activity_pembayaran.puml)
Detail alur transaksi pembayaran:
- Pembayaran online (payment gateway)
- Pembayaran offline
- Validasi pembayaran
- Pembaruan status
- Generasi bukti pembayaran

### 5. Pengelolaan Laporan (activity_laporan.puml)
Alur pembuatan dan ekspor laporan:
- Laporan pembayaran
- Laporan tunggakan
- Filter dan kustomisasi
- Ekspor berbagai format (PDF/Excel)
- Preview dan printing

## Sequence Diagram

### Pembayaran Online (sequence_pembayaran.puml)
Detail interaksi sistem pembayaran:
- Komunikasi dengan payment gateway
- Callback handling
- Status updates
- Error handling

## Class dan ERD

### Class Diagram (class.puml)
Struktur OOP sistem:
- Models dan relationships
- Services dan Controllers
- Business logic
- Third-party integrations

### ERD (erd.puml)
Struktur database:
- Entitas dan atribut
- Relationships
- Kardinalitas
- Constraints

## Penggunaan dalam Skripsi

### Bab Analisis
- Use Case untuk definisi kebutuhan
- Activity Diagrams untuk alur proses
- Class Diagram untuk arsitektur

### Bab Perancangan
- ERD untuk struktur database
- Sequence Diagram untuk interaksi
- Activity Diagrams untuk detail implementasi

### Bab Implementasi
- Class Diagram untuk struktur kode
- Sequence Diagram untuk integrasi
- Activity Diagrams untuk validasi

## Best Practices

### Maintenance Diagram
1. Konsistensi:
   - Gunakan naming convention yang sama
   - Ikuti standar UML
   - Pertahankan level detail yang seragam

2. Updating:
   - Update setiap ada perubahan sistem
   - Validasi konsistensi antar diagram
   - Dokumentasikan perubahan

3. Version Control:
   - Commit bersama kode terkait
   - Tag versi penting
   - Backup regular

## Tools dan Rendering

### PlantUML
```bash
# Install PlantUML
java -jar plantuml.jar

# Render single file
plantuml diagram.puml

# Render directory
plantuml diagrams/*.puml
```

### IDE Integration
- VSCode: PlantUML extension
- IntelliJ: PlantUML integration
- Eclipse: PlantUML plugin

### Export Options
- PNG untuk dokumentasi
- SVG untuk web
- PDF untuk skripsi