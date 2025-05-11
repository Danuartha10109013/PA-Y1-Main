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
            <h2>Simpanan Pokok</h2>
        </div>
        <!-- Form -->
        <form id="simpanan-form" action="{{route('midtrans')}}" method="POST">

            @csrf

            <!-- Jumlah Transfer -->
            @if ($simpananPokok->status_pembayaran != 'success')
            <div class="form-group">
                <label for="nominal">Jumlah Transfer (IDR)</label>
                <input type="hidden" name="anggota_id" value="{{Auth::user()->anggota_id}}">
                <input type="hidden" name="simpanan_id" value="{{$simpanan_id}}">
                <input type="hidden" name="jenis" value="pokok">
                <input type="hidden" name="amount" value="{{$simpananPokok->nominal}}">
                <input type="text" id="nominal" name="nominal" class="form-control"
                    value="Rp. {{ number_format($simpananPokok->nominal, 2) }}" readonly>
            </div>
            <button type="submit" class="btn btn-success mt-4">Bayar</button>
        </form>
    </div>

        {{-- <form id="simpanan-form" action="{{ route('payment.store.pokok') }}" method="POST">
            @csrf

            <!-- Jumlah Transfer -->
            @if ($simpananPokok)
    <div class="form-group">
        <label for="nominal">Jumlah Transfer (IDR)</label>
        <input type="text" id="nominal" name="nominal" class="form-control"
               value="Rp. {{ number_format($simpananPokok->nominal, 2) }}" readonly>
    </div> --}}
    @elseif($simpananPokok->status_pembayaran == 'success')
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nomor Invoice</th>
                        <th>Nama</th>
                        <th>Nominal</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Status</th>
                        <th>Virtual Account</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>
                                {{ $simpananPokok->no_simpanan_pokok }}
                            </td>
                            <td>
                                @php
                                    $name = $simpananPokok->anggota_id
                                        ? \App\Models\Anggota::where('id', $simpananPokok->anggota_id)->value('nama')
                                        : \App\Models\User::where('id', $simpananPokok->user_id)->value('name');
                                @endphp
                                {{ $name }}
                            </td>
                            <td>Rp. {{ number_format($simpananPokok->nominal, 2) }}</td>
                            <td>{{$simpananPokok->updated_at->format('d M Y')}}</td>
                            <td>
                                {{  $simpananPokok->status_pembayaran  }}
                            </td>
                            <td>{{ $simpananPokok->virtual_account ?? 'Tidak Ada' }}</td>
                        </tr>
                </tbody>
            </table>
        </div>
    @else
        <p>Tidak ada data simpanan pokok.</p>
    @endif






            <!-- Pilihan Bank Transfer -->
            {{-- <div class="form-group">
                <label for="bank">Pilih Bank Transfer</label>
                <select id="bank" name="bank" class="form-control" required>
                    <option value="BRI">BANK BRI</option>
                    <option value="BNI">BANK BNI</option>
                    <option value="BCA">BANK BCA</option>
                    <option value="MANDIRI">BANK MANDIRI</option>
                </select>
            </div> --}}

            <!-- Tombol Kirim -->


    <!-- Script Format Jumlah Transfer -->


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
