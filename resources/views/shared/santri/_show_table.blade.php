@php
    use Carbon\Carbon;
@endphp

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
                <thead>
                    <tr class="bg-light">
                        <th class="fw-medium">Bulan</th>
                        <th class="fw-medium">Tanggal Bayar</th>
                        <th class="fw-medium">Nominal</th>
                        <th class="fw-medium">Metode</th>
                        <th class="fw-medium">Status</th>
                        <th class="text-center fw-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembayaranBulanan as $p)
                    @php
                        $isLunas = $p->status === 'success';
                        $buttonSize = 'btn-sm';
                        $month = (int)$p->bulan;
                        $monthName = $p->nama_bulan;
                    @endphp
                    <tr>
                        <td>{{ $monthName }}</td>
                        <td>{{ isset($p->tanggal_bayar) ? $p->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                        <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                        <td>
                            @if($isLunas)
                                <span class="badge bg-info">{{ optional($p->metode_pembayaran)->nama ?? '-' }}</span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = $p->status === 'success' ? 'success' : ($p->status === 'pending' ? 'warning' : 'danger');
                                $statusText = $p->status === 'success' ? 'Lunas' : ($p->status === 'pending' ? 'Pending' : 'Belum Lunas');
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <!-- Detail Button -->
                                <button type="button"
                                        class="btn {{ $buttonSize }} btn-info d-flex align-items-center px-2"
                                        onclick="showDetail(
                                            '{{ isset($p->exists) && $p->exists ? $p->id : '' }}',
                                            '{{ $monthName }}',
                                            {{ $p->nominal }},
                                            '{{ $tahun }}',
                                            '{{ $p->status }}',
                                            '{{ isset($p->tanggal_bayar) ? $p->tanggal_bayar->translatedFormat("d F Y H:i") : "-" }}',
                                            '{{ optional($p->metode_pembayaran)->nama ?? "-" }}'
                                        )"
                                        title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Verify/Create Button -->
                                @if(!$isLunas)
                                    <button type="button"
                                            class="btn {{ $buttonSize }} btn-success d-flex align-items-center px-2"
                                            @if(isset($p->exists) && $p->exists)
                                                onclick="verifikasiPembayaran('{{ $p->id }}', '{{ $monthName }}', {{ $p->nominal }})"
                                                title="Verifikasi Pembayaran"
                                            @else
                                                onclick="tambahPembayaran('{{ $tahun }}', '{{ $month }}', {{ $p->nominal }}, {{ $santri->id }})"
                                                title="Tambah Pembayaran"
                                            @endif>
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif

                                <!-- Delete Button -->
                                @if(auth()->user()->role === 'admin' && isset($p->exists) && $p->exists)
                                    <button type="button"
                                            class="btn {{ $buttonSize }} btn-danger d-flex align-items-center px-2"
                                            onclick="confirmDeletePembayaran(
                                                '{{ $p->id }}',
                                                '{{ $month }}',
                                                '{{ $tahun }}'
                                            )"
                                            title="Hapus Pembayaran">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>
