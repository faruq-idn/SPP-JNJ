<nav class="nav flex-column">
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="{{ route('admin.santri.index') }}" class="nav-link {{ request()->routeIs('admin.santri.*') ? 'active' : '' }}">
        <i class="fas fa-user-graduate"></i> Data Santri
    </a>
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('admin.pembayaran.*') ? 'active' : '' }}"
            href="{{ route('admin.pembayaran.index') }}">
            <i class="fas fa-money-bill"></i>
            <span>Pembayaran SPP</span>
        </a>
    </li>
    <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i> Laporan
    </a>
    <a href="{{ route('admin.kategori.index') }}" class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
        <i class="fas fa-tags"></i> Kategori Santri
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i> Pengguna
    </a>
</nav>
