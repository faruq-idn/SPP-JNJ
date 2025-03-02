<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Santri</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $santri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td onclick="window.location='<?php echo e(route('admin.santri.show', $s->id)); ?>'"><?php echo e($s->nisn); ?></td>
                        <td onclick="window.location='<?php echo e(route('admin.santri.show', $s->id)); ?>'"><?php echo e($s->nama); ?></td>
                        <td onclick="window.location='<?php echo e(route('admin.santri.show', $s->id)); ?>'"><?php echo e($s->jenjang); ?> <?php echo e($s->kelas); ?></td>
                        <td onclick="window.location='<?php echo e(route('admin.santri.show', $s->id)); ?>'"><?php echo e($s->kategori->nama ?? '-'); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($s->status_color); ?>">
                                <?php echo e(ucfirst($s->status)); ?>

                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('admin.santri.show', $s->id)); ?>"
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#santriFormModal"
                                        data-mode="edit"
                                        data-id="<?php echo e($s->id); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" 
                                        onclick="hapusSantri(<?php echo e($s->id); ?>)"
                                        class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\SPP-JNJ\resources\views\admin\santri\partials\_table.blade.php ENDPATH**/ ?>