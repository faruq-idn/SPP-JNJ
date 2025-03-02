<!-- Modal Update Tarif -->
<div class="modal fade" id="updateTarifModal" tabindex="-1" aria-labelledby="updateTarifModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateTarifModalLabel">Update Tarif SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpdateTarif" onsubmit="submitUpdateTarifForm(event)">
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="tarif_kategori_id">
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal Tarif Baru</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                class="form-control" 
                                id="nominal" 
                                name="nominal" 
                                min="0"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                required>
                        </div>
                        <small class="text-muted">Hanya masukkan angka tanpa titik atau koma</small>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="berlaku_mulai" class="form-label">Berlaku Mulai</label>
                        <input type="date" class="form-control" id="berlaku_mulai" name="berlaku_mulai" 
                            value="<?php echo e(date('Y-m-d')); ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="tarif_keterangan" class="form-label">Keterangan Perubahan</label>
                        <textarea class="form-control" id="tarif_keterangan" name="keterangan" rows="2"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Tarif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views/admin/kategori/modals/tarif.blade.php ENDPATH**/ ?>