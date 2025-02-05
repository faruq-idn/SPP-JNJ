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
                    <!-- Tombol Semua Santri -->
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
