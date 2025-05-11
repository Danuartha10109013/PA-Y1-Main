@extends('layouts.dashboard-layout')
@section('title', $title)
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

        <!-- Main Panel -->
        <!-- Header -->
        <div class="header d-flex align-items-center mb-4">
            <i class="fas fa-arrow-left mr-3" style="cursor: pointer;" onclick="goBackToHome(this)"
                data-roles="{{ auth()->user()->roles }}"></i>
            <h2>Simpanan Sukarela</h2>
        </div>
        <!-- Form -->
        <form id="simpanan-form" action="{{ route('payment.store.sukarela') }}" method="POST">
            @csrf

            <!-- Jumlah Transfer -->
            <div class="form-group">
                <label for="nominal">Jumlah Transfer (IDR)</label>
                <input type="text" id="nominal" name="nominal" class="form-control" placeholder="Masukan Nominal"
                    oninput="formatIDR(this, 'raw_amount')" required>
            </div>

             {{-- <form id="simpanan-form" action="{{route('midtrans')}}" method="POST">

            @csrf --}}

            <!-- Jumlah Transfer -->
    {{-- <div class="form-group">
        <label for="nominal">Jumlah Transfer (IDR)</label>
        <input type="hidden" name="anggota_id" value="{{Auth::user()->anggota_id}}">
        <input type="hidden" name="simpanan_id" value="{{$simpanan_id}}">
        <input type="number" id="nominal" name="amount" class="form-control" placeholder="Masukan Nominal">

        <input type="hidden" name="jenis" value="sukarela"> --}}
        {{-- <input type="text" id="nominal" name="nominal" class="form-control" --}}
               {{-- value="Rp. {{ number_format($simpananPokok->nominal, 2) }}" readonly> --}}
    {{-- </div> --}}

            <!-- Pilihan Bank Transfer -->
            <div class="form-group">
                <label for="bank">Pilih Bank Transfer</label>
                <select id="bank" name="bank" class="form-control" required>
                    <option value="BRI">BANK BRI</option>
                    <option value="BNI">BANK BNI</option>
                    <option value="BCA">BANK BCA</option>
                    <option value="MANDIRI">BANK MANDIRI</option>
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

    <!-- Script Format Jumlah Transfer -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('simpanan-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah submit default
    
            const nominalField = document.getElementById('nominal');
            const form = e.target;
            const formData = new FormData(form);
            const url = form.action;
    
            // Hapus format 'Rp.' dan tanda titik sebelum submit
            const rawNominal = nominalField.value.replace(/[^0-9]/g, '');
            formData.set('nominal', rawNominal);
    
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
                        title: 'Berhasil!',
                        text: 'Terima Kasih, Transaksi Anda Sedang Diproses.',
                        confirmButtonColor: '#3085d6',
                    });
                } else if (response.status === 202) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Transaksi Tertunda',
                        text: 'Mohon Maaf, Anda Masih Memiliki Transaksi yang Belum Selesai.',
                        confirmButtonColor: '#3085d6',
                    });
                } else if (response.status === 203) {
                    window.location.href = "{{ route('hasil.simpanan.sukarela') }}";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan, silakan coba lagi.',
                        confirmButtonColor: '#d33',
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal mengirim permintaan: ' + error.message,
                    confirmButtonColor: '#d33',
                });
            });
        });
    
        document.getElementById('nominal').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, ''); // Hanya angka
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Tambahkan titik sebagai pemisah ribuan
            e.target.value = 'Rp. ' + value;
        });
    </script>
    

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
@endsection
