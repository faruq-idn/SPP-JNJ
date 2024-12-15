@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <p class="text-muted text-center">Halaman ini masih dalam pengembangan</p>
        </div>
    </div>
</div>
@endsection
