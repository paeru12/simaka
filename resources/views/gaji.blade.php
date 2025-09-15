@extends('layout.layout')
@section('title', 'Gaji Guru')
@section('content')

<div class="pagetitle">
    <h1>Gaji Guru</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Gaji Guru</li>
        </ol>
    </nav>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Gaji Guru</h5>
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
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Gapok</th>
                                <th>Tunjangan</th>
                                <th>Total Mapel</th>
                                <th>Total Masuk</th>
                                <th>Total Gaji</th>
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
                    url: "{{ route('gajians.filter') }}",
                    type: "POST",
                    data: {
                        bulan: bulan,
                        tahun: tahun
                    },
                    success: function(res) {
                        let tbody = "";
                        if (res.length > 0) {
                            res.forEach((item, index) => {
                                let gapok = Number(item.gapok ?? 0);
                                let tunjangan = Number(item.tunjangan ?? 0);
                                let honorMapel = Number(item.gaji ?? (item.total_hadir * 8000));
                                let totalGaji = Number(item.total_gaji ?? (gapok + tunjangan + honorMapel));

                                tbody += `
                                        <tr>
                                            <th>${index+1}.</th>
                                            <td class="text-capitalize">${item.nama}</td>
                                            <td class="text-capitalize">${item.jabatan ?? '-'}</td>
                                            <td>Rp.${gapok.toLocaleString('id-ID')}</td>
                                            <td>Rp.${tunjangan.toLocaleString('id-ID')}</td>
                                            <td>${item.total_mapel} Mapel</td>
                                            <td>${item.total_hadir} Kali</td>
                                            <td>Rp.${totalGaji.toLocaleString('id-ID')}</td>
                                            <td class="aksi">
                                                <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown">
                                                    <i class="ri-bar-chart-horizontal-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center" href="/gaji/slip-gaji/${item.guru_id}/slip/${bulan}/${tahun}">
                                                            <i class="bi bi-pencil-square"></i>
                                                            <span>Slip Gaji</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        `;
                            });
                        }
                        $("table tbody").html(tbody);
                    },

                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseText
                        });
                    }
                });
            }
        });
    });
</script>
@endsection