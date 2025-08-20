@extends('layouts.master')

@section('title', 'Information Program')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Information Program</h1>
        </div>

        <div class="section-body">
            <a href="#" class="btn btn-primary mb-3" id="btn-modal">
                <i class="fas fa-plus"></i> Add Information Program
            </a>
            <table class="table table-bordered w-100" id="information-program-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
    @include('layouts.admin.information-program.modal-form')

@endsection

@push('scripts')
    <!-- DataTables -->


    <script>
        $(document).ready(function() {
            $('#information-program-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.information-program.data') }}',
                columns: [{
                        data: 'no',
                        name: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'content',
                        name: 'content',
                    },
                    {
                        data: 'created_by',
                        name: 'created_by',
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
                $('#information-program-id').val('');
                $('#modal-title').text('Add Information Program');
                modal.modal('show');
            });

            form.on('submit', function(e) {
                e.preventDefault();
                const id = $('#information-program-id').val();
                const url = id ? `/admin/information-program/${id}` : `/admin/information-program`;
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
                            message: 'Information Program saved successfully.',
                            position: 'topRight'
                        })
                        $('#information-program-table').DataTable().ajax.reload(null, false);
                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Something went wrong.',
                            position: 'topRight'
                        })
                    }
                });
            });

            $('#information-program-table').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                $.get(`/admin/information-program/${id}/edit`, function(res) {
                    $('#modal-title').text('Edit Information Program');
                    $('#information-program-id').val(res.id);
                    $('#title').val(res.title);
                    $('#content').val(res.content);
                    $('#modal').modal('show');
                });
            });

             $('#content').on('input', function() {
                const charCount = $(this).val().length;
                $('#count-content').text(`${charCount} / 1000 karakter`);
            });

        });
    </script>
@endpush
