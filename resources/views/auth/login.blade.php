@extends('layouts.auth-layout')
@section('title', $title)
@section('content')

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Handle SweetAlert for Errors -->
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal!',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Coba Lagi'
        });
    </script>
@endif

<!-- Handle SweetAlert for Success -->
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
@endif

<section class="p-3 p-md-4 p-xl-5">
    <div class="container">
        <div class="card border-light-subtle shadow-sm">
            <div class="row g-0">
                <div class="col-12 col-md-6 position-relative">
                    <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy"
                        src="{{ asset('skydash/images/Login.jpg') }}" alt="Login Image">
                </div>

                <div class="col-12 col-md-6 position-relative">
                    <button type="button" class="close-button position-absolute top-0 end-0"
                        style="background: none; border: none; font-size: 24px; color: black; padding: 10px;">&times;</button>
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="mb-5">
                            <img src="{{ asset('Landingpace/img/logo.png') }}" class="mb-3 img-fluid"
                                style="max-width: 150px;">
                            <h3>Log in</h3>
                        </div>

                        <form action="{{ route('login-verifikasi') }}" method="POST">
                            @csrf
                            <div class="row gy-3 gy-md-4 overflow-hidden">
                                <div class="col-12">
                                    <label for="email" class="form-label">Email Kantor <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="name@example.com" required autofocus>
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="Masukan password anda" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglesPassword">
                                            <i class="bi bi-eye-slash toggles-password-icon"></i>
                                        </button>
                                    </div>
                                    
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn bsb-btn-xl btn-primary" type="submit">Log in</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-12">
                                <hr class="mt-5 mb-4 border-secondary-subtle">
                                <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end">
                                    <a href="{{ route('register') }}" class="link-secondary text-decoration-none">Create new account</a>
                                    <a href="#" class="link-secondary text-decoration-none">Forgot password?</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> <!-- End Right Side -->
            </div>
        </div>
    </div>
</section>

<!-- Toggle Password Visibility -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('togglesPassword');
        const passwordInput = document.getElementById('password');
        const icon = toggleButton.querySelector('.toggles-password-icon');

        toggleButton.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';

            // Toggle icon class
            icon.classList.remove('bi-eye', 'bi-eye-slash');
            icon.classList.add(isHidden ? 'bi-eye' : 'bi-eye-slash');
        });
    });
</script>



@endsection
