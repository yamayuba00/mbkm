@extends('layouts.master')

@section('title', 'MBKM Program')

@section('content')

    <section class="section">

        <div class="section-header">
            <h1>MBKM Program</h1>
        </div>

        <div class="section-body">

            <a href="#" class="btn btn-secondary mb-3" id="btn-refresh">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>

            <table class="table table-bordered w-100" id="mbkm-program-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Periode - Program</th>
                        <th>Lecturer</th>
                        <th>IPK</th>
                        <th>Total SKS</th>
                        <th>Validate Doc</th>
                        <th>Validated (You)</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
    @include('layouts.lecturer.mbkm-program.modal-form')

@endsection

@push('scripts')


    <script>

        $('#btn-refresh').on('click', function() {
            $('#mbkm-program-table').DataTable().ajax.reload();
        });
        $(document).ready(function() {
            $('#mbkm-program-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('lecturer.verification-program-mbkm.data') }}',
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
                        data: 'validated_by',
                        name: 'validated_by',
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
            const modal = $('#modal-input-nilai');
            const form = $('#form');

            form.on('submit', function(e) {
                e.preventDefault();

                const id = $('#mbkm-program-id').val();
                const url = `/lecturer/verification-program-mbkm/${id}/update`;
                const method = 'POST';
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
                        $('#mbkm-program-table').DataTable().ajax.reload(null, false);
                        iziToast.success({
                            title: 'Success',
                            message: 'Successfully saved.',
                            position: 'topRight',
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

            $('#mbkm-program-table').on('click', '.btn-input-nilai', function() {
                const id = $(this).data('id');
                $.get(`/lecturer/verification-program-mbkm/${id}/edit`, function(res) {
                    console.log(res);
                    $('#modal-title').text('Input Nilai: ' + res.student.username);
                    $('#mbkm-program-id').val(res.id);
                    $('#academic_value').val(res.academic_value ?? 0);
                    $('#field_value').val(res.field_value ?? 0);
                    $('#reason').val(res.reason ?? '');

                    $('#modal-input-nilai').modal('show');
                });
            });

            $('#modal-input-nilai').on('hide.bs.modal', function() {
                $('#academic_value').val('');
                $('#field_value').val('');
                $('#reason').val('');
            });

            $(document).on('click', '.btn-accepted-program', function(e) {
                e.preventDefault();

                var button = $(this);
                var programId = button.data('id');

                if (!confirm('Apakah Anda yakin ingin memvalidasi program ini?')) return;

                $.ajax({
                    url: '/lecturer/verification-program-mbkm/validate',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: programId
                    },
                    success: function(response) {   
                        iziToast.success({
                            title: 'Success',
                            message: response.message,
                            position: 'topRight'
                        });
                        $('#mbkm-program-table').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        iziToast.error({
                            title: 'Error',
                            message: xhr.responseJSON?.message ||
                                'Terjadi kesalahan server.',
                            position: 'topRight'
                        });
                    }
                });
            });

        });
    </script>
@endpush
