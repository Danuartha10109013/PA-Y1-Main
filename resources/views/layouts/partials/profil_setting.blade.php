@extends('layouts.dashboard-layout')
@section('title', $title)

@section('content')
    <div class="d-flex mt-3">
        <i class="fas fa-arrow-left mr-3 mt-1" style="cursor: pointer;" data-role="{{ Auth::user()->roles }}"
            onclick="goBackToHome(this)"></i>
        <h3 class="">Settings</h3>
    </div>
    <div class="container-fluid p-3"> <!-- Menggunakan container-fluid dan padding yang lebih baik -->
        <div class="row">
            <div class="col-md-5 col-xl-4">

                <div class="card">
                    <div class="card-header">

                        <h5 class="card-title mb-0">Profile Settings</h5>
                    </div>

                    <div class="list-group list-group-flush" role="tablist">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account"
                            role="tab">
                            Account
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#password"
                            role="tab">
                            Password
                        </a>
                        @if (Auth::user()->roles === 'anggota')
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#virtualAcount"
                                role="tab">
                                Rekening
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-7 col-xl-8">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="account" role="tabpanel">

                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions float-right">
                                    
                                </div>
                                <h5 class="card-title mb-0">Profil  Anda</h5>
                            </div>
                                @php
                                    $dataAnggota = \App\Models\Anggota::find(Auth::user()->anggota_id);
                                    // dd($dataAnggota);
                                @endphp
                            @if (Auth::user()->roles == 'anggota')
                            <div class="card-body">
                                <form action="{{ route('profile.update',$dataAnggota->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <!-- Nama -->
                                            <div class="form-group">
                                                <label for="inputNama">Nama</label>
                                                <input type="text" class="form-control" name="nama" id="inputNama"
                                                    value="{{ old('nama', $dataAnggota->nama) }}" required>
                                            </div>

                                            <!-- Email -->
                                            <div class="form-group">
                                                <label for="inputEmail">Email Kantor</label>
                                                <input type="email" class="form-control" name="email_kantor" id="inputEmail"
                                                    value="{{ old('email_kantor', $dataAnggota->email_kantor) }}" required>
                                            </div>

                                            <!-- No Handphone -->
                                            <div class="form-group">
                                                <label for="inputPhone">No. Handphone</label>
                                                <input type="text" class="form-control" name="no_handphone" id="inputPhone"
                                                    value="{{ old('no_handphone', $dataAnggota->no_handphone) }}">
                                            </div>

                                            <!-- Tempat Lahir -->
                                            <div class="form-group">
                                                <label for="inputTempatLahir">Tempat Lahir</label>
                                                <input type="text" class="form-control" name="tempat_lahir" id="inputTempatLahir"
                                                    value="{{ old('tempat_lahir', $dataAnggota->tempat_lahir) }}">
                                            </div>

                                            <!-- Tanggal Lahir -->
                                            <div class="form-group">
                                                <label for="inputTglLahir">Tanggal Lahir</label>
                                                <input type="date" class="form-control" name="tgl_lahir" id="inputTglLahir"
                                                    value="{{ old('tgl_lahir', $dataAnggota->tgl_lahir) }}">
                                            </div>

                                            <!-- Alamat Domisili -->
                                            <div class="form-group">
                                                <label for="inputAlamatDomisili">Alamat Domisili</label>
                                                <textarea class="form-control" name="alamat_domisili" id="inputAlamatDomisili" rows="2">{{ old('alamat_domisili', $dataAnggota->alamat_domisili) }}</textarea>
                                            </div>

                                            <!-- Alamat KTP -->
                                            <div class="form-group">
                                                <label for="inputAlamatKTP">Alamat KTP</label>
                                                <textarea class="form-control" name="alamat_ktp" id="inputAlamatKTP" rows="2">{{ old('alamat_ktp', $dataAnggota->alamat_ktp) }}</textarea>
                                            </div>

                                            <!-- NIK -->
                                            <div class="form-group">
                                                <label for="inputNIK">NIK</label>
                                                <input type="text" class="form-control" name="nik" id="inputNIK"
                                                    value="{{ old('nik', $dataAnggota->nik) }}" maxlength="16">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <!-- Avatar -->
                                                <img id="avatarPreview" 
                                                    src="{{ $dataAnggota->avatar_url ?? 'https://via.placeholder.com/128' }}"
                                                    alt="Profile picture" class="rounded-circle" width="128" height="128">

                                                <div class="mt-2">
                                                    <label class="btn btn-primary">
                                                        <i class="fa fa-upload"></i> Upload
                                                        <input type="file" name="avatar" id="avatarInput" hidden accept="image/*">
                                                    </label>
                                                </div>
                                                <small>Gunakan gambar minimal 128x128px dalam format .jpg</small>
                                            </div>
                                        </div>
                                        <script>
                                            document.getElementById('avatarInput').addEventListener('change', function (event) {
                                                const file = event.target.files[0];
                                                if (file) {
                                                    const reader = new FileReader();
                                                    reader.onload = function (e) {
                                                        document.getElementById('avatarPreview').src = e.target.result;
                                                    };
                                                    reader.readAsDataURL(file);
                                                }
                                            });
                                            </script>

                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                                </form>


                            </div>
                            @else
                            <div class="card-body">
                                <form action="{{ route('profile.update',Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="inputUsername">Username</label>
                                                <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}"
                                                    id="inputUsername" placeholder="Username">
                                            </div>
                                            <div class="form-group">
                                                <label for="inputUsername">Email</label>
                                                <!-- <textarea rows="2" class="form-control" id="inputBio" value="{{ Auth::user()->email }}"
                                                    placeholder="Tell something about yourself"></textarea> -->
                                                <input type="text" class="form-control" value="{{ Auth::user()->email }}"
                                                    id="inputEmail" name="email" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <!-- Avatar -->
                                                <img id="avatarPreview" 
                                                    src="{{ Auth::user()->avatar_url ?? 'https://via.placeholder.com/128' }}"
                                                    alt="Profile picture" class="rounded-circle" width="128" height="128">

                                                <div class="mt-2">
                                                    <label class="btn btn-primary">
                                                        <i class="fa fa-upload"></i> Upload
                                                        <input type="file" name="avatar" id="avatarInput" hidden accept="image/*">
                                                    </label>
                                                </div>
                                                <small>Gunakan gambar minimal 128x128px dalam format .jpg</small>
                                            </div>
                                        </div>
                                        <script>
                                            document.getElementById('avatarInput').addEventListener('change', function (event) {
                                                const file = event.target.files[0];
                                                if (file) {
                                                    const reader = new FileReader();
                                                    reader.onload = function (e) {
                                                        document.getElementById('avatarPreview').src = e.target.result;
                                                    };
                                                    reader.readAsDataURL(file);
                                                }
                                            });
                                        </script>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>

                                </form>
                            </div>
                            @endif
                        </div>



                    </div>
                    <div class="tab-pane fade" id="password" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Password</h5>

                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                <form action="{{ route('forgot-password', ['id' => auth()->user()->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="form-group position-relative">
                                        <label for="current_password">Current password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="current_password"
                                                name="current_password" required>
                                            <span class="input-group-text">
                                                <i class="bi bi-eye-slash toggle-password" data-target="current_password"
                                                    style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                        <small><a href="#">Forgot your password?</a></small>
                                        @error('current_password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group position-relative">
                                        <label for="new_password">New password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" required>
                                            <span class="input-group-text">
                                                <i class="bi bi-eye-slash toggle-password" data-target="new_password"
                                                    style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                        @error('new_password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group position-relative">
                                        <label for="new_password_confirmation">Verify password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_password_confirmation"
                                                name="new_password_confirmation" required>
                                            <span class="input-group-text">
                                                <i class="bi bi-eye-slash toggle-password"
                                                    data-target="new_password_confirmation" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                    </div>


                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- <-- Virtual Account --> --}}
                    @if (Auth::user()->roles === 'anggota')
                        {{-- <-- Virtual Account --> --}}
                        <div class="tab-pane fade" id="virtualAcount" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead style="background-color: #EEEEEE;">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Bank</th>
                                            <th>Rekening</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($virtualAccounts as $key => $virtualAccount)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $virtualAccount->nama_bank }}</td>
                                                <td>{{ $virtualAccount->virtual_account_number }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Rekening</h5>

                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    <form action="{{ route('virtual-account', ['id' => auth()->user()->id]) }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-group position-relative">
                                            <label for="nama_bank">Nama Bank</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="nama_bank"
                                                    name="nama_bank" required>
                                            </div>
                                            @error('nama_bank')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group position-relative">
                                            <label for="virtual_account_number">No Rekening</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="virtual_account_number"
                                                    name="virtual_account_number" required>
                                            </div>
                                            @error('virtual_account_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>

    </div>
    <script>
        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetField = document.getElementById(targetId);
                const isPassword = targetField.getAttribute('type') === 'password';

                targetField.setAttribute('type', isPassword ? 'text' : 'password');
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });
    </script>
    <script>
        function goBackToHome(element) {
            // Get the user's role from the data-role attribute
            const roles = element.getAttribute('data-role');
            let route = '';

            // Determine the route based on the user's role
            if (roles === 'anggota') {
                route = "{{ route('home-anggota') }}";
            } else if (roles === 'manager') {
                route = "{{ route('home.manager') }}";
            } else if (roles === 'ketua') {
                route = "{{ route('home-ketua') }}";
            } else if (roles === 'admin') {
                route = "{{ route('home-admin') }}";
            }

            // Redirect to the determined route
            if (route) {
                window.location.href = route;
            }
        }
    </script>


@endsection
