@extends('layout.layout')
@section('title', 'Detail Rekap Absen Guru')
@section('content')

<div class="pagetitle">
    <h1>Rekap Absen Guru {{ucfirst($guru->nama)}}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Rekap Absen Guru</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row">
        <div class="col-md-9">
            <div class="card recent-sales">
                <div class="card-body">
                    <h5 class="card-title mb-0">Rekap Absen Guru {{ucfirst($guru->nama)}}</h5>
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
                        <div class="table-responsive">
                            <table class="table table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mapel</th>
                                        <th>Hadir</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Alpha</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="">
                <div class="row align-items-center">
                    <div class="col-6 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title mb-0 pb-1">Total Hadir</h6>
                                <h5 class="mb-0" id="totalHadir">0</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title mb-0 pb-1">Total Izin</h6>
                                <h5 class="mb-0" id="totalIzin">0</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title mb-0 pb-1">Total Sakit</h6>
                                <h5 class="mb-0" id="totalSakit">0</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title mb-0 pb-1">Total Alpha</h6>
                                <h5 class="mb-0" id="totalAlpha">0</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#bulan, #tahun").on("change", function() {
            let bulan = $("#bulan").val();
            let tahun = $("#tahun").val();

            if (bulan && tahun) {
                $.ajax({
                    url: "{{ route('rekap.detailf') }}",
                    type: "POST",
                    data: {
                        bulan: bulan,
                        tahun: tahun
                    },
                    success: function(res) {
                        let tbody = "";
                        if (res.length > 0) {
                            res.forEach((item, index) => {
                                tbody += `
                                    <tr>
                                        <th>${index+1}.</th>
                                        <td class="text-capitalize">${item.mata_pelajaran?.nama_mapel ?? '-'}</td>
                                        <td>${item.hadir}</td>
                                        <td>${item.izin}</td>
                                        <td>${item.sakit}</td>
                                        <td>${item.alfa}</td>
                                    </tr>
                                    `;
                            });
                        } else {
                            tbody = `<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>`;
                        }
                        $("table tbody").html(tbody);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
                });

                $.ajax({
                    url: "{{ route('rekap.total') }}",
                    type: "POST",
                    data: {
                        bulan: bulan,
                        tahun: tahun,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $("#totalHadir").text(data.total_hadir ?? 0);
                        $("#totalIzin").text(data.total_izin ?? 0);
                        $("#totalSakit").text(data.total_sakit ?? 0);
                        $("#totalAlpha").text(data.total_alpha ?? 0);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            }
        });

        let tahunSekarang = new Date().getFullYear();
        let bulanSekarang = new Date().getMonth() + 1;
        $("#tahun").val(tahunSekarang).trigger("change");
        $("#bulan").val(bulanSekarang).trigger("change");
    });
</script>
@endsection