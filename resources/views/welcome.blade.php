<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistem Pembayaran SPP - Pesantren Jabal Nur Jadid</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <style>
            .navbar-brand img {
                height: 50px;
            }

            .hero {
                background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                            url('{{ asset("images/pesantren-bg.jpg") }}') center/cover no-repeat;
                min-height: 500px;
                color: white;
            }

            .feature-icon {
                font-size: 2.5rem;
                color: #28a745;
                margin-bottom: 1rem;
            }

            .footer {
                background-color: #333;
                color: white;
            }

            .social-links a {
                color: white;
                margin: 0 10px;
                font-size: 1.5rem;
                transition: color 0.3s;
            }

            .social-links a:hover {
                color: #28a745;
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="#">SPP JNJ</a>

                <div class="d-flex">
                    @auth
                        <span class="navbar-text me-3">
                            Selamat datang, {{ Auth::user()->name }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero d-flex align-items-center">
            <div class="container text-center">
                <h1 class="display-4 mb-4">Selamat Datang di Sistem Pembayaran SPP</h1>
                <p class="lead mb-4">
                    Sistem informasi pembayaran SPP yang memudahkan wali santri melakukan pembayaran
                    dan memantau riwayat pembayaran secara online
                </p>
                <a href="{{ route('login') }}" class="btn btn-success btn-lg px-4">
                    Mulai Sekarang
                </a>
            </div>
        </section>

        <!-- Content Section -->
        <section class="py-5" id="tentang">
            <div class="container">
                <h2 class="text-center mb-5">Mengapa Menggunakan Sistem Kami?</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="feature-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h4>Pembayaran Online</h4>
                            <p>Bayar SPP kapan saja dan dimana saja melalui berbagai metode pembayaran online</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="feature-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <h4>Riwayat Lengkap</h4>
                            <p>Pantau riwayat pembayaran dan status tagihan dengan mudah</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="feature-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h4>Notifikasi</h4>
                            <p>Dapatkan notifikasi untuk setiap pembayaran dan tagihan yang akan jatuh tempo</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Informasi Section -->
        <section class="bg-light py-5" id="informasi">
            <div class="container">
                <h2 class="text-center mb-5">Informasi Terkini</h2>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Pembayaran SPP Semester Baru</h5>
                                <p class="card-text">Pembayaran SPP untuk semester baru sudah dapat dilakukan melalui sistem mulai tanggal 1 Juli 2024.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Panduan Penggunaan Sistem</h5>
                                <p class="card-text">Silakan unduh panduan penggunaan sistem pembayaran SPP online untuk informasi lebih detail.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Kontak Section -->
        <section class="py-5" id="kontak">
            <div class="container">
                <h2 class="text-center mb-5">Hubungi Kami</h2>
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center">
                        <p class="mb-4">
                            Jika Anda memiliki pertanyaan atau mengalami kendala, silakan hubungi kami:
                        </p>
                        <div class="d-flex justify-content-center gap-4">
                            <div>
                                <i class="fas fa-phone mb-2 text-success"></i>
                                <p>+62 123 4567 8900</p>
                            </div>
                            <div>
                                <i class="fas fa-envelope mb-2 text-success"></i>
                                <p>info@jabalnurjadid.sch.id</p>
                            </div>
                            <div>
                                <i class="fas fa-map-marker-alt mb-2 text-success"></i>
                                <p>Jl. Pesantren No. 123, Kota</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <p>&copy; {{ date('Y') }} Pesantren Jabal Nur Jadid. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
