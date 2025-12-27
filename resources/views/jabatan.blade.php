@extends('layout.layout')
@section('title', 'Jabatan')
@section('content')

<div class="pagetitle">
    <h1>Jabatan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Jabatan</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Data Jabatan</h5>
            <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addJabatanModal">
                Add Jabatan
            </button>

            {{-- Modal Add --}}
            <div class="modal fade" id="addJabatanModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="jabatanForm" novalidate>
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add Jabatan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="jabatan" placeholder="Nama Jabatan">
                                    <label>Nama Jabatan</label>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp.</span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="nominal_gaji" name="nominal_gaji" oninput="convertToCurrency(this)" placeholder="Nominal Gaji /JP">
                                        <label for="nominal_gaji">Nominal Gaji /JP</label>
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
            <div class="modal fade" id="editJabatanModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="updateJabatanForm" novalidate>
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Jabatan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="jabatan" id="edit_jabatan" placeholder="Jabatan">
                                    <label>Jabatan</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" oninput="convertToCurrency(this)"
                                        name="nominal_gaji" id="edit_nominal_gaji" placeholder="Nominal Gaji">
                                    <label>Nominal Gaji /JP</label>
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
                            <th>Jabatan</th>
                            <th>Nominal Gaji /JP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $jabatan)
                        <tr>
                            <th>{{ $loop->iteration }}.</th>
                            <td class="text-capitalize">{{ $jabatan->jabatan }}</td>
                            <td>Rp.{{ number_format($jabatan->nominal_gaji, 0, ',', '.') }}</td>
                            <td class="aksi">
                                <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown"><i class="ri-bar-chart-horizontal-fill"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                                    <li>
                                        <button class="dropdown-item d-flex align-items-center editBtn" data-id="{{ $jabatan->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Update</span>
                                        </button>
                                    </li>
                                    @if (strtolower($jabatan->jabatan) !== 'admin')
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <button class="dropdown-item d-flex align-items-center deleteBtn" data-id="{{ $jabatan->id }}">
                                            <i class="ri ri-delete-bin-6-fill"></i>
                                            <span>Delete</span>
                                        </button>
                                    </li>
                                    @endif
                                </ul>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <p>Tidak ada data</p>
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
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            input.value = '';
        }
    }

    function cleanCurrency(value) {
        return value.replace(/[^\d]/g, '');
    }

    $(function() {
        $('#jabatanForm').submit(function(e) {
            e.preventDefault();
            let nominal_gaji = cleanCurrency($("input[name='nominal_gaji']").val());

            let formData = $(this).serializeArray();
            formData.push({
                name: "nominal_gaji",
                value: nominal_gaji
            });
            $.ajax({
                url: "{{ route('jabatan.store') }}",
                type: "POST",
                data: $.param(formData),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Menyimpan data Jabatan',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#jabatanForm')[0].reset();
                        $('#addJabatanModal').modal('hide');
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

        $('.editBtn').click(function() {
            let id = $(this).data('id');
            $.get("{{ url('jabatan') }}/" + id, function(data) {
                $('#edit_id').val(data.id);
                $('#edit_jabatan').val(data.jabatan);
                $('#edit_nominal_gaji').val(data.nominal_gaji);
                if (data.jabatan.toLowerCase() === 'admin') {
                    $('#edit_jabatan').prop('readonly', true);
                } else {
                    $('#edit_jabatan').prop('readonly', false);
                }
                $('#editJabatanModal').modal('show');
            });
        });

        $('#updateJabatanForm').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            let nominal_gaji = cleanCurrency($("#edit_nominal_gaji").val());

            let formData = $(this).serializeArray();
            formData.push({
                name: "nominal_gaji",
                value: nominal_gaji
            });
            $.ajax({
                url: "{{ url('jabatan') }}/" + id,
                type: "POST",
                data: $.param(formData),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Mengedit data Jabatan',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#updateJabatanForm')[0].reset();
                        $('#editJabatanModal').modal('hide');
                        setTimeout(() => location.reload(), 800);
                    }else {
                        Swal.fire("Gagal", res.message, "error");
                    }
                },
                error: function(response) { 
                    let errors = response.responseJSON?.errors;
                    let errorMessages = "";

                    const fieldTranslations = {
                        jabatan: {
                            required: "Field Jabatan wajib diisi.",
                            string: "Field Jabatan harus berupa teks.",
                            max: "Field Jabatan maksimal 255 karakter.",
                        },
                        nominal_gaji: {
                            required: "Field Gaji Pokok wajib diisi.",
                            string: "Field Gaji Pokok harus berupa teks.",
                            max: "Field Gaji Pokok maksimal 255 karakter.",
                        },
                    };

                    if (errors && Object.keys(errors).length > 0) {
                        errorMessages = Object.entries(errors)
                            .map(([field, messages]) => {
                                return messages.map(msgKey => {
                                    return fieldTranslations[field]?.[msgKey] ?? msgKey;
                                }).join('\n');
                            })
                            .join('\n');
                    } else if (response.responseJSON?.message) {
                        errorMessages = response.responseJSON.message;
                    } else {
                        errorMessages = "Terjadi kesalahan tidak diketahui.";
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMessages
                    });
                },
            });
        });

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
                        url: "{{ url('jabatan') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Menghapus data jabatan',
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