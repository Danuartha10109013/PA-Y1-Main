@extends('layouts.dashboard-layout')
@section('title', 'Verifikasi Penarikan')

@section('content')
<div class="content-background">
    <div class="container-scroller">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Verifikasi Penarikan</h3>
                    </div>
                    <div class="card-body">
                        <p>Anda akan menarik dana sebesar: <strong>Rp {{ number_format($jumlahPenarikan, 0, ',', '.') }}</strong></p>
                        @php
                        $labelType = $type === 'sukarela' ? 'Simpanan Sukarela' : ($type === 'berjangka' ? 'Simpanan Berjangka' : '');
                    @endphp
                        <p>Dari <strong>Rekening {{$labelType}}</strong></p>
                        <p>ke Rekening Tujuan <strong>{{$bank}}</strong></p>
                        <form action="{{ route('penarikan.ajukan') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jumlah" value="{{ $jumlahPenarikan }}">

                            <input type="hidden" name="type" value="{{ $type  }}">
                            <input type="hidden" name="bank" value="{{ $bank  }}">
                            <button type="submit" class="btn btn-success btn-block">Konfirmasi Penarikan</button>
                        </form>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block mt-2">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
