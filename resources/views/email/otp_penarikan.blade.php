<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kode OTP - Penarikan {{$penarikan->no_penarikan}}</title>
</head>
<body>

<h2>Informasi Penarikan Dana</h2>

<p>Berikut adalah detail penarikan Anda:</p>

<ul>
    <li><strong>No Penarikan:</strong> {{ $penarikan->no_penarikan }}</li>
    <li><strong>Jumlah:</strong> Rp {{ number_format($penarikan->nominal, 0, ',', '.') }}</li>
    <li><strong>Status:</strong> {{ ucfirst($penarikan->status) }}</li>
    <li><strong>Bank:</strong> {{ $penarikan->bank }}</li>
    <li><strong>Kode OTP:</strong> <strong>{{ $penarikan->otp_code }}</strong></li>
</ul>

<p>Silakan gunakan OTP ini untuk verifikasi penarikan Anda.</p>
</body>
</html>