@extends('layouts.admin')

@section('title', 'Buat Tagihan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Tagihan</h1>
        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.pembayaran.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Santri</label>
                            <select class="form-select @error('santri_id') is-invalid @enderror" 
                                    name="santri_id" required>
                                <option value="">Pilih Santri</option>
                                @foreach($santri as $s)
                                    <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }} ({{ $s->nisn }})
                                    </option>
                                @endforeach
                            </select>
                            @error('santri_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Tahun</label>
                            <select class="form-select @error('tahun') is-invalid @enderror" 
                                    name="tahun" required>
                                @php
                                    $currentYear = date('Y');
                                    $yearRange = range($currentYear - 1, $currentYear + 1);
                                @endphp
                                @foreach($yearRange as $year)
                                    <option value="{{ $year }}" 
                                        {{ (old('tahun') ?? $currentYear) == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Bulan</label>
                            <select class="form-select @error('bulan') is-invalid @enderror" 
                                    name="bulan" required>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}" 
                                        {{ (old('bulan') ?? date('n')) == $month ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromDate(null, $month, 1)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Nominal tagihan akan diambil dari tarif terbaru kategori santri yang dipilih.
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Buat Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 if needed
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
        $('select[name="santri_id"]').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Santri',
            allowClear: true
        });
    }
});
</script>
@endpush
