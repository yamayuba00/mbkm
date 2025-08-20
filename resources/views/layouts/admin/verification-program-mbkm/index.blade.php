@extends('layouts.master')

@section('title', 'Verification MBKM Program')

@section('content')

    <section class="section">

        <div class="section-header">
            <h1>Verification MBKM Program</h1>
        </div>


        <div class="section-body">
            <table class="table table-bordered table-striped w-100" id="mbkm-program-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Program</th>
                        <th>Student Name</th>
                        <th>Lecturer</th>
                        <th>IPK</th>
                        <th>Total SKS</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
    @include('layouts.admin.verification-program-mbkm.modal-form')

@endsection

@push('scripts')
    <!-- DataTables -->

    <script>
        $(document).ready(function() {
            $('#mbkm-program-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.verification-program-mbkm.data') }}',
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
                        data: 'program_mbkm',
                        name: 'program_mbkm'
                    },
                    {
                        data: 'student',
                        name: 'student'
                    },
                    {
                        data: 'lecturer',
                        name: 'lecturer'
                    },

                    {
                        data: 'ipk',
                        name: 'ipk',
                    },
                    {
                        data: 'sks',
                        name: 'sks',
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

        $(function() {
            const modal = $('#modal-mbkm-program');
            const form = $('#form');

            // Approve
            $('#approve-id').on('click', function() {
                const id = $('#mbkm-program-id').val();
                if (!id) return;

                $.ajax({
                    url: '/admin/verification-program-mbkm/' + id + '/status',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: 2
                    },
                    success: function(res) {
                        $('#modal-mbkm-program').modal('hide');
                        $('#mbkm-program-table').DataTable().ajax.reload(null, false);
                        iziToast.success({
                            title: 'Accepted',
                            message: 'Mbkm your program is accepted.',
                            position: 'topRight'
                        });
                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Failed to approve the program.',
                            position: 'topRight'
                        });
                    }
                });
            });
            $('#rejected-id').on('click', function() {
                const id = $('#mbkm-program-id').val();
                if (!id) return;

                $.ajax({
                    url: '/admin/verification-program-mbkm/' + id + '/status',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: 3
                    },
                    success: function(res) {
                        $('#modal-mbkm-program').modal('hide');
                        $('#mbkm-program-table').DataTable().ajax.reload(null, false);
                        iziToast.success({
                            title: 'Rejected',
                            message: 'Mbkm your program is rejected.',
                            position: 'topRight'
                        });
                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Failed to approve the program.',
                            position: 'topRight'
                        });
                    }
                });
            });

            $('#mbkm-program-table').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                $.get(`/admin/verification-program-mbkm/${id}/edit`, function(res) {
                    $('#modal-title').text('Verification MBKM Program');
                    $('#mbkm-program-id').val(res.id);
                    $('#title').val(res.title);
                    $('#lecturer_id').val(res.lecturer.username);
                    $('#student_name').val(res.student.username);
                    // $('#submission_types_id').val(res.submission_type.program_mbkm);
                    // $('#submission_period_id').val(res.submission_period?.periode);
                    $('#whatsapp').val(res.student.detail?.phone);
                    $('#nim').val(
                        (res.student.detail?.nim || '') + ' - ' + (res.student.detail?.class ||
                            '')
                    );
                    $('#program_mbkm_submission').val(
                        (res.submission_period?.periode || '') + ' - ' + (res.submission_type
                            .program_mbkm || '')
                    );


                    $('#ipk').val(res.ipk);
                    $('#sks').val(res.sks || '');
                    $('#cv-preview, #khs-preview, #portfolio-preview').html('');

                    // Tampilkan preview/link jika ada
                    if (res.cv) {
                        $('#cv-preview').html(
                            `<a href="/storage/uploads/cv/${res.cv}.pdf" target="_blank">Lihat CV</a>`
                        );
                    }
                    if (res.khs) {
                        $('#khs-preview').html(
                            `<a href="/storage/uploads/khs/${res.khs}.pdf" target="_blank">Lihat KHS</a>`
                        );
                    }
                    if (res.portfolio) {
                        $('#portfolio-preview').html(
                            `<a href="/storage/uploads/portfolios/${res.portfolio}.pdf" target="_blank">Lihat Portofolio</a>`
                        );
                    }
                    $('#modal-mbkm-program').modal('show');
                });
            });

        });
    </script>
@endpush
