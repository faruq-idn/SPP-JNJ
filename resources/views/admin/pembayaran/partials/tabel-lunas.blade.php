<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th>Tanggal Bayar</th>
                <th>Santri</th>
                <th>Periode</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lunas as $p)
                <tr>
                    <td>{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        <div class="fw-bold">{{ $p->santri->nama }}</div>
                        <small class="text-muted">{{ $p->santri->nisn }}</small>
                    </td>
                    <td>{{ $p->nama_bulan }} {{ $p->tahun }}</td>
                    <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                    <td>
                        @if($p->metode_pembayaran)
                            <span class="badge bg-info">{{ $p->metode_pembayaran->nama }}</span>
                        @else
                            <span class="badge bg-secondary">Manual</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-info" onclick="showDetail({{ $p->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-3">
                        <i class="fas fa-info-circle me-2"></i>Belum ada pembayaran yang lunas
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
            Menampilkan {{ $lunas->firstItem() ?? 0 }}-{{ $lunas->lastItem() ?? 0 }}
            dari {{ $lunas->total() }} data
        </div>
        <div>
            {{ $lunas->links('pagination::simple-bootstrap-5') }}
        </div>
    </div>
</div>
