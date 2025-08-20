@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>

        <div class="section-body">
            {{-- Welcome Card --}}
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>🎓 Welcome to Your MBKM Dashboard!</h4>
                        </div>
                        <div class="card-body">
                            <p id="dashboard-intro"></p>

                            <button class="btn btn-primary" id="btn-register-mbkm-program">
                                <i class="fas fa-graduation-cap"></i> MBKM Program Registration
                            </button>
                            <button class="btn btn-info">
                                <i class="fas fa-tasks"></i> Track Your MBKM Program
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Information + Lecturer --}}
            <div class="row">
                {{-- Info Program --}}
                <div class="col-md-7 col-sm-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Latest Information Program</h4>
                            <div class="card-header-action">
                                <a href="#" class="btn btn-primary">View All</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border list-unstyled-noborder" id="information-programs">
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Side Panel --}}
                <div class="col-md-5 col-sm-12">
                    {{-- Presentation Info --}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h4>Presentation Type and Location</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border list-unstyled-noborder" id="presentation-info">
                            </ul>

                        </div>
                    </div>

                    {{-- Lecturer Info --}}
                    <div class="card card-info mt-3">
                        <div class="card-header">
                            <h4>Your Lecturer</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group" id="lecturer-info"></ul>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary btn-block">
                                <i class="fas fa-user"></i> Submit Lecturer Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#btn-register-mbkm-program').on('click', function() {
                window.location.replace('{{ route('student.mbkm-program.index') }}');
            });

            $.ajax({
                url: '{{ route('student.dashboard.data') }}',
                method: 'GET',
                success: function(response) {
                    const {
                        lecturer,
                        information,
                        welcome_text,
                        mbkm_program
                    } = response.data;
                    console.log(mbkm_program[0]);
                    $('#dashboard-intro').text(welcome_text);
                    if (lecturer) {
                        $('#lecturer-info').html(`
                        <li class="list-group-item"><strong>Name:</strong> ${lecturer.name}</li>
                        <li class="list-group-item"><strong>NIDN:</strong> ${lecturer.nidn}</li>
                        <li class="list-group-item"><strong>Email:</strong> ${lecturer.email}</li>
                        <li class="list-group-item"><strong>Phone:</strong> ${lecturer.phone}</li>
                    `);
                    } else {
                        $('#lecturer-info').html('<p>No lecturer assigned.</p>');
                    }

                    // Info Program
                    if (information.length > 0) {
                        information.forEach(info => {
                            $('#information-programs').append(`
                            <li class="media">
                                <div class="media-body">
                                    <div class="media-title mb-1">${info.title}</div>
                                    <div class="text-time">${info.created_at}</div>
                                    <div class="media-description text-muted">${info.content}</div>
                                    <div class="media-links">
                                        <span>${info.created_by}</span>
                                    </div>
                                </div>
                            </li>
                        `);
                        });
                    } else {
                        $('#information-programs').append('<p>No information programs available.</p>');
                    }

                    // Presentation Info
                    if (mbkm_program[0]) {
                        let program = mbkm_program[0];

                        $('#presentation-info').append(`
                            <li class="media">
                                <div class="media-body">
                                    <div class="media-title mb-1">${program.title}</div>
                                    <div class="text-time">${program.submission_period_id} - ${program.submission_type_id}</div>
                                    <div class="media-description text-muted">${program.validated_at} - ${program.approval_status}</div>
                                    <div class="media-links">
                                         ${
                                            program.reason
                                                ? `<a href="${program.reason}" target="_blank" class="btn btn-primary w-100 text-white">
                                                        <i class="fas fa-link"></i> Click Link Meet
                                                </a>`
                                                : `<button class="btn btn-secondary w-100 text-white" disabled>
                                                    Link not sent yet
                                                    </button>`
                                        }
                                    </div>
                                </div>
                            </li>
                        `);
                    }
                },
                error: function() {
                    $('#lecturer-info').html('<p>Error fetching lecturer data.</p>');
                    $('#information-programs').html('<p>Error fetching information programs.</p>');
                }
            });
        });
    </script>
@endpush
