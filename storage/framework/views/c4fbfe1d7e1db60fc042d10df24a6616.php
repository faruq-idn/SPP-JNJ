
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Santri</h6>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="30%">NISN</th>
                <td><?php echo e($santri->nisn); ?></td>
            </tr>
            <tr>
                <th>Nama Lengkap</th>
                <td><?php echo e($santri->nama); ?></td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td><?php echo e($santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td><?php echo e($santri->tanggal_lahir->format('d F Y')); ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?php echo e($santri->alamat); ?></td>
            </tr>
            <tr>
                <th>Tanggal Masuk</th>
                <td><?php echo e($santri->tanggal_masuk->format('d F Y')); ?></td>
            </tr>
            <tr>
                <th>Jenjang & Kelas</th>
                <td><?php echo e($santri->jenjang); ?> - <?php echo e($santri->kelas); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge bg-<?php echo e($santri->status === 'aktif' ? 'success' : 'secondary'); ?>">
                        <?php echo e(ucfirst($santri->status)); ?>

                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views/shared/santri/_data_santri.blade.php ENDPATH**/ ?>