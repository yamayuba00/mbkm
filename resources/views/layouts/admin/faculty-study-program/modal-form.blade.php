<div class="modal fade" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog" role="document">
        <form id="form" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title-faculty">Add Faculty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="faculty-id">

                    <div class="form-group">
                        <label for="name">Name Fakultas <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control input-name" required>
                    </div>

                    {{-- code auto generate --}}
                    <div class="form-group">
                        <label for="code">Code Fakultas</label>
                        <input type="text" name="code" id="code" class="form-control input-code" readonly>

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
