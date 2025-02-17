{{-- Data Wali Card --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Wali Santri</h6>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="30%">Nama Wali</th>
                <td>
                    @if($santri->wali_id)
                        {{ $santri->wali->name }}
                    @elseif($santri->nama_wali)
                        {{ $santri->nama_wali }}
                        <span class="badge bg-warning text-dark">Belum terhubung</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Nomor HP Wali</th>
                <td>{{ $santri->wali->no_hp ?? '-' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $santri->wali->email ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status Wali</th>
                <td>
                    @if($santri->wali_id)
                        <span class="badge bg-success">Terhubung</span>
                    @elseif($santri->nama_wali)
                        <span class="badge bg-warning text-dark">Menunggu Klaim</span>
                    @else
                        <span class="badge bg-secondary">Belum Ada Wali</span>
                    @endif
                </td>
            </tr>
        </table>
        
    </div>
</div>
