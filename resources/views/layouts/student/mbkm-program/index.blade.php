@extends('layouts.master')

@section('title', 'MBKM Program')

@section('content')

    <section class="section">

        <div class="section-header">
            <h1>MBKM Program</h1>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>MBKM Program Information</h4>
                    </div>
                    <div class="card-body">
                        <p>
                            Silakan buat Program MBKM. Perlu diketahui, data tidak bisa diubah selama statusnya masih
                            <strong>'Pending'</strong> hingga mendapat keputusan <strong><span
                                    class="text-danger">'Ditolak'</span></strong>.
                            <br>
                            <i>
                                You can create an MBKM Program. However, the data cannot be modified while the status is
                                still pending or has been rejected.
                            </i>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="#" class="btn btn-primary" id="btn-add-mbkm-program">
                        <i class="fas fa-plus"></i> Add MBKM Program
                    </a>
                    <button class="btn btn-secondary" id="btn-refresh-mbkm">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            <table class="table table-bordered w-100 " id="mbkm-program-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Periode - Program</th>
                        <th>Lecturer</th>
                        <th>IPK</th>
                        <th>SKS</th>
                        <th>Academic Value</th>
                        <th>Field Value</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
    @include('layouts.student.mbkm-program.modal-form')

@endsection

@push('scripts')
    <!-- DataTables -->

    <script>
        $('#btn-refresh-mbkm').on('click', function() {
            $('#mbkm-program-table').DataTable().ajax.reload(null, false);
        });

        $(document).ready(function() {
            $.ajax({
                url: '{{ route('student.mbkm.program.getSubmissionTypes') }}',
                method: 'GET',
                success: function(data) {

                    let select = $('#submission_types_id');
                    select.empty();
                    select.append('<option value="">-- Choose Program --</option>');
                    data.forEach(function(item) {
                        select.append(
                            `<option value="${item.id}">${item.program_mbkm}</option>`
                        );
                    });


                },
                error: function() {
                    console.error('Failed to fetch submission types');
                }
            });
        });
        $(document).ready(function() {
            $.ajax({
                url: '{{ route('student.mbkm.program.getSubmissionPeriods') }}',
                method: 'GET',
                success: function(data) {

                    let select = $('#submission_period_id');
                    select.empty();
                    select.append('<option value="">-- Choose Periode --</option>');
                    data.forEach(function(item) {
                        select.append(
                            `<option value="${item.id}">${item.periode}</option>`
                        );
                    });


                },
                error: function() {
                    console.error('Failed to fetch submission types');
                }
            });
        });


        $(document).ready(function() {
            $('#mbkm-program-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('student.mbkm.program.data') }}',
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
                        data: 'academic_value',
                        name: 'academic_value',
                    },
                    {
                        data: 'field_value',
                        name: 'field_value',
                    },
                    {
                        data: 'reason',
                        name: 'reason',
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

            $('#btn-add-mbkm-program').on('click', function() {
                form.trigger('reset');
                $('#mbkm-program-id').val('');
                $('#modal-title').text('Add MBKM Program');
                modal.modal('show');
            });

            form.on('submit', function(e) {
                e.preventDefault();

                const id = $('#mbkm-program-id').val();
                const url = id ? `/student/mbkm-program/${id}` : `/student/mbkm-program`;
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
                        $('#mbkm-program-table').DataTable().ajax.reload(null, false);
                        iziToast.success({
                            title: 'Success',
                            message: 'Program berhasil disimpan.',
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
                                message: 'Terjadi kesalahan saat menyimpan data.',
                                position: 'topRight'
                            });
                        }
                    }
                });
            });

            $('#mbkm-program-table').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                $.get(`/student/mbkm-program/${id}/edit`, function(res) {
                    $('#modal-title').text('Edit MBKM Program');
                    $('#mbkm-program-id').val(res.id);
                    $('#ipk').val(res.ipk);
                    $('#submission_period_id').val(res.submission_period_id);
                    $('#submission_types_id').val(res.submission_types_id);
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

            $(document).on('click', '.btn-toggle-status', function(e) {
                e.preventDefault();
                var button = $(this);
                var userId = button.data('id');
                const confirmText = button.text().trim() === 'Deactivate' ?
                    'Are you sure you want to deactivate this user?' :
                    'Are you sure you want to activate this user?';

                if (!confirm(confirmText)) return;

                $.ajax({
                    url: '/student/mbkm-program/toggle-status',
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
                            $('#mbkm-program-table').DataTable().ajax.reload(null,
                                false);
                        } else {

                            iziToast.success({
                                title: 'Error',
                                message: 'Gagal memperbarui status',
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
