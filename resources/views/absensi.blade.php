@extends('layout.layout')
@section('title', 'Absensi Mapel')
@section('content')

<div class="pagetitle">
    <h1>Absensi Mapel</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Absensi Mapel </li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="card recent-sales">
        <div class="card-body">
            <h5 class="card-title mb-0">Data Absensi Mapel</h5>
            @if(Auth::user()->jabatan->jabatan != 'admin')

            @if($absen)
            <a href="{{route('absen-qr.index')}}" class="btn btn-purple mb-2"> Absen QR Code</a>
            @else
            <button type="button" class="btn btn-purple mb-2"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                title="Silahkan Absensi Harian"
                data-bs-custom-class="tooltip-ungu">Absen QR Code
            </button>
            @endif
            <button type="button" class="btn btn-purple mb-2" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                Absensi Izin/Sakit
            </button>
            <div class="modal fade" id="addJadwalModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="absenForm" class="needs-validation" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Absensi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <select name="hari" id="hari" class="form-select" aria-label="Floating label select example" required>
                                                <option value="">Pilih Hari</option>
                                                @foreach($hari as $h)
                                                <option value="{{ $h }}">{{ $h }}</option>
                                                @endforeach
                                            </select>
                                            <label for="hari">Hari</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <select name="kelas" id="kelas" class="form-select" aria-label="Floating label select example" required>
                                                <option value="">Pilih kelas</option>
                                            </select>
                                            <label for="kelas">kelas</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <select name="mapel_id" id="mapel_id" class="form-select" aria-label="Floating label select example" required>
                                                <option value="">Pilih Mapel</option>
                                            </select>
                                            <label for="mapel_id">Mata Pelajaran</label>
                                        </div>
                                    </div>
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
                                <button class="btn btn-purple" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-12">
                <div class="row needs-validation" novalidate>
                    <div class="col-4 mb-2">
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
                    <div class="col-4 mb-2">
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
                    <div class="col-4 mb-2">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="search" placeholder="Search">
                            <label for="search">Search</label>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Table Data Absensi --}}
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Guru</th>
                            <th>Kelas</th>
                            <th>Ruangan</th>
                            <th>Mapel</th>
                            <th>Hari & Waktu</th>
                            <th>Tanggal</th>
                            <th>Jam Absen</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Foto</th>
                            @if(Auth::user()->jabatan->jabatan == 'admin')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>

                @include('utils.pagination')
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(function() {
        $('#absenForm').submit(function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            $.ajax({
                url: "{{ route('absensi-mapel.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Menyimpan data Absensi',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#absenForm')[0].reset();
                        $('#addAbsenModal').modal('hide');
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

    $(function() {
        $('#hari').on('change', function() {
            let hari = $(this).val();
            $('#kelas').empty().append('<option value="">Pilih kelas</option>');
            $('#mapel_id').empty().append('<option value="">Pilih Mapel</option>');

            if (hari) {
                $.get("{{ route('absensi.getKelas') }}", {
                    hari: hari
                }, function(data) {
                    $('#kelas').empty().append('<option value="">Pilih kelas</option>');
                    data.forEach(j => {
                        $('#kelas').append(
                            `<option value="${j.kelas.id}">
                            ${j.kelas.kelas} ${j.kelas.rombel}
                        </option>`
                        );
                    });
                });
            }
        });

        $('#kelas').on('change', function() {
            let kelasId = $(this).val();
            let hari = $('#hari').val();
            $('#mapel_id').empty().append('<option value="">Pilih Mapel</option>');

            if (kelasId && hari) {
                $.get("{{ route('absensi.getMapel') }}", {
                    hari: hari,
                    kelas_id: kelasId
                }, function(data) {
                    data.forEach(j => {
                        let jamMulai = formatJam(j.jam_mulai);
                        let jamSelesai = formatJam(j.jam_selesai);
                        $('#mapel_id').append(
                            `<option value="${j.mata_pelajaran.id}" data-jadwal="${j.id}">
                            ${j.mata_pelajaran.nama_mapel} (${jamMulai} s/d ${jamSelesai} WIB)
                        </option>`
                        );
                    });
                });
            }
        });

        function formatJam(jam) {
            if (!jam) return '';
            let [h, m] = jam.split(':');
            return `${h}:${m}`;
        }

        $('#mapel_id').on('change', function() {
            let jadwalId = $(this).find(':selected').data('jadwal');
            $('input[name="jadwal_id"]').remove();
            $('<input>').attr({
                type: 'hidden',
                name: 'jadwal_id',
                value: jadwalId
            }).appendTo('#absenForm');
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
<script src="{{ asset('assets/js/render/absensiMapelRow.js') }}"></script>

{{-- PAGE --}}
<script src="{{ asset('assets/js/pages/absensi-mapel.js') }}"></script>
@endsection