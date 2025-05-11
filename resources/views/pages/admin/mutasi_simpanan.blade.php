@extends('layouts.dashboard-layout')
@section('title', $title)

@section('content')
    <div class="content-background">
        <div class="search-bar d-flex align-items-center justify-content-between mb-3">
            <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" />
            <div class="icons">
                <!-- Export buttons with dynamic tab context -->
                <a href="{{ route('export.pdf.simpanan', ['type' => 'sukarela']) }}" class="export-link" id="export-excel-link" data-type="sukarela">
                    <i class="fas fa-file-pdf text-danger"></i>
                </a>
                <a href="{{ route('export.excel.simpanan', ['type' => 'sukarela']) }}" class="export-link" id="export-excel-link" data-type="sukarela">
                    <i class="fas fa-file-excel text-success"></i>
                </a>
            </div>
        </div>

        @php $tabs = ['sukarela', 'berjangka', 'wajib', 'pokok']; @endphp

        <!-- Tabs Header -->
        <ul class="nav nav-tabs" id="simpananTabs" role="tablist">
            @foreach ($tabs as $index => $tab)
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($index == 0) active @endif"
                        id="{{ $tab }}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ $tab }}"
                        type="button"
                        role="tab"
                        aria-controls="{{ $tab }}"
                        aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                        {{ ucfirst($tab) }}
                    </button>
                </li>
            @endforeach
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content mt-3" id="simpananTabsContent">
            @foreach ($tabs as $index => $tab)
                <div class="tab-pane fade @if($index == 0) show active @endif"
                    id="{{ $tab }}"
                    role="tabpanel"
                    aria-labelledby="{{ $tab }}-tab">

                    <h4>Mutasi Simpanan {{ ucfirst($tab) }}</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nomor Invoice</th>
                                <th>Nama</th>
                                <th>Nominal</th>
                                @if ($tab == 'berjangka')
                                    <th>Jangka Waktu</th>
                                    <th>Jumlah Jasa</th>
                                @endif
                                <th>Status</th>
                                <th>Virtual Account</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($$tab as $item)
                                <tr>
                                    <td>
                                        @if ($tab == 'wajib') {{ $item->no_simpanan_wajib }}
                                        @elseif ($tab == 'pokok') {{ $item->no_simpanan_pokok }}
                                        @else {{ $item->no_simpanan }} @endif
                                    </td>
                                    <td>
                                        @php
                                            $name = $item->anggota_id
                                                ? \App\Models\Anggota::where('id', $item->anggota_id)->value('nama')
                                                : \App\Models\User::where('id', $item->user_id)->value('name');
                                        @endphp
                                        {{ $name }}
                                    </td>
                                    <td>Rp. {{ number_format($item->nominal, 2) }}</td>
                                    @if ($tab == 'berjangka')
                                        <td>{{ $item->jangka_waktu }} Bulan</td>
                                        <td>Rp. {{ number_format($item->jumlah_jasa_perbulan, 2) }}</td>
                                    @endif
                                    <td>
                                        {{ ucfirst($tab == 'pokok' || $tab == 'wajib' ? $item->status_pembayaran : $item->status_payment) }}
                                    </td>
                                    <td>{{ $item->virtual_account ?? 'Tidak Ada' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end">
                        {{ $$tab->links() }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // Update export link when tab is changed
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.addEventListener('click', function () {
                let type = this.dataset.bsTarget.replace('#', '');

                // Update the Excel export link dynamically
                let excelLink = document.getElementById('export-excel-link');
                let excelRoute = excelLink.href;
                excelLink.href = excelRoute.replace(/type=([^&]*)/, `type=${type}`);
                excelLink.setAttribute('data-type', type);
            });
        });
    </script>



    <style>
        svg .w-5 {
          display: none;
        }
        .hidden{
          display: none;
        }
      </style>

        

    <style>
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

        .search-bar {
            margin-top: 20px;
        }
    </style>
@endsection
