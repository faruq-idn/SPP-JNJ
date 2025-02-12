{{-- Data Santri Card --}}
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Santri</h6>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="30%">NISN</th>
                <td>{{ $santri->nisn }}</td>
            </tr>
            <tr>
                <th>Nama Lengkap</th>
                <td>{{ $santri->nama }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td>{{ $santri->tanggal_lahir->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $santri->alamat }}</td>
            </tr>
            <tr>
                <th>Tanggal Masuk</th>
                <td>{{ $santri->tanggal_masuk->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Jenjang & Kelas</th>
                <td>{{ $santri->jenjang }} - {{ $santri->kelas }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge bg-{{ $santri->status === 'aktif' ? 'success' : 'secondary' }}">
                        {{ ucfirst($santri->status) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>
