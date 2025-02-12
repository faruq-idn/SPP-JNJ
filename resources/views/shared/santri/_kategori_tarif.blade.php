{{-- Kategori & Tarif SPP Card --}}
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Kategori & Tarif SPP</h6>
    </div>
    <div class="card-body">
        {{-- Informasi Kategori & Tarif --}}
        <div class="table-responsive mb-4">
            <table class="table">
                <tr>
                    <th width="30%">Kategori</th>
                    <td>{{ $santri->kategori->nama }}</td>
                </tr>
                <tr>
                    <th>Tarif SPP</th>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            @if($santri->kategori->tarifTerbaru)
                                <span>Rp {{ number_format($santri->kategori->tarifTerbaru->nominal, 0, ',', '.') }}</span>
                                <span class="badge bg-info">per bulan</span>
                            @else
                                <span class="text-muted">Belum diatur</span>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Status SPP per tahun --}}
        @foreach($pembayaranPerTahun as $tahun => $pembayaranBulanan)
            @php
                $totalBulan = count($pembayaranBulanan);
                $lunasBulan = $pembayaranBulanan->where('status', 'success')->count();
                $isLunas = $lunasBulan === $totalBulan;
                $presentase = ($lunasBulan / $totalBulan) * 100;
                $statusClass = $isLunas ? 'success' : ($presentase > 50 ? 'warning' : 'danger');
            @endphp
            <div class="p-3 rounded-3 bg-{{ $statusClass }} bg-opacity-10 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="mb-1">Status SPP {{ $tahun }}</h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-{{ $statusClass }}">
                                {{ $lunasBulan }}/{{ $totalBulan }} Bulan
                            </span>
                            @if($isLunas)
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-danger">Belum Lunas</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted mb-1">Tunggakan</div>
                        <div class="fw-bold text-danger">
                            Rp {{ number_format($totalTunggakanPerTahun[$tahun] ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="progress" style="height: 8px">
                    <div class="progress-bar bg-{{ $statusClass }}" 
                         role="progressbar" 
                         style="width: {{ $presentase }}%" 
                         aria-valuenow="{{ $presentase }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Total Tunggakan --}}
        <div class="d-flex justify-content-between align-items-center p-3 bg-danger bg-opacity-10 rounded-3">
            <h6 class="mb-0">Total Tunggakan Keseluruhan:</h6>
            <div class="text-danger fw-bold fs-5">
                Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>
