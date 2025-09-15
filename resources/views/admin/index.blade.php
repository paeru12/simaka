@extends('layout.layout')
@section('title', 'Administrator')
@section('content')
<style>
    #passwordIcon {
        font-size: 1.2rem;
    }

    .input-group {
        position: relative;
    }

    #showHidePassword,
    #showHidePassword2 {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background-color: transparent;
    }
</style>
<div class="pagetitle">
    <h1 class="text-capitalize">Administrator</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active"><a href="">Administrator</a></li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0">Data Admin</h5>
                    <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                        Add Admin
                    </button>

                    <div class="modal fade" id="verticalycentered" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="formTambahGuru" class="needs-validation" enctype="multipart/form-data" novalidate>
                                    @csrf
                                    <input type="hidden" name="role" value="guru">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Admin</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="nama" class="form-control" id="name" placeholder="Nama" required>
                                                    <label for="name">Nama</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                                                    <label for="email">Email</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="nik" class="form-control" id="nik" placeholder="NIK" required>
                                                    <label for="nik">NIK</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" name="no_hp" class="form-control" id="no_hp" placeholder="No HP" required>
                                                    <label for="no_hp">No HP</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <select class="form-select" name="jk" id="jk">
                                                        <option selected disabled>Open this select menu</option>
                                                        <option value="L">Laki-laki</option>
                                                        <option value="P">Perempuan</option>
                                                    </select>
                                                    <label for="jk">Jenis Kelamin</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <div class="input-group">
                                                        <div class="form-floating flex-grow-1">
                                                            <input type="password" name="password" class="form-control" id="yourPassword" placeholder="Password" required>
                                                            <label for="yourPassword">Password</label>
                                                        </div>
                                                        <button id="showHidePassword" type="button" class="btn btn-secondary text-dark">
                                                            <i id="passwordIcon" class="bi bi-eye-slash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="invalid-feedback">Please enter your password!</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <div class="input-group">
                                                        <div class="form-floating flex-grow-1">
                                                            <input type="password" name="kpassword" class="form-control" id="yourPassword2" placeholder="Konfirmasi Password" required>
                                                            <label for="yourPassword2">Konfirmasi Password</label>
                                                        </div>
                                                        <button id="showHidePassword2" type="button" class="btn btn-secondary text-dark">
                                                            <i id="passwordIcon2" class="bi bi-eye-slash"></i>
                                                        </button>
                                                    </div>
                                                    <div class="invalid-feedback">Please enter your password!</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="row align-items-center">
                                                <div class="col-3">
                                                    <img src="{{ asset('assets/img/blank.jpg') }}" class="w-100 shadow" alt="" id="gam">
                                                </div>
                                                <div class="col-9">
                                                    <div class="input-group mb-3">
                                                        <input type="file" class="form-control" id="input-type-file" name="foto" aria-label="file example" onchange="readUrl(this)" required>
                                                        <button class="btn btn-purple" type="button" onclick="hapusGambar()">
                                                            <i class="ri ri-delete-bin-6-line"></i>
                                                        </button>
                                                    </div>
                                                    <div id="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-purple" id="button-add">
                                            <span id="button-text">Save</span>
                                            <span id="button-loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">JK</th>
                                    <th scope="col">No HP</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $d)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}.</th>
                                    <td>
                                        <img src="{{asset($d->guru->foto)}}" class="img-thumbnail p-0 border-none rounded-circle"
                                            style="width: 40px; aspect-ratio: 1/1; object-fit: cover;" loading="lazy">
                                    </td>
                                    <td class="text-capitalize">{{ $d->guru->nama }}</td>
                                    <td>{{ $d->email }}</td>
                                    <td>{{ $d->guru->jk }}</td>
                                    <td>{{ $d->guru->no_hp }}</td>
                                    <td class="aksi">
                                        <button class="btn btn-purple btn-sm"
                                            data-bs-toggle="dropdown"><i class="ri-bar-chart-horizontal-fill"></i></button>

                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('admin.edits', ['id' => $d->guru_id]) }}">
                                                    <i class="bi bi-person"></i>
                                                    <span>Profile</span>
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"
                                                    class="dropdown-item d-flex align-items-center btn-delete"
                                                    data-id="{{ $d->guru_id }}">
                                                    <i class="ri ri-delete-bin-6-fill"></i>
                                                    <span>Delete</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
<script src="{{ asset('assets/js/main2.js') }}"></script>
@endpush
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    $(document).ready(function() {

        $('#formTambahGuru').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            const password = formData.get('password');
            const confirmPassword = formData.get('kpassword');
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Cocok',
                    text: 'Password dan Konfirmasi Password harus sama!'
                });
                return;
            }
            $('#button-add').prop('disabled', true);
            $('#button-loader').removeClass('d-none');
            $.ajax({
                url: "{{ route('admin.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            showConfirmButton: false,
                        });
                        $('#button-add').prop('disabled', false);
                        $('#button-loader').addClass('d-none');
                    }

                },
                error: function(response) {
                    console.log(response);
                    let errors = response.responseJSON?.errors;
                    let errorMessages = "";
                    const fieldTranslations = {
                        nik: {
                            "validation.unique": "NIK sudah digunakan",
                            "validation.required": "NIK wajib diisi"
                        },
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
                    $('#button-add').prop('disabled', false);
                    $('#button-loader').addClass('d-none');
                },
                complete: function() {
                    $('#button-add').prop('disabled', false);
                    $('#button-loader').addClass('d-none');
                }
            });
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/${id}`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message || 'Terjadi kesalahan.'
                                });
                            }
                        },
                        error: function(response) {

                            let errors = response.responseJSON?.errors;
                            let errorMessages = "";
                            if (response.responseJSON?.message) {
                                errorMessages = response.responseJSON.message;
                            } else {
                                errorMessages = "Terjadi kesalahan tidak diketahui.";
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: errorMessages
                            });
                        }
                    });
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
    });
</script>
@endsection