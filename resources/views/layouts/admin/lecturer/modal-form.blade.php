<!-- Modal Form Mahasiswa -->
<div class="modal fade" id="modal-lecturer" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="form-modal" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add Lecturer </h5>
                    <button type="button" class="close" data-dismiss="modal" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="lecturer-id">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="nidn">NIDN <span class="text-danger">*</span></label>
                            <input type="text" name="nidn" id="nidn" class="form-control" required value="{{ old('nidn') }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="username">Nama / Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" id="gender" required>
                                <option value="">-- Choose Gender --</option>
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                        </div>

                         <div class="form-group col-md-12">
                            <label for="phone">Phone Number (WA) <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Address <span class="text-danger">(Optional '-' for empty) </span></label>
                            <textarea name="address" class="form-control" id="address" rows="2"></textarea>
                        </div>
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
