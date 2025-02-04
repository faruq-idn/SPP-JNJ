<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, private">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <title>@yield('title') - Wali Panel</title>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const bottomNav = document.querySelector('.bottom-nav');

        menuToggle.addEventListener('click', function() {
            bottomNav.classList.toggle('active');
        });
    });
    </script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
       </style>
    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="wali-layout bg-light pt-3"> <!-- Menambahkan bg-light dan pt-3 untuk body -->
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm"> <!-- Menambahkan shadow-sm untuk navbar -->
        <div class="container-fluid">
            <a class="navbar-brand" href="#" class="navbar-brand"> <!-- Menambahkan kelas navbar-brand -->
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="d-inline-block align-top"> <!-- Menambahkan kelas d-inline-block dan align-top untuk logo -->
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
        <div class="container"> <!-- Menambahkan container untuk content-wrapper -->
           <main class="container pt-4"> <!-- Menambahkan container class dan padding top pada main content -->
               @yield('content')
           </main>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav bg-white border-top fixed-bottom"> <!-- Mengganti div dengan nav untuk bottom-nav dan menambahkan border-top dan fixed-bottom -->
        <div class="container">
            <div class="row g-0 justify-content-around"> <!-- Menambahkan justify-content-around untuk bottom-nav -->
                <div class="col-4 text-center"> <!-- Menambahkan text-center untuk memusatkan ikon dan teks -->
                    <a href="{{ route('wali.dashboard') }}"
                       class="nav-link {{ Route::is('wali.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home fa-lg"></i> <!-- Memperbesar ikon dengan fa-lg -->
                        <span class="d-block">Home</span> <!-- Menambahkan d-block untuk teks di bawah ikon -->
                    </a>
                </div>
                <div class="col-4 text-center"> <!-- Menambahkan text-center untuk memusatkan ikon dan teks -->
                    <a href="{{ route('wali.tagihan') }}"
                       class="nav-link {{ Route::is('wali.tagihan') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar fa-lg"></i> <!-- Memperbesar ikon dengan fa-lg -->
                        <span class="d-block">Tagihan</span> <!-- Menambahkan d-block untuk teks di bawah ikon -->
                    </a>
                </div>
                <div class="col-4 text-center"> <!-- Menambahkan text-center untuk memusatkan ikon dan teks -->
                    <a href="{{ route('wali.hubungkan') }}"
                       class="nav-link {{ Route::is('wali.hubungkan') ? 'active' : '' }}">
                        <i class="fas fa-link fa-lg"></i> <!-- Memperbesar ikon dengan fa-lg -->
                        <span class="d-block">Hubungkan</span> <!-- Menambahkan d-block untuk teks di bawah ikon -->
                    </a>
                </div>
            </div>
        </div>
    </nav>

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
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#profileModal">Test Modal Button</button>
    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: white;">
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
        const profileModal = document.getElementById('profileModal');
        const modalInstance = profileModal ? bootstrap.Modal.getInstance(profileModal) : null;

        Swal.fire({
            title: 'Berhasil',
            text: '{{ session('success') }}',
            icon: 'success'
        }).then(() => {
            // Tutup modal jika ada
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    @endif

    // Tampilkan modal jika ada error validasi
    @if($errors->any())
        const profileModal = document.getElementById('profileModal');
        if (profileModal) {
            const modal = new bootstrap.Modal(profileModal);
            modal.show();
        }
    @endif
    </script>
</body>
</html>
