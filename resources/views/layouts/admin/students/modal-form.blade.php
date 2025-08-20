<!-- Modal Form Mahasiswa -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-mahasiswa">
    <div class="modal-dialog" role="document">
        <form id="form-mahasiswa" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add Student </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="mahasiswa-id">

                    <div class="form-group">
                        <label for="nim">NIM</label>
                        <input type="text" name="nim" id="nim" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Nama / Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>


                    <div class="form-group">
                        <label for="faculties">Faculty</label>
                        <select name="faculties_id" id="faculties_id" class="form-control" required></select>
                    </div>
                    <div class="form-group">
                        <label for="prodi">Prodi</label>
                        <select name="prodi_id" id="prodi_id" class="form-control " required></select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
