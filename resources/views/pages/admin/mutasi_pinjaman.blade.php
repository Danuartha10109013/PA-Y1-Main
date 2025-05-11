@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')

<div class="content-background">
    @php
        $tabs = [
            'emergency' => [
                'title' => 'Pinjaman Emergency',
                'data' => $pinjamanEmergency,
                'saldo' => $saldoEmergency,
            ],
            'anggunan' => [
                'title' => 'Pinjaman Anggunan',
                'data' => $pinjamanAngunan,
                'saldo' => $saldoAngunan,
            ],
            'non_anggunan' => [
                'title' => 'Pinjaman Non-Anggunan',
                'data' => $pinjamanNonAngunan,
                'saldo' => $saldoNonAngunan,
            ],
        ];
    @endphp

    <!-- Tabs Header -->
    <ul class="nav nav-tabs" id="pinjamanTabs" role="tablist">
        @foreach ($tabs as $key => $tab)
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($loop->first) active @endif"
                        id="{{ $key }}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ $key }}"
                        type="button"
                        role="tab"
                        aria-controls="{{ $key }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $tab['title'] }}
                </button>
            </li>
        @endforeach
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="pinjamanTabsContent">
        @foreach ($tabs as $key => $tab)
            <div class="tab-pane fade @if($loop->first) show active @endif"
                 id="{{ $key }}"
                 role="tabpanel"
                 aria-labelledby="{{ $key }}-tab">

                <!-- Filter + Export -->
                <form method="GET" action="{{ route('mutasi-pinjaman') }}" class="mt-3 mb-2">
                    <div class="d-flex flex-row">
                        <input type="date" name="start_date" class="form-control me-2" style="width: 200px;"
                            value="{{ request('start_date') }}">
                        <input type="date" name="end_date" class="form-control me-2" style="width: 200px;"
                            value="{{ request('end_date') }}">
                        <input type="hidden" name="jenisPinjaman" value="{{ $key }}">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>

                <div class="icons d-flex flex-row-reverse mb-3 align-items-center">
                    <a href="{{ route('export.pdf', array_merge(request()->only(['start_date', 'end_date']), ['jenisPinjaman' => $key])) }}" class="ms-3">
                        <i class="fas fa-file-pdf fa-lg text-danger"></i>
                    </a>
                    <a href="{{ route('export.excel', array_merge(request()->only(['start_date', 'end_date']), ['jenisPinjaman' => $key])) }}">
                        <i class="fas fa-file-excel fa-lg text-success"></i>
                    </a>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nomor Pinjaman</th>
                                <th>Nama</th>
                                <th>Pokok Pinjaman</th>
                                <th>Tenor</th>
                                <th>Angsuran Pokok</th>
                                <th>Tanggal Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tab['data'] as $data)
                                <tr>
                                    <td>{{ $data->nomor_pinjaman }}</td>
                                    <td>{{ $data->user->name ?? '-' }}</td>
                                    <td>Rp. {{ number_format($data->amount, 2) }}</td>
                                    <td>{{ $data->jangka_waktu }} Bulan</td>
                                    <td>Rp. {{ number_format($data->nominal_angsuran, 2) }}</td>
                                    <td>{{ $data->created_at->format('d-m-Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data pinjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end">
                        {{ $tab['data']->links() }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


    <style>
        svg .w-5 {
          display: none;
        }
        .hidden{
          display: none;
        }
        .icons i {
            font-size: 24px;
            color: #007bff;
            margin-right: 12px;
            transition: color 0.3s ease;
        }

        .icons i:hover {
            color: #007bff;
        }

        .action-icons i {
            font-size: 20px;
            cursor: pointer;
            margin-right: 8px;
            transition: color 0.3s ease;
        }

        .action-icons i.edit {
            color: #007bff;
        }

        .action-icons i.delete {
            color: #dc3545;
        }

        .action-icons i.detail {
            color: #007bff;
        }

        .action-icons i:hover {
            opacity: 0.7;
        }

        h4 {
            margin-bottom: 20px;
            text-align: center;
        }

        .table-responsive {
            overflow-x: auto;
            margin-bottom: 20px;
        }

        .action-icons {
            text-align: center;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }

        .action-icons i {
            cursor: pointer;
            color: #007bff;
            font-size: 18px;
        }

        .action-icons i:hover {
            color: #dc3545;
        }

        .content-background {
            padding: 20px;
        }

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-bar input {
            max-width: 300px;
        }
    </style>
@endsection
