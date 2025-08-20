<!-- Modal Form Mahasiswa -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal-mbkm-program">
    <div class="modal-dialog modal-lg" role="document">
        <form id="form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add MBKM Program </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="mbkm-program-id">

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="title">Title MBKM Program <span class="text-danger">*</span> </label>
                            <input type="text" name="title" id="title" class="form-control"
                                value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="lecturer_id">Lecturer</label>
                            <input type="text" name="lecturer_id" id="lecturer_id" class="form-control"
                                value="{{ Auth::user()->lecturer->username }}" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="student_name">Nama / Username</label>
                            <input type="text" name="student_name" id="student_name" class="form-control"
                                value="{{ Auth::user()->username }}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nim">NIM - Class</label>
                            <input type="text" name="nim" id="nim" class="form-control"
                                value="{{ Auth::user()->detail->nim }} - {{ Auth::user()->detail->class }}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nim">Whatsapp</label>
                            <input type="text" name="nim" id="nim" class="form-control"
                                value="{{ Auth::user()->detail->phone }}" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="submission_period_id">Periode <span class="text-danger">*</span>
                            </label>
                            <select name="submission_period_id" id="submission_period_id" class="form-control" required>
                                <option value="">-- Choose Periode --</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="submission_types_id">Program Type <span class="text-danger">*</span>
                            </label>
                            <select name="submission_types_id" id="submission_types_id" class="form-control" required>
                                <option value="">-- Chooose Program --</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ipk">IPK <span class="text-danger">*</span> </label>
                            <input type="text" name="ipk" id="ipk" class="form-control" required
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sks">Total SKS <span class="text-danger">*</span> </label>
                            <input type="text" name="sks" id="sks" class="form-control" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="cv">Curriculum Vitae (PDF - 1MB) <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="cv" id="cv" class="form-control-file" accept=".pdf">
                            <div id="cv-preview" class="mt-2"></div>

                        </div>
                        <div class="form-group col-md-4">
                            <label for="khs">Transcript KHS (PDF - 1MB) <span class="text-danger">*</span> </label>
                            <input type="file" name="khs" id="khs" class="form-control-file" accept=".pdf">
                            <div id="khs-preview" class="mt-2"></div>

                        </div>
                        <div class="form-group col-md-4">
                            <label for="portfolio">Portofolio(PDF - 1MB) <span class="text-danger">*</span> </label>
                            <input type="file" name="portfolio" id="portfolio" class="form-control-file"
                                accept=".pdf">
                            <div id="portfolio-preview" class="mt-2"></div>
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
