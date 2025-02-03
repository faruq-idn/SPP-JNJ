@extends('layouts.wali')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Pembayaran Gagal</div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @else
                            <div class="alert alert-danger">
                                Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi atau hubungi administrator.
                            </div>
                        @endif
                        <a href="{{ route('wali.tagihan') }}" class="btn btn-primary">Kembali ke Tagihan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection