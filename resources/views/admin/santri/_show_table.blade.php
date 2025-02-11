<!-- Tab tahun -->
<div class="card-header bg-white py-3 border-bottom">
    <ul class="nav nav-tabs card-header-tabs mb-0">
        @foreach($pembayaranPerTahun as $tahun => $pembayaranBulanan)
            @php
                $lunasBulan = $pembayaranBulanan->where('status', 'success')->count();
                $totalBulan = count($pembayaranBulanan);
                $statusClass = $lunasBulan === $totalBulan ? 'success' : ($lunasBulan > 0 ? 'warning' : 'danger');
            @endphp
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 {{ $loop->first ? 'active' : '' }}"
                   data-bs-toggle="tab"
                   href="#tahun-{{ $tahun }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $tahun }}</span>
                    <span class="badge bg-{{ $statusClass }} rounded-pill">
                        {{ $lunasBulan }}/{{ $totalBulan }}
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div class="tab-content">
    @foreach($pembayaranPerTahun as $tahun => $pembayaranBulanan)
    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
         id="tahun-{{ $tahun }}">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Bulan</th>
                        <th>Tanggal Bayar</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembayaranBulanan as $p)
                    <tr>
                        <td>{{ $p->nama_bulan }}</td>
                        <td>{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                        <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                        <td>
                            @if($p->status == 'success')
                                <span class="badge bg-info">{{ optional($p->metode_pembayaran)->nama ?? '-' }}</span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'success' ? 'success' : ($p->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ $p->status == 'success' ? 'Lunas' : ($p->status == 'pending' ? 'Pending' : 'Belum Lunas') }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if(isset($p->id))
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="showDetail('{{ $p->id }}', '{{ $p->nama_bulan }}', {{ $p->nominal }}, '{{ $p->tahun }}', '{{ $p->status }}', '{{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d/m/Y H:i') : '-' }}', '{{ optional($p->metode_pembayaran)->nama ?? '-' }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($p->status != 'success')
                                        <button class="btn btn-success" onclick="verifikasiPembayaran('{{ $p->id }}', '{{ $p->nama_bulan }}', {{ $p->nominal }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-danger" onclick="hapusPembayaran('{{ $p->id }}', '{{ $p->nama_bulan }}', '{{ $p->tahun }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @else
                                <button class="btn btn-sm btn-primary" onclick="tambahPembayaran('{{ $tahun }}', '{{ $p->bulan }}', {{ $p->nominal }})">
                                    <i class="fas fa-plus me-1"></i>Bayar
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>
