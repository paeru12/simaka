@extends('layout.layout')
@section('title', 'Data Kelas')
@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">

<style>
    .bootstrap-tagsinput {
        width: 100%;
        min-height: calc(3.5rem + 2px);
        padding: 0.75rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        background-color: #fff;
    }

    .bootstrap-tagsinput .tag {
        margin-right: 2px;
        color: white;
        background-color: #0d6efd;
        padding: 0.2em 0.5em;
        border-radius: 0.25rem;
        display: inline-block;
    }
</style>

<div class="pagetitle">
    <h1>Data Kelas</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Kelas</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        Data Kelas
                    </h5>
                    <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addKelasModal">
                        Add Kelas
                    </button>

                    {{-- Modal Add --}}
                    <div class="modal fade" id="addKelasModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="kelasForm" class="need-validation" novalidate>
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Kelas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <select name="jurusan_id" class="form-select text-capitalize" id="floatingSelect" aria-label="Floating label select example">
                                                <option selected disabled>Open this select menu</option>
                                                @foreach ($data as $j)
                                                <option value="{{ $j->id }}">{{ $j->nama }}</option>
                                                @endforeach
                                            </select>
                                            <label for="floatingSelect">Pilih Jurusan</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="kelas" placeholder="Kelas">
                                            <label>Kelas</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" data-role="tagsinput" name="rombel" placeholder="Rombel">
                                            <!-- <label>Rombel</label> -->
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-purple" type="submit">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Edit --}}
                    <div class="modal fade" id="editKelasModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="updateKelasForm" class="need-validation" novalidate>
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" id="edit_id">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kelas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <select name="jurusan_id" class="form-select text-capitalize" id="edit_jurusan_id" aria-label="Floating label select example">
                                                <option selected disabled>Open this select menu</option>
                                                @foreach ($data as $j)
                                                <option value="{{ $j->id }}">{{ $j->nama }}</option>
                                                @endforeach
                                            </select>
                                            <label for="edit_jurusan_id">Pilih Jurusan</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="edit_kelas" name="kelas" placeholder="Kelas" required>
                                            <label>Kelas</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="edit_rombel" name="rombel" placeholder="Rombel" required>
                                            <label>Rombel</label>
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
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Rombel</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-capitalize">
                                @foreach ($kelas as $k)
                                <tr>
                                    <th>{{ $loop->iteration }}.</th>
                                    <td class="text-uppercase">{{ $k->kelas }} {{$k->jurusan->nama}}</td>
                                    <td>{{ $k->rombel }}</td>
                                    <td class="aksi">
                                        <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown"><i class="ri-bar-chart-horizontal-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                                            <li>
                                                <button class="dropdown-item d-flex align-items-center editBtnKelas" data-id="{{ $k->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                    <span>Update</span>
                                                </button>
                                            </li>
                                            @if ($k->jadwal_count == 0)
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <button class="dropdown-item d-flex align-items-center deleteBtnKelas" data-id="{{ $k->id }}">
                                                    <i class="ri ri-delete-bin-6-fill"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </li>
                                            @endif
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script>
    $(function() {
        // CREATE
        $('#kelasForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('kelas.store') }}",
                type: "POST",
                data: $(this).serialize(),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Menyimpan data Kelas',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#kelasForm')[0].reset();
                        $('#addKelasModal').modal('hide');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        Swal.fire("Gagal", res.message, "error");
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: xhr.responseJSON.message
                    });
                    setTimeout(() => location.reload(), 1500);
                }
            });
        });

        // SHOW UPDATE FORM
        $('.editBtnKelas').click(function() {
            let id = $(this).data('id');
            $.get("{{ url('kelas') }}/" + id, function(data) {
                $('#edit_id').val(data.id);
                $('#edit_jurusan_id').val(data.jurusan_id);
                $('#edit_kelas').val(data.kelas);
                $('#edit_rombel').val(data.rombel);
                $('#editKelasModal').modal('show');
            });
        });
        // UPDATE
        $('#updateKelasForm').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();

            $.ajax({
                url: "{{ url('kelas') }}/" + id,
                type: "POST",
                data: $(this).serialize(),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Mengedit data Kelas',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#updateKelasForm')[0].reset();
                        $('#editKelasModal').modal('hide');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        Swal.fire("Gagal", res.message, "error");
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message
                    });
                }
            });
        });
        // DELETE
        $('.deleteBtnKelas').click(function() {
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
                        url: "{{ url('kelas') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Menghapus data kelas',
                                didOpen: () => Swal.showLoading(),
                                allowOutsideClick: false
                            });
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire("Berhasil", res.message, "success");
                                setTimeout(() => location.reload(), 800);
                            } else {
                                Swal.fire("Gagal", res.message, "error");
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.message
                            });
                        }
                    });
                }
            });
        });

    });
</script>
@endsection