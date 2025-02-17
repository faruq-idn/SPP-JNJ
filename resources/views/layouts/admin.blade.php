{{-- Pastikan tidak ada whitespace sebelum DOCTYPE --}}<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, private">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <title>@yield('title') - Admin Panel</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.partials.styles')
    @include('layouts.partials.custom-styles')
</head>
<body class="admin-layout">
<div class="container-fluid p-0">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-3">
            <h5 class="text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="height: 40px;">
                <span class="ms-2">{{ ucfirst(Auth::user()->role) }} Panel</span>
            </h5>
            @if(Auth::user()->role === 'admin')
                @include('layouts.partials.sidebar-admin')
            @elseif(Auth::user()->role === 'petugas')
                @include('layouts.partials.sidebar-petugas')
            @else
                @include('layouts.partials.sidebar-wali')
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @include('shared.navbar._navbar')

        <!-- Page Content -->
        <div class="p-4">
            @stack('before-content')
            @yield('content')
            @stack('after-content')
        </div>
    </div>
</div>

    @yield('modals')

    @include('layouts.partials.scripts')
    @stack('scripts')
</body>
</html>
