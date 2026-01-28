@extends('layout.layout')
@section('title', 'Ruangan')
@section('content')
<div class="pagetitle">
    <h1>Data Ruangan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Ruangan</li>
        </ol>
    </nav>
</div>

{{-- Modal Add --}}
<div class="modal fade" id="addRuanganModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="RuanganForm" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama" placeholder="Nama Ruangan">
                        <label>Nama Ruangan</label>
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
<div class="modal fade" id="editRuanganModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="updateRuanganForm" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title">Update Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama" id="edit_nama" placeholder="Nama Ruangan">
                        <label>Nama Ruangan</label>
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

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Data Ruangan</h5>

            <!-- filter -->
            <div class="col-12">
                <div class="row needs-validation d-flex justify-content-between align-items-center gap-2" novalidate>
                    <div class="col-4 col-md-4">
                        <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addRuanganModal">
                            Tambah Ruangan
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
                            <th>Nama Ruangan</th>
                            <th>QR Code</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-capitalize"></tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div id="dataInfo" class="text-muted small"></div>
                    <ul class="pagination pagination-sm" id="pagination"></ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/pages/ruangan.js') }}"></script>
<script src="{{ asset('assets/js/render/ruanganRow.js') }}"></script>
@include('utils.utils')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    $(function() {
        // CREATE
        $('#RuanganForm').submit(function(e) {
            e.preventDefault();
            // Bersihkan nilai sebelum submit

            let formData = $(this).serializeArray();
            formData.push({
                name: "nama",
                value: $("input[name='nama']").val()
            });
            $.ajax({
                url: "{{ route('ruangan.store') }}",
                type: "POST",
                data: $.param(formData),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Menyimpan data Ruangan',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#RuanganForm')[0].reset();
                        $('#addRuanganModal').modal('hide');
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

        // UPDATE
        $('#updateRuanganForm').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            // Bersihkan nilai sebelum submit
            let nama = $("#edit_nama").val();

            let formData = $(this).serializeArray();
            formData.push({
                name: "nama",
                value: nama
            });
            $.ajax({
                url: "{{ url('ruangan') }}/" + id,
                type: "POST",
                data: $.param(formData),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Mengedit data Ruangan',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#updateRuanganForm')[0].reset();
                        $('#editRuanganModal').modal('hide');
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
                }
            });
        });

    });
</script>
@endsection