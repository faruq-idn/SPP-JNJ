<nav class="nav flex-column">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}" 
               href="{{ route('petugas.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('petugas.pembayaran.*') ? 'active' : '' }}"
               href="{{ route('petugas.pembayaran.index') }}">
                <i class="fas fa-money-bill"></i>
                <span>Pembayaran SPP</span>
            </a>
        </li>
    </ul>
</nav>
