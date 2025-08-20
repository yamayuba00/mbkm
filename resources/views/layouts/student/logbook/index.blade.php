@extends('layouts.master')

@section('title', 'Logbook')

@section('content')

    <section class="section">

        <div class="section-header">
            <h1>Logbook</h1>
        </div>

        <div class="section-body">
            <a href="#" class="btn btn-primary mb-3" id="btn-add-logbook">
                <i class="fas fa-plus"></i> Add LogBook
            </a>
            <a href="#" class="btn btn-secondary mb-3" id="btn-refresh">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>
            <table class="table table-bordered w-100" id="logbook-table">
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
    @include('layouts.student.logbook.modal-form')

@endsection

@push('scripts')
    <script>
        $('#btn-refresh').on('click', function() {
            $('#logbook-table').DataTable().ajax.reload(null, false);
        });
        $(document).ready(function() {

            $.get('/student/logbook/get-mbkm', function(res) {
                console.log(res);
                if (res.length > 0) {
                    res.forEach(function(program) {
                        $('#mbkm_program_id').append(
                            `<option value="${program.id}">
                                ${program.title} - (${program.submission_period.periode} - ${program.submission_type.program_mbkm})
                            </option>`
                        );
                    });
                } else {
                    $('#mbkm_program_id').append('<option value="">Tidak ada program tersedia</option>');
                }
            }).fail(function() {
                alert('Gagal memuat program MBKM');
            });

            $('#logbook-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('student.logbook.data') }}',
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

        $(function() {
            const modal = $('#modal-form');
            const form = $('#form');

            $('#btn-add-logbook').on('click', function() {
                form.trigger('reset');
                $('#data-id').val('');
                $('#modal-title').text('Add LogBook');
                modal.modal('show');
            });

            form.on('submit', function(e) {
                e.preventDefault();

                const id = $('#data-id').val();
                const url = id ? `/student/logbook/${id}` : `/student/logbook`;
                const method = id ? 'POST' : 'POST';
                const formData = new FormData(this);

                if (id) {
                    formData.append('_method', 'PUT')
                }

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function() {
                        modal.modal('hide');
                        $('#logbook-table').DataTable().ajax.reload(null, false);
                        iziToast.success({
                            title: 'Success',
                            message: 'Successfully saved data.',
                            position: 'topRight'
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorMsg = '';
                            for (let field in errors) {
                                errorMsg += `${errors[field][0]}<br>`;
                            }

                            iziToast.error({
                                title: 'Validation Error',
                                message: errorMsg,
                                position: 'topRight',
                                timeout: 6000
                            });
                        } else {
                            iziToast.error({
                                title: 'Error',
                                message: 'Something went wrong.',
                                position: 'topRight'
                            });
                        }
                    }
                });
            });

            $('#logbook-table').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                $.get(`/student/logbook/${id}/edit`, function(res) {
                    $('#modal-title').text('Edit LogBook');
                    $('#data-id').val(res.id);
                    $('#mbkm_program_id').val(res.mbkm_program_id);
                    $('#date').val(res.date);
                    $('#activity').val(res.activity);
                    $('#output').val(res.output);
                    $('#obstacle').val(res.obstacle);
                    $('#duration').val(res.duration);
                    $('#status').val(res.status);

                    $('#modal-form').modal('show');
                });
            });
            $('#logbook-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const id = $(this).data('id');

                if (confirm('Apakah kamu yakin ingin menghapus logbook ini?')) {
                    $.ajax({
                        url: `/student/logbook/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            iziToast.success({
                                title: 'Success',
                                message: 'Successfully deleted data.',
                                position: 'topRight'
                            });

                            $('#logbook-table').DataTable().ajax.reload(null, false);
                        },
                        error: function() {
                            iziToast.error({
                                title: 'Error',
                                message: 'Terjadi kesalahan saat menghapus.',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });


        });
    </script>
@endpush
