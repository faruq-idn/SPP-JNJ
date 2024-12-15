<nav class="nav flex-column">
    <a href="{{ route('petugas.dashboard') }}" class="nav-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="{{ route('petugas.santri.index') }}" class="nav-link {{ request()->routeIs('petugas.santri.*') ? 'active' : '' }}">
        <i class="fas fa-user-graduate"></i> Data Santri
    </a>
    <a href="{{ route('petugas.pembayaran.index') }}" class="nav-link {{ request()->routeIs('petugas.pembayaran.*') ? 'active' : '' }}">
        <i class="fas fa-money-bill-wave"></i> Pembayaran SPP
    </a>
    <a href="{{ route('petugas.laporan.index') }}" class="nav-link {{ request()->routeIs('petugas.laporan.*') ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i> Laporan
    </a>
</nav>
