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

                    {{-- Modal Add Guru --}}
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
                                                <option value="" selected disabled>Open this select menu</option>
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
                                        <div class="form-floating mb-3">
                                            <select name="jabatan_id" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                <option selected disabled>Open this select menu</option>
                                                @foreach ($jabatan as $j)
                                                <option value="{{ $j->id }}">{{ $j->jabatan }}</option>
                                                @endforeach
                                            </select>
                                            <label for="floatingSelect">Pilih Jabatan</label>
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

                    {{-- Modal Update --}}
                    <div class="modal fade" id="editGuruModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="updateGuruForm" novalidate>
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" id="edit_id">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Guru</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <div class="row align-items-center">
                                                <div class="col-3">
                                                    <img src="" class="w-100 shadow-sm" alt="" id="gam">
                                                </div>
                                                <div class="col-9">
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" id="gambar" name="foto" onchange="readUrl(this)">
                                                        <button class="btn btn-purple" type="button" onclick="hapusGambar()">
                                                            <i class="ri ri-delete-bin-6-line"></i>
                                                        </button>
                                                    </div>
                                                    <div class="form-text">Pilih gambar guru (jpg, png, webp, jfif, jpeg)</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="nama" id="edit_nama" placeholder="Nama Guru">
                                            <label for="edit_nama">Nama Guru</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="nik" id="edit_nik" placeholder="NIK">
                                            <label for="edit_nik">NIK</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="jenis_kelamin" id="edit_jk">
                                                <option value="" selected disabled>Open this select menu</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                            <label for="edit_jk">Jenis Kelamin</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="alamat" id="edit_alamat" placeholder="Alamat" style="height: 50px"></textarea>
                                            <label for="edit_alamat">Alamat</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="no_hp" id="edit_nohp" placeholder="No HP">
                                            <label for="edit_nohp">No HP</label>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <select name="jabatan_id" class="form-select" id="edit_jabatan">
                                                <option selected disabled>Open this select menu</option>
                                                @foreach ($jabatan as $j)
                                                <option value="{{ $j->id }}">{{ $j->jabatan }}</option>
                                                @endforeach
                                            </select>
                                            <label for="edit_jabatan">Pilih Jabatan</label>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-purple" type="submit">Update</button>
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
                                    <th scope="col">Jabatan</th>
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
                                    <td>{{ $g->jabatan->jabatan }}</td>
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
                                                <button class="dropdown-item d-flex align-items-center editBtn" data-id="{{ $g->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                    <span>Update</span>
                                                </button>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <button class="dropdown-item d-flex align-items-center deleteBtn" data-id="{{ $g->id }}">
                                                    <i class="ri ri-delete-bin-6-fill"></i>
                                                    <span>Delete</span>
                                                </button>
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

        // SHOW UPDATE FORM
        $('.editBtn').click(function() {
            let id = $(this).data('id');
            $.get("{{ url('guru') }}/" + id, function(data) {
                console.log(data);
                $('#edit_id').val(data.id);
                $('#edit_nama').val(data.nama);
                $('#edit_nik').val(data.nik);
                $('#edit_jk').val(data.jenis_kelamin);
                $('#edit_alamat').val(data.alamat);
                $('#edit_nohp').val(data.no_hp);
                $('#edit_jabatan').val(data.jabatan_id);
                let baseUrl = "{{ url('/') }}/";
                if (data.foto) {
                    $('#gam').attr('src', baseUrl + data.foto);
                } else {
                    $('#gam').attr('src', "{{ asset('assets/img/blank.jpg') }}");
                }

                $('#editGuruModal').modal('show');
            });
        });

        // UPDATE
        $('#updateGuruForm').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ url('guru') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Mengupdate data guru',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#updateGuruForm')[0].reset();
                        $('#editGuruModal').modal('hide');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        Swal.fire("Gagal", res.message, "error");
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire("Error", "Terjadi kesalahan sistem!", "error");
                }
            });
        });

        // DELETE
        $('.deleteBtn').click(function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('guru') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Menghapus data guru',
                                didOpen: () => Swal.showLoading(),
                                allowOutsideClick: false
                            });
                        },
                        success: function(res) {
                            Swal.close();
                            if (res.success) {
                                Swal.fire("Berhasil", res.message, "success");
                                setTimeout(() => location.reload(), 800);
                            } else {
                                Swal.fire("Gagal", res.message, "error");
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire("Error", "Terjadi kesalahan sistem!", "error");
                        }
                    });
                }
            });
        });

    });
</script>

@endsection