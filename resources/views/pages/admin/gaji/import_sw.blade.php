@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
    <div class="content-background col-md-6">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('potongan.gaji.store', ['uuid' => $pinjamanAktif->uuid, 'month' => $month ]) }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="bukti_pembayaran">Upload Bukti Pemotongan Gaji</label>
                <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" class="form-control @error('bukti_pembayaran') is-invalid @enderror" accept="image/*">
                
                @error('bukti_pembayaran')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-center">
                <a href="{{ route('data.potongan.gaji') }}" class="btn btn-secondary">Batalkan</a>
                <button type="submit" class="btn btn-success">Upload</button>
            </div>
        </form>        
    </div>
@endsection
