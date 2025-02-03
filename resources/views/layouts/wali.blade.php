<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, private">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <title>@yield('title') - Wali Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Midtrans -->
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 56px;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,.05);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .card {
                margin-bottom: 1rem;
            }

            .table-responsive {
                margin: 0 -0.75rem;
            }

            .table td, .table th {
                white-space: nowrap;
            }

            h2 {
                font-size: 1.5rem;
            }

            .card-title {
                font-size: 1.1rem;
            }
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0,0,0,.1);
            z-index: 1000;
        }

        .bottom-nav .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem 0;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.8rem;
        }

        .bottom-nav .nav-link.active {
            color: #0d6efd;
        }

        .bottom-nav i {
            font-size: 1.2rem;
            margin-bottom: 0.2rem;
        }

        /* Add padding to prevent content from being hidden behind bottom nav */
        .content-wrapper {
            padding-bottom: 70px;
        }

        /* Table Styles */
        .table-responsive {
            margin: 0;
            border-radius: 0.5rem;
            background: white;
        }

        .table {
            margin-bottom: 0;
        }

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

        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,.05);
            transition: transform 0.2s;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 1.25rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Table Header & Footer */
        .table thead th {
            border-top: 0;
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 1rem 0.75rem;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }

        /* Responsive padding adjustments */
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

        /* Bottom Navigation adjustments */
        .content-wrapper {
            padding-bottom: calc(70px + 1rem);
        }

        /* Alert spacing */
        .alert {
            margin-bottom: 1.5rem;
        }
    </style>
    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="wali-layout">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30">
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
        @yield('content')
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="container">
            <div class="row g-0">
                <div class="col-4">
                    <a href="{{ route('wali.dashboard') }}"
                       class="nav-link {{ Route::is('wali.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('wali.tagihan') }}"
                       class="nav-link {{ Route::is('wali.tagihan') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Tagihan</span>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('wali.hubungkan') }}"
                       class="nav-link {{ Route::is('wali.hubungkan') ? 'active' : '' }}">
                        <i class="fas fa-link"></i>
                        <span>Hubungkan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    <script>
    document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
        new bootstrap.Dropdown(element);
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
    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
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
                            <input type="text"
                                   name="no_hp"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   value="{{ Auth::user()->no_hp }}"
                                   required>
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

    <!-- Tambahkan script -->
    <script>
    // Pindahkan fungsi submitProfile ke sini
    function submitProfile() {
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
                document.getElementById('profileForm').submit();
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
            // Tutup modal setelah berhasil
            bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
        });
    @endif

    @if($errors->any())
        // Tampilkan modal jika ada error validasi
        new bootstrap.Modal(document.getElementById('profileModal')).show();
    @endif
    </script>

    </script>

    <!-- Tambahkan error handling -->
    <script>
    // Cek apakah Midtrans SDK berhasil dimuat
    if (typeof snap === 'undefined') {
        console.error('Midtrans SDK tidak berhasil dimuat');
    }
    </script>
</body>
</html>
