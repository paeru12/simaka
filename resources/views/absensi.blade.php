@extends('layout.layout')
@section('title', 'Absensi')
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

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Data Absensi</h5>
            @if(Auth::user()->role != 'admin')
            <a href="{{route('absenqr.index')}}" class="btn btn-purple btn-sm"><i class="ri ri-qr-scan-line"></i>
                <span>Absen QR Code</span>
            </a>
            <button type="button" class="btn btn-purple" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                Absensi
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
                                            <input type="time" name="jam_absen" id="jam_absen" class="form-control" placeholder="Jam Absen" value="{{ \Carbon\Carbon::now()->format('H:i') }}" required>
                                            <label for="jam_absen">Jam Absen</label>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="Izin">Izin</option>
                                            <option value="Sakit">Sakit</option>
                                        </select>
                                        <label for="status">Status</label>
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
            {{-- Table Data Absensi --}}
            <div class="table-responsive">
                <table class="table table-hover datatable">
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensi as $a)
                        <tr>
                            <th>{{ $loop->iteration }}.</th>
                            <td class="text-capitalize">{{ $a->user->name }}</td>
                            <td class="text-capitalize">{{ $a->jadwal->kelas->kelas }} {{ $a->jadwal->kelas->rombel }}</td>
                            <td class="text-capitalize">{{ $a->jadwal->ruangan->nama }}</td>
                            <td class="text-capitalize">{{ $a->mataPelajaran->nama_mapel }}</td>
                            <td>{{ $a->jadwal->hari }} - {{ \Carbon\Carbon::parse($a->jadwal->jam_mulai)->format('H:i') }} s/d {{ \Carbon\Carbon::parse($a->jadwal->jam_selesai)->format('H:i') }} WIB</td>
                            <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($a->jam_absen)->format('H:i') }} WIB</td>
                            <td>
                                @if($a->status == 'Alpha')
                                <span class="badge bg-danger">Alpha</span>
                                @elseif($a->status == 'Izin')
                                <span class="badge bg-warning text-dark">Izin</span>
                                @elseif($a->status == 'Sakit')
                                <span class="badge bg-info text-dark">Sakit</span>
                                @else
                                <span class="badge bg-success">Hadir</span>
                                @endif
                            </td>
                            <td>
                                @if($a->keterangan)
                                <p>{{ $a->keterangan }}</p>
                                @else
                                <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#foto{{ $a->id }}">
                                    <i class="ri-bar-chart-horizontal-fill"></i>
                                </button>
                                <div class="modal fade" id="foto{{ $a->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Bukti Absensi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img @if($a->foto) src="{{ asset($a->foto) }}" @else src="{{ asset('assets/img/blank.jpg') }}" @endif alt="Bukti Absen" class="w-100">
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        $('#hari').on('change', function() {
            let hari = $(this).val();
            $('#kelas').empty().append('<option value="">Pilih kelas</option>');
            $('#mapel_id').empty().append('<option value="">Pilih Mapel</option>');

            if (hari) {
                $.ajax({
                    url: "{{ route('absensi.getKelas') }}",
                    type: "GET",
                    data: {
                        hari: hari
                    },
                    beforeSend: function() {
                        // optional: kasih loading kecil
                        $('#kelas').append('<option disabled>Loading...</option>');
                    },
                    success: function(data) {
                        $('#kelas').empty().append('<option value="">Pilih kelas</option>');
                        data.forEach(j => {
                            $('#kelas').append(
                                `<option value="${j.kelas.id}" data-jadwal="${j.id}">
                            ${j.kelas.kelas} ${j.kelas.rombel}
                        </option>`
                            );
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire("Error", "Gagal mengambil data kelas", "error");
                    }
                });
            }
        });


        $('#kelas').on('change', function() {
            let kelasId = $(this).val();
            $('#mapel_id').empty().append('<option value="">Pilih Mapel</option>');

            if (kelasId) {
                $.get("{{ route('absensi.getMapel') }}", {
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

        // helper untuk format jam dari "08:08:00" -> "08:08"
        function formatJam(jam) {
            if (!jam) return '';
            let [h, m] = jam.split(':'); // ambil jam & menit saja
            return `${h}:${m}`;
        }


        // simpan jadwal_id otomatis ketika pilih mapel
        $('#mapel_id').on('change', function() {
            let jadwalId = $(this).find(':selected').data('jadwal');
            $('<input>').attr({
                type: 'hidden',
                name: 'jadwal_id',
                value: jadwalId
            }).appendTo('#absenForm');
        });

        // CREATE
        $('#absenForm').submit(function(e) {
            console.log($(this).serialize());
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            $.ajax({
                url: "{{ route('absensi.store') }}",
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
                }
            });
        });
    });
</script>
@endsection