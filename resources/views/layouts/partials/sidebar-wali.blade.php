<nav class="nav flex-column">
    <a href="{{ route('wali.dashboard') }}" class="nav-link {{ request()->routeIs('wali.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="{{ route('wali.tagihan') }}" class="nav-link {{ request()->routeIs('wali.tagihan') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar"></i> Tagihan SPP
    </a>
    <a href="{{ route('wali.pembayaran') }}" class="nav-link {{ request()->routeIs('wali.pembayaran') ? 'active' : '' }}">
        <i class="fas fa-history"></i> Riwayat Pembayaran
    </a>
</nav>
