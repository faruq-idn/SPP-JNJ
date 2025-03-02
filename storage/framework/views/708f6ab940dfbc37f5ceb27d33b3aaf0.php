<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="img-fluid mb-3" style="height: 80px;">
                <h4 class="text-dark mb-4">Selamat Datang di<br>Sistem Pembayaran SPP</h4>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="<?php echo e(route('login.submit')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="email" name="email" value="<?php echo e(old('email')); ?>"
                                placeholder="nama@contoh.com" required autofocus>
                            <label for="email">Email</label>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                            <div class="position-absolute end-0 top-50 translate-middle-y d-flex pe-3" style="padding-right: 2.5rem !important;">
                                <i id="passwordSuccessCheck" class="fas fa-check text-success me-4 d-none"></i>
                                <i class="far fa-eye-slash toggle-password" style="cursor: pointer; margin-right: 0.5rem;"></i>
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>
                            <?php if(Route::has('password.request')): ?>
                                <a href="<?php echo e(route('password.request')); ?>" class="text-decoration-none small">
                                    Lupa Password?
                                </a>
                            <?php endif; ?>
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
                    &copy; <?php echo e(date('Y')); ?> Pesantren Jabal Nur Jadid.<br>
                    All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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

    // Handle login button loading state
    $('form').submit(function(e) {
        const loginButton = $('#loginButton');
        const buttonText = $('#buttonText');
        const loadingSpinner = $('#loadingSpinner');
        
        // Aktivasi loading state
        const setLoading = (isLoading) => {
            loginButton.prop('disabled', isLoading);
            buttonText.text(isLoading ? 'Memproses' : 'Masuk');
            isLoading ? loadingSpinner.removeClass('d-none') : loadingSpinner.addClass('d-none');
        };

        setLoading(true);

        // Reset validations
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        $('#passwordSuccessCheck').addClass('d-none');

        // Handle form submission
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            credentials: 'same-origin'
        })
        .then(response => response.json().then(data => {
            if (!response.ok) {
                throw new Error(data.message || 'Email atau password salah.');
            }
            return data;
        }))
        .then(data => {
            // Hanya tampilkan centang dan redirect jika login berhasil
            if (data.url) {
                $('#passwordSuccessCheck').removeClass('d-none');
                $('#email, #password').removeClass('is-invalid');
                setTimeout(() => {
                    window.location.href = data.url;
                }, 500);
            }
        })
        .catch(error => {
            // Reset semua state dan tampilkan error
            setLoading(false);
            $('#passwordSuccessCheck').addClass('d-none');
            $('#email, #password').addClass('is-invalid');
            $('#email').after(`<div class="invalid-feedback">${error.message}</div>`);
        });

        e.preventDefault();
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\auth\login.blade.php ENDPATH**/ ?>