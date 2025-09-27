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

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Data Ruangan</h5>
            <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addRuanganModal">
                Tambah Ruangan
            </button>

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

            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ruangan</th>
                            <th>QR Code</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-capitalize">
                        @forelse ($data as $ruangan)
                        <tr>
                            <th>{{ $loop->iteration }}.</th>
                            <td>{{ $ruangan->nama }}</td>
                            <td>
                                <!-- Tombol -->
                                @if(!$ruangan->qrKelas)
                                <button class="btn btn-primary btn-generate" data-id="{{ $ruangan->id }}">
                                    Generate QR Code
                                </button>
                                @else
                                <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#qrModal{{$ruangan->id}}">
                                    Download QR Code
                                </button>

                                {{-- Modal QR --}}
                                <div class="modal fade" id="qrModal{{$ruangan->id}}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body" id="qrContent{{$ruangan->id}}">
                                                <div class="row justify-content-center align-items-center">
                                                    <h5 class="card-title text-center text-uppercase fw-bold fs-3 mb-0">{{ $ruangan->nama }}</h5>
                                                    <p class="card-title fw-semibold text-center py-0" style="font-size: 14px;">Scan untuk absensi</p>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <img src="{{ asset($ruangan->qrKelas->file) }}" class="w-75" alt="">
                                                    </div>
                                                    <div class="row justify-content-center align-items-center mt-2">
                                                        <div class="col-8 text-center">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <img src="{{asset($logo)}}" class="w-25" alt="">
                                                                <p class="card-title text-purple fw-bold">{{$nama}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                                <button type="button" id="printBtn" class="btn btn-purple btn-sm mt-2 d-print-none" onclick='downloadPDF(@json($ruangan->id), "{{ $ruangan->nama }}")'>Download</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                            <td class="aksi">
                                <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown"><i class="ri-bar-chart-horizontal-fill"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                                    <li>
                                        <button class="dropdown-item d-flex align-items-center editBtn" data-id="{{ $ruangan->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Update </span>
                                        </button>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <button class="dropdown-item d-flex align-items-center deleteBtn" data-id="{{ $ruangan->id }}">
                                            <i class="ri ri-delete-bin-6-fill"></i>
                                            <span>Delete</span>
                                        </button>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
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
                    console.log(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message
                    });
                }
            });
        });

        // SHOW UPDATE FORM
        $('.editBtn').click(function() {
            let id = $(this).data('id');
            $.get("{{ url('ruangan') }}/" + id, function(data) {
                $('#edit_id').val(data.id);
                $('#edit_nama').val(data.nama);
                $('#editRuanganModal').modal('show');
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
                        url: "{{ url('ruangan') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Menghapus data ruangan',
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
                            console.log(xhr);
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

        // CETAK QR KELAS
        $('.btn-generate').on('click', function() {
            let kelasId = $(this).data('id');

            $.ajax({
                url: "{{ route('qrkelas.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ruangan_id: kelasId
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Membuat QR Code Ruangan',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    if (res.success) {
                        Swal.close();
                        Swal.fire("Berhasil", res.message, "success");
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        Swal.fire("Gagal", res.message, "error");
                    }
                },
                error: function(err) {
                    console.log(err);
                    Swal.close();
                    Swal.fire("Error", err.responseJSON.message, "error");
                }
            });
        });
    });

    function downloadPDF(id, nama) {
        const element = document.getElementById("qrContent" + id);
        const opt = {
            margin: [15, 0, 0, 0],
            filename: "qr-ruangan-" + nama + "-" + id + ".pdf",
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'mm',
                format: 'a5',
                orientation: 'portrait'
            }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>
@endsection