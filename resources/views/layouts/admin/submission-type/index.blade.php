@extends('layouts.master')

@section('title', 'Submission Type')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Submission Type</h1>
        </div>

        <div class="section-body">
            <a href="#" class="btn btn-primary mb-3" id="btn-modal">
                <i class="fas fa-plus"></i> Add Submission Type
            </a>
            <table class="table table-bordered w-100" id="submission-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Program MBKM</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
    @include('layouts.admin.submission-type.modal-form')

@endsection

@push('scripts')
    <!-- DataTables -->


    <script>
        $(document).ready(function() {
            $('#submission-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.submission-type.data') }}',
                columns: [{
                        data: 'no',
                        name: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'program_mbkm',
                        name: 'program_mbkm',
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
            // const passwordGroup = $('#password-group');

            $('#btn-modal').on('click', function() {
                form.trigger('reset');
                $('#submission-type-id').val('');
                $('#modal-title').text('Add Submission Type');
                modal.modal('show');
            });

            form.on('submit', function(e) {
                e.preventDefault();
                const id = $('#submission-type-id').val();
                const url = id ? `/admin/submission-type/${id}` : `/admin/submission-type`;
                const method = id ? 'PUT' : 'POST';

                const formData = $(this).serializeArray();

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function() {
                        modal.modal('hide');
                        iziToast.success({
                            title: 'Success',
                            message: 'Submission Type saved successfully.',
                            position: 'topRight'
                        })
                        $('#submission-table').DataTable().ajax.reload(null, false);
                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Something went wrong.',
                            position: 'topRight'
                        })
                        alert('Terjadi kesalahan saat menyimpan data.');
                    }
                });
            });

            $('#submission-table').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                $.get(`/admin/submission-type/${id}/edit`, function(res) {
                    $('#modal-title').text('Edit Submission Type');
                    $('#submission-type-id').val(res.id);
                    $('#program_mbkm').val(res.program_mbkm);
                    $('#modal').modal('show');
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
                    url: '/admin/submission-type/toggle-status',
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
                            $('#submission-table').DataTable().ajax.reload(null,
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
