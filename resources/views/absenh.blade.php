@extends('layout.layout')
@section('title', 'Absensi Harian')
@section('content')

<div class="pagetitle">
    <h1>Absensi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Absensi</li>
        </ol>
    </nav>
</div>
<div class="modal fade" id="absenharians" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="jabatanForm" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Absensi Datang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                @include('absensiharian')
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="absenizin" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="izinform" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Absensi Izin/Sakit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating mb-3">
                                <input type="date" name="tanggal" id="tanggal" class="form-control" placeholder="Tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                <label for="tanggal">Tanggal</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating mb-3">
                                <select name="status" id="status" class="form-select" required>
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                </select>
                                <label for="status">Status</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan">
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <div class="mb-3">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <img src="{{ asset('assets/img/blank.jpg') }}" class="w-100 shadow" alt="" id="gam">
                                </div>
                                <div class="col-9">
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" id="input-type-file" name="foto" aria-label="file example" onchange="readUrl(this)" required>
                                        <button class="btn btn-purple" type="button" onclick="hapusGambar()">
                                            <i class="ri ri-delete-bin-6-line"></i>
                                        </button>
                                    </div>
                                    <div id="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="sendizin" class="btn btn-purple">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
<section class="dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="card recent-sales">
                <div class="card-body">
                    <h5 class="card-title mb-0">Absensi Harian</h5>

                    @php
                    $statusNonHadir = ['Izin', 'Sakit', 'Alpha'];
                    @endphp

                    @if(!$absen || !in_array($absen->status, $statusNonHadir))
                    @if(!$absen)
                    <button type="button" class="btn btn-sm mb-2 btn-purple" data-bs-toggle="modal" data-bs-target="#absenharians">
                        Absensi Datang
                    </button>
                    @elseif(!$absen->jam_pulang)
                    <button type="button" id="btnAbsenPulang" class="btn btn-sm mb-2 btn-danger">
                        Absen Pulang
                    </button>
                    @endif
                    @endif
                    <button type="button" class="btn btn-sm mb-2 btn-purple" data-bs-toggle="modal" data-bs-target="#absenizin">
                        Absensi Izin/Sakit
                    </button>
                    <div class="col-12">
                        <div class="row needs-validation" novalidate>
                            <div class="col-md-4 mb-2">
                                <div class="form-floating">
                                    <select class="form-select" id="bulan">
                                        <option value="">-- Pilih Bulan --</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                            @endfor
                                    </select>
                                    <label for="bulan">Pilih Bulan</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-floating">
                                    <select id="tahun" class="form-select">
                                        <option value="">-- Pilih Tahun --</option>
                                        @for ($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <label for="tahun">Pilih Tahun</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="search" placeholder="Search">
                                    <label for="search">Search</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Tanggal</th>
                                <th>Jam Datang</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Foto</th>
                                @if(Auth::user()->jabatan->jabatan == 'admin')
                                <th>Aksi</th>
                                @endif
                            </thead>
                            <tbody id="tableBody"></tbody>
                        </table>
                        @include('utils.pagination')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

    $('#btnAbsenPulang').on('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Yakin ingin absen pulang?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Absen Pulang'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('absensi.harian.pulang') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Menyimpan data absensi pulang',
                            didOpen: () => Swal.showLoading(),
                            allowOutsideClick: false
                        });
                    },
                    success: function(res) {
                        Swal.close();
                        if (res.success) {
                            Swal.fire("Berhasil", res.message, "success");
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            Swal.fire("Gagal", res.message, "error");
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ?? "Terjadi kesalahan saat absen"
                        });
                    }
                });
            }
        });
    });

    $('#sendizin').on('click', function(e) {
        e.preventDefault();
        const form = $('#izinform')[0];
        const formData = new FormData(form);
        $('#sendizin').prop('disabled', true).text('Mengirim...');

        $.ajax({
            url: "{{ route('absensi.izinStore') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Menyimpan data Izin Anda',
                    didOpen: () => Swal.showLoading(),
                    allowOutsideClick: false
                });
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#absenizin').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                let errorMessages = "";
                if (xhr.responseJSON?.errors) {
                    errorMessages = Object.values(xhr.responseJSON.errors).flat().join("\n");
                } else if (xhr.responseJSON?.message) {
                    errorMessages = xhr.responseJSON.message;
                } else {
                    errorMessages = "Terjadi kesalahan tidak diketahui.";
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: errorMessages
                });
            },
            complete: function() {
                $('#sendizin').prop('disabled', false).text('Kirim');
            }
        });
    });
</script>

@endsection
@section('scripts')
<script>
    const BASE_URL = "{{ asset('') }}";
    const IS_ADMIN = {{ Auth::user()->jabatan->jabatan === 'admin' ? 'true' : 'false' }};
</script>

{{-- UTILS --}}
<script src="{{ asset('assets/js/main2.js') }}"></script>
<script src="{{ asset('assets/js/utils/date.js') }}"></script>
<script src="{{ asset('assets/js/utils/debounce.js') }}"></script>
<script src="{{ asset('assets/js/utils/pagination.js') }}"></script>
<script src="{{ asset('assets/js/utils/datainfo.js') }}"></script>

{{-- RENDER --}}
<script src="{{ asset('assets/js/render/absensiRow.js') }}"></script>

{{-- PAGE --}}
<script src="{{ asset('assets/js/pages/absensi-harian.js') }}"></script>
@endsection