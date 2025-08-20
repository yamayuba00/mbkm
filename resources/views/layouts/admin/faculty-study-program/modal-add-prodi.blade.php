<div class="modal fade" tabindex="-1" role="dialog" id="modal-prodi">
    <div class="modal-dialog" role="document">
        <form id="form-prodi" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Prodi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="faculty_id" id="faculty_id">
                    <input type="hidden" name="id" id="prodi-id">

                    <div class="form-group">
                        <label for="name">Name Prodi <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="prodi-name" class="form-control input-name" required>
                    </div>
                    <div class="form-group">
                        <label for="code">Code Prodi <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="prodi-code" class="form-control input-code" readonly>
                    </div>

                    <div class="form-group">
                        <label for="code">Level <span class="text-danger">*</span></label>
                        <select name="level" id="level" class="form-control" required>
                            <option value="">-- Choose Level --</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="D4">D4</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
