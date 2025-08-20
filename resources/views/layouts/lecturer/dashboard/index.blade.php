@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>

        <div class="section-body">
            <div class="row" id="dashboard-stats">
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ route('lecturer.dashboard.data') }}',
                method: 'GET',
                success: function(response) {
                    const dashboardData = response.data.dashboardData;

                    dashboardData.forEach(stat => {
                        $('#dashboard-stats').append(`
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-${stat.color}">
                                        <i class="${stat.icon}"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>${stat.title}</h4>
                                        </div>
                                        <div class="card-body">
                                            ${stat.value}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    })

                    // if(latestMbkmPrograms.length > 0) {
                    //     latestMbkmPrograms.forEach(program => {
                    //         $('#latest-programs').append(`
                //             <li class="media">
                //                 <div class="media-body">
                //                     <div class="media-title mb-1">${program.title}</div>
                //                     <div class="text-time">${program.created_at}</div>
                //                     <div class="media-description text-muted">${program.content}</div>
                //                     <div class="media-links">
                //                         <span>${program.created_by}</span>
                //                     </div>
                //                 </div>
                //             </li>
                //         `);
                    //     })
                    // }

                },
                error: function(xhr) {
                    // $('#lecturer-info').html('<p>Error fetching lecturer data.</p>');
                    $('#latest-programs').html('<p>Error fetching information programs.</p>');
                }
            });
        });
        $(document).ready(function() {
            $('#information').on('input', function() {
                const charCount = $(this).val().length;
                $('#char-count').text(`${charCount} / 1000 karakter`);
            });
        });
    </script>
@endpush
