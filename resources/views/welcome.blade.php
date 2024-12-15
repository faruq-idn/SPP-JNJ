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
            .navbar {
                background-color: rgba(255, 255, 255, 0.95);
                box-shadow: 0 2px 4px rgba(0,0,0,.08);
                padding: 1rem 0;
            }

            .navbar-brand img {
                height: 40px;
                transition: transform 0.3s ease;
            }

            .navbar-brand:hover img {
                transform: scale(1.05);
            }

            .nav-link {
                color: #2c3e50;
                font-weight: 500;
                padding: 0.5rem 1rem;
                margin: 0 0.25rem;
                border-radius: 0.25rem;
                transition: all 0.3s ease;
            }

            .nav-link:hover {
                color: #16a085;
                background-color: rgba(22, 160, 133, 0.1);
            }

            .nav-link.active {
                color: #16a085;
                background-color: rgba(22, 160, 133, 0.1);
            }

            .btn-login {
                background-color: #16a085;
                color: white;
                padding: 0.5rem 1.5rem;
                border-radius: 2rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .btn-login:hover {
                background-color: #138a72;
                color: white;
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(22, 160, 133, 0.2);
            }

            .hero-section {
                background: linear-gradient(135deg, #16a085 0%, #138a72 100%);
                padding: 6rem 0;
                color: white;
            }

            .feature-icon {
                width: 64px;
                height: 64px;
                background: rgba(22, 160, 133, 0.1);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                color: #16a085;
                font-size: 1.5rem;
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

            .hero-image {
                position: relative;
                perspective: 1000px;
                transform-style: preserve-3d;
            }

            .hero-image img {
                transform: translateZ(0);
                transition: all 0.5s ease;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            }

            .hero-image:hover img {
                transform: translateZ(50px) rotateY(-5deg);
                box-shadow: 20px 20px 40px rgba(0,0,0,0.3);
            }

            .floating-elements {
                position: absolute;
                width: 100%;
                height: 100%;
                pointer-events: none;
            }

            .floating-element {
                position: absolute;
                background: rgba(255,255,255,0.1);
                border-radius: 50%;
                animation: float 6s infinite ease-in-out;
            }

            .element-1 {
                width: 60px;
                height: 60px;
                top: 10%;
                left: -30px;
                animation-delay: 0s;
            }

            .element-2 {
                width: 40px;
                height: 40px;
                top: 20%;
                right: -20px;
                animation-delay: 1s;
            }

            .element-3 {
                width: 50px;
                height: 50px;
                bottom: 15%;
                right: 10%;
                animation-delay: 2s;
            }

            @keyframes float {
                0%, 100% {
                    transform: translateY(0) rotate(0deg);
                }
                50% {
                    transform: translateY(-20px) rotate(10deg);
                }
            }

            .hero-text {
                opacity: 0;
                transform: translateY(30px);
                animation: fadeInUp 1s ease forwards;
            }

            .hero-text:nth-child(2) {
                animation-delay: 0.3s;
            }

            .hero-text:nth-child(3) {
                animation-delay: 0.6s;
            }

            @keyframes fadeInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 991px) {
                .hero-section {
                    padding: 8rem 0 4rem;
                    text-align: center;
                }

                .hero-image {
                    margin-top: 3rem;
                    max-width: 80%;
                    margin-left: auto;
                    margin-right: auto;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="me-2">
                    <span class="d-none d-sm-inline">Pesantren Jabal Nur Jadid</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link active" href="#beranda">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tentang">Tentang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#fitur">Fitur</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#kontak">Kontak</a>
                        </li>
                        <li class="nav-item ms-lg-3">
                            <a href="{{ route('login') }}" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section" id="beranda">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="hero-text">
                            <h1 class="display-4 fw-bold mb-4">Sistem Pembayaran SPP Online</h1>
                        </div>
                        <div class="hero-text">
                            <p class="lead mb-4">Bayar SPP dengan mudah, cepat, dan aman melalui berbagai metode pembayaran.</p>
                        </div>
                        <div class="hero-text">
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                                Mulai Sekarang
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-image">
                            <div class="floating-elements">
                                <div class="floating-element element-1"></div>
                                <div class="floating-element element-2"></div>
                                <div class="floating-element element-3"></div>
                            </div>
                            <img src="{{ asset('images/hero-image.png') }}" alt="Hero Image" class="img-fluid">
                        </div>
                    </div>
                </div>
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
        <script>
        // Smooth scroll untuk menu
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Active state untuk menu saat scroll
        window.addEventListener('scroll', function() {
            let current = '';
            const sections = document.querySelectorAll('section');

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 60) {
                    current = section.getAttribute('id');
                }
            });

            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').substring(1) === current) {
                    link.classList.add('active');
                }
            });
        });

        // Add 3D effect to hero image
        document.addEventListener('mousemove', function(e) {
            const heroImage = document.querySelector('.hero-image');
            if (!heroImage) return;

            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;

            heroImage.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });

        // Reset transform on mouse leave
        document.querySelector('.hero-image')?.addEventListener('mouseleave', function() {
            this.style.transform = 'rotateY(0) rotateX(0)';
        });
        </script>
    </body>
</html>
