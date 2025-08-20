@extends('layouts.master')

@section('title', 'Lecturer')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Lecturer</h1>
        </div>

        <div class="section-body">
            <a href="#" class="btn btn-primary mb-3" id="btn-modal-lecturer">
                <i class="fas fa-plus"></i> Add Lecturer
            </a>
            <table class="table table-bordered w-100" id="table-serverside">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nidn</th>
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
    @include('layouts.admin.lecturer.modal-form')

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-serverside').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.lecturers.data') }}',
                columns: [{
                        data: 'no',
                        name: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nidn',
                        name: 'nidn'
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
            const modal = $('#modal-lecturer');
            const form = $('#form-modal');
            const passwordGroup = $('#password-group');
            const passwordConfirmationGroup = $('#password-confirmation-group');

            $('#btn-modal-lecturer').on('click', function() {
                form.trigger('reset');
                $('#lecturer-id').val('');
                $('#modal-title').text('Add Lecturer');

                // Aktifkan kembali email dan nidn
                $('#nidn').prop('disabled', false);
                $('#email').prop('disabled', false);

                // Tampilkan kembali password jika pakai
                passwordGroup.removeClass('d-none');

                // Tampilkan modal
                modal.modal('show');

                // Pastikan focus hanya setelah modal selesai dibuka
                modal.one('shown.bs.modal', function() {
                    $('#nidn').trigger('focus');
                });
            });

            form.on('submit', function(e) {
                e.preventDefault();
                const id = $('#lecturer-id').val();
                const url = id ? `/admin/lecturers/${id}` : `/admin/lecturers`;
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function() {
                        // iziToast
                        $('#table-serverside').DataTable().ajax.reload(null, false);
                        iziToast.success({
                            title: 'Success',
                            message: 'Lecturer saved successfully.',
                            position: 'topRight'
                        });
                        modal.modal('hide');
                    },
                    error: function() {
                        toastr.error('Terjadi kesalahan saat menyimpan data.');
                        // alert('Terjadi kesalahan saat menyimpan data.');
                    }
                });
            });

            $('#table-serverside').on('click', '.btn-edit', function() {
                const id = $(this).data('id');

                $.get(`/admin/lecturers/${id}/edit`, function(res) {
                    const modal = $('#modal-lecturer');

                    // Buka modal
                    modal.modal('show');

                    // Tunggu modal benar-benar siap
                    modal.one('shown.bs.modal', function() {
                        // Delay sedikit agar Bootstrap selesai memproses aria-hidden
                        setTimeout(() => {
                            $('#modal-title').text('Edit Lecturer');
                            $('#lecturer-id').val(res.id);
                            $('#username').val(res.username);
                            $('#email').val(res.email).prop('disabled', true);
                            $('#nidn').val(res.detail?.nidn || '').prop('disabled',
                                true);
                            $('#address').val(res.detail?.address || '');
                            $('#phone').val(res.detail?.phone || '');
                            $('#gender').val(String(res.gender || ''));

                            // Sembunyikan password field
                            $('#password-group').addClass('d-none');
                        }, 10); // Delay minimal 10ms, bisa disesuaikan
                    });
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
                    url: '/admin/lecturer/toggle-status',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: userId
                        // status: targetStatus

                    },
                    success: function(response) {
                        if (response.success) {
                          
                            iziToast.success({
                                title: 'Success',
                                message: response.message,
                                position: 'topRight'
                            });
                            $('#table-serverside').DataTable().ajax.reload(null,
                                false);
                        } else {

                            iziToast.success({
                                title: 'Error',
                                message: 'Gagal memperbarui status',
                                position: 'topRight'
                            });
                            // toastr.error('Gagal memperbarui status');
                        }
                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Terjadi kesalahan server',
                            position: 'topRight'
                        });
                        // toastr.error('Terjadi kesalahan server');
                    }
                });
            });


        });
    </script>
@endpush
