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
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-purple" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<section class="section dashboard">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Data Mapel</h5>

            <!-- filter -->
            <div class="col-12">
                <div class="row needs-validation d-flex justify-content-between align-items-center gap-2" novalidate>
                    <div class="col-4 col-md-4">
                        <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addMapelModal">
                            Add Mapel
                        </button>
                    </div>
                    <div class="col-7 col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="search" placeholder="Search">
                            <label for="search">Search</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end filter -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mapel</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-capitalize">
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-3 w-100">
                    <div id="dataInfo" class="text-muted small"></div>
                    <ul class="pagination pagination-sm" id="pagination"></ul>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
@include('utils.utils')
<script src="{{ asset('assets/js/render/mapelRow.js') }}"></script>
<script src="{{ asset('assets/js/pages/mapel.js') }}"></script>
<script>
    $(function() {
        // CREATE
        $('#mapelForm').submit(function(e) {
            e.preventDefault();

            let formData = $(this).serializeArray();
            formData.push({
                name: "gaji",
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
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON.message
                    });
                    setTimeout(() => location.reload(), 1500);
                }
            });
        });

        // SHOW UPDATE FORM
        $('.editBtn').click(function() {
            let id = $(this).data('id');
            $.get("{{ url('mapel') }}/" + id, function(data) {
                $('#edit_id').val(data.id);
                $('#edit_nama_mapel').val(data.nama_mapel);
                $('#editMapelModal').modal('show');
            });
        });

        // UPDATE
        $('#updateMapelForm').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();

            let formData = $(this).serializeArray();
            formData.push({
                name: "gaji",
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
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON.message
                    });
                    setTimeout(() => location.reload(), 1500);
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
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON.message
                            });
                            setTimeout(() => location.reload(), 1500);
                        }
                    });
                }
            });
        });
    });
</script>
@endsection