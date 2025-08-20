@extends('layouts.master')

@section('title', 'Activities')

@section('content')

    <section class="section">

        <div class="section-header">
            <h1>Activities</h1>
        </div>

        <div class="section-body">
            <a href="#" class="btn btn-secondary mb-3" id="btn-refresh">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>
            <table class="table table-bordered w-100" id="activities-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Lecturer</th>
                        <th>Program MBKM</th>
                        <th>Date</th>
                        <th>Activity</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
    @include('layouts.lecturer.activities.modal-form')

@endsection

@push('scripts')
    <!-- DataTables -->

    <script>
        $('#btn-refresh').on('click', function() {
            $('#activities-table').DataTable().ajax.reload(null, false);
        });
        $(document).ready(function() {

            $('#activities-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('lecturer.activities.data') }}',
                columns: [{
                        data: 'no',
                        name: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'lecturer',
                        name: 'lecturer'
                    },
                    {
                        data: 'program_mbkm',
                        name: 'program_mbkm'
                    },

                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'activity',
                        name: 'activity',
                    },
                    {
                        data: 'duration',
                        name: 'duration',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        $('#activities-table').on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            $.get(`/lecturer/activities/${id}/edit`, function(res) {
                $('#modal-title').text('View Activities: ' + res.student.username);
                $('#data-id').val(res.id);
                $('#mbkm_program_id').val(res.mbkm_program.title);
                $('#date').val(res.date);
                $('#activity_title').val(res.activity_title);
                $('#activity_detail').val(res.activity_detail);
                $('#location').val(res.location);
                $('#evidence_file').val(res.evidence_file);
                $('#output').val(res.output);
                $('#obstacle').val(res.obstacle);
                $('#duration').val(res.duration);
                $('#status').val(res.status);

                if (res.status != 0) {
                    $('#approve-id').addClass('d-none');
                    $('#rejected-id').addClass('d-none');
                }

                if (res.status != 0) {
                    $('#modal-form input, #modal-form textarea, #modal-form select').attr('disabled', true);
                    $('#submit-button').hide(); // sembunyikan tombol simpan kalau ada
                } else {
                    $('#modal-form input, #modal-form textarea, #modal-form select').attr('disabled',
                        false);
                    $('#submit-button').show();
                }
                $('#modal-form').modal('show');
            });


            function updateLogbookStatus(status) {
                const id = $('#data-id').val();

                $.ajax({
                    url: `/lecturer/activities/${id}/validate`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        status: status
                    },
                    success: function(res) {
                        $('#modal-form').modal('hide');
                        $('#activities-table').DataTable().ajax.reload(null, false);

                        iziToast.success({
                            title: 'Success',
                            message: 'Successfully Change Status Activities.',
                            position: 'topRight'
                        });
                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Error Change Status Activities.',
                            position: 'topRight'
                        });
                    }
                });
            }

            $('#approve-id').on('click', function() {
                updateLogbookStatus(1);
            });

            $('#rejected-id').on('click', function() {
                updateLogbookStatus(2);
            });
        });
    </script>
@endpush
