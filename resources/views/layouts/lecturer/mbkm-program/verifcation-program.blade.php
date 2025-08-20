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
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control"
                                value="" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="lecturer_id">Lecturer</label>
                            <input type="text" name="lecturer_id" id="lecturer_id" class="form-control"
                                value="" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="student_name">Nama / Username</label>
                            <input type="text" name="student_name" id="student_name" class="form-control"
                                value="" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nim">NIM - Class</label>
                            <input type="text" name="nim" id="nim" class="form-control"
                                value="" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="whatsapp">Whatsapp</label>
                            <input type="text" name="whatsapp" id="whatsapp" class="form-control"
                                value="" disabled>
                        </div>
                    </div>
                 

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="program_mbkm_submission">Periode - MBKM Program <span class="text-danger">*</span>
                            </label>
                             <input type="text" name="program_mbkm_submission" id="program_mbkm_submission" class="form-control"
                                value="" disabled>
                          
                        </div>
                        <div class="form-group col-md-4">
                            <label for="ipk">IPK <span class="text-danger">*</span> </label>
                            <input type="text" name="ipk" id="ipk" class="form-control" disabled
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="sks">Total SKS <span class="text-danger">*</span> </label>
                            <input type="text" name="sks" id="sks" class="form-control" disabled
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="cv">Curriculum Vitae<span class="text-danger">*</span>
                            </label>
                            {{-- <input type="file" name="cv" id="cv" class="form-control-file" accept=".pdf"> --}}
                            <div id="cv-preview" class="mt-2"></div>

                        </div>
                        <div class="form-group col-md-4">
                            <label for="khs">Transcript KHS <span class="text-danger">*</span> </label>
                            {{-- <input type="file" name="khs" id="khs" class="form-control-file" accept=".pdf"> --}}
                            <div id="khs-preview" class="mt-2"></div>

                        </div>
                        <div class="form-group col-md-4">
                            <label for="portfolio">Portofolio<span class="text-danger">*</span> </label>
                            {{-- <input type="file" name="portfolio" id="portfolio" class="form-control-file"
                                accept=".pdf"> --}}
                            <div id="portfolio-preview" class="mt-2"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button id="approve-id" type="button" class="btn btn-primary">Accepted</button>
                    <button id="rejected-id" type="button" class="btn btn-danger">Rejected</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
