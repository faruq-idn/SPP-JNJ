<nav class="nav flex-column">
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="{{ route('admin.santri.index') }}" class="nav-link {{ request()->routeIs('admin.santri.*') ? 'active' : '' }}">
        <i class="fas fa-user-graduate"></i> Data Santri
    </a>
    <a class="nav-link {{ Request::routeIs('admin.pembayaran.*') ? 'active' : '' }}"
        href="{{ route('admin.pembayaran.index') }}">
        <i class="fas fa-money-bill"></i> Pembayaran SPP
    </a>
    <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i> Laporan
    </a>
    <a href="{{ route('admin.kategori.index') }}" class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
        <i class="fas fa-tags"></i> Kategori Santri
    </a>
    <div class="nav-item">
        <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
           data-bs-toggle="collapse"
           href="#userSubmenu">
            <span>
                <i class="fas fa-users"></i> Manajemen Pengguna
            </span>
            <i class="fas fa-chevron-down"></i>
        </a>
        <div class="collapse {{ request()->routeIs('admin.users.*') ? 'show' : '' }}" id="userSubmenu">
            <div class="ps-3">
                <a href="{{ route('admin.users.index') }}?type=petugas"
                   class="nav-link {{ request()->query('type') == 'petugas' ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i> Petugas
                </a>
                <a href="{{ route('admin.users.index') }}?type=wali"
                   class="nav-link {{ request()->query('type') == 'wali' ? 'active' : '' }}">
                    <i class="fas fa-user-friends"></i> Wali Santri
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
.nav-link {
    color: rgba(255, 255, 255, 0.8);
    padding: 0.8rem 1rem;
    border-radius: 0.25rem;
    margin: 0.2rem 0;
}

.nav-link:hover,
.nav-link.active {
    color: white;
    background-color: rgba(255, 255, 255, 0.1);
}

#userSubmenu .nav-link {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.collapse:not(.show) .fas.fa-chevron-down {
    transform: rotate(-90deg);
}

.fas.fa-chevron-down {
    transition: transform 0.2s;
}
</style>
