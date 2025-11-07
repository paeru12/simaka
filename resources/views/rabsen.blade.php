@extends('layout.layout')
@section('title', 'Rekap Absen Guru')
@section('content')

<div class="pagetitle">
    <h1>Rekap Absen Guru</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Rekap Absen</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Data Rekap Absen Guru</h5>
            <div class="row">
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
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Guru</th>
                                <!-- <th>Jabatan</th> -->
                                <th>Bulan</th>
                                <th>Total Mapel</th>
                                <th>Total Hadir</th>
                                <th>Total Izin</th>
                                <th>Total Sakit</th>
                                <th>Total Alpha</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
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
                    url: "{{ route('rekap.filter') }}",
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
                                    <td class="text-capitalize">${item.guru.nama}</td>
                                    <td>${$("#bulan option:selected").text()} ${tahun}</td>
                                    <td>${item.guru.total_mapel}</td>
                                    <td>${item.hadir}</td>
                                    <td>${item.izin}</td>
                                    <td>${item.sakit}</td>
                                    <td>${item.alfa}</td> 
                                    <td class="aksi">
                                        <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown">
                                            <i class="ri-bar-chart-horizontal-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                href="/rekap/${item.guru_id}/detail/${bulan}/${tahun}">
                                                    <i class="bi bi-pencil-square"></i>
                                                    <span>Detail</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            `;
                            });
                        } else {
                            tbody = `<tr><td colspan="9" class="text-center">No entries found</td></tr>`;
                        }
                        $("table tbody").html(tbody);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
                });
            }
        });
    });
</script>
@endsection