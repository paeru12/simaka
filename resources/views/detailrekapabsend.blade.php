@extends('layout.layout')
@section('title', 'Detail Rekap Absen')
@section('content')

<div class="pagetitle">
    <h1>Rekap Absen {{ucfirst($guru->jabatan->jabatan)}} {{ucfirst($guru->nama)}}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Rekap Absen {{ucfirst($guru->jabatan->jabatan)}}</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row">
        <div class="col-md-12">
            <div class="card recent-sales">
                <div class="card-body">
                    <h5 class="card-title mb-0">Rekap Absen {{ucfirst($guru->jabatan->jabatan)}} {{ucfirst($guru->nama)}}</h5>
                    <div class="col-8">
                        <div class="row needs-validation" novalidate>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <select class="form-select" id="bulan" aria-label="Floating label select example">
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
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

                    <div class="row">
                        <div class="col-12">
                            <h5 class="card-title">Absensi Harian</h5>
                            <table class="table table-hover ">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Hadir</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Alpha</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize" id="absensiHarian">
                                </tbody>
                            </table>
                        </div>
                    </div>
                     @if($guru->jabatan->jabatan == 'guru')
                    <div class="row">
                        <div class="table-responsive">
                            <h5 class="card-title">Absensi Mata Pelajaran</h5>
                            <table class="table table-hover ">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mapel</th>
                                        <th>Hadir</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Alpha</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize" id="absensiMapel">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
    $(document).ready(function() {
        function loadData(bulan, tahun) {
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();
            var guru_id = '{{ $guru->id }}';

            if (bulan && tahun) {
                $.ajax({
                    url: '/detailrekapdata/' + guru_id + '/' + bulan + '/' + tahun,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var absensiMapel = '';
                        if (data.rekap.length > 0) {
                            $.each(data.rekap, function(index, item) {
                                absensiMapel += `
                                <tr>
                                    <th scope="row">${index + 1}.</th>
                                    <td>${item.mata_pelajaran.nama_mapel}</td>
                                    <td>${item.hadir}</td>
                                    <td>${item.izin}</td>
                                    <td>${item.sakit}</td>
                                    <td>${item.alfa}</td>
                                    <td>${parseInt(item.hadir) + parseInt(item.izin) + parseInt(item.sakit) + parseInt(item.alfa)}</td>
                                </tr>
                            `;
                            });
                        } else {
                            absensiMapel = `
                                <tr>
                                    <th scope="row">1.</th>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            `;
                        }
                        $('#absensiMapel').html(absensiMapel);
                        var harianTbody = '';
                        if (data.rekapHarian[0].hadir == null) {
                            harianTbody = `
                                <tr>
                                    <th scope="row">1.</th>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            `;
                        } else {
                            $.each(data.rekapHarian, function(index, item) {
                                harianTbody += `
                                <tr>
                                    <th scope="row">${index + 1}.</th>
                                    <td>${item.hadir}</td>
                                    <td>${item.izin}</td>
                                    <td>${item.sakit}</td>
                                    <td>${item.alfa}</td>
                                    <td>${parseInt(item.hadir) + parseInt(item.izin) + parseInt(item.sakit) + parseInt(item.alfa)}</td>
                                </tr>
                            `;
                            });
                        }
                        $('#absensiHarian').html(harianTbody);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }
        };

        // Otomatis isi bulan & tahun sekarang
        let bulanSekarang = new Date().getMonth() + 1;
        let tahunSekarang = new Date().getFullYear();
        $('#bulan').val(bulanSekarang);
        $('#tahun').val(tahunSekarang);

        // Jalankan filter pertama kali
        loadData(bulanSekarang, tahunSekarang);

        $('#bulan, #tahun').on('change', function() {
            let bulan = $('#bulan').val();
            let tahun = $('#tahun').val();

            if (bulan && tahun) {
                loadData(bulan, tahun);
            }
        });
    });
</script>
@endsection