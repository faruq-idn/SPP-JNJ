<nav class="nav flex-column">
    <div class="p-3">
        <div class="d-flex align-items-center mb-3">
            <div class="flex-shrink-0">
                <i class="fas fa-user-circle fa-2x text-light"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-0 text-light">{{ Auth::user()->name }}</h6>
                <small class="text-light opacity-75">Wali Santri</small>
            </div>
        </div>
    </div>

    <div class="nav-items">
        <a href="{{ route('wali.dashboard') }}"
           class="nav-link d-flex align-items-center {{ request()->routeIs('wali.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt fa-fw me-3"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('wali.tagihan') }}"
           class="nav-link d-flex align-items-center {{ request()->routeIs('wali.tagihan') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar fa-fw me-3"></i>
            <span>Tagihan SPP</span>
        </a>

        <a href="{{ route('wali.pembayaran') }}"
           class="nav-link d-flex align-items-center {{ Request::routeIs('wali.pembayaran') ? 'active' : '' }}">
            <i class="fas fa-history fa-fw me-3"></i>
            <span>Riwayat Pembayaran</span>
        </a>
    </div>
</nav>
