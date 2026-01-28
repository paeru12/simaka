@extends('layout.layout')
@section('title', 'Dashboard')
@section('content')
@php
$hour = now()->format('H');
if ($hour >= 5 && $hour < 12) {
    $salam='Selamat Pagi' ;
    } elseif ($hour>= 12 && $hour < 15) {
        $salam='Selamat Siang' ;
        } elseif ($hour>= 15 && $hour < 18) {
            $salam='Selamat Sore' ;
            } else {
            $salam='Selamat Malam' ;
            }
            $panggilan=Auth::user()->guru->jk === 'L' ? 'Pak' : 'Bu';
            @endphp
            <div class="pagetitle mt-3">
                <h1 class="text-capitalize">{{ $salam }}, {{ $panggilan }} {{ Auth::user()->guru->nama }}</h1>
                <p>Selamat datang di dashboard absensi Anda. Pantau kehadiran, izin, dan rekap absensi dengan mudah di sini.</p>
            </div>
            <div class="modal fade" id="absenharians" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form novalidate>
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
            <section class="section dashboard">
                <div class="row">
                    <div class="col-md-9">
                        <div class="col-12">
                            <div class="col-12">
                                <div class="card recent-sales">
                                    <div class="card-body">
                                        <h5 class="card-title mb-0">Absensi Harian <span>| Bulan Ini</span></h5>
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

                                        <div class="table-responsive">
                                            <table class="table datatable">
                                                <thead>
                                                    <th>No</th>
                                                    <th>Nama Guru</th>
                                                    <th>Tanggal</th>
                                                    <th>Jam Datang</th>
                                                    <th>Jam Pulang</th>
                                                    <th>Status</th>
                                                    <th>Foto</th>
                                                </thead>
                                                <tbody>
                                                    @forelse($absensi as $absen)
                                                    <tr>
                                                        <th>{{ $loop->iteration }}.</th>
                                                        <td class="text-capitalize">{{ $absen->guru->nama }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d M Y') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($absen->jam_datang)->format('H:i') }} WIB</td>
                                                        <td>
                                                            @if($absen->jam_pulang)
                                                            {{ \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i') }} WIB
                                                            @else
                                                            <p>-</p>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($absen->status == 'Alpha')
                                                            <span class="badge bg-danger">Alpha</span>
                                                            @elseif($absen->status == 'Izin')
                                                            <span class="badge bg-warning text-dark">Izin</span>
                                                            @elseif($absen->status == 'Sakit')
                                                            <span class="badge bg-info text-dark">Sakit</span>
                                                            @else
                                                            <span class="badge bg-success">Hadir</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#foto{{ $absen->id }}">
                                                                <i class="ri-bar-chart-horizontal-fill"></i>
                                                            </button>
                                                            <div class="modal fade" id="foto{{ $absen->id }}" tabindex="-1">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Bukti Absensi</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <img @if($absen->foto) src="{{ asset($absen->foto) }}" @else src="{{ asset('assets/img/blank.jpg') }}" @endif alt="Bukti Absen" class="w-100" loading="lazy">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">Tidak ada data absensi</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(Auth::user()->jabatan->jabatan == 'guru')
                            <div class="card recent-sales">
                                <div class="card-body">
                                    <h5 class="card-title mb-0">Absensi Mata Pelajaran
                                        @if(Auth::user()->jabatan->jabatan == 'admin')
                                        <span>| Hari Ini</span>
                                        @else
                                        <span>| Minggu Ini</span>
                                        @endif
                                    </h5>
                                    @if($absen) 
                                    @if(Auth::user()->jabatan->jabatan != 'admin')
                                    <a href="{{route('absen-qr.index')}}" class="btn btn-purple mb-3"><i class="ri ri-qr-scan-line "></i> Absen QR Code</a>
                                    @endif
                                    @else
                                    <button type="button" class="btn btn-purple mb-3"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="bottom"
                                        title="Silahkan Absensi Harian"
                                        data-bs-custom-class="tooltip-ungu">
                                        <i class="ri ri-qr-scan-line"></i> Absen QR Code
                                    </button>
                                    @endif
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover datatable">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No</th>
                                                    @if(Auth::user()->jabatan->jabatan == 'admin')
                                                    <th scope="col">Guru</th>
                                                    @else
                                                    <th scope="col">Hari</th>
                                                    @endif
                                                    <th scope="col">Mata Pelajaran</th>
                                                    <th scope="col">Kelas</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Foto</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-capitalize">
                                                @foreach($data as $a)
                                                <tr>
                                                    <th scope="row">{{$loop->iteration}}.</th>
                                                    @if(Auth::user()->jabatan->jabatan == 'admin')
                                                    <td>{{ $a->guru->nama }}</td>
                                                    @else
                                                    <td>{{ $a->jadwal->hari }}</td>
                                                    @endif
                                                    <td>{{ $a->mataPelajaran->nama_mapel }}</td>
                                                    <td class="text-uppercase">{{ $a->jadwal->kelas->kelas }} {{ $a->jadwal->kelas->jurusan->nama }} {{ $a->jadwal->kelas->rombel }}</td>
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
                                                                        <img @if($a->foto) src="{{ asset($a->foto) }}" @else src="{{ asset('assets/img/blank.jpg') }}" @endif alt="Bukti Absen" class="w-100" loading="lazy">
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
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 d-none d-md-block">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-0 pb-1">Jumlah Guru & Staff</h6>
                                        <h3 class="mb-0">{{ $guru }}</h3>
                                        <p class="mb-0">Total Guru & Staff</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-0 pb-1">Jumlah Kelas</h6>
                                        <h3 class="mb-0">{{ $kelas }}</h3>
                                        <p class="mb-0">Total Kelas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-0 pb-1">Jumlah Ruangan</h6>
                                        <h3 class="mb-0">{{ $ruangan }}</h3>
                                        <p class="mb-0">Total Ruangan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-0 pb-1">Jumlah Mapel</h6>
                                        <h3 class="mb-0">{{ $mapel }}</h3>
                                        <p class="mb-0">Total Mapel</p>
                                    </div>
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

            @push('scripts')
            <script src="{{ asset('assets/js/main2.js') }}"></script>
            @endpush