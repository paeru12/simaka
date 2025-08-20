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
            <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                Absensi
            </button>
            <div class="modal fade" id="addJadwalModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="absenForm" class="needs-validation" novalidate>
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add Jadwal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-floating mb-3">
                                        <select name="guru_id" id="guru_id" class="form-select" aria-label="Floating label select example" required>
                                            <option value="">Pilih Guru</option>
                                            @foreach($guru as $g)
                                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                            @endforeach
                                        </select>
                                        <label for="guru_id">Guru</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <select name="jadwal_id" id="jadwal_id" class="form-select" aria-label="Floating label select example" required>
                                            <option value="">Pilih Jadwal</option>
                                        </select>
                                        <label for="jadwal_id">Jadwal</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <select name="mapel_id" id="mapel_id" class="form-select" aria-label="Floating label select example" required>
                                            <option value="">Pilih Mapel</option>
                                        </select>
                                        <label for="mapel_id">Mata Pelajaran</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="date" name="tanggal" id="tanggal" class="form-control" placeholder="Tanggal" required>
                                        <label for="tanggal">Tanggal</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="time" name="jam_absen" id="jam_absen" class="form-control" placeholder="Jam Absen" required>
                                        <label for="jam_absen">Jam Absen</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="Hadir">Hadir</option>
                                            <option value="Izin">Izin</option>
                                            <option value="Sakit">Sakit</option>
                                            <option value="Alfa">Alfa</option>
                                        </select>
                                        <label for="status">Status</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan">
                                        <label for="keterangan">Keterangan</label>
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
            {{-- Alert --}}
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Form Tambah Absensi --}}


            {{-- Table Data Absensi --}}
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Guru</th>
                            <th>Mapel</th>
                            <th>Hari & Waktu</th>
                            <th>Tanggal</th>
                            <th>Jam Absen</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensi as $a)
                        <tr>
                            <th>{{ $loop->iteration }}.</th>
                            <td>{{ $a->guru->nama }}</td>
                            <td>{{ $a->mataPelajaran->nama_mapel }}</td>
                            <td>{{ $a->jadwal->hari }} - {{ $a->jadwal->jam_mulai }} s/d {{ $a->jadwal->jam_selesai }}</td>
                            <td>{{ $a->tanggal }}</td>
                            <td>{{ $a->jam_absen }}</td>
                            <td>{{ $a->status }}</td>
                            <td>{{ $a->keterangan }}</td>
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
    // Saat pilih guru → tampilkan jadwal
    $('#guru_id').on('change', function() {
        let guruId = $(this).val();
        if (guruId) {
            $.ajax({
                url: "{{ url('/absensi/get-data') }}/" + guruId,
                type: "GET",
                success: function(res) {
                    $('#jadwal_id').empty().append('<option value="">Pilih Jadwal</option>');
                    $('#mapel_id').empty().append('<option value="">Pilih Mapel</option>');
                    res.jadwal.forEach(function(j) {
                        $('#jadwal_id').append(
                            '<option value="' + j.id + '">' + j.hari + ' - ' + j.jam_mulai + ' s/d ' + j.jam_selesai + ' (' + j.kelas.kelas +' '+ j.kelas.rombel + ')</option>'
                        );
                    });
                    window.jadwalData = res.jadwal;
                }
            });
        } else {
            $('#jadwal_id').empty().append('<option value="">Pilih Jadwal</option>');
            $('#mapel_id').empty().append('<option value="">Pilih Mapel</option>');
        }
    });

    $('#jadwal_id').on('change', function() {
        let jadwalId = $(this).val();
        $('#mapel_id').empty().append('<option value="">Pilih Mapel</option>');

        if (jadwalId && window.jadwalData) {
            let selectedJadwal = window.jadwalData.find(j => j.id == jadwalId);
            if (selectedJadwal && selectedJadwal.mata_pelajaran) {
                $('#mapel_id').append(
                    '<option value="' + selectedJadwal.mata_pelajaran.id + '">' + selectedJadwal.mata_pelajaran.nama_mapel + '</option>'
                );
            }
        }
    });

    $(function() {
        // CREATE
        $('#absenForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('absensi.store') }}",
                type: "POST",
                data: $(this).serialize(),
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