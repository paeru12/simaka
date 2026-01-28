@extends('layout.layout')
@section('title', 'Gaji Guru')
@section('content')

<div class="pagetitle">
    <h1>Potongan Gaji</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Potongan Gaji</li>
        </ol>
    </nav>
</div>

{{-- Modal Add --}}
<div class="modal fade" id="addPotonganModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="potonganForm" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Potongan Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama_potongan" placeholder="Nama Potongan">
                        <label>Nama Potongan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" oninput="convertToCurrency(this)" name="jumlah_potongan" placeholder="Jumlah Potongan">
                        <label>Jumlah Potongan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="keterangan" placeholder="Keterangan">
                        <label>Keterangan</label>
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
<div class="modal fade" id="editPotonganModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="updatePotonganForm" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title">Update Potongan Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama_potongan" id="edit_nama_potongan" placeholder="Nama Potongan">
                        <label>Nama Potongan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" oninput="convertToCurrency(this)" name="jumlah_potongan" id="edit_jumlah_potongan" placeholder="Jumlah Potongan">
                        <label>Jumlah Potongan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="keterangan" id="edit_keterangan" placeholder="Keterangan">
                        <label>Keterangan</label>
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

<section class="dashboard">
    <div class="card recent-sales">
        <div class="card-body">
            <h5 class="card-title mb-0">Data Potongan Gaji</h5>

            <!-- filter -->
            <div class="col-12">
                <div class="row needs-validation d-flex justify-content-between align-items-center gap-2" novalidate>
                    <div class="col-4 col-md-4">
                        <button type="button" class="btn btn-purple btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addPotonganModal">
                            Add Potongan Gaji
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
                            <th scope="col">No</th>
                            <th scope="col">Nama Potongan</th>
                            <th scope="col">Jumlah Potongan</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Aksi</th>
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
<script src="{{ asset('assets/js/render/potonganRow.js') }}"></script>
<script src="{{ asset('assets/js/pages/potongan.js') }}"></script>
<script>
    $(function() {
        window.convertToCurrency = function(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) {
                input.value = new Intl.NumberFormat('id-ID').format(value);
            } else {
                input.value = '';
            }
        };

        function cleanCurrency(value) {
            return value.replace(/[^\d]/g, '');
        }
        // CREATE
        $('#potonganForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serializeArray();
            formData.forEach((item) => {
                if (item.name === 'jumlah_potongan') {
                    item.value = cleanCurrency(item.value);
                }
            });
            $.ajax({
                url: "{{ route('potongan-gaji.store') }}",
                type: "POST",
                data: $.param(formData),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Menyimpan data potongan gaji',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#potonganForm')[0].reset();
                        $('#addPotonganModal').modal('hide');
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

        $('.editBtn').click(function() {
            let id = $(this).data('id');
            $.get("{{ url('potongan-gaji') }}/" + id, function(data) {
                $('#edit_id').val(data.id);
                $('#edit_nama_potongan').val(data.nama_potongan);
                $('#edit_jumlah_potongan').val(new Intl.NumberFormat('id-ID').format(data.jumlah_potongan));
                $('#edit_keterangan').val(data.keterangan);
                $('#editPotonganModal').modal('show');
            });
        });

        // UPDATE
        $('#updatePotonganForm').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            let formData = $(this).serializeArray();
            formData.forEach((item) => {
                if (item.name === 'jumlah_potongan') {
                    item.value = cleanCurrency(item.value);
                }
            });
            $.ajax({
                url: "{{ url('potongan-gaji') }}/" + id,
                type: "POST",
                data: $.param(formData),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Mengedit data potongan gaji',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#updatePotonganForm')[0].reset();
                        $('#editPotonganModal').modal('hide');
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
                        url: "{{ url('potongan-gaji') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Menghapus data potongan gaji',
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