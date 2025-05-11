@if (auth()->user()->roles == 'bendahara')
@extends('layouts.dashboard-layout')
@section('title', $title)
@section('content')
<div class="content-background" style="background: white">

    <div class="search-bar d-flex align-items-center">
        <input type="text" id="search-input" placeholder="Search" class="form-control mr-2" style="width: 200px;" />

        <!-- Filter Status Dropdown -->
        <select id="status-filter" class="form-control mr-2" style="width: 220px;">
            <option value="">🔍 Filter Status...</option>
            <option value="Diterima Ketua">Diterima Ketua</option>
            <option value="Ditolak Ketua">Ditolak Ketua</option>
            <option value="Ditolak Bendahara">Ditolak Bendahara</option>
            <option value="Ditolak Manager">Ditolak Manager</option>
            <option value="Menunggu Approve Ketua">Menunggu Approve Ketua</option>
            <option value="Pengajuan">Pengajuan</option>
        </select>

        <div class="ml-auto d-flex">
            <button type="button" class="btn btn-success" onclick="updateStatusSimpanan('approved')">Terima</button>
            <button type="button" class="btn btn-danger" onclick="updateStatusSimpanan('rejected')">Tolak</button>
        </div>
        @csrf
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById('search-input');
            const statusFilter = document.getElementById('status-filter');
            const tableBody = document.getElementById('table-body');
            const rows = tableBody.querySelectorAll('tr');

            function filterAndSearch() {
                const searchQuery = searchInput.value.toLowerCase();
                const statusQuery = statusFilter.value.toLowerCase();

                rows.forEach(row => {
                    const rowData = row.textContent.toLowerCase();
                    const matchSearch = rowData.includes(searchQuery);
                    const matchStatus = statusQuery === "" || rowData.includes(statusQuery);
                    row.style.display = matchSearch && matchStatus ? '' : 'none';
                });
            }

            // Listener kolom pencarian
            searchInput.addEventListener('input', filterAndSearch);

            // Listener dropdown status
            statusFilter.addEventListener('change', filterAndSearch);

            // Inisialisasi: tampilkan semua
            filterAndSearch();
        });
        </script>
    <div class="table-responsive">
        <select id="perPage" class="form-control w-auto mb-3" onchange="changePerPage()">
            <option value="5">5 baris</option>
            <option value="10" selected>10 baris</option>
            <option value="20">20 baris</option>
        </select>
        <table class="table table-bordered">
            <thead bgcolor="EEEEEE">
                <tr>
                    <th>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="select-all">
                            <label class="custom-control-label" for="select-all"></label>
                        </div>
                    </th>
                    <th>No Simpanan</th>
                    <th>Nama</th>
                    <th>Nominal</th>
                    <th>Bank</th>
                    <th>Rekening Simpanan</th>
                    <th>Status Payment</th>
                    <th>Virtual Account</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="table-body">
                      @foreach ($simpananSukarelas as $key => $data)
    <tr>
        <td>
            <div class="custom-control custom-checkbox">
                @if ($data->rekeningSimpananSukarela) <!-- Pastikan relasi tidak null -->
                    <input type="checkbox" class="custom-control-input checkbox-item" id="checkbox-{{ $data->rekeningSimpananSukarela->id }}" data-id="{{ $data->rekeningSimpananSukarela->id }}">
                    <label class="custom-control-label" for="checkbox-{{ $data->rekeningSimpananSukarela->id }}"></label>
                @else
                    <span>Data rekening tidak ditemukan</span>
                @endif
            </div>
        </td>
        <td>{{ $data->no_simpanan }}</td>
        <td>{{ $data->user->name }}</td>
        <td>Rp. {{ number_format($data->nominal, 2) }}</td>
        <td>{{ $data->bank }}</td>
        <td>{{ $data->rekeningSimpananSukarela->status ?? 'N/A' }}</td>
        <td>{{ $data->status_payment }}</td>
        <td>{{ $data->virtual_account ?? 'N/A' }}</td>
        <td>
            @if ($data->rekeningSimpananSukarela->approval_ketua == 'approved')
                <span class="badge badge-border-success">Diterima Ketua</span>
            @elseif ($data->rekeningSimpananSukarela->approval_ketua == 'rejected')
                <span class="badge badge-border-danger">Ditolak Ketua</span>
            @elseif ($data->rekeningSimpananSukarela->approval_bendahara == 'approved')
                <span class="badge badge-border-warning">Menunggu Approve Ketua</span>
            @elseif ($data->rekeningSimpananSukarela->approval_bendahara == 'rejected')
                <span class="badge badge-border-danger">Ditolak Bendahara</span>
            @elseif ($data->rekeningSimpananSukarela->approval_manager == 'approved')
                <span class="badge badge-border-warning">Menunggu Approve Bendahara</span>
            @elseif ($data->rekeningSimpananSukarela->approval_manager == 'rejected')
                <span class="badge badge-border-danger">Ditolak Manager</span>
            @else
                <span class="badge badge-border-warning">Pengajuan</span>
            @endif
        </td>
        <td class="action-icons">
            <a href="{{ route('simpanan.sukarela.detail', $data->no_simpanan) }}" style="z-index: 9999; position: relative;">
                <i class="fas fa-eye text-success"></i>
            </a>
        </td>
    </tr>
@endforeach
            </tbody>
        </table>
    </div>
    <ul class="pagination" id="pagination">
        <!-- Ini nanti diisi otomatis via JS -->
    </ul>
    <script>
        const rows = [...document.querySelectorAll('#table-body tr')];
        const pagination = document.getElementById('pagination');
        const perPageSelect = document.getElementById('perPage');

        let currentPage = 1;
        let perPage = parseInt(perPageSelect.value);

        function paginateData() {
            const totalRows = rows.length;
            const totalPages = Math.ceil(totalRows / perPage);
            const start = (currentPage - 1) * perPage;
            const end = start + perPage;

            // Tampilkan hanya baris yang sesuai halaman
            rows.forEach((row, index) => {
                row.style.display = index >= start && index < end ? '' : 'none';
            });

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            pagination.innerHTML = '';

            // Prev
            const prevClass = currentPage === 1 ? ' disabled' : '';
            pagination.innerHTML += `
                <li class="page-item${prevClass}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">&laquo;</a>
                </li>
            `;

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const active = currentPage === i ? ' active' : '';
                pagination.innerHTML += `
                    <li class="page-item${active}">
                        <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                    </li>
                `;
            }

            // Next
            const nextClass = currentPage === totalPages ? ' disabled' : '';
            pagination.innerHTML += `
                <li class="page-item${nextClass}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">&raquo;</a>
                </li>
            `;
        }

        function changePage(page) {
            const totalPages = Math.ceil(rows.length / perPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            paginateData();
        }

        function changePerPage() {
            perPage = parseInt(perPageSelect.value);
            currentPage = 1;
            paginateData();
        }

        // Inisialisasi
        window.onload = paginateData;
    </script>
</div>
<style>
    .swal-wide {
        width: 500px !important;
    }

    /* Perbesar ukuran ikon */
    .swal-icon-size {
        font-size: 1rem !important;
        /* margin-top: 20px; */
    }
</style>
<script>
function updateStatusSimpanan(status) {
    console.log("Status yang diterima: ", status); // Debugging status

    const confirmMessage = status === 'approved' ?
        "Apakah anda yakin ingin menyetujui anggota ini?" :
        "Apakah anda yakin ingin menolak anggota ini?";

    const successMessage = status === 'approved' ?
        "Anggota telah disetujui." :
        "Anggota telah ditolak.";

    Swal.fire({
        title: "Approve Anggota?",
        text: confirmMessage,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
        customClass: {
                popup: 'swal-wide', // Tambahkan kelas CSS untuk memperlebar pop-up
                icon: 'swal-icon-size' // Atur ukuran ikon agar tidak terpotong
            }
    }).then((result) => {
        if (result.isConfirmed) {
            const selectedCheckboxes = document.querySelectorAll('.checkbox-item:checked');
            if (selectedCheckboxes.length === 0) {
                Swal.fire("Warning!", "Pilih setidaknya satu anggota!", "warning");
                return;
            }

            selectedCheckboxes.forEach(checkbox => {
                const id = checkbox.getAttribute('data-id');
                console.log("ID yang dikirim: ", id); // Debugging ID

                $.ajax({
                    url: "{{ route('status.simpanan.sukarela.bendahara', ['id' => 'ID', 'status' => 'STATUS']) }}"
                        .replace('ID', id)
                        .replace('STATUS', status),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log("Response sukses: ", response); // Debugging respons
                        Swal.fire({
                            title: "Success!",
                            text: successMessage,
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.log("Response error: ", xhr); // Debugging error
                        Swal.fire("Error!", `Terjadi kesalahan: ${xhr.responseText}`, "error");
                    }
                });
            });
        }
    });
}

</script>

<Script>
    document.getElementById('select-all').addEventListener('change', function() {
        var isChecked = this.checked;
        var checkboxes = document.querySelectorAll('.checkbox-item');

        checkboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });

    // Event listener untuk mengambil ID dari checkbox yang dipilih
    document.querySelectorAll('.checkbox-item').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var id = this.dataset.id;
            if (this.checked) {
                console.log('Checkbox with ID ' + id + ' is checked.');
            } else {
                console.log('Checkbox with ID ' + id + ' is unchecked.');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Fetch count for All
        $.ajax({
            url: '/bendahara/count-data-simpanan-sukarela/all',
            type: 'GET',
            success: function(response) {
                $('.btn-wrapper .count').eq(0).text(response.count);
            }
        });

        // Fetch count for Terima
        $.ajax({
            url: '/bendahara/count-data-simpanan-sukarela/approved',
            type: 'GET',
            success: function(response) {
                $('.btn-wrapper .count').eq(1).text(response.count);
            }
        });

        // Fetch count for Pengajuan (Belum Diterima)
        $.ajax({
            url: '/bendahara/count-data-simpanan-sukarela/pending',
            type: 'GET',
            success: function(response) {
                $('.btn-wrapper .count').eq(2).text(response
                    .count); // Update angka di tombol "Belum Diterima"
            }
        });


        // Fetch count for Ditolak
        $.ajax({
            url: '/bendahara/count-data-simpanan-sukarela/rejected',
            type: 'GET',
            success: function(response) {
                $('.btn-wrapper .count').eq(3).text(response.count);
            }
        });
    });

    function filterdata(status) {
        // Lakukan filter berdasarkan status
        $.ajax({
            url: `/data/filter/${status}`,
            type: 'GET',
            success: function(response) {
                // Update tabel dengan data yang difilter
                $('tbody').html(response); // Sesuaikan dengan respon yang diberikan dari server
            }
        });
    }
</script>
<script>
    // Misalkan ada 24 data total dan hanya menampilkan 8 per halaman
    const totaldata = 24;
    const perPage = 8;
    let currentPage = 1; // Anda bisa mengubah nilai ini sesuai nomor halaman

    function updateShowingInfo() {
        const start = (currentPage - 1) * perPage + 1;
        const end = Math.min(currentPage * perPage, totaldata);
        document.getElementById('start').innerText = start;
        document.getElementById('end').innerText = end;
        document.getElementById('total').innerText = totaldata;
    }

    // Panggil fungsi saat halaman dimuat atau saat pagination diubah
    updateShowingInfo();
</script>
@endsection
@endif
