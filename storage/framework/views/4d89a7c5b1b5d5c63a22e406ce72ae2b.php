
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Wali Santri</h6>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="30%">Nama Wali</th>
                <td>
                    <?php if($santri->wali_id): ?>
                        <?php echo e($santri->wali->name); ?>

                    <?php elseif($santri->nama_wali): ?>
                        <?php echo e($santri->nama_wali); ?>

                        <span class="badge bg-warning text-dark">Belum terhubung</span>
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Nomor HP Wali</th>
                <td><?php echo e($santri->wali->no_hp ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo e($santri->wali->email ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Status Wali</th>
                <td>
                    <?php if($santri->wali_id): ?>
                        <span class="badge bg-success">Terhubung</span>
                    <?php elseif($santri->nama_wali): ?>
                        <span class="badge bg-warning text-dark">Menunggu Klaim</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Belum Ada Wali</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        
    </div>
</div>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\shared\santri\_wali_info.blade.php ENDPATH**/ ?>