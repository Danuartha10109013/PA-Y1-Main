@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
    <div class="content-background" style="background: white">
        <h3>{{$title}}</h3>
        <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Nomor Simpanan</th>
                        <td>{{ $penarikan->no_penarikan }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $penarikan->user ? $penarikan->user->name : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Nominal Simpanan</th>
                        <td>Rp. {{ number_format($penarikan->nominal, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Bank</th>
                        <td>{{ $penarikan->bank }}</td>
                    </tr>
                    
                    
                    <tr>
                        <th>Status Penarikan</th>
                        <td>{{$penarikan->status_ketua}}</td>
                    </tr>
                    {{-- <tr>
                        <th>Skor SPK</th>
                        <td>{{ $penarikan->score }}</td>
                    </tr>
                    <tr>
                        <th>Level SPK</th>
                        <td>{{ $penarikan->level }}</td>
                    </tr> --}}
                </table>

        </div>
        @if (auth()->check() && auth()->user()->roles == 'manager')
            <a href="{{ route('home.manager') }}" class="btn btn-primary">Kembali</a>
        @endif

        @if (auth()->check() && auth()->user()->roles == 'bendahara')
            <a href="{{ route('bendahara.index') }}" class="btn btn-primary">Kembali</a>
        @endif

        @if (auth()->check() && auth()->user()->roles == 'ketua')
            <a href="{{ route('home-ketua') }}" class="btn btn-primary">Kembali</a>
        @endif

        @if (auth()->check() && auth()->user()->roles == 'admin')
            <a href="{{ route('home-admin') }}" class="btn btn-primary">Kembali</a>
        @endif

    </div>
@endsection
