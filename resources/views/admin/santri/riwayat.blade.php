@extends('layouts.admin')

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('title', 'Riwayat Kenaikan Kelas')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Kenaikan Kelas</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable">
                    <thead>
                        <tr>
                            <th>Nama Santri</th>
                            <th>Kelas Sebelum</th>
                            <th>Kelas Sesudah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Diproses Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $r)
                        <tr>
                            <td>{{ $r->santri->nama }}</td>
                            <td>{{ $r->santri->jenjang }} {{ $r->kelas_sebelum }}</td>
                            <td>{{ $r->kelas_sesudah ? $r->santri->jenjang . ' ' . $r->kelas_sesudah : 'Lulus' }}</td>
                            <td>
                                <span class="badge bg-{{ $r->status === 'aktif' ? 'success' : ($r->status === 'lulus' ? 'info' : 'secondary') }}">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </td>
                            <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $r->creator->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada riwayat kenaikan kelas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $riwayat->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        // Cek apakah ada data di tabel
        if ($('#dataTable tbody tr').length > 1) {
            $('#dataTable').DataTable({
                pageLength: 25,
                order: [[4, 'desc']]
            });
        }
    });
</script>
@endpush
