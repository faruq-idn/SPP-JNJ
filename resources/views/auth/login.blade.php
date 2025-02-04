@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid mb-3" style="height: 80px;">
                <h4 class="text-dark mb-4">Selamat Datang di<br>Sistem Pembayaran SPP</h4>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}"
                                placeholder="nama@contoh.com" required autofocus>
                            <label for="email">Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                            <div class="position-absolute end-0 top-50 translate-middle-y pe-3" style="z-index: 2; right: 2.5rem;">
                                <i id="passwordSuccessCheck" class="fas fa-check text-success me-2 d-none"></i>
                                <i class="far fa-eye-slash toggle-password" style="cursor: pointer; right: -2.5rem;"></i>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="password-strength mb-3">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="4"></div>
                            </div>
                            <p class="password-strength-text mt-2 small text-muted">Kekuatan Password: Belum diuji</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none small">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-3" id="loginButton">
                            <i class="fas fa-sign-in-alt me-2"></i> <span id="buttonText">Masuk</span>
                            <span id="loadingSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted">
                    &copy; {{ date('Y') }} Pesantren Jabal Nur Jadid.<br>
                    All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
<script>
$(document).ready(function() {
    // Toggle password visibility
    $('.toggle-password').click(function() {
        const input = $('#password');
        const icon = $(this);

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Animation on load
    $('.card').hide().fadeIn(1000);

    // Input focus animation
    $('.form-control').focus(function() {
        $(this).parent().addClass('focused');
    }).blur(function() {
        if (!$(this).val()) {
            $(this).parent().removeClass('focused');
        }
    });

    // Password strength check
    $('#password').on('input', function() {
        const password = $(this).val();
        const result = zxcvbn(password);
        const progressBar = $('.progress-bar');
        const strengthText = $('.password-strength-text');
        const strengthPercentage = (result.score / 4) * 100;

        progressBar.attr('aria-valuenow', result.score);
        progressBar.width(strengthPercentage + '%');

        if (result.score === 0) {
            progressBar.removeClass('bg-success bg-warning bg-danger').addClass('bg-danger');
            strengthText.text('Kekuatan Password: Sangat Lemah');
        } else if (result.score === 1) {
            progressBar.removeClass('bg-success bg-warning bg-danger').addClass('bg-danger');
            strengthText.text('Kekuatan Password: Lemah');
        } else if (result.score === 2) {
            progressBar.removeClass('bg-success bg-warning bg-danger').addClass('bg-warning');
            strengthText.text('Kekuatan Password: Cukup');
        } else if (result.score === 3) {
            progressBar.removeClass('bg-success bg-warning bg-danger').addClass('bg-success');
            strengthText.text('Kekuatan Password: Kuat');
        } else if (result.score === 4) {
            progressBar.removeClass('bg-success bg-warning bg-danger').addClass('bg-success');
            strengthText.text('Kekuatan Password: Sangat Kuat');
        }
    });

    // Handle login button loading state
    $('form').submit(function() {
        const loginButton = $('#loginButton');
        const buttonText = $('#buttonText');
        const loadingSpinner = $('#loadingSpinner');

        loginButton.prop('disabled', true);
        buttonText.text('Memproses');
        loadingSpinner.removeClass('d-none');
    });
});
</script>
@endpush

@push('styles')
<style>
.form-floating > .form-control:focus,
.form-floating > .form-control:not(:placeholder-shown) {
    padding-top: 1.625rem;
    padding-bottom: 0.625rem;
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    opacity: .65;
    transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.toggle-password:hover {
    color: #0d6efd;
}

.btn-primary {
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card {
    border-radius: 15px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.form-floating {
    position: relative;
}

.focused label {
    color: #0d6efd;
}
</style>
@endpush
@endsection
