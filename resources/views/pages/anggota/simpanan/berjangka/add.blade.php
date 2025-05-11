@extends('layouts.dashboard-layout')
@section('title', 'Simpanan-Berjangka')
@section('content')
<div class="content-background">
    <!-- Error Display -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header -->
    <div class="header d-flex align-items-center mb-4">
        <i class="fas fa-arrow-left mr-3" style="cursor: pointer;" onclick="goBackToHome(this)"
            data-roles="{{ auth()->user()->roles }}"></i>
        <h2>Simpanan Berjangka</h2>
    </div>

    <!-- Form -->

    <form action="{{ route('payment.store') }}" method="POST" id="simpanan-berjangka-form">
        @csrf

        <!-- Jumlah Transfer -->
        <div class="form-group">
            <label for="nominal">Jumlah Transfer (IDR)</label>
            <input type="text" id="nominal" name="nominal" class="form-control" placeholder="Masukan Nominal"
                value="{{ old('nominal') }}" required>
        </div>
 {{-- <form id="simpanan-form" action="{{route('midtrans')}}" method="POST">

            @csrf

            <!-- Jumlah Transfer -->
            @if ($simpananPokok)
    <div class="form-group">
        <label for="nominal">Jumlah Transfer (IDR)</label>
        <input type="hidden" name="anggota_id" value="{{Auth::user()->anggota_id}}">
        <input type="hidden" name="simpanan_id" value="{{$simpanan_id}}">
        <input type="hidden" name="jenis" value="pokok">
        <input type="hidden" name="amount" value="{{$simpananPokok->nominal}}">
        <input type="text" id="nominal" name="nominal" class="form-control"
               value="Rp. {{ number_format($simpananPokok->nominal, 2) }}" readonly>
    </div> --}}
        <!-- Jangka Waktu -->
        <div class="form-group">
            <label for="jangka_waktu">Jangka Waktu (Bulan)</label>
            <select id="jangka_waktu" name="jangka_waktu" class="form-control" required>
                <option value="">Pilih Jangka Waktu</option>
                <option value="3" {{ old('jangka_waktu') == 3 ? 'selected' : '' }}>3 Bulan</option>
                <option value="6" {{ old('jangka_waktu') == 6 ? 'selected' : '' }}>6 Bulan</option>
                <option value="12" {{ old('jangka_waktu') == 12 ? 'selected' : '' }}>12 Bulan</option>
                <option value="24" {{ old('jangka_waktu') == 24 ? 'selected' : '' }}>24 Bulan</option>
            </select>
        </div>

        <!-- Jumlah Jasa -->
        <div class="form-group">
            <label for="jumlah_jasa_perbulan">Jumlah Jasa (IDR)</label>
            <input type="text" id="jumlah_jasa_perbulan" name="jumlah_jasa_perbulan" class="form-control"
                placeholder="Jumlah Jasa Akan Dihitung Otomatis" readonly>
        </div>

        <!-- Pilihan Bank -->
        <div class="form-group">
            <label for="bank">Pilih Bank</label>
            <select id="bank" name="bank" class="form-control" required>
                <option value="">Pilih Bank</option>
                <option value="BRI" {{ old('bank') == 'BANK BRI' ? 'selected' : '' }}>BANK BRI</option>
                <option value="BNI" {{ old('bank') == 'BANK BNI' ? 'selected' : '' }}>BANK BNI</option>
                <option value="BCA" {{ old('bank') == 'BANK BCA' ? 'selected' : '' }}>BANK BCA</option>
                <option value="MANDIRI" {{ old('bank') == 'BANK MANDIRI' ? 'selected' : '' }}>BANK MANDIRI</option>
            </select>
        </div>

        <!-- Tombol Kirim -->
        <button type="submit" class="btn btn-primary mt-4">LANJUTKAN</button>
    </form>
</div>

<!-- Modal Pop-Up -->
<div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="responseModalLabel">Informasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="responseMessage">
                <!-- Pesan akan diisi oleh JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Navigasi Kembali -->
<script>
    function goBackToHome(element) {
        const roles = element.getAttribute('data-roles');
        let route = '';

        if (roles === 'anggota') {
            route = "{{ route('home-anggota') }}";
        } else if (roles === 'admin') {
            route = "{{ route('home-admin') }}";
        }

        if (route) {
            window.location.href = route;
        }
    }
</script>

<!-- Script untuk Format Input Jumlah dan Perhitungan Jasa -->
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Format nominal dan hitung bunga saat input
    document.querySelectorAll('#nominal').forEach((element) => {
        element.addEventListener('input', function (e) {
            let value = e.target.value.replace(/[^0-9]/g, ''); // Hapus non-digit
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format ribuan
            e.target.value = 'Rp. ' + value;

            // Hitung jasa otomatis
            const nominal = parseInt(value.replace(/\D/g, '')) || 0;
            const bungaPerTahun = 0.05;
            const jangkaWaktu = parseInt(document.getElementById('jangka_waktu').value) || 0;

            if (jangkaWaktu > 0 && nominal > 0) {
                const jasaPerBulan = (nominal * bungaPerTahun) / 12;
                document.getElementById('jumlah_jasa_perbulan').value = 'Rp. ' + jasaPerBulan.toLocaleString('id-ID');
            } else {
                document.getElementById('jumlah_jasa_perbulan').value = '';
            }
        });
    });

    // Hitung jasa saat pilih jangka waktu
    document.getElementById('jangka_waktu').addEventListener('change', function () {
        const nominalField = document.getElementById('nominal');
        const nominal = parseInt(nominalField.value.replace(/\D/g, '')) || 0;
        const bungaPerTahun = 0.05;
        const jangkaWaktu = parseInt(this.value) || 0;

        if (jangkaWaktu > 0 && nominal > 0) {
            const jasaPerBulan = (nominal * bungaPerTahun) / 12;
            document.getElementById('jumlah_jasa_perbulan').value = 'Rp. ' + jasaPerBulan.toLocaleString('id-ID');
        } else {
            document.getElementById('jumlah_jasa_perbulan').value = '';
        }
    });

    // Submit form pakai fetch & Swal
    document.getElementById('simpanan-berjangka-form').addEventListener('submit', function (e) {
        e.preventDefault();

        // Bersihkan format Rp.
        document.querySelectorAll('#nominal, #jumlah_jasa_perbulan').forEach((element) => {
            element.value = element.value.replace(/[^0-9]/g, '');
        });

        const form = e.target;
        const formData = new FormData(form);
        const url = form.action;

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => {
            if (response.status === 201) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Terima Kasih, Simpanan Berjangka Anda Sedang Diajukan. Mohon Menunggu Persetujuan.',
                });
            } else if (response.status === 202) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pengajuan Tertunda',
                    text: 'Mohon Maaf, Anda Masih Memiliki Pengajuan yang Belum Disetujui.',
                });
            } else if (response.status === 203) {
                window.location.href = "{{ route('hasil.simpanan') }}";
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan, silakan coba lagi.',
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Gagal mengirim permintaan: ' + error.message,
            });
        });
    });
</script>

@endsection
