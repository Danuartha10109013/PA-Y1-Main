@if (auth()->user()->hasRole('admin'))
@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')

<div class="content-background">
    <!-- Search Bar and Add Button -->
    <div class="search-bar d-flex align-items-center mb-3">
        <input type="text" placeholder="Search" class="form-control mr-2" style="width: 200px;" id="searchInput" oninput="searchData()"/>
        <button class="btn custom-blue-button ml-2" data-toggle="modal" data-target="#tambahDataModal">Tambah Data</button>
    </div>

    <!-- Modal for Adding New Data -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1" role="dialog" aria-labelledby="tambahDataModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalTitle">Tambah Data Anggota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

               <form id="tambahDataForm" action="{{ route('anggota.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="card-body">
            <table class="table-detail">
                <tr>
                    <td><strong>Nama Anggota:</strong></td>
                    <td><input type="text" name="nama" class="form-control" required maxlength="255"></td>
                </tr>
                <tr>
                    <td><strong>Tempat Lahir:</strong></td>
                    <td><input type="text" name="tempat_lahir" class="form-control" required maxlength="255"></td>
                </tr>
                <tr>
                    <td><strong>Tanggal Lahir:</strong></td>
                    <td><input type="date" name="tgl_lahir" class="form-control" required></td>
                </tr>
                <tr>
                    <td><strong>NIK:</strong></td>
                    <td>
                        <input type="text" name="nik" class="form-control" required maxlength="16"
                            pattern="[0-9]{16}" placeholder="Masukkan 16 digit angka NIK">
                    </td>
                </tr>
                <tr>
                    <td><strong>Email Kantor:</strong></td>
                    <td>
                        <input type="email" name="email_kantor" class="form-control" required maxlength="255"
                            placeholder="email@kantor.com">
                    </td>
                </tr>
                <tr>
                    <td><strong>No Handphone:</strong></td>
                    <td>
                        <input type="text" name="no_handphone" class="form-control" required maxlength="255"
                            placeholder="Masukkan no handphone Anda" pattern="[0-9]*">
                    </td>
                </tr>
                <tr>
                    <td><strong>Alamat Domisili:</strong></td>
                    <td><input type="text" name="alamat_domisili" class="form-control" required maxlength="255"></td>
                </tr>
                <tr>
                    <td><strong>Alamat KTP:</strong></td>
                    <td><input type="text" name="alamat_ktp" class="form-control" required maxlength="255"></td>
                </tr>
                <tr>
                    <td><strong>Simpanan Pokok:</strong></td>
                    <td>
                        <input type="text" name="simpanan_pokok" class="form-control" value="1000000" readonly>
                    </td>
                </tr>
                <tr>
                    <td><strong>Metode Pembayaran:</strong></td>
                    <td>
                        <select name="metode_pembayaran" class="form-control" required>
                            <option value="Pembayaran Manual">Pembayaran Manual</option>
                            {{-- <option value="Potong Gaji Otomatis">Potong Gaji Otomatis</option> --}}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Simpanan Wajib:</strong></td>
                    <td>
                        <input type="text" name="simpanan_wajib" class="form-control" value="50000" readonly>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn custom-blue-button ml-2">Simpan Data</button>
    </div>
</form>


            </div>
        </div>
    </div>

    <!-- Icon Group -->
    <!--<div class="icon-group mt-2 mb-3">
        <button class="icon-btn" title="Print"><i class="fa fa-print"></i></button>
        <button class="icon-btn" title="Upload"><i class="fa fa-upload"></i></button>
        <button class="icon-btn" title="Download"><i class="fa fa-download"></i></button>
        <button class="icon-btn" title="Load"><i class="fa fa-circle-notch"></i></button>
    </div>-->

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tempat Lahir</th>
                    <th>Tgl Lahir</th>
                    <th>NIK</th>
                    <th>Email Kantor</th>
                    <th>No Handphone</th>
                    <th>Alamat Domisili</th>
                    <th>Alamat KTP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($anggota as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->nama }}</td> <!-- Tambah Nama -->
                    <td>{{ $data->tempat_lahir }}</td>
                    <td>{{ $data->tgl_lahir }}</td>
                    <td>{{ $data->nik }}</td>
                    <td>{{ $data->email_kantor }}</td>
                    <td>{{ $data->no_handphone }}</td>
                    <td>{{ $data->alamat_domisili }}</td>
                    <td>{{ $data->alamat_ktp }}</td>
                    <td>
                        @if ($data->status_ketua == 'Diterima')
                            <span class="badge badge-border-success">Diterima Ketua</span>
                        @elseif($data->status_ketua == 'Ditolak')
                            <a href="#" data-toggle="modal" data-target="#rejectionMessageModal" data-message="{{ $data->alasan_ditolak }}" onclick="rejectionMessageModal(this)">
                                <span class="badge badge-border-danger">Ditolak Ketua</span>
                            </a>
                        @elseif($data->status_bendahara == 'Diterima' )
                            <span class="badge badge-border-warning">Menunggu Approval Ketua</span>
                        @elseif($data->status_bendahara == 'Ditolak')
                            <a href="#" data-toggle="modal" data-target="#rejectionMessageModal" data-message="{{ $data->alasan_ditolak }}" onclick="rejectionMessageModal(this)">
                                <span class="badge badge-border-danger">Ditolak Bendahara</span>
                            </a>
                        @elseif($data->status_manager == 'Diterima' )
                            <span class="badge badge-border-warning">Menunggu Approval Bendahara</span>
                        @elseif($data->status_manager == 'Ditolak')
                            <a href="#" data-toggle="modal" data-target="#rejectionMessageModal" data-message="{{ $data->alasan_ditolak }}" onclick="rejectionMessageModal(this)">
                                <span class="badge badge-border-danger">Ditolak Manager</span>
                            </a>
                        @else
                            <span class="badge badge-border-warning">Pengajuan</span>
                        @endif
                    </td>
                    <td>
                        <a href="#" class="action-icons" data-toggle="modal" data-target="#detailModal{{$data->id}}"><i class="fas fa-eye"></i></a>
                        <a href="#" class="action-icons" data-toggle="modal" data-target="#editModal{{ $data->id }}"><i class="fas fa-edit edit"></i></a>
                        @php
                            $users_id = \App\Models\User::where('anggota_id', $data->id)->value('id');
                            $user = \App\Models\User::find($users_id);
                        @endphp

@if ($user && $user->active == 1)
<!-- Tombol Nonaktifkan -->
<a href="#" class="action-icons btn-nonaktifkan-user text-danger"
   data-id="{{ $user->id }}"
   data-name="{{ $user->name }}">
    <i class="fas fa-ban"></i>
</a>
@elseif ($user && $user->active == 0)
<!-- Tombol Aktifkan -->
<a href="#" class="action-icons btn-aktifkan-user text-success"
   data-id="{{ $user->id }}"
   data-name="{{ $user->name }}">
    <i class="fas fa-check"></i>
</a>
@else
<!-- Jika $user null -->
<span class="text-muted">User tidak ditemukan</span>
@endif

                        <style>
                            /* Atur ukuran pop-up agar lebih luas */
                            .swal-wide {
                            width: 400px !important;
                        }

                        /* Perbesar ukuran ikon */
                        .swal-icon-size {
                            font-size: 1rem !important;
                        }
                    </style>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const baseAktifkanUrl = "{{ route('anggota.aktifkan', ['id' => '__ID__']) }}";
                                const baseNonaktifkanUrl = "{{ route('anggota.nonaktif', ['id' => '__ID__']) }}";

                                document.querySelectorAll('.btn-aktifkan-user').forEach(button => {
                                    button.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        const userId = this.getAttribute('data-id');
                                        const userName = this.getAttribute('data-name');

                                        Swal.fire({
                                            title: 'Aktifkan Pengguna?',
                                            text: `Apakah anda ingin mengaktifkan pengguna ${userName}?`,
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonColor: '#198754',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Ya, Aktifkan!',
                                            cancelButtonText: 'Batal',
                                            customClass: {
                                                popup: 'swal-wide', // Tambahkan kelas CSS untuk memperlebar pop-up
                                                icon: 'swal-icon-size' // Atur ukuran ikon agar tidak terpotong
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                const finalUrl = baseAktifkanUrl.replace('__ID__', userId);
                                                window.location.href = finalUrl;
                                            }
                                        });
                                    });
                                });

                                document.querySelectorAll('.btn-nonaktifkan-user').forEach(button => {
                                    button.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        const userId = this.getAttribute('data-id');
                                        const userName = this.getAttribute('data-name');

                                        Swal.fire({
                                            title: 'Nonaktifkan Pengguna?',
                                            text: `Apakah Anda yakin ingin menonaktifkan pengguna ${userName}?`,
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#6c757d',
                                            confirmButtonText: 'Ya, Nonaktifkan!',
                                            cancelButtonText: 'Batal',
                                            customClass: {
                                                popup: 'swal-wide', // Tambahkan kelas CSS untuk memperlebar pop-up
                                                icon: 'swal-icon-size' // Atur ukuran ikon agar tidak terpotong
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                const finalUrl = baseNonaktifkanUrl.replace('__ID__', userId);
                                                window.location.href = finalUrl;
                                            }
                                        });
                                    });
                                });
                            });
                            </script>



                    </td>
                </tr>
                <!-- Detail Modal -->
                @endforeach
            </tbody>
        </table>
    </div>
    @foreach ($anggota as $data)
    <div class="modal fade" id="detailModal{{$data->id}}" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Anggota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table-detail">
                        <tr>
                            <td><strong>Nama Anggota:</strong></td>
                            <td>{{ $data->nama }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tempat Lahir:</strong></td>
                            <td>{{ $data->tempat_lahir }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Lahir:</strong></td>
                            <td>{{ $data->tgl_lahir }}</td>
                        </tr>
                        <tr>
                            <td><strong>NIK:</strong></td>
                            <td>{{ $data->nik }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email Kantor:</strong></td>
                            <td>{{ $data->email_kantor }}</td>
                        </tr>
                        <tr>
                            <td><strong>No Handphone:</strong></td>
                            <td>{{ $data->no_handphone }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alamat Domisili:</strong></td>
                            <td>{{ $data->alamat_domisili }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alamat KTP:</strong></td>
                            <td>{{ $data->alamat_ktp }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @foreach ($anggota as $data)
    <!-- Modal Edit Anggota -->
    <div class="modal fade" id="editModal{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Anggota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('anggota.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama Anggota:</label>
                            <input type="text" class="form-control" name="nama" value="{{ $data->nama }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir:</label>
                            <input type="text" class="form-control" name="tempat_lahir" value="{{ $data->tempat_lahir }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tgl_lahir">Tanggal Lahir:</label>
                            <input type="date" class="form-control" name="tgl_lahir" value="{{ $data->tgl_lahir }}" required>
                        </div>
                        <div class="form-group">
    <label for="nik">NIK:</label>
    <input type="text" class="form-control" name="nik" id="nik"
           value="{{ $data->nik }}" required
           pattern="[0-9]{16}" maxlength="16"
           placeholder="Masukkan 16 digit angka NIK"
           title="NIK harus terdiri dari 16 digit angka">
</div>
                        <div class="form-group">
    <label for="email_kantor">Email Kantor:</label>
    <input type="email" class="form-control" name="email_kantor" id="email_kantor"
           value="{{ $data->email_kantor }}" required maxlength="255"
           placeholder="email@kantor.com"
           pattern="^[^@\s]+@[^@\s]+\.[^@\s]+$"
           title="Format email tidak valid, gunakan format email@kantor.com">
</div>
                        <div class="form-group">
                            <label for="no_handphone">No Handphone:</label>
                            <input type="text" class="form-control" name="no_handphone" value="{{ $data->no_handphone }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat_domisili">Alamat Domisili:</label>
                            <input type="text" class="form-control" name="alamat_domisili" value="{{ $data->alamat_domisili }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat_ktp">Alamat KTP:</label>
                            <input type="text" class="form-control" name="alamat_ktp" value="{{ $data->alamat_ktp }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn custom-blue-button ml-2">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach



</div>

{{-- <div class="modal fade" id="rejectionMessageModal" tabindex="-1" role="dialog" aria-labelledby="rejectionMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectReasonModalLabel">Alasan Penolakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="rejectionMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div> --}}


@endsection
@endif

