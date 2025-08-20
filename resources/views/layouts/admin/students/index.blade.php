@extends('layouts.master')

@section('title', 'Students')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Students</h1>
        </div>

        <div class="section-body">

            {{-- refresh --}}
            <a href="#" class="btn btn-secondary mb-3" id="btn-refresh">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>
            {{-- refresh --}}
         
            <table class="table table-bordered w-100" id="mahasiswa-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nim</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Prodi</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
    @include('layouts.admin.students.modal-form')

@endsection

@push('scripts')
    <!-- DataTables -->


    <script>

        $('#btn-refresh').on('click', function(e) {
            e.preventDefault();
            $('#mahasiswa-table').DataTable().ajax.reload();
        });
      
        $(document).ready(function() {
            $('#mahasiswa-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.students.data') }}',
                columns: [{
                        data: 'no',
                        name: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'prodi',
                        name: 'prodi'
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
            const modal = $('#modal-mahasiswa');
            const form = $('#form-mahasiswa');
            const passwordGroup = $('#password-group');

            $('#btn-tambah-mahasiswa').on('click', function() {
                form.trigger('reset');
                $('#mahasiswa-id').val('');
                $('#modal-title').text('Add Student');
                passwordGroup.removeClass('d-none');
                modal.modal('show');
            });

            form.on('submit', function(e) {
                e.preventDefault();
                const id = $('#mahasiswa-id').val();
                const url = id ? `/admin/students/${id}` : `/admin/students`;
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function() {
                        modal.modal('hide');
                        iziToast.success({
                            title: 'Success',
                            message: response.message,
                            position: 'topRight'
                        });
                        $('#mahasiswa-table').DataTable().ajax.reload(null, false);
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

            $('#mahasiswa-table').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                $.get(`/admin/students/${id}/edit`, function(res) {
                    $('#modal-title').text('Edit Student');
                    $('#mahasiswa-id').val(res.id);
                    $('#username').val(res.username);
                    $('#email').val(res.email);
                    $('#faculties_id').val(res.detail?.faculties_id || '');
                    $('#prodi_id').val(res.detail?.prodi_id || '');
                    $('#nim').val(res.detail?.nim || '');

                    $('#modal-mahasiswa').modal('show');
                });
            });

            $(document).on('click', '.btn-toggle-status', function(e) {
                e.preventDefault();
                var button = $(this);
                var userId = button.data('id');


                // Konfirmasi
                const confirmText = button.text().trim() === 'Deactivate' ?
                    'Are you sure you want to deactivate this user?' :
                    'Are you sure you want to activate this user?';

                if (!confirm(confirmText)) return;

                $.ajax({
                    url: '/admin/students/toggle-status',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: userId

                    },
                    success: function(response) {
                        if (response.success) {

                            iziToast.success({
                                title: 'Success',
                                message: response.message,
                                position: 'topRight'
                            });
                            $('#mahasiswa-table').DataTable().ajax.reload(null,
                                false);
                        } else {

                            iziToast.success({
                                title: 'Error',
                                message: 'Something went wrong.',
                                position: 'topRight'
                            });
                        }
                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Terjadi kesalahan server',
                            position: 'topRight'
                        });
                    }
                });
            });

        });
    </script>
@endpush
