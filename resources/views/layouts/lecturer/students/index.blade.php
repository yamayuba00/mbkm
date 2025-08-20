@extends('layouts.master')

@section('title', 'Students')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Students</h1>
        </div>

        <div class="section-body">

            <table class="table table-bordered w-100" id="mahasiswa-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nim</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Prodi</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
    @include('layouts.lecturer.students.modal-form')

@endsection

@push('scripts')
    <!-- DataTables -->


    <script>
        $(document).ready(function() {
            $('#mahasiswa-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('lecturer.students.data') }}',
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
                        data: 'status',
                        name: 'status'
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


            $('#mahasiswa-table').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                $.get(`/admin/students/${id}/edit`, function(res) {
                    $('#modal-title').text('Edit Student');
                    $('#mahasiswa-id').val(res.id);
                    $('#username').val(res.username);
                    $('#email').val(res.email);
                    $('#nim').val(res.detail?.nim || '');
                    $('#modal-mahasiswa').modal('show');
                });
            });

            // reset password confirm

            $('#mahasiswa-table').on('click', '.btn-reset-password', function(e) {
                e.preventDefault();
                const id = $(this).data('id');

                if (confirm('Are you sure want to reset password?')) {
                    $.ajax({
                        url: `/lecturer/students/${id}/reset-password`,
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            iziToast.success({
                                title: 'Success',
                                message: 'Successfully reset password.',
                                position: 'topRight'
                            });
                        },
                        error: function(xhr) {
                            iziToast.error({
                                title: 'Gagal',
                                message: xhr.responseJSON?.message ||
                                    'Something went wrong.',
                                position: 'topRight'
                            });
                        }
                    });
                }
            });




        });
    </script>
@endpush
