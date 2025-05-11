@extends('layouts.dashboard-layout')
@section('title', 'Penarikan Simpanan')

@section('content')
<div class="container">
    <h1>Penarikan Simpanan</h1>
    <p>Saldo Simpanan Sukarela Anda: <strong>Rp {{ number_format($sukarela, 0, ',', '.') }}</strong></p>
    <p>Saldo Simpanan Berjangka Anda: <strong>Rp {{ number_format($berjangka, 0, ',', '.') }}</strong></p>
    <!-- Form Penarikan -->
    <!-- Tombol Buka Modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formPenarikanModal">
        Ajukan Penarikan
    </button>
  <!-- Modal -->
<div class="modal fade" id="formPenarikanModal" tabindex="-1" aria-labelledby="formPenarikanLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content p-3">
        <div class="modal-header">
          <h5 class="modal-title" id="formPenarikanLabel">Form Penarikan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('penarikan.verifikasi') }}" method="POST">
              @csrf
  
              <div class="form-group mb-3">
                  <label for="jumlah">Jumlah Penarikan</label>
                  <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Masukkan jumlah (contoh: 100000)" required>
              </div>
  
              <div class="form-group mb-3">
                  <label for="type">Pilih Rekening Simpanan</label>
                  <select name="type" class="form-control" required>
                      <option value="" selected disabled>-- Pilih jenis simpanan --</option>
                      <option value="sukarela">Simpanan Sukarela</option>
                      <option value="berjangka">Simpanan Berjangka</option>
                  </select>
              </div>
              @php
              $user_ids = Auth::user()->id;
              $va = App\Models\VirtualAccount::where('user_id', $user_ids)->get();
          @endphp
          
          <div class="form-group mb-3">
              <label for="bank">Pilih Bank Tujuan</label>
              <select name="bank" class="form-control" required>
                  <option value="" selected disabled>-- Pilih bank tujuan --</option>
                  @foreach ($va as $account)
                      <option value="{{ $account->nama_bank }}-{{ $account->virtual_account_number }}">
                          {{ $account->nama_bank }} - {{ $account->virtual_account_number }}
                      </option>
                  @endforeach
              </select>
          </div>
          
  
  
              <div class="modal-footer p-0 d-flex justify-content-between">
                  <button type="submit" class="btn btn-primary">Ajukan Penarikan</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  

    <br>
    <br>
    <h3>History</h3>

<ul class="nav nav-tabs mb-3" id="penarikanTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="sukarela-tab" data-bs-toggle="tab" data-bs-target="#sukarela" type="button" role="tab" aria-controls="sukarela" aria-selected="true">Sukarela</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="berjangka-tab" data-bs-toggle="tab" data-bs-target="#berjangka" type="button" role="tab" aria-controls="berjangka" aria-selected="false">Berjangka</button>
    </li>
</ul>

<div style="margin-top: -30px" class="tab-content" id="penarikanTabsContent">
    {{-- Tab Sukarela --}}
    <div class="tab-pane fade show active" id="sukarela" role="tabpanel" aria-labelledby="sukarela-tab">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NO Penarikan</th>
                        <th>Bank</th>
                        <th>Nominal</th>
                        <th>Status Manager</th>
                        <th>Status Bendahara</th>
                        <th>Status Ketua</th>
                        <th>Status Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sukarela1 as $data)
                        <tr>
                            <td>{{ $data->no_penarikan }}</td>
                            <td>{{ $data->bank }}</td>
                            <td>Rp {{ number_format($data->nominal, 0, ',', '.') }}</td>
                            <td>{{ $data->status_manager }}</td>
                            <td>{{ $data->status_bendahara }}</td>
                            <td>{{ $data->status_ketua }}</td>
                            @if ($data->otp_code == 'success')
                            <td>Terverifikasi</td>
                            @else
                            <td>Belum Terverifikasi
                                <form action="{{route('verifikasi-ulang')}}" method="POST">
                                    @csrf
                                    <input type="hidden" value="sukarela" name="type">
                                    <input type="hidden" value="{{$data->id}}" name="id">
                                    <button type="submit" style="background: none; border: none; color: #ff0505; padding: 0; font: inherit; cursor: pointer;">
                                        Verifikasi Ulang
                                    </button>
                                    
                                </form>
                            </td>
                            @endif
                            <td>
                                <a href="{{ route('penarikan.sukarela.detail', $data->id) }}" style="z-index: 9999; position: relative;">
                                    <i class="fas fa-eye text-success"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tab Berjangka --}}
    <div class="tab-pane fade" id="berjangka" role="tabpanel" aria-labelledby="berjangka-tab">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NO Penarikan</th>
                        <th>Bank</th>
                        <th>Nominal</th>
                        <th>Status Manager</th>
                        <th>Status Bendahara</th>
                        <th>Status Ketua</th>
                        <th>Status Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($berjangka1 as $data)
                        <tr>
                            <td>{{ $data->no_penarikan }}</td>
                            <td>{{ $data->bank }}</td>
                            <td>Rp {{ number_format($data->nominal, 0, ',', '.') }}</td>
                            <td>{{ $data->status_manager }}</td>
                            <td>{{ $data->status_bendahara }}</td>
                            <td>{{ $data->status_ketua }}</td>
                            @if ($data->otp_code == 'success')
                            <td>Terverifikasi</td>
                            @else
                            <td>Belum Terverifikasi
                                <form action="{{route('verifikasi-ulang')}}" method="POST">
                                    @csrf
                                    <input type="hidden" value="berjangka" name="type">
                                    <input type="hidden" value="{{$data->id}}" name="id">
                                    <button type="submit" style="background: none; border: none; color: #ff0505; padding: 0; font: inherit; cursor: pointer;">
                                        Verifikasi Ulang
                                    </button>
                                    
                                </form>
                            </td>
                            @endif
                            <td>
                                <a href="{{ route('penarikan.berjangka.detail', $data->id) }}" style="z-index: 9999; position: relative;">
                                    <i class="fas fa-eye text-success"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

@endsection
