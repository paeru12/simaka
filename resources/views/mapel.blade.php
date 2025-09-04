@extends('layout.layout')
@section('title', 'Mapel')
@section('content')

<div class="pagetitle">
    <h1>Data Mapel</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Mata Pelajaran</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Data Mapel</h5>
            <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addMapelModal">
                Add Mapel
            </button>

            {{-- Modal Add --}}
            <div class="modal fade" id="addMapelModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="mapelForm" novalidate>
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add Mapel</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="nama_mapel" placeholder="Nama Mapel">
                                    <label>Nama Mapel</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="kode_mapel" placeholder="Kode Mapel">
                                    <label>Kode Mapel</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" oninput="convertToCurrency(this)" name="gaji" placeholder="Gaji">
                                    <label>Honor /Mengajar</label>
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
            <div class="modal fade" id="editMapelModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="updateMapelForm" novalidate>
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Mapel</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="nama_mapel" id="edit_nama_mapel" placeholder="Nama Mapel">
                                    <label>Nama Mapel</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="kode_mapel" id="edit_kode_mapel" placeholder="Kode Mapel">
                                    <label>Kode Mapel</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" oninput="convertToCurrency(this)" name="gaji" id="edit_gaji" placeholder="Gaji">
                                    <label>Honor /Mengajar</label>
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
                            <th>Nama Mapel</th>
                            <th>Kode Mapel</th>
                            <th>Honor /Mengajar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mapel as $m)
                        <tr>
                            <th>{{ $loop->iteration }}.</th>
                            <td>{{ $m->nama_mapel }}</td>
                            <td>{{ $m->kode_mapel }}</td>
                            <td>Rp.{{ number_format($m->gaji, 0, ',', '.') }}</td>
                            <td class="aksi">
                                <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown"><i class="ri-bar-chart-horizontal-fill"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                                    <li>
                                        <button class="dropdown-item d-flex align-items-center editBtn" data-id="{{ $m->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Update</span>
                                        </button>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <button class="dropdown-item d-flex align-items-center deleteBtn" data-id="{{ $m->id }}">
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
</section>

<script>
    function convertToCurrency(input) {
        let value = input.value.replace(/[^\d]/g, ''); // ambil hanya angka
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value); // tampil Rp style (tanpa "Rp" biar lebih aman)
        } else {
            input.value = '';
        }
    }

    function cleanCurrency(value) {
        return value.replace(/[^\d]/g, '');
    }

    $(function() {
        // CREATE
        $('#mapelForm').submit(function(e) {
            e.preventDefault();
            let gaji = cleanCurrency($("input[name='gaji']").val());

            let formData = $(this).serializeArray();
            formData.push({
                name: "gaji",
                value: gaji
            });
            $.ajax({
                url: "{{ route('mapel.store') }}",
                type: "POST",
                data: $.param(formData),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Menyimpan data mapel',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#mapelForm')[0].reset();
                        $('#addMapelModal').modal('hide');
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
            $.get("{{ url('mapel') }}/" + id, function(data) {
                $('#edit_id').val(data.id);
                $('#edit_nama_mapel').val(data.nama_mapel);
                $('#edit_kode_mapel').val(data.kode_mapel);
                $('#edit_gaji').val(data.gaji);
                $('#editMapelModal').modal('show');
            });
        });

        // UPDATE
        $('#updateMapelForm').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            let gaji = cleanCurrency($("input[id='edit_gaji']").val());

            let formData = $(this).serializeArray();
            formData.push({
                name: "gaji",
                value: gaji
            });
            $.ajax({
                url: "{{ url('mapel') }}/" + id,
                type: "POST",
                data: $.param(formData),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Mengedit data mapel',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#updateMapelForm')[0].reset();
                        $('#editMapelModal').modal('hide');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        Swal.fire("Gagal", res.message, "error");
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
                        url: "{{ url('mapel') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Menghapus data mapel',
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