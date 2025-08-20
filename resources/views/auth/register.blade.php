@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="login-brand">
                <img src="{{ asset('assets/mbkm-137.png') }}" alt="logo" class="logo-fix">
                <img src="{{ asset('assets/logoupb.png') }}" alt="logo" class="logo-fix">
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h4>Register</h4>
                    <span>Please fill in your details (Required)</span>
                </div>

                <div class="card-body">
                    <form method="POST" autocomplete="off">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="username">Username</label>
                                <input id="username" type="text" class="form-control" name="username" autofocus>
                            </div>
                            <div class="form-group col-6">
                                <label for="nim">Nim</label>
                                <input id="nim" type="text" class="form-control" name="nim" autofocus>
                            </div>

                            <div class="form-group col-6">
                                <label for="frist_name">Gender</label>
                                <select class="form-control" name="gender">
                                    <option value="">-- Choose Gender --</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>

                            <div class="form-group col-6">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control" name="email">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-6">
                                <label for="class">Classes</label>
                                <input id="class" type="text" class="form-control" name="class">
                            </div>
                            <div class="form-group col-6">
                                <label for="phone">Phone</label>
                                <input id="phone" type="text" class="form-control" name="phone"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="15">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group col-6">
                                <label for="password" class="d-block">Password</label>
                                <input id="password" type="password" class="form-control pwstrength"
                                    data-indicator="pwindicator" name="password">
                            </div>
                            <div class="form-group col-6">
                                <label for="password2" class="d-block">Password Confirmation</label>
                                <input id="password2" type="password" class="form-control" name="password-confirm">
                            </div>
                            <div class="form-group col-12">
                                <label for="lecturer_id" class="d-block">Lecturer</label>
                                <select id="lecturer_id" name="lecturer_id" class="form-control w-100">
                                    {{-- <option value="">-- Choose a lecturer --</option> --}}
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="faculties_id" class="d-block">Faculty</label>
                                <select id="faculties_id" name="faculties_id" class="form-control select2-faculty">
                                    {{-- <option value="">-- Choose a lecturer --</option> --}}
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="prodi_id" class="d-block">Prodi</label>
                                <select id="prodi_id" name="prodi_id" class="form-control select2-prodi">
                                    {{-- <option value="">-- Choose a lecturer --</option> --}}
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                Register Account
                            </button>
                        </div>
                        <div class="text-muted text-center">
                            Already have an account? <a href="{{ route('login') }}">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // get lecturers
        $(document).ready(function() {
            $('#lecturer_id').select2({
                placeholder: '-- Choose a lecturer --',
                width: '100%',

                ajax: {
                    url: '{{ route('lecturers.get') }}', // endpoint API kamu
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data.data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.username + ' (' + item.nidn + ')'
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });
        $(document).ready(function() {
            $('#faculties_id').select2({
                placeholder: '-- Select Faculty --',
                width: '100%',

                ajax: {
                    url: '{{ route('faculties.get') }}', // endpoint API kamu
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
            $('#prodi_id').select2({
                placeholder: '-- Select Faculty --',
                width: '100%',

                ajax: {
                    url: function() {
                        const facultyId = $('#faculties_id').val();
                        return '/prodi/' + facultyId;
                    },
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data.map(prodi => ({
                                id: prodi.id,
                                text: prodi.name
                            }))
                        };
                    }
                }
            });
        });
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = {
                username: document.querySelector('#username').value,
                nim: document.querySelector('#nim').value,
                gender: document.querySelector('[name="gender"]').value,
                email: document.querySelector('#email').value,
                class: document.querySelector('#class').value,
                phone: document.querySelector('#phone').value,
                password: document.querySelector('#password').value,
                password_confirmation: document.querySelector('#password2').value,
                lecturer_id: document.querySelector('#lecturer_id').value
            };

            fetch("{{ route('register.post') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify(formData)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.redirect) {
                        iziToast.success({
                            title: 'Success',
                            message: 'Registration successful!',
                            position: 'topRight'
                        });
                        setTimeout(() => {

                            window.location.href = data.redirect;
                        }, 1000);
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: data.message || 'Registration failed.',
                            position: 'topRight'
                        });
                    }
                })
                .catch(err => {
                    iziToast.error({
                        title: 'Error',
                        message: 'Something went wrong.',
                        position: 'topRight'
                    });
                });
        });
    </script>
@endpush
