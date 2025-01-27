@extends('layouts.wali')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Pembayaran Berhasil</div>

                    <div class="card-body">
                        <div class="alert alert-success" role="alert">
                            Pembayaran SPP Anda berhasil diproses. Terima kasih!
                        </div>
                        <a href="{{ route('wali.tagihan') }}" class="btn btn-primary">Kembali ke Tagihan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection