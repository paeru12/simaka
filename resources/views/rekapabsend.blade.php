@extends('layout.layout')
@section('title', 'Rekap Absen Guru & Staff')
@section('content')

<div class="pagetitle">
    <h1>Rekap Absen Guru & Staff</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Rekap Absen</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="card recent-sales">
        <div class="card-body">
            <h5 class="card-title mb-0">Data Rekap Absen Guru & Staff</h5>
            <div class="row">
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

                <div class="table-responsive mt-3">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Bulan</th>
                                <th>Total Mapel</th>
                                <th>Total Hadir</th>
                                <th>Total Izin</th>
                                <th>Total Sakit</th>
                                <th>Total Alpha</th>
                                <th>Total Hadir Mapel</th>
                                <th>Total Kehadiran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    $(document).ready(function() {
        $("#bulan, #tahun").on("change", function() {
            let bulan = $("#bulan").val();
            let tahun = $("#tahun").val();

            if (bulan && tahun) {
                $.ajax({
                    url: "{{ route('rekap-filter') }}",
                    type: "POST",
                    data: { bulan, tahun },
                    success: function(res) {
                        let tbody = "";
                        if (res.length > 0) {
                            res.forEach((item, index) => {
                                tbody += `
                                    <tr>
                                        <th>${index + 1}.</th>
                                        <td class="text-capitalize">${item.nama}</td>
                                        <td class="text-capitalize">${item.jabatan}</td>
                                        <td>${$("#bulan option:selected").text()} ${tahun}</td>
                                        <td>${item.total_hadir_mapel}</td>
                                        <td>${item.total_hadir_harian}</td>
                                        <td>${item.total_izin}</td>
                                        <td>${item.total_sakit}</td>
                                        <td>${item.total_alpha}</td>
                                        <td>${item.total_hadir_mapel}</td>
                                        <td>${item.total_kehadiran}</td>
                                        <td>
                                         
                                            <a href="/detail/${item.guru_id}/${bulan}/${tahun}" class="btn btn-sm btn-purple">
                                                <i class="ri-bar-chart-horizontal-fill"></i>
                                            </a>
                                        </td> 
                                    </tr>`; 
                            });
                        } else {
                            tbody = `<tr><td colspan="10" class="text-center">Tidak ada data</td></tr>`;
                        }
                        $("table tbody").html(tbody);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
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
