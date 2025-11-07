@extends('layout.layout')
@section('title', 'Gaji ' . ucfirst(Auth::user()->jabatan->jabatan))
@section('content')

<div class="pagetitle">
    <h1>Gaji {{ ucfirst(Auth::user()->jabatan->jabatan) }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Laporan Gaji</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Gaji {{ ucfirst(Auth::user()->jabatan->jabatan) }}</h5>

            <div class="col-md-4 mb-3">
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

            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Total Masuk</th>
                            <th>Total Gaji</th>
                            <th>Slip Gaji</th>
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
    $(document).ready(function() {
        $("#tahun").on("change", function() {
            let tahun = $(this).val();
            if (tahun) {
                $.ajax({
                    url: "{{ route('gaji.filterAll') }}",
                    type: "POST",
                    data: {
                        tahun: tahun
                    },
                    success: function(res) {
                        let tbody = "";
                        if (res.length > 0) {
                            res.forEach((item, index) => {
                                tbody += `
                                    <tr>
                                        <th>${index+1}.</th>
                                        <td>${item.bulan}</td>
                                        <td>${item.total_hadir} Kali</td>
                                        <td>Rp.${item.total_gaji.toLocaleString('id-ID')}</td>
                                        <td>
                                            <a href="/gaji/slip-gaji/${item.guru_id}/slip/${item.bulan_index}/${tahun}" 
                                            class="btn btn-sm btn-purple">
                                            <i class="ri-bar-chart-horizontal-fill"></i>
                                            </a>
                                        </td>
                                    </tr>`;
                            });
                        } else {
                            tbody = `<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>`;
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
        $("#tahun").val(tahunSekarang).trigger("change");
    });
</script>
@endsection