<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Persetujuan</title>
</head>
<body>
    <h1>Pengajuan Anda Telah Disetujui</h1>
    <p>Halo {{ $userName }},</p>
    <p>Pengajuan simpanan sukarela Anda dengan nomor simpanan <strong>{{ $noSimpanan }}</strong> telah disetujui oleh ketua.</p>
    <p>Silakan lakukan pembayaran melalui virtual account berikut:</p>
    <ul>
        {{-- <li>Virtual Account: <strong>{{ $virtualAccount }}</strong></li> --}}
        <li>Batas Waktu Pembayaran: <strong>{{ \Carbon\Carbon::parse($expiredAt)->format('d-m-Y H:i') }}
        </strong></li>
        <li>Link Pembayaran : <strong>http://127.0.0.1:8000/payment/{{$snapToken}}/{{$noSimpanan}}</strong></li>
    </ul>
    <p>Terima kasih.</p>
</body>
</html>
