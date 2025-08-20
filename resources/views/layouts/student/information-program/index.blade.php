@extends('layouts.master')

@section('title', 'Information Program')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Information Program</h1>
        </div>

        <div class="section-body">
            
            <table class="table table-bordered w-100" id="information-program-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Created By</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>

@endsection

@push('scripts')

    <script>
        $(document).ready(function() {
            $('#information-program-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('student.information-program.data') }}',
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
                
                ]
            });
        });

        
    </script>
@endpush
