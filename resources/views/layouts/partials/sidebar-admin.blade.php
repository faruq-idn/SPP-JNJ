<nav class="nav flex-column">
    <ul class="nav flex-column">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>

        <!-- Data Santri dengan Sub Menu -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/santri*') ? '' : 'collapsed' }}"
               href="#"
               data-bs-toggle="collapse"
               data-bs-target="#collapseKelas">
                <i class="fas fa-users fa-fw me-2"></i>
                <span>Data Santri</span>
                <i class="fas fa-angle-down ms-auto"></i>
            </a>
            <div id="collapseKelas" class="collapse {{ Request::is('admin/santri*') ? 'show' : '' }}">
                <div class="submenu">
                    <!-- Tombol Semua Santri dipindah ke atas -->
                    <div class="submenu-section">
                        <a href="{{ route('admin.santri.index') }}"
                           class="btn-all-santri">
                            <i class="fas fa-list me-1"></i>Semua Santri
                        </a>
                    </div>

                    <!-- SMP -->
                    <div class="submenu-section">
                        <div class="submenu-header">SMP</div>
                        @php
                            $kelasSMP = ['7A', '7B', '8A', '8B', '9A', '9B'];
                        @endphp
                        @foreach($kelasSMP as $kelas)
                            <a class="submenu-item {{ isset($currentKelas) && $currentKelas['jenjang'] == 'SMP' && $currentKelas['kelas'] == $kelas ? 'active' : '' }}"
                               href="{{ route('admin.santri.kelas', ['jenjang' => 'smp', 'kelas' => $kelas]) }}">
                                <span>Kelas {{ $kelas }}</span>
                                @php
                                    $count = \App\Models\Santri::where('jenjang', 'SMP')
                                        ->where('kelas', $kelas)
                                        ->where('status', 'aktif')
                                        ->count();
                                @endphp
                                <span class="badge">{{ $count }}</span>
                            </a>
                        @endforeach
                    </div>

                    <!-- SMA -->
                    <div class="submenu-section">
                        <div class="submenu-header">SMA</div>
                        @php
                            $kelasSMA = ['10A', '10B', '11A', '11B', '12A', '12B'];
                        @endphp
                        @foreach($kelasSMA as $kelas)
                            <a class="submenu-item {{ isset($currentKelas) && $currentKelas['jenjang'] == 'SMA' && $currentKelas['kelas'] == $kelas ? 'active' : '' }}"
                               href="{{ route('admin.santri.kelas', ['jenjang' => 'sma', 'kelas' => $kelas]) }}">
                                <span>Kelas {{ $kelas }}</span>
                                @php
                                    $count = \App\Models\Santri::where('jenjang', 'SMA')
                                        ->where('kelas', $kelas)
                                        ->where('status', 'aktif')
                                        ->count();
                                @endphp
                                <span class="badge">{{ $count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </li>

        <!-- Pembayaran SPP -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.pembayaran.*') ? 'active' : '' }}"
               href="{{ route('admin.pembayaran.index') }}">
                <i class="fas fa-money-bill"></i> Pembayaran SPP
            </a>
        </li>

        <!-- Laporan -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.laporan.*') ? 'active' : '' }}"
               href="{{ route('admin.laporan.index') }}">
                <i class="fas fa-file-alt"></i> Laporan
            </a>
        </li>

        <!-- Kategori Santri -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.kategori.*') ? 'active' : '' }}"
               href="{{ route('admin.kategori.index') }}">
                <i class="fas fa-tags"></i> Kategori Santri
            </a>
        </li>

        <!-- Manajemen Pengguna -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('admin.users.*') ? 'active' : '' }} d-flex align-items-center justify-content-between"
               href="#userSubmenu"
               data-bs-toggle="collapse"
               aria-expanded="{{ Request::routeIs('admin.users.*') ? 'true' : 'false' }}">
                <span>
                    <i class="fas fa-users-cog"></i> Manajemen Pengguna
                </span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse {{ Request::routeIs('admin.users.*') ? 'show' : '' }}" id="userSubmenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->query('type') == 'petugas' ? 'active' : '' }}"
                           href="{{ route('admin.users.index') }}?type=petugas">
                            <i class="fas fa-user-tie"></i> Petugas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->query('type') == 'wali' ? 'active' : '' }}"
                           href="{{ route('admin.users.index') }}?type=wali">
                            <i class="fas fa-user-friends"></i> Wali Santri
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
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

/* Submenu Styling */
.submenu {
    padding: 0.5rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 0.5rem;
    margin: 0.5rem;
}

.submenu-section {
    margin-bottom: 1rem;
}

.submenu-section:last-child {
    margin-bottom: 0;
}

.submenu-header {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.submenu-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0.75rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    border-radius: 0.25rem;
    transition: all 0.2s ease;
    margin-bottom: 0.25rem;
}

.submenu-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.submenu-item.active {
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

.submenu-item .badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 1rem;
}

.btn-all-santri {
    display: block;
    text-align: center;
    padding: 0.5rem;
    background: #0d6efd;
    color: white;
    text-decoration: none;
    border-radius: 0.25rem;
    transition: all 0.2s ease;
}

.btn-all-santri:hover {
    background: #0b5ed7;
    color: white;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .submenu {
        padding: 0.25rem;
    }

    .submenu-item {
        padding: 0.5rem;
    }

    .submenu-header {
        padding: 0.5rem 0.25rem;
    }
}
</style>
