@extends('layouts.wali')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Pembayaran Gagal</div>

                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            Maaf, pembayaran SPP Anda gagal diproses. Silakan coba lagi atau hubungi administrator.
                        </div>
                        <a href="{{ route('wali.tagihan') }}" class="btn btn-primary">Kembali ke Tagihan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection