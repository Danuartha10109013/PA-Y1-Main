@extends('layouts.dashboard-layout')
@section('title', 'Verifikasi Penarikan')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded p-4">
        <h4 class="mb-3 text-center">Verifikasi Penarikan Dana</h4>
        @php
            $email = Auth::user()->email;
            $explode = explode('@', $email);
            $username = $explode[0];
            $domain = $explode[1];

            $maskedEmail = substr($username, 0, 2) . str_repeat('*', max(strlen($username) - 3, 0)) . substr($username, -1) . '@' . substr($domain, 0, 3) . '...';
        @endphp

        <center><p>Silahkan periksa email anda: {{ $maskedEmail }}</p></center>

        @if(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session("success") }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session("error") }}',
                    showConfirmButton: true
                });
            });
        </script>
        @endif
        
        <form action="{{ route('penarikan.verifikasi-kode') }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="penarikan_id" value="{{ $penarikan->id }}">
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="form-group mb-3">
                <label for="otp_code">Masukkan Kode OTP</label>
                <input type="text" name="otp_code" id="otp_code" class="form-control text-center @error('otp_code') is-invalid @enderror"
                    placeholder="6 Digit Kode OTP" maxlength="6" required
                    data-url="{{ route('penarikan.verifikasi-kode') }}"
                    data-id="{{ $penarikan->id }}" data-type="{{ $type }}">

                @error('otp_code')
                    <div class="invalid-feedback text-center">{{ $message }}</div>
                @enderror
            </div>

            {{-- Countdown timer --}}
            <div class="text-center mb-3">
                <span id="countdown" class="fw-bold text-danger fs-5"></span>
            </div>

            <div class="d-flex justify-content-center">
                <button class="btn btn-primary px-4" type="submit" id="verifyBtn">Verifikasi</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const verifyBtn = document.getElementById("verifyBtn");
        const countdownElement = document.getElementById("countdown");

        // Get expired time from backend (in seconds)
        const expireTime = new Date("{{ \Carbon\Carbon::parse($penarikan->otp_expired_at)->format('Y-m-d H:i:s') }}").getTime();

        let countdownInterval = setInterval(function () {
            let now = new Date().getTime();
            let distance = expireTime - now;

            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (distance > 0) {
                countdownElement.innerHTML = `Kode OTP kedaluwarsa dalam ${minutes}m ${seconds}s`;
            } else {
                clearInterval(countdownInterval);
                countdownElement.innerHTML = "Kode OTP telah kedaluwarsa!";
                verifyBtn.disabled = true;
            }
        }, 1000);
    });

    document.addEventListener("DOMContentLoaded", function () {
        const otpInput = document.get ElementById("otp_code");

        otpInput.addEventListener("input", function () {
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });
    });
</script>
@endsection