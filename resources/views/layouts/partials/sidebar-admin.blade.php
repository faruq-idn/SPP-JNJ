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
            <a class="nav-link {{ Request::is('admin/santri*') ? 'active' : '' }} d-flex align-items-center justify-content-between"
               href="#santriSubmenu"
               data-bs-toggle="collapse"
               aria-expanded="{{ Request::is('admin/santri*') ? 'true' : 'false' }}">
                <span>
                    <i class="fas fa-users"></i> Data Santri
                </span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse {{ Request::is('admin/santri*') ? 'show' : '' }}" id="santriSubmenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/santri') ? 'active' : '' }}"
                           href="{{ route('admin.santri.index') }}">
                            <i class="fas fa-list"></i> Semua Santri
                        </a>
                    </li>
                    <!-- SMP -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/santri/kelas/smp*') ? 'active' : '' }}"
                           href="#smpSubmenu"
                           data-bs-toggle="collapse"
                           aria-expanded="{{ Request::is('admin/santri/kelas/smp*') ? 'true' : 'false' }}">
                            <i class="fas fa-school"></i> SMP
                        </a>
                        <div class="collapse {{ Request::is('admin/santri/kelas/smp*') ? 'show' : '' }}" id="smpSubmenu">
                            <ul class="nav flex-column ms-3">
                                @foreach(['7A', '7B', '8A', '8B', '9A', '9B'] as $kelas)
                                    <li class="nav-item">
                                        <a class="nav-link {{ Request::is('admin/santri/kelas/smp/'.$kelas) ? 'active' : '' }}"
                                           href="{{ route('admin.santri.kelas', ['jenjang' => 'smp', 'kelas' => $kelas]) }}">
                                            Kelas {{ $kelas }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <!-- SMA -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/santri/kelas/sma*') ? 'active' : '' }}"
                           href="#smaSubmenu"
                           data-bs-toggle="collapse"
                           aria-expanded="{{ Request::is('admin/santri/kelas/sma*') ? 'true' : 'false' }}">
                            <i class="fas fa-school"></i> SMA
                        </a>
                        <div class="collapse {{ Request::is('admin/santri/kelas/sma*') ? 'show' : '' }}" id="smaSubmenu">
                            <ul class="nav flex-column ms-3">
                                @foreach(['10A', '10B', '11A', '11B', '12A', '12B'] as $kelas)
                                    <li class="nav-item">
                                        <a class="nav-link {{ Request::is('admin/santri/kelas/sma/'.$kelas) ? 'active' : '' }}"
                                           href="{{ route('admin.santri.kelas', ['jenjang' => 'sma', 'kelas' => $kelas]) }}">
                                            Kelas {{ $kelas }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                </ul>
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
</style>
