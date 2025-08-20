<!-- Modal Form Mahasiswa -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-input-nilai">
    <div class="modal-dialog" role="document">
        <form id="form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Input Nilai </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="mbkm-program-id">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="academic_value">Input Nilai (Academic) <span class="text-danger">*</span> </label>
                            <input type="text" name="academic_value" id="academic_value" class="form-control" 
                                value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" max="100">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="field_value">Input Nilai (Non Academic) <span class="text-danger">*</span> </label>
                            <input type="text" name="field_value" id="field_value" class="form-control"
                                value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" max="100">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="reason">Reason Link<span class="text-danger">*</span> </label>
                            <input type="text" name="reason" id="reason" class="form-control"
                                value="">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
