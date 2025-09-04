@extends('layout.layout')
@section('title', 'Profile ')
@section('content')
    <style>
        #passwordIcon {
            font-size: 1.2rem;
        }

        .input-group {
            position: relative;
        }

        #showHidePassword,
        #showHidePassword2,
        #showHidePassword3 {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background-color: transparent;
        }
    </style>
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Administrator</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ asset($data->foto) }}" alt="Profile" class="rounded-circle" style="aspect-ratio: 1/1; object-fit: cover;">
                        <h2 class="text-capitalize">{{ $data->name }}</h2>
                        <p class="card-text text-capitalize">{{ $data->jabatan->jabatan }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#profile-overview">Overview</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                                    Profile</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#profile-change-password">Ubah Password</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-2">
                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">Profile Details</h5>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div class="col-lg-9 col-md-8 text-capitalize">{{ $data->name }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $data->email }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">No HP</div>
                                    <div class="col-lg-9 col-md-8">{{ $data->no_hp }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">JK</div>
                                    <div class="col-lg-9 col-md-8">{{ $data->jk }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Jabatan</div>
                                    <div class="col-lg-9 col-md-8">{{ $data->jabatan->jabatan }}</div>
                                </div>

                            </div>
                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                <form id="formUpdateProfile" enctype="multipart/form-data" data-id="{{ $data->id }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row align-items-center mb-3">
                                        <label for="" class="col-md-4 col-lg-3 col-form-label">Image</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="row align-items-center">
                                                <div class="col-3">
                                                    <img src="{{ asset($data->foto) }}" class="w-100 shadow" alt=""
                                                        id="gam">
                                                </div>
                                                <div class="col-9">
                                                    <div class="input-group mb-3 ">
                                                        <input type="file" class="form-control" name="foto"
                                                            id="input-type-file" style="border-radius: 5px 0 0 5px;"
                                                            aria-label="file example" onchange="readUrl(this)">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-purple "
                                                                style="border-radius: 0 5px 5px 0;" type="button"
                                                                onclick="hapusGambar()"><i
                                                                    class="ri ri-delete-bin-6-line"></i></button>
                                                        </div>
                                                    </div>
                                                    <div id="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="name" type="text" class="form-control" id="fullName"
                                                value="{{ $data->name }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control" id="Email"
                                                value="{{ $data->email }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="no_hp" class="col-md-4 col-lg-3 col-form-label">No HP</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="no_hp" type="text" class="form-control" id="no_hp"
                                                value="{{ $data->no_hp }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="jk" class="col-md-4 col-lg-3 col-form-label">JK</label>
                                        <div class="col-md-8 col-lg-9">
                                            <select name="jk" id="jk" class="form-select" required>
                                                <option value="" disabled>Pilih JK</option>
                                                <option value="Laki-laki"
                                                    {{ $data->jk == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                                </option>
                                                <option value="Perempuan"
                                                    {{ $data->jk == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                                </option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="text-center">
                                        <button id="button-update" type="submit" class="btn btn-purple">
                                            Simpan
                                            <span id="loader-update" class="spinner-border spinner-border-sm d-none"
                                                role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade pt-3" id="profile-change-password">

                                <form id="formChangePassword" data-id="{{ $data->id }}">
                                    @csrf
                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Password
                                            Lama</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="input-group">
                                                <input type="password" name="password" class="form-control"
                                                    id="yourPassword" required>
                                                <button id="showHidePassword" type="button"
                                                    class="btn btn-secondary text-dark"><i id="passwordIcon"
                                                        class="bi bi-eye-slash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Password
                                            Baru</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="input-group">
                                                <input type="password" name="newpassword" class="form-control"
                                                    id="yourPassword2" required>
                                                <button id="showHidePassword2" type="button"
                                                    class="btn btn-secondary text-dark"><i id="passwordIcon2"
                                                        class="bi bi-eye-slash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Ulangi
                                            Password Baru</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="input-group">
                                                <input type="password" name="renewpassword" class="form-control"
                                                    id="yourPassword3" required>
                                                <button id="showHidePassword3" type="button"
                                                    class="btn btn-secondary text-dark"><i id="passwordIcon3"
                                                        class="bi bi-eye-slash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-danger fst-italic" style="font-size: 14px;">*Panjang password minimal 8
                                        karakter</p>
                                    <div class="text-center">
                                        <button id="button-password" type="submit" class="btn btn-purple">
                                            Ubah Password
                                            <span id="loader-password" class="spinner-border spinner-border-sm d-none"
                                                role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            
            $('#formUpdateProfile').on('submit', function(e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const id = $(form).data('id'); // pastikan ada data-id di form-nya

                $('#button-update').prop('disabled', true);
                $('#loader-update').removeClass('d-none');

                $.ajax({
                    url: `/admin/${id}/update-profile`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat menyimpan data.'
                    });
                    },
                    complete: function() {
                        $('#button-update').prop('disabled', false);
                        $('#loader-update').addClass('d-none');
                    }
                });
            });
            $('#formChangePassword').on('submit', function(e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const id = $(form).data('id');

                const newPassword = formData.get('newpassword');
                const confirmPassword = formData.get('renewpassword');

                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Cocok',
                        text: 'Password baru dan konfirmasi harus sama.'
                    });
                    return;
                }

                $('#button-password').prop('disabled', true);
                $('#loader-password').removeClass('d-none');

                $.ajax({
                    url: `/admin/${id}/change-password`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            form.reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal mengubah password.'
                        });
                    },
                    complete: function() {
                        $('#button-password').prop('disabled', false);
                        $('#loader-password').addClass('d-none');
                    }
                });
            });


        });
        document.addEventListener("DOMContentLoaded", function() {
            const passwordInput = document.getElementById("yourPassword");
            const passwordIcon = document.getElementById("passwordIcon");

            document.getElementById("showHidePassword").addEventListener("click", function() {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    passwordIcon.classList.remove("bi-eye-slash");
                    passwordIcon.classList.add("bi-eye");
                } else {
                    passwordInput.type = "password";
                    passwordIcon.classList.remove("bi-eye");
                    passwordIcon.classList.add("bi-eye-slash");
                }
            });
            const passwordInput2 = document.getElementById("yourPassword2");
            const passwordIcon2 = document.getElementById("passwordIcon2");
            document.getElementById("showHidePassword2").addEventListener("click", function() {
                if (passwordInput2.type === "password") {
                    passwordInput2.type = "text";
                    passwordIcon2.classList.remove("bi-eye-slash");
                    passwordIcon2.classList.add("bi-eye");
                } else {
                    passwordInput2.type = "password";
                    passwordIcon2.classList.remove("bi-eye");
                    passwordIcon2.classList.add("bi-eye-slash");
                }
            });
            const passwordInput3 = document.getElementById("yourPassword3");
            const passwordIcon3 = document.getElementById("passwordIcon3");
            document.getElementById("showHidePassword3").addEventListener("click", function() {
                if (passwordInput3.type === "password") {
                    passwordInput3.type = "text";
                    passwordIcon3.classList.remove("bi-eye-slash");
                    passwordIcon3.classList.add("bi-eye");
                } else {
                    passwordInput3.type = "password";
                    passwordIcon3.classList.remove("bi-eye");
                    passwordIcon3.classList.add("bi-eye-slash");
                }
            });
        });
    </script>
@endsection
