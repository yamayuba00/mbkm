@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
                <img src="{{ asset('assets/mbkm-137.png') }}" alt="logo" class="logo-fix">
                <img src="{{ asset('assets/logoupb.png') }}" alt="logo" class="logo-fix">
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h4>Login</h4>
                </div>

                <div class="card-body">
                    <form id="form-login" method="POST" action="{{ route('login.post') }}" class="needs-validation"
                        novalidate="">
                        @csrf
                        <div id="login-error" class="alert alert-danger d-none"></div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control" name="email" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password" class="control-label">Password</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" id="btn-login">Login</button>
                        </div>
                    </form>

                    <div class="mt-5 text-muted text-center">
                        Don't have an account? <a href="{{ route('register') }}">Create One</a> <br> 
                        Forget your password?
                        <a href="{{ route('forgot-password') }}">Forgot Password</a>
                    </div>

                </div>
            </div>

            <div class="mt-5 text-muted text-center">
                {{-- <div class="simple-footer">MBKM UPB</div> --}}
                <div class="simple-footer">Copyright &copy; {{ date('Y')}} Access Dev Tech 💖</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#form-login').submit(function(e) {
                e.preventDefault();

                $('#btn-login').prop('disabled', true).text('Logging in...');
                $('#login-error').addClass('d-none').html('');

                $.ajax({
                    url: "{{ route('login.post') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            iziToast.success({
                                title: 'Success',
                                message: 'Login successful, redirecting!',
                                position: 'topRight'
                            });
                            window.location.replace(response.redirect);
                        }
                    },
                    error: function(xhr) {

                        let message = 'Login Failed, check your email and password.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        $('#login-error').removeClass('d-none').html(message);
                        $('#btn-login').prop('disabled', false).text('Login');
                    }
                });
            });
        });
    </script>
@endpush
