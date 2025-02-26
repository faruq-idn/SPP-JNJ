<nav class="nav flex-column">
    <ul class="nav flex-column">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is('petugas/dashboard') ? 'active' : '' }}" href="{{ route('petugas.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Data Santri dengan Sub Menu -->
        <li class="nav-item">
            <div class="nav-link-wrapper">
                <a class="nav-link {{ Request::is('petugas/santri*') ? 'active' : '' }}"
                   href="{{ route('petugas.santri.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Data Santri</span>
                </a>
                <button type="button" 
                        class="btn-dropdown {{ Request::is('petugas/santri*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseKelas"
                        aria-expanded="{{ Request::is('petugas/santri*') ? 'true' : 'false' }}"
                        aria-label="Toggle navigation">
                    <i class="fas fa-angle-down"></i>
                </button>
            </div>
            <div id="collapseKelas" class="collapse {{ Request::is('petugas/santri*') ? 'show' : '' }}">
                <div class="submenu">
                    <div class="submenu-sections">
                        <div class="submenu-section">
                            <div class="submenu-header">SMP</div>
                            <div class="submenu-items">
                                @php
                                    $kelasSMP = ['7A', '7B', '8A', '8B', '9A', '9B'];
                                @endphp
                                @foreach($kelasSMP as $kelas)
                                    @php
                                        $count = \App\Models\Santri::where('jenjang', 'SMP')
                                            ->where('kelas', $kelas)
                                            ->where('status', 'aktif')
                                            ->count();
                                        $isActive = isset($currentKelas) &&
                                                  $currentKelas['jenjang'] == 'SMP' &&
                                                  $currentKelas['kelas'] == $kelas;
                                    @endphp
                                    <a class="submenu-item {{ $isActive ? 'active' : '' }}"
                                       href="{{ route('petugas.santri.kelas', ['jenjang' => 'smp', 'kelas' => $kelas]) }}"
                                       title="Kelas {{ $kelas }}">
                                        <span>Kelas {{ $kelas }}</span>
                                        <span class="badge">{{ $count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="submenu-section">
                            <div class="submenu-header">SMA</div>
                            <div class="submenu-items">
                                @php
                                    $kelasSMA = ['10A', '10B', '11A', '11B', '12A', '12B'];
                                @endphp
                                @foreach($kelasSMA as $kelas)
                                    @php
                                        $count = \App\Models\Santri::where('jenjang', 'SMA')
                                            ->where('kelas', $kelas)
                                            ->where('status', 'aktif')
                                            ->count();
                                        $isActive = isset($currentKelas) &&
                                                  $currentKelas['jenjang'] == 'SMA' &&
                                                  $currentKelas['kelas'] == $kelas;
                                    @endphp
                                    <a class="submenu-item {{ $isActive ? 'active' : '' }}"
                                       href="{{ route('petugas.santri.kelas', ['jenjang' => 'sma', 'kelas' => $kelas]) }}"
                                       title="Kelas {{ $kelas }}">
                                        <span>Kelas {{ $kelas }}</span>
                                        <span class="badge">{{ $count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        <!-- Pembayaran SPP -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('petugas.pembayaran.*') ? 'active' : '' }}"
               href="{{ route('petugas.pembayaran.index') }}">
                <i class="fas fa-money-bill"></i>
                <span>Pembayaran SPP</span>
            </a>
        </li>

        <!-- Laporan -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('petugas.laporan.*') ? 'active' : '' }}"
               href="{{ route('petugas.laporan.index') }}">
                <i class="fas fa-file-alt"></i>
                <span>Laporan</span>
            </a>
        </li>
    </ul>
</nav>
