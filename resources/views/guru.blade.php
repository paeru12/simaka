@extends('layout.layout')
@section('title', 'Guru')
@section('content')

<div class="pagetitle">
    <h1 class="text-capitalize">Data Guru</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active"><a href="">Guru</a></li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0">Data Guru</h5>
                    <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                        Add Guru
                    </button>
                    <div class="modal fade" id="verticalycentered" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="guruForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Guru</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <div class="row align-items-center">
                                                <div class="col-3">
                                                    <img src="{{ asset('assets/img/blank.jpg') }}" class="w-100 shadow-sm" alt="" id="gam">
                                                </div>
                                                <div class="col-9">
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" id="gambar" name="foto" onchange="readUrl(this)" required>
                                                        <button class="btn btn-purple" type="button" onclick="hapusGambar()">
                                                            <i class="ri ri-delete-bin-6-line"></i>
                                                        </button>
                                                    </div>
                                                    <div class="form-text">Pilih gambar guru (jpg, png, webp, jfif, jpeg)</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Guru">
                                            <label for="nama">Nama Guru</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="nik" id="nik" placeholder="NIK">
                                            <label for="nik">NIK</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="jenis_kelamin" id="jk">
                                                <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                            <label for="jk">Jenis Kelamin</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="alamat" placeholder="Alamat" id="alamat" style="height: 50px"></textarea>
                                            <label for="alamat">Alamat</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="no_hp" id="nohp" placeholder="No HP">
                                            <label for="nohp">No HP</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-purple">Save changes</button>
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
                                    <th scope="col">Nama Guru</th>
                                    <th scope="col">NIK</th>
                                    <th scope="col">JK</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">No HP</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($guru as $g)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}.</th>
                                    <td>
                                        <img src="{{asset($g->foto)}}" class="img-thumbnail p-0 border-none"
                                            style="width: 60px;" loading="lazy">
                                    </td>
                                    <td>{{ $g->nama }}</td>
                                    <td>{{ $g->nik }}</td>
                                    <td>{{ $g->jenis_kelamin }}</td>
                                    <td>{{ $g->alamat }}</td>
                                    <td>{{ $g->no_hp }}</td>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                    <td class="aksi">
                                        <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown"><i class="ri-bar-chart-horizontal-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="">
                                                    <i class="bi bi-pencil-square"></i>
                                                    <span>Update</span>
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="">
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

<script>
$(document).ready(function() {
    $('#guruForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('guru.store') }}", // pastikan route ini sesuai
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Menyimpan data guru',
                    didOpen: () => Swal.showLoading(),
                    allowOutsideClick: false
                });
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    $('#guruForm')[0].reset();
                    $('#verticalycentered').modal('hide');
                    $('#gam').attr('src', "{{ asset('assets/img/blank.jpg') }}");

                    setTimeout(() => location.reload(), 1200);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message 
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                let msg = 'Terjadi kesalahan saat menyimpan data.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: msg
                });
            }
        });
    });
});
</script>

@endsection