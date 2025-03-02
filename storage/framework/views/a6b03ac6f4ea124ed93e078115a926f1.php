<!-- Modal Tambah Kategori -->
<div class="modal fade" id="createKategoriModal" tabindex="-1" aria-labelledby="createKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createKategoriModalLabel">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCreateKategori" onsubmit="submitCreateForm(event)">
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Kategori</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="biaya_makan" class="form-label">Biaya Makan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control rincian-biaya" id="biaya_makan" name="biaya_makan" min="0" step="1" pattern="\d*" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="biaya_asrama" class="form-label">Biaya Asrama</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control rincian-biaya" id="biaya_asrama" name="biaya_asrama" min="0" step="1" pattern="\d*" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="biaya_listrik" class="form-label">Biaya Listrik</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control rincian-biaya" id="biaya_listrik" name="biaya_listrik" min="0" step="1" pattern="\d*" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="biaya_kesehatan" class="form-label">Biaya Kesehatan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control rincian-biaya" id="biaya_kesehatan" name="biaya_kesehatan" min="0" step="1" pattern="\d*" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="nominal_spp" class="form-label">Total SPP (Otomatis)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="nominal_spp" name="nominal_spp" readonly>
                                </div>
                                <small class="text-muted">Total akan dihitung otomatis dari rincian biaya</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-labelledby="editKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKategoriModalLabel">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditKategori" onsubmit="submitEditForm(event)">
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" id="edit_kategori_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nama" class="form-label">Nama Kategori</label>
                                <input type="text" class="form-control" id="edit_nama" name="nama" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_biaya_makan" class="form-label">Biaya Makan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control rincian-biaya" id="edit_biaya_makan" name="biaya_makan" min="0" step="1" pattern="\d*" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_biaya_asrama" class="form-label">Biaya Asrama</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control rincian-biaya" id="edit_biaya_asrama" name="biaya_asrama" min="0" step="1" pattern="\d*" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_biaya_listrik" class="form-label">Biaya Listrik</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control rincian-biaya" id="edit_biaya_listrik" name="biaya_listrik" min="0" step="1" pattern="\d*" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_biaya_kesehatan" class="form-label">Biaya Kesehatan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control rincian-biaya" id="edit_biaya_kesehatan" name="biaya_kesehatan" min="0" step="1" pattern="\d*" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_nominal_spp" class="form-label">Total SPP (Otomatis)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="edit_nominal_spp" name="nominal_spp" readonly>
                                </div>
                                <small class="text-muted">Total akan dihitung otomatis dari rincian biaya</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>

            <div class="modal-body border-top pt-4">
                <h6 class="mb-3">Riwayat Perubahan Tarif</h6>
                <div class="table-responsive" id="riwayatTarifTable">
                    <table class="table table-sm table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Biaya Makan</th>
                                <th>Biaya Asrama</th>
                                <th>Biaya Listrik</th>
                                <th>Biaya Kesehatan</th>
                                <th>Total SPP</th>
                                <th>Berlaku Sejak</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="riwayatTarifBody">
                            <tr>
                                <td colspan="8" class="text-center">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\SPP-JNJ\resources\views/admin/kategori/modals/create-edit.blade.php ENDPATH**/ ?>