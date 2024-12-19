<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th>Periode Tagihan</th>
                <th>Santri</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Tanggal Generate</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($belumLunas as $p)
                <tr>
                    <td>
                        <div class="fw-bold">{{ $p->nama_bulan }} {{ $p->tahun }}</div>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $p->santri->nama }}</div>
                        <small class="text-muted">{{ $p->santri->nisn }}</small>
                    </td>
                    <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                    <td>
                        @if($p->status == 'pending')
                            <span class="badge bg-warning">Belum Bayar</span>
                        @elseif($p->status == 'failed')
                            <span class="badge bg-danger">Gagal</span>
                        @endif
                    </td>
                    <td>
                        <small>{{ $p->created_at->format('d/m/Y H:i') }}</small>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info" onclick="showDetail({{ $p->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-3">
                        <i class="fas fa-info-circle me-2"></i>Tidak ada tagihan yang belum lunas
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-3">
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Menampilkan {{ $belumLunas->firstItem() ?? 0 }}-{{ $belumLunas->lastItem() ?? 0 }}
            dari {{ $belumLunas->total() }} data
        </div>
        <div>
            {{ $belumLunas->links('pagination::simple-bootstrap-5') }}
        </div>
    </div>
</div>
