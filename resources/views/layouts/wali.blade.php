<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, private">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <title>@yield('title') - Wali Panel</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/wali-responsive.css') }}" rel="stylesheet">
    <!-- Midtrans -->
        <script type="text/javascript"
                src="https://app.sandbox.midtrans.com/snap/snap.js"
                data-client-key="{{ config('midtrans.client_key') }}">
        </script>

    <style>
        /* Responsive adjustments - handled by Bootstrap grid and utilities */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .table-responsive {
                margin: 0;
                padding: 0.5rem;
            }

            .table td,
            .table th {
                padding: 0.75rem;
                white-space: nowrap;
            }

            .card-body {
                padding: 1.25rem;
            }

            .card .table-responsive {
                margin: 0 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .table td,
            .table th {
                min-width: 120px;
            }

            .card-body {
                padding: 1rem;
            }
        }

        @media (min-width: 768px) {
            .container-fluid {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .card .table-responsive {
                margin: 0;
                padding: 0 1rem;
            }

            .table td,
            .table th {
                padding: 1rem;
            }
        }

        /* Dropdown animation */
        .dropdown-menu {
            display: block;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease-in-out;
        }
        
        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
    </style>
    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="wali-layout bg-light">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="d-inline-block align-top">
            </a>
            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link text-dark text-decoration-none dropdown-toggle"
                            type="button"
                            id="userDropdown"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profileModal">
                                <i class="fas fa-user-edit me-2"></i>Edit Profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content-wrapper">
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav fixed-bottom">
        <div class="container-fluid">
            <div class="row g-0 justify-content-around">
                <div class="col-4">
                    <div class="text-center">
                        <a href="{{ route('wali.dashboard') }}"
                           class="nav-link {{ Request::routeIs('wali.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Home</span>
                        </a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <a href="{{ route('wali.tagihan') }}"
                           class="nav-link {{ Request::routeIs('wali.tagihan') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Tagihan</span>
                        </a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <a href="{{ route('wali.hubungkan') }}"
                           class="nav-link {{ Request::routeIs('wali.hubungkan') ? 'active' : '' }}">
                            <i class="fas fa-link"></i>
                            <span>Hubungkan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    @stack('scripts')
    <script>
    // Initialize dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
            new bootstrap.Dropdown(element);
        });

        // Add smooth animation to all dropdowns
        document.querySelectorAll('.dropdown').forEach(function(dropdown) {
            dropdown.addEventListener('show.bs.dropdown', function() {
                const menu = this.querySelector('.dropdown-menu');
                menu.style.display = 'block';
                setTimeout(() => {
                    menu.classList.add('show');
                }, 0);
            });

            dropdown.addEventListener('hide.bs.dropdown', function(e) {
                e.preventDefault();
                const menu = this.querySelector('.dropdown-menu');
                menu.classList.remove('show');
                setTimeout(() => {
                    menu.style.display = 'none';
                    bootstrap.Dropdown.getInstance(this.querySelector('.dropdown-toggle')).hide();
                }, 200);
            });
        });
    });

    document.getElementById('logout-form').addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Konfirmasi Logout',
            text: "Anda yakin ingin keluar dari sistem?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
    </script>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalTitle">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalTitle">
                        <i class="fas fa-user-edit me-2"></i>Edit Profil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="profileForm" action="{{ route('wali.profil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ Auth::user()->name }}"
                                   pattern="[A-Za-z\s]+"
                                   title="Gunakan huruf dan spasi saja"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ Auth::user()->email }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="tel"
                                   name="no_hp"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   value="{{ Auth::user()->no_hp }}"
                                   pattern="^[0-9\+\-\(\)\s]{10,15}$"
                                   title="Masukkan nomor HP yang valid (10-15 digit)"
                                   oninput="this.value = this.value.replace(/[^0-9\+\-\(\)\s]/g, '')"
                                   required>
                            <small class="text-muted">Contoh: 081234567890 atau +62812-3456-7890</small>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitProfile()">
                        <i class="fas fa-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts untuk modal profil -->
    <script>
    function submitProfile() {
        const form = document.getElementById('profileForm');
        const noHp = form.querySelector('input[name="no_hp"]');
        const email = form.querySelector('input[name="email"]');
        
        // Validasi nomor HP
        const noHpValue = noHp.value.replace(/[\s\-\(\)\+]/g, '');
        if (noHpValue.length < 10 || noHpValue.length > 15) {
            Swal.fire('Error', 'Nomor HP harus antara 10-15 digit', 'error');
            return;
        }
        
        // Validasi email
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/;
        if (!emailRegex.test(email.value)) {
            Swal.fire('Error', 'Format email tidak valid', 'error');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Perubahan',
            text: 'Apakah Anda yakin ingin menyimpan perubahan profil?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Handle response after form submission
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil',
            text: '{{ session('success') }}',
            icon: 'success'
        }).then(() => {
            if (profileModalInstance) {
                profileModalInstance.hide();
            }
            if (window.location.pathname === '/wali/profil') {
                window.location.href = '{{ route("wali.dashboard") }}';
            }
        });
    @endif

    // Variable untuk modal profil
    let profileModalElement = document.getElementById('profileModal');
    let profileModalInstance = null;
    
    // Inisialisasi instance modal dan event handlers
    if (profileModalElement) {
        profileModalInstance = new bootstrap.Modal(profileModalElement);
        
        // Hapus aria-hidden saat modal terbuka
        profileModalElement.addEventListener('shown.bs.modal', function () {
            profileModalElement.removeAttribute('aria-hidden');
        });

        // Handle focus pada button di dalam modal
        profileModalElement.querySelectorAll('button').forEach(button => {
            button.addEventListener('focus', function() {
                profileModalElement.removeAttribute('aria-hidden');
            });
        });
    }

    // Tampilkan modal jika ada error validasi
    @if($errors->any())
        if (profileModalInstance) {
            profileModalInstance.show();
        }
    @endif
    </script>

    
</body>
</html>
