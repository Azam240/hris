<!-- Modal Tambah Promosi -->
<div class="modal fade" id="promosiModal" tabindex="-1" aria-labelledby="promosiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="promosiModalLabel">Tambah Data Promosi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="id_peg" class="form-label">ID Pegawai</label>
                        <input type="text" class="form-control" id="id_peg" name="id_peg" required>
                    </div>
                    <div class="mb-3">
                        <label for="jbt_lama" class="form-label">Jabatan Lama</label>
                        <input type="text" class="form-control" id="jbt_lama" name="jbt_lama" required>
                    </div>
                    <div class="mb-3">
                        <label for="jbt_baru" class="form-label">Jabatan Baru</label>
                        <input type="text" class="form-control" id="jbt_baru" name="jbt_baru" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_promosi" class="form-label">Tanggal Promosi</label>
                        <input type="date" class="form-control" id="tanggal_promosi" name="tanggal_promosi" required>
                    </div>
                    <div class="mb-3">
                        <label for="alasan_promosi" class="form-label">Alasan Promosi</label>
                        <textarea class="form-control" id="alasan_promosi" name="alasan_promosi" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="add_promosi" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Promosi -->
<div class="modal fade" id="editPromosiModal" tabindex="-1" aria-labelledby="editPromosiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPromosiModalLabel">Edit Data Promosi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <input type="hidden" id="edit_id_peg" name="id_peg">
                    <div class="mb-3">
                        <label for="edit_jbt_lama" class="form-label">Jabatan Lama</label>
                        <input type="text" class="form-control" id="edit_jbt_lama" name="jbt_lama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jbt_baru" class="form-label">Jabatan Baru</label>
                        <input type="text" class="form-control" id="edit_jbt_baru" name="jbt_baru" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tanggal_promosi" class="form-label">Tanggal Promosi</label>
                        <input type="date" class="form-control" id="edit_tanggal_promosi" name="tanggal_promosi" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_alasan_promosi" class="form-label">Alasan Promosi</label>
                        <textarea class="form-control" id="edit_alasan_promosi" name="alasan_promosi" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit_promosi" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>