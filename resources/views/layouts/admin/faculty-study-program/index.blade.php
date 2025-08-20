@extends('layouts.master')

@section('title', 'Faculty & Study Program')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Faculty & Study Program</h1>
        </div>

        <div class="section-body">
            <a href="#" class="btn btn-primary mb-3" id="btn-modal">
                <i class="fas fa-plus"></i> Add Faculty
            </a>
            <table class="table table-bordered w-100" id="faculty-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Prodi Count</th>
                        <th>User Count</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="section-body mt-5">

            <table class="table table-bordered w-100" id="prodi-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Faculty</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Level</th>
                        <th>Users</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
    @include('layouts.admin.faculty-study-program.modal-form')

    @include('layouts.admin.faculty-study-program.modal-add-prodi')


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#faculty-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.program-campus.getDataFaculty') }}',
                columns: [{
                        data: 'no',
                        name: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'code',
                        name: 'code',
                    },
                    {
                        data: 'prodi_count',
                        name: 'prodi_count',
                    },
                    {
                        data: 'user_count',
                        name: 'user_count',
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
        $(document).ready(function() {
            $('#prodi-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/admin/program-campus/get-prodi',
                    type: 'GET'
                },
                columns: [{
                        data: 'no',
                        name: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'faculty_id',
                        name: 'faculty_id',
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'code',
                        name: 'code',
                    },

                    {
                        data: 'level',
                        name: 'level',
                    },
                    {
                        data: 'userCount',
                        name: 'userCount',
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
            const modal = $('#modal');
            const form = $('#form');

            $('#btn-modal').on('click', function() {
                form.trigger('reset');
                $('#faculty-id').val('');
                $('#modal-title-faculty').text('Add Faculties');
                modal.modal('show');
            });

            $('#form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#faculty-id').val();
                const url = id ? `/admin/program-campus/${id}` : `/admin/program-campus`;
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function() {
                        $('#modal').modal('hide');
                        iziToast.success({
                            title: 'Success',
                            message: 'Faculty saved successfully.',
                            position: 'topRight'
                        });
                        $('#faculty-table').DataTable().ajax.reload(null, false);
                        $('#prodi-table').DataTable().ajax.reload(null, false);
                        // reset form
                        $('#form')[0].reset();

                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Something went wrong.',
                            position: 'topRight'
                        });
                    }
                });
            });


            $('#faculty-table').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                $.get(`/admin/program-campus/${id}/edit`, function(res) {
                    $('#modal-title-faculty').text('Edit Faculty');
                    $('#faculty-id').val(res.id);
                    $('#name').val(res.name);
                    $('#code').val(res.code);
                    $('#modal').modal('show');
                });
            });

            $('#faculty-table').on('click', '.btn-trash-faculty', function() {
                const id = $(this).data('id');

                if (confirm('Yakin ingin menghapus data faculty ini ?')) {
                    // Lakukan aksi AJAX delete atau redirect, contoh:
                    $.ajax({
                        url: `/admin/program-campus/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            iziToast.success({
                                title: 'Success',
                                message: res.message,
                                position: 'topRight'
                            })
                            $('#prodi-table').DataTable().ajax.reload(null, false);
                            $('#faculty-table').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            let message = 'Something went wrong.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }

                            iziToast.error({
                                title: 'Error',
                                message: message,
                                position: 'topRight'
                            });
                        }
                    })
                } else {
                    return false;
                }
            });

            $('#faculty-table').on('click', '.btn-add-prodi', function() {

                const id = $(this).data('id');
                const facultyName = $(this).closest('tr').find('td:nth-child(2)')
                    .text();
                $('#modal-prodi .modal-title').text('Add Prodi for ' + facultyName);
                $('#faculty_id').val(id);
                $('#prodi-id').val('');
                $('#modal-prodi').modal('show');
            });

            $('#form-prodi').on('submit', function(e) {
                e.preventDefault();
                const id = $('#prodi-id').val();
                const facultyId = $('#faculty_id').val();
                const url = id ? `/admin/program-campus/${facultyId}/store-prodi/${id}` :
                    `/admin/program-campus/${facultyId}/store-prodi`;
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function() {
                        $('#modal-prodi').modal('hide');
                        iziToast.success({
                            title: 'Success',
                            message: 'Prodi saved successfully.',
                            position: 'topRight'
                        });
                        $('#prodi-table').DataTable().ajax.reload(null, false);
                        $('#faculty-table').DataTable().ajax.reload(null, false);
                        // reset form
                        $('#form-prodi')[0].reset();
                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Something went wrong.',
                            position: 'topRight'
                        });
                    }
                });
            });

            $('#prodi-table').on('click', '.btn-edit-prodi', function() {
                if ($('#modal-prodi').length === 0) {
                    const template = document.getElementById('template-modal-prodi');
                    $('body').append(template.innerHTML);
                }
                const id = $(this).data('id');
                console.log(id);
                $.get(`/admin/program-campus/prodi/${id}/edit`, function(res) {
                    console.log('res', res);
                    $('#modal-prodi .modal-title').text('Edit Prodi - ' + res.faculty);
                    $('#prodi-id').val(res.id);
                    $('#faculty_id').val(res.faculty_id);
                    $('#prodi-name').val(res.name_prodi);
                    $('#prodi-code').val(res.code);
                    $('#level').val(res.level);
                    $('#modal-prodi').modal('show');
                });
            });

            $('#prodi-table').on('click', '.btn-delete-prodi', function() {
                const id = $(this).data('id');

                if (confirm('Yakin ingin menghapus data prodi ini?')) {
                    // Lakukan aksi AJAX delete atau redirect, contoh:
                    $.ajax({
                        url: `/admin/program-campus/prodi/${id}/delete`,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            iziToast.success({
                                title: 'Success',
                                message: res.message,
                                position: 'topRight'
                            })
                            $('#prodi-table').DataTable().ajax.reload(null, false);
                            $('#faculty-table').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            let message = 'Something went wrong.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }

                            iziToast.error({
                                title: 'Error',
                                message: message,
                                position: 'topRight'
                            });
                        }
                    })
                } else {
                    return false;
                }
            });

        });
        $(document).on('input', '.input-name', function() {
            let $modal = $(this).closest('.modal');
            let name = $(this).val().trim();
            let code = '';

            if (name !== '') {
                let words = name.split(' ');
                code = words.map(word => word.charAt(0)).join('').toUpperCase();
            }

            $modal.find('.input-code').val(code);
        });
    </script>
@endpush
