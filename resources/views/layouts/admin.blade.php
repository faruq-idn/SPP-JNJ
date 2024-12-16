<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, private">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <title>@yield('title') - Admin Panel</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex, nofollow, noarchive">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        /* Fixed sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px; /* Sesuaikan dengan lebar sidebar */
            z-index: 100;
            overflow-y: auto;
            background-color: #343a40;
            color: white;
        }

        /* Fixed navbar */
        .top-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 250px; /* Sesuaikan dengan lebar sidebar */
            z-index: 99;
            background: white;
        }

        /* Main content padding */
        .main-content {
            margin-left: 250px; /* Sesuaikan dengan lebar sidebar */
            padding-top: 70px; /* Sesuaikan dengan tinggi navbar */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
                transition: 0.3s;
            }
            .sidebar.show {
                left: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .top-navbar {
                left: 0;
            }
        }

        /* Existing styles */
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1rem;
            border-radius: 0.25rem;
            margin: 0.2rem 0;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 1.5rem;
            text-align: center;
        }

        .widget-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .widget-card:hover {
            transform: translateY(-5px);
        }

        .notification-item {
            border-left: 4px solid #28a745;
            background-color: white;
            margin-bottom: 0.5rem;
            padding: 1rem;
            border-radius: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="p-3">
                <h5 class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="height: 40px;">
                    <span class="ms-2">{{ ucfirst(Auth::user()->role) }} Panel</span>
                </h5>
                @if(Auth::user()->role === 'admin')
                    @include('layouts.partials.sidebar-admin')
                @elseif(Auth::user()->role === 'petugas')
                    @include('layouts.partials.sidebar-petugas')
                @else
                    @include('layouts.partials.sidebar-wali')
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm top-navbar">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" id="sidebarToggle">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog"></i> Profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                        <button type="button" class="dropdown-item text-danger" onclick="confirmLogout()">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Default DataTables configuration -->
    <script>
    $(document).ready(function() {
        // Konfigurasi default untuk DataTables
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            responsive: true
        });

        // Konfigurasi default untuk Select2
        $.fn.select2.defaults.set("theme", "bootstrap-5");
        $.fn.select2.defaults.set("language", "id");

        // Konfigurasi default untuk SweetAlert2
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Tampilkan toast jika ada pesan sukses
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif
    });
    </script>

    @stack('scripts')
    <script>
    // Toggle sidebar on mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });

    // Hide sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.getElementById('sidebarToggle');
            if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Prevent back navigation
    (function() {
        // Prevent back button
        window.history.pushState(null, null, window.location.href);
        window.addEventListener('popstate', function() {
            Swal.fire({
                title: 'Peringatan Navigasi',
                text: "Anda akan keluar dari sistem jika kembali ke halaman sebelumnya. Lanjutkan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form logout
                    document.getElementById('logout-form').submit();
                } else {
                    window.history.pushState(null, null, window.location.href);
                }
            });
        });

        // Reload if accessed from back-forward cache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted ||
                window.performance &&
                window.performance.navigation.type === 2) {
                window.location.reload();
            }
        });
    })();

    // Logout confirmation
    function confirmLogout() {
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
                document.getElementById('logout-form').submit();
            }
        });
    }
    </script>
</body>
</html>
