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

<section class="section profile">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="{{ asset($data->foto) }}" alt="Profile" class="rounded-circle" style="aspect-ratio: 1/1; object-fit: cover;">
                    <h2 class="text-capitalize">{{ $data->nama }}</h2>
                    <p class="card-text mb-0 text-capitalize">
                        {{ $data->users->jabatan->jabatan }} -
                        @if ($data->users->status == '1')
                        Aktif
                        @else
                        Non-Aktif
                        @endif
                    </p>
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
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Update
                                Profile</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#akun-edit">Update
                                Akun</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#profile-change-password">Update Password</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-2">
                        <div class="tab-pane fade show active profile-overview text-capitalize" id="profile-overview">
                            <h5 class="card-title">Profile Details</h5>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label ">Nama</div>
                                <div class="col-lg-9 col-md-8 text-capitalize">: {{ $data->nama }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label ">NIK</div>
                                <div class="col-lg-9 col-md-8 text-capitalize">: {{ $data->nik }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Email</div>
                                <div class="col-lg-9 col-md-8">: <span class="text-lowercase">{{ $data->users->email }}</span></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">No HP</div>
                                <div class="col-lg-9 col-md-8">: {{ $data->no_hp }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">JK</div>
                                <div class="col-lg-9 col-md-8">: {{ $data->jk }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Jabatan</div>
                                <div class="col-lg-9 col-md-8">: {{ $data->users->jabatan->jabatan }}</div>
                            </div>

                        </div>
                        <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                            <form id="formUpdateProfile" enctype="multipart/form-data" data-id="{{$data->id}}">
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
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nama</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="nama" type="text" class="form-control" id="fullName"
                                            value="{{ $data->nama }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="nik" class="col-md-4 col-lg-3 col-form-label">NIK</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="nik" type="text" class="form-control" id="nik"
                                            value="{{ $data->nik }}" required>
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
                                            <option value="L"
                                                {{ $data->jk == 'L' ? 'selected' : '' }}>Laki-laki
                                            </option>
                                            <option value="P"
                                                {{ $data->jk == 'P' ? 'selected' : '' }}>Perempuan
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
                        <div class="tab-pane fade profile-edit pt-3" id="akun-edit">
                            <form id="formUpdateAkun" enctype="multipart/form-data" data-id="{{ $data->users->id }}">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="email" class="form-control" id="Email"
                                            value="{{ $data->users->email }}" required>
                                    </div>
                                </div>
                                @if (Auth::user()->jabatan->jabatan != 'admin')
                                <div class="row">
                                    <label class="col-md-4 col-lg-3 col-form-label">Jabatan</label>
                                    <div class="col-md-8 col-lg-9 text-capitalize">: {{ $data->users->jabatan->jabatan }}</div>
                                    <input type="hidden" name="jabatan_id" value="{{ $data->users->jabatan_id }}">
                                </div>
                                @else
                                <div class="row mb-3">
                                    <label for="jabatan" class="col-md-4 col-lg-3 col-form-label">Jabatan</label>
                                    <div class="col-md-8 col-lg-9">
                                        <select name="jabatan_id" id="jabatan" class="form-select text-capitalize" required>
                                            <option disabled>Pilih Jabatan</option>
                                            @foreach ($jabatan as $item)
                                            <option class="text-capitalize" value="{{ $item->id }}"
                                                {{ $data->users->jabatan_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->jabatan }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-md-4 col-lg-3 col-form-label">Status</label>
                                    <div class=" col-md-8 col-lg-9 text-capitalize">
                                        <input class="form-check-input" type="checkbox" id="gridCheck2" name="status" value="1" {{ $data->users->status == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gridCheck2">
                                            @if ($data->users->status == '1')
                                            Aktif
                                            @else
                                            Non-Aktif
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                @endif

                                <div class="text-center">
                                    <button id="button-update-akun" type="submit" class="btn btn-purple">
                                        Simpan
                                        <span id="loader-update-akun" class="spinner-border spinner-border-sm d-none"
                                            role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade pt-3" id="profile-change-password">

                            <form id="formChangePassword" data-id="{{ $data->users->id }}">
                                @csrf
                                <div class="row mb-3">
                                    <label for="yourPassword" class="col-md-4 col-lg-3 col-form-label">Password
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
                                    <label for="yourPassword2" class="col-md-4 col-lg-3 col-form-label">Password
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
                                    <label for="yourPassword3" class="col-md-4 col-lg-3 col-form-label">Ulangi
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
                                <p class="text-purple fst-italic" style="font-size: 14px;">*Panjang password minimal 8
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
@push('scripts')
<script src="{{ asset('assets/js/main2.js') }}"></script>
@endpush
<script>
    $(document).ready(function() {

        const statusCheckbox = $('#gridCheck2');
        const statusLabel = statusCheckbox.next('label');

        // Simpan status awal supaya bisa dibandingkan
        const initialStatus = statusCheckbox.prop('checked');

        statusCheckbox.on('change', function(e) {
            e.preventDefault();

            let isChecked = $(this).prop('checked');
            let message = isChecked ?
                "Apakah Anda yakin ingin mengaktifkan akun ini?" :
                "Apakah Anda yakin ingin menonaktifkan akun ini?";

            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Update label sesuai status baru
                    statusLabel.text(isChecked ? 'Aktif' : 'Non-Aktif');
                    $('#formUpdateAkun').submit();
                } else {
                    // Kembalikan checkbox ke status awal jika batal
                    statusCheckbox.prop('checked', !isChecked);
                }
            });
        });

        $('#formUpdateProfile').on('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const id = $(form).data('id');

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
                error: function(response) {

                    let errors = response.responseJSON?.errors;
                    let errorMessages = "";

                    const fieldTranslations = {
                        nik: {
                            "validation.unique": "NIK sudah digunakan",
                            "validation.required": "NIK wajib diisi"
                        }
                    };

                    if (errors && Object.keys(errors).length > 0) {
                        errorMessages = Object.entries(errors)
                            .map(([field, messages]) => {
                                return messages.map(msgKey => {
                                    return fieldTranslations[field]?.[msgKey] ?? msgKey;
                                }).join('\n');
                            })
                            .join('\n');
                    } else if (response.responseJSON?.message) {
                        errorMessages = response.responseJSON.message;
                    } else {
                        errorMessages = "Terjadi kesalahan tidak diketahui.";
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMessages
                    });
                },
                complete: function() {
                    $('#button-update').prop('disabled', false);
                    $('#loader-update').addClass('d-none');
                }
            });
        });
        $('#formUpdateAkun').on('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const id = $(form).data('id');

            $('#button-update-akun').prop('disabled', true);
            $('#loader-update-akun').removeClass('d-none');

            $.ajax({
                url: `/admin/${id}/update-akun`,
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
                error: function(response) {
                    let errors = response.responseJSON?.errors;
                    let errorMessages = "";
                    const fieldTranslations = {
                        email: {
                            "validation.unique": "Email sudah digunakan",
                            "validation.required": "Email wajib diisi",
                            "validation.email": "Format email tidak valid"
                        },
                    };

                    if (errors && Object.keys(errors).length > 0) {
                        errorMessages = Object.entries(errors)
                            .map(([field, messages]) => {
                                return messages.map(msgKey => {
                                    return fieldTranslations[field]?.[msgKey] ?? msgKey;
                                }).join('\n');
                            })
                            .join('\n');
                    } else if (response.responseJSON?.message) {
                        errorMessages = response.responseJSON.message;
                    } else {
                        errorMessages = "Terjadi kesalahan tidak diketahui.";
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMessages
                    });
                },
                complete: function() {
                    $('#button-update-akun').prop('disabled', false);
                    $('#loader-update-akun').addClass('d-none');
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