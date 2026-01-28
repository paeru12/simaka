@extends('layout.layout')
@section('title', 'Data Jurusan')
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
    <h1>Data Jurusan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Jurusan</li>
        </ol>
    </nav>
</div>
{{-- Modal Add --}}
<div class="modal fade" id="addJurusanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="JurusanForm" class="need-validation" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Jurusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" data-role="tagsinput" name="nama" placeholder="Jurusan">

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
<div class="modal fade" id="editJurusanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editJurusanForm" class="need-validation" novalidate>
                <input type="hidden" name="id" id="editJurusanId">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jurusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama" id="editJurusanNama" placeholder="Jurusan" required>
                        <label>Jurusan</label>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Jurusan</h5>

                    <!-- filter -->
                    <div class="col-12">
                        <div class="row needs-validation d-flex justify-content-between align-items-center gap-2" novalidate>
                            <div class="col-4 col-md-4">
                                <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addJurusanModal">
                                    Add Jurusan
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
                                    <th>Jurusan</th>
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
        </div>
    </div>
</section>
@endsection

@section('scripts')
@include('utils.utils')
<script src="{{ asset('assets/js/render/jurusanRow.js') }}"></script>
<script src="{{ asset('assets/js/pages/jurusan.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script>
    $(function() {
        //jurusan
        $('#JurusanForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('jurusan.store') }}",
                type: "POST",
                data: $(this).serialize(),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Menyimpan data Jurusan',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#JurusanForm')[0].reset();
                        $('#addJurusanModal').modal('hide');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        Swal.fire("Gagal", res.message, "error");
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!!',
                        text: xhr.responseJSON.message
                    });
                    setTimeout(() => location.reload(), 2000);
                }
            });
        });

        // UPDATE
        $('#editJurusanForm').submit(function(e) {
            e.preventDefault();
            let id = $('#editJurusanId').val();
            $.ajax({
                url: "{{ url('jurusan') }}/" + id,
                type: "POST",
                data: $(this).serialize(),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Mengedit data Jurusan',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#editJurusanForm')[0].reset();
                        $('#editJurusanModal').modal('hide');
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
    });
</script>
@endsection