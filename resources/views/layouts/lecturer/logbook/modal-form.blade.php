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
                        <div class="form-group col-md-4">
                            <label for="date">Tanggal / Date <span class="text-danger">*</span> </label>
                            <input type="date" name="date" id="date" class="form-control"
                                value="" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="duration">Waktu / Duration (Minutes) <span class="text-danger">*</span> </label>
                            <input type="text" name="duration" id="duration" class="form-control"
                                value="" oninput="this.value = this.value.replace(/[^0-9]/g, '')" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="mbkm_program_id">Mbkm Program (You) <span class="text-danger">*</span> </label>
                             <input type="text" name="mbkm_program_id" id="mbkm_program_id" class="form-control"
                                value="" disabled>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="activity">Aktivitas / Activity <span class="text-danger">*</span> </label>
                            <textarea name="activity"  cols="30" rows="10" class="form-control" id="activity" disabled></textarea>

                        </div>
                        <div class="form-group col-md-6">
                            <label for="output">Hasil / Output <span class="text-danger">*</span> </label>
                            <textarea name="output"  cols="30" rows="10" class="form-control" id="output" disabled></textarea>
                           
                        </div>
                        <div class="form-group col-md-6">
                            <label for="obstacle">Masalah / Obstacle (If Any) </label>
                            <textarea name="obstacle" cols="30" rows="10" class="form-control" id="obstacle" disabled></textarea>
                        </div>
                        
                    </div>

                </div>
                <div class="modal-footer">
                    <button id="approve-id" type="button" class="btn btn-primary">Accept</button>
                    <button id="rejected-id" type="button" class="btn btn-danger">Rejected</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
