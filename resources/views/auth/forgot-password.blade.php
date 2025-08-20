@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
                <img src="{{ asset('assets/mbkm-137.png') }}" alt="logo" class="logo-fix">
                <img src="{{ asset('assets/logoupb.png') }}" alt="logo" class="logo-fix">
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h4>Forgot Password</h4>
                </div>

                <div class="card-body">
                    <form id="form-reset" method="POST" action="" class="needs-validation" novalidate="">
                        @csrf

                        <div id="login-error" class="alert alert-danger d-none"></div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control" name="email" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="nim">NIM</label>
                            <input id="nim" type="text" class="form-control" name="nim" required>
                        </div>

                        <div id="password-section" style="display: none;">
                            <div class="form-group">
                                <label for="password">Password Baru</label>
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input id="password_confirmation" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" id="btn-password">Reset
                                Password</button>
                        </div>
                    </form>
                    <div class="mt-5 text-muted text-center">
                        Don't have an account? <a href="{{ route('register') }}">Create One</a> <br>
                        Have an account?
                        <a href="{{ route('login') }}">Login</a>
                    </div>

                </div>
            </div>

            <div class="mt-5 text-muted text-center">
                {{-- <div class="simple-footer">MBKM UPB</div> --}}
                <div class="simple-footer">Copyright &copy; {{ date('Y') }} Access Dev Tech 💖</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#form-reset').on('submit', function(e) {
                e.preventDefault();

                const email = $('#email').val();
                const nim = $('#nim').val();
                const passwordSection = $('#password-section');

                // Jika password belum diisi → validasi email & nim dulu
                if (passwordSection.is(':hidden')) {
                    $.ajax({
                        url: '/reset-password/verify',
                        method: 'POST',
                        data: {
                            _token: $('input[name="_token"]').val(),
                            email: email,
                            nim: nim
                        },
                        success: function(res) {
                            passwordSection.show();
                            iziToast.success({
                                title: 'Verified',
                                message: 'Please enter a new password',
                                position: 'topRight'
                            });
                        },
                        error: function(err) {
                            $('#login-error').removeClass('d-none').text(err.responseJSON
                                .message);
                        }
                    });
                } else {
                    // Tahap submit password baru
                    $.ajax({
                        url: '/reset-password/update',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(res) {
                            iziToast.success({
                                title: 'Berhasil',
                                message: res.message,
                                position: 'topRight'
                            });
                            window.location.href = '/login';
                        },
                        error: function(err) {
                            $('#login-error').removeClass('d-none').text(err.responseJSON
                                .message);
                        }
                    });
                }
            });
        });
    </script>
@endpush
