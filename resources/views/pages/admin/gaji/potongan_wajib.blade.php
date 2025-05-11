@if (auth()->user()->roles == 'admin')
    @extends('layouts.dashboard-layout')
    @section('title', $title)
    @section('content')
        <div class="content-background" style="background: white">
            <h3>Potongan Gaji Pinjaman</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead style="background-color: #EEEEEE;">
                        <tr>
                            <th>No</th>
                            <th>Nama Anggota</th>
                            <th>Pembayaran Bulan Ini</th>
                            <th>Tanggal Pembayaran</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anggota as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->nama }}</td>
                            <td>
                                @php
                                    $bulanIni = \Carbon\Carbon::now()->month;
                                    $tahunIni = \Carbon\Carbon::now()->year;

                                    // Simpanan bulan ini
                                    $simpananBulanIni = \App\Models\SimpananWajib::where('anggota_id', $data->id)
                                        ->whereMonth('created_at', $bulanIni)
                                        ->whereYear('created_at', $tahunIni)
                                        ->first();

                                    // Simpanan yang pending selain bulan ini
                                    $simpananPendingLain = \App\Models\SimpananWajib::where('anggota_id', $data->id)
                                        ->where('status_pembayaran', 'pending')
                                        ->where(function ($query) use ($bulanIni, $tahunIni) {
                                            $query->whereMonth('created_at', '!=', $bulanIni)
                                                ->orWhereYear('created_at', '!=', $tahunIni);
                                        })
                                        ->get();
                                @endphp

                                {{-- Tampilkan status bulan ini --}}
                                @if ($simpananBulanIni)
                                    {{ $simpananBulanIni->status_pembayaran }}
                                @else
                                    Waiting List
                                @endif

                                {{-- Tambahkan info bulan lain yang pending --}}
                                @if ($simpananPendingLain->isNotEmpty())
                                    <br>
                                    @foreach ($simpananPendingLain as $pending)
                                    <a href="{{ route('input.potongan.gaji.create.sw', $pending->id) }}">
                                        <span class="text-danger">Belum bulan {{ \Carbon\Carbon::parse($pending->created_at)->translatedFormat('F') }}</span><br>
                                    </a>
                                    @endforeach
                                @endif
                            </td>

                            <td>
                                {{ $simpananBulanIni->tanggal_pembayaran ?? '' }}
                            </td>

                            <td>
                                @if (!empty($simpananBulanIni->image))
                                    <img src="{{ asset('storage/' . $simpananBulanIni->image) }}" alt="image" style="width: 50px;">
                                @endif
                            </td>

                            <td class="action-icons">
                                <a href="#">
                                    <i class="fa fa-solid fa-eye"></i>
                                </a>
                                @if ($simpananBulanIni)
                                    <a href="{{ route('input.potongan.gaji.create.sw', $simpananBulanIni->id) }}">
                                        <i class="fa fa-solid fa-edit"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    @endsection
@endif
