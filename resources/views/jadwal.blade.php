@extends('layout.layout')
@section('title', 'Jadwal')
@section('content')

<div class="pagetitle">
    <h1 class="text-capitalize">Jadwal</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Jadwal Guru</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0">Data Jadwal</h5>
                    <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                        Add Jadwal
                    </button>

                    {{-- Modal Add --}}
                    <div class="modal fade" id="addJadwalModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="JadwalForm" novalidate>
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Jadwal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <select name="guru_id" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                <option selected>Open this select menu</option>
                                                @foreach ($guru as $g)
                                                <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                                @endforeach
                                            </select>
                                            <label for="floatingSelect">Pilih Guru</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select name="mapel_id" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                <option selected>Open this select menu</option>
                                                @foreach ($mapel as $m)
                                                <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                                                @endforeach
                                            </select>
                                            <label for="floatingSelect">Pilih Mapel</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-floating mb-3">
                                                    <select name="hari" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                        <option selected>Open this select menu</option>
                                                        <option value="Senin">Senin</option>
                                                        <option value="Selasa">Selasa</option>
                                                        <option value="Rabu">Rabu</option>
                                                        <option value="Kamis">Kamis</option>
                                                        <option value="Jumat">Jumat</option>
                                                    </select>
                                                    <label for="floatingSelect">Pilih Hari</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-floating mb-3">
                                                    <select name="kelas_id" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                        <option selected>Open this select menu</option>
                                                        @forelse($kelas as $k)
                                                        <option value="{{$k->id}}">{{$k->kelas}} {{$k->rombel}}</option>
                                                        @empty
                                                        <option>No Data!!</option>
                                                        @endforelse
                                                    </select>
                                                    <label for="floatingSelect">Pilih Kelas</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-floating mb-3">
                                                    <input type="time" class="form-control" name="jam_mulai" placeholder="Jam Mulai">
                                                    <label>Jam Mulai</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-floating mb-3">
                                                    <input type="time" class="form-control" name="jam_selesai" placeholder="Jam Selesai">
                                                    <label>Jam Selesai</label>
                                                </div>
                                            </div>
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

                    {{-- Modal Update --}}
                    <div class="modal fade" id="editJadwalModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="updateJadwalForm" novalidate>
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" id="edit_id">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Jadwal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <select name="guru_id" id="edit_guru_id" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                <option selected>Open this select menu</option>
                                                @foreach ($guru as $g)
                                                <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                                @endforeach
                                            </select>
                                            <label for="floatingSelect">Pilih Guru</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select name="mapel_id" id="edit_mapel_id" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                <option selected>Open this select menu</option>
                                                @foreach ($mapel as $m)
                                                <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                                                @endforeach
                                            </select>
                                            <label for="floatingSelect">Pilih Mapel</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-floating mb-3">
                                                    <select name="hari" id="edit_hari" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                        <option selected>Open this select menu</option>
                                                        <option value="Senin">Senin</option>
                                                        <option value="Selasa">Selasa</option>
                                                        <option value="Rabu">Rabu</option>
                                                        <option value="Kamis">Kamis</option>
                                                        <option value="Jumat">Jumat</option>
                                                    </select>
                                                    <label for="floatingSelect">Pilih Hari</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" id="edit_kelas" class="form-control" name="kelas" placeholder="Kelas">
                                                    <label>Kelas</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-floating mb-3">
                                                    <input type="time" id="edit_jam_mulai" class="form-control" name="jam_mulai" placeholder="Jam Mulai">
                                                    <label>Jam Mulai</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-floating mb-3">
                                                    <input type="time" id="edit_jam_selesai" class="form-control" name="jam_selesai" placeholder="Jam Selesai">
                                                    <label>Jam Selesai</label>
                                                </div>
                                            </div>
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
                                    <th>Guru</th>
                                    <th>Mapel</th>
                                    <th>Hari</th>
                                    <th>Kelas</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwal as $j)
                                <tr>
                                    <th>{{ $loop->iteration }}.</th>
                                    <td>{{ $j->guru->nama }}</td>
                                    <td>{{ $j->mataPelajaran->nama_mapel }}</td>
                                    <td>{{ $j->hari }}</td>
                                    <td>{{ $j->kelas->kelas }} {{ $j->kelas->rombel }}</td>
                                    <td>{{ $j->jam_mulai }}</td>
                                    <td>{{ $j->jam_selesai }}</td>
                                    <td class="aksi">
                                        <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown"><i class="ri-bar-chart-horizontal-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                                            <li>
                                                <button class="dropdown-item d-flex align-items-center editBtn" data-id="{{ $j->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                    <span>Update</span>
                                                </button>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <button class="dropdown-item d-flex align-items-center deleteBtn" data-id="{{ $j->id }}">
                                                    <i class="ri ri-delete-bin-6-fill"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </li>
                                        </ul>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Data Available</td>
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
    $(function() {
        // CREATE
        $('#JadwalForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('jadwal.store') }}",
                type: "POST",
                data: $(this).serialize(),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Menyimpan data Jadwal',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#JadwalForm')[0].reset();
                        $('#addJadwalModal').modal('hide');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        Swal.fire("Gagal", res.message, "error");
                    }
                }
            });
        });

        // SHOW UPDATE FORM
        $('.editBtn').click(function() {
            let id = $(this).data('id');
            $.get("{{ url('jadwal') }}/" + id, function(data) {
                $('#edit_id').val(data.id);
                $('#edit_guru_id').val(data.guru_id);
                $('#edit_mapel_id').val(data.mapel_id);
                $('#edit_hari').val(data.hari);
                $('#edit_kelas').val(data.kelas);
                $('#edit_jam_mulai').val(data.jam_mulai.substring(0, 5));
                $('#edit_jam_selesai').val(data.jam_selesai.substring(0, 5));
                $('#editJadwalModal').modal('show');
            });
        });

        // UPDATE
        $('#updateJadwalForm').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            $.ajax({
                url: "{{ url('jadwal') }}/" + id,
                type: "PUT",
                data: $(this).serialize(),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Mengedit data jadwal',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#updateJadwalForm')[0].reset();
                        $('#editJadwalModal').modal('hide');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        console.log(res);
                        // Swal.fire("Gagal", res.message, "error");
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.message
                        });
                    }
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
                        url: "{{ url('jadwal') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Menghapus data jadwal',
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
                        }
                    });
                }
            });
        });

    });
</script>
@endsection