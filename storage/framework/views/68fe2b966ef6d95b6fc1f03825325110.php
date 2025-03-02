<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, private">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <title><?php echo $__env->yieldContent('title'); ?> - Admin Panel</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php echo $__env->make('layouts.partials.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('layouts.partials.custom-styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body class="admin-layout">
<div class="container-fluid p-0">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-3">
            <h5 class="text-center mb-4">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="img-fluid" style="height: 40px;">
                <span class="ms-2"><?php echo e(ucfirst(Auth::user()->role)); ?> Panel</span>
            </h5>
            <?php if(Auth::user()->role === 'admin'): ?>
                <?php echo $__env->make('layouts.partials.sidebar-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php elseif(Auth::user()->role === 'petugas'): ?>
                <?php echo $__env->make('layouts.partials.sidebar-petugas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?>
                <?php echo $__env->make('layouts.partials.sidebar-wali', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php echo $__env->make('shared.navbar._navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Page Content -->
        <div class="p-4">
            <?php echo $__env->yieldPushContent('before-content'); ?>
            <?php echo $__env->yieldContent('content'); ?>
            <?php echo $__env->yieldPushContent('after-content'); ?>
        </div>
    </div>
</div>

    <?php echo $__env->yieldContent('modals'); ?>

    <?php echo $__env->make('layouts.partials.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views/layouts/admin.blade.php ENDPATH**/ ?>