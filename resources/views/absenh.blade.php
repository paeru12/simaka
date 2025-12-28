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
                    <div class="col-8">
                        <div class="row needs-validation" novalidate>
                            <div class="col-md-6 mb-2">
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
                            <div class="col-md-6 mb-2">
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
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table datatable">
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
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    /* =========================
   LOAD DATA (GLOBAL)
========================= */
    function loadData(bulan, tahun) {
        $.ajax({
            url: "{{ route('absensi.harian.filter') }}",
            method: "POST",
            data: {
                bulan: bulan,
                tahun: tahun,
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $("table tbody").html(`
                <tr>
                    <td colspan="10" class="text-center">Memuat data...</td>
                </tr>
            `);
            },
            success: function(res) {
                let tbody = "";

                if (res.length > 0) {
                    res.forEach((absen, index) => {
                        let jamPulang = absen.jam_pulang ?
                            `${new Date('1970-01-01T' + absen.jam_pulang).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})} WIB` :
                            '-';
                        let jamDatang = absen.jam_datang ?
                            `${new Date('1970-01-01T' + absen.jam_datang).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})} WIB` :
                            '-';

                        let badge = '';
                        switch (absen.status) {
                            case 'Alpha':
                                badge = `<span class="badge bg-danger">Alpha</span>`;
                                break;
                            case 'Izin':
                                badge = `<span class="badge bg-warning text-dark">Izin</span>`;
                                break;
                            case 'Sakit':
                                badge = `<span class="badge bg-info text-dark">Sakit</span>`;
                                break;
                            default:
                                badge = `<span class="badge bg-success">Hadir</span>`;
                        }

                        tbody += `
                                <tr>
                                    <th>${index + 1}.</th>
                                    <td class="text-capitalize">${absen.guru?.nama ?? '-'}</td>
                                    <td class="text-capitalize">${absen.guru?.jabatan?.jabatan ?? '-'}</td>
                                    <td>${new Date(absen.tanggal).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'})}</td>
                                    <td>${jamDatang}</td>
                                    <td>${jamPulang}</td>
                                    <td>${badge}</td>
                                    <td>${absen.keterangan ?? '-'}</td>
                                    <td>
                                        <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#foto${absen.id}">
                                            <i class="ri-bar-chart-horizontal-fill"></i>
                                        </button>
                                        <div class="modal fade" id="foto${absen.id}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Bukti Absensi</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img src="${absen.foto ? `/${absen.foto}` : '{{ asset("assets/img/blank.jpg") }}'}" class="w-100">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @if(Auth::user()->jabatan->jabatan == 'admin')
                                    <td>
                                        <button class="btn btn-danger btn-sm deleteBtn" data-id="${absen.id}">
                                            <i class="ri ri-delete-bin-3-line"></i>
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                            `;
                    });
                } else {
                    tbody = `<tr><td colspan="9" class="text-center">Tidak ada data absensi</td></tr>`;
                }

                $("table tbody").html(tbody);
            },
            error: function() {
                $("table tbody").html(`
                <tr>
                    <td colspan="10" class="text-center text-danger">
                        Gagal memuat data
                    </td>
                </tr>
            `);
            }
        });
    }

    /* =========================
       DOCUMENT READY
    ========================= */
    $(document).ready(function() {

        // set bulan & tahun sekarang
        let bulanSekarang = new Date().getMonth() + 1;
        let tahunSekarang = new Date().getFullYear();

        $('#bulan').val(bulanSekarang);
        $('#tahun').val(tahunSekarang);

        // load awal
        loadData(bulanSekarang, tahunSekarang);

        /* =========================
           FILTER BULAN & TAHUN
        ========================= */
        $('#bulan, #tahun').on('change', function() {
            let bulan = $('#bulan').val();
            let tahun = $('#tahun').val();

            if (bulan && tahun) {
                loadData(bulan, tahun);
            }
        });

        /* =========================
           DELETE ABSENSI
        ========================= */
        $(document).on('click', '.deleteBtn', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Hapus Data Absensi?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('absensih') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire('Berhasil', res.message, 'success');

                                // 🔥 refresh tabel TANPA reload
                                loadData($('#bulan').val(), $('#tahun').val());
                            } else {
                                Swal.fire('Gagal', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Gagal menghapus data', 'error');
                        }
                    });
                }
            });
        });

    });

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