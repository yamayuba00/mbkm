<!-- Modal Form Mahasiswa -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form id="form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add Logbook </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="data-id">

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="mbkm_program_id">Mbkm Program (You) <span class="text-danger">*</span> </label>
                            <select name="mbkm_program_id" class="form-control" id="mbkm_program_id">
                                <option value="">-- Choose Program --</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="activity_title">Judul Aktivitas / Activity Title <span
                                    class="text-danger">*</span> </label>
                            <input type="text" name="activity_title" id="activity_title" class="form-control"
                                value="">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="activity_detail">Detail Aktivitas / Activity Detail <span
                                    class="text-danger">*</span> </label>
                            <textarea name="activity_detail" id="activity_detail" cols="30" rows="10" class="form-control"></textarea>

                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="location"> Lokasi / Location <span class="text-danger">*</span> </label>
                            <input type="location" name="location" id="location" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="date">Tanggal / Date <span class="text-danger">*</span> </label>
                            <input type="date" name="date" id="date" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="duration">Waktu / Duration (Minutes) <span class="text-danger">*</span> </label>
                            <input type="text" name="duration" id="duration" class="form-control" value=""
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="evidence_file">Bukti / Evidence <span class="text-danger">*</span> </label>
                            <input type="text" name="evidence_file" id="evidence_file" class="form-control">
                            <span class="text-muted text-sm">Hint: Send in the form of a Google Drive URL link, Docs, or Spreadsheet</span>

                        </div>
                        <div class="form-group col-md-6">
                            <label for="output">Hasil / Output <span class="text-danger">*</span> </label>
                            <textarea name="output" cols="30" rows="10" class="form-control" id="output"></textarea>

                        </div>
                        <div class="form-group col-md-6">
                            <label for="obstacle">Masalah / Obstacle (If Any) </label>
                            <textarea name="obstacle" cols="30" rows="10" class="form-control" id="obstacle"></textarea>
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
