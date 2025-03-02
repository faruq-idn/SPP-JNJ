<!-- Select2 Bootstrap 5 Theme -->
<link href="<?php echo e(asset('vendor/select2/css/select2-bootstrap-5-theme.min.css')); ?>" rel="stylesheet">

<!-- Custom CSS -->
<link href="<?php echo e(asset('css/custom.css')); ?>" rel="stylesheet">

<!-- Vite CSS -->
<?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss']); ?>

<!-- Essential Datatable Styles -->
<style>
    .dataTables_wrapper .btn-group {
        gap: 0.25rem;
    }
</style>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views/layouts/partials/custom-styles.blade.php ENDPATH**/ ?>