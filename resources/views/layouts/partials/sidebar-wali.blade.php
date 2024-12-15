<nav class="nav flex-column">
    <a href="{{ route('wali.dashboard') }}" class="nav-link {{ request()->routeIs('wali.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="{{ route('wali.tagihan') }}" class="nav-link {{ request()->routeIs('wali.tagihan') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar"></i> Tagihan SPP
    </a>
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('wali.pembayaran') ? 'active' : '' }}"
            href="{{ route('wali.pembayaran') }}">
            <i class="fas fa-history"></i>
            <span>Riwayat Pembayaran</span>
        </a>
    </li>
</nav>
