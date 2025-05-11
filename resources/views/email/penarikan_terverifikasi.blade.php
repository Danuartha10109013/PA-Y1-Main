<!DOCTYPE html>
<html>
<head>
    <title>Penarikan Dana Diverifikasi</title>
</head>
<body>
    <h3>Penarikan Telah Diverifikasi</h3>
    <p>Halo {{ $users->name }},</p>

    <p>Penarikan dana Anda dengan detail berikut telah diverifikasi:</p>

    <ul>
        <li><strong>No. Penarikan:</strong> {{ $penarikan->no_penarikan }}</li>
        <li><strong>Jumlah:</strong> Rp{{ number_format($penarikan->nominal, 0, ',', '.') }}</li>
        <li><strong>Bank Tujuan:</strong> {{ $penarikan->bank }}</li>
        <li><strong>Status:</strong> {{ ucfirst($penarikan->status) }}</li>
    </ul>

    <p>Saldo Anda sedang diproses dan akan segera dikirim ke rekening tujuan.</p>

    <p>Terima kasih.</p>
</body>
</html>
