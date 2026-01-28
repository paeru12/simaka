@extends('layout.layout')
@section('title', 'Detail Rekap Absen '.ucfirst(Auth::user()->jabatan->jabatan))
@section('content')

<div class="pagetitle">
    <h1 class="text-capitalize">Rekap Absen {{Auth::user()->jabatan->jabatan}} </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active text-capitalize">Rekap Absen {{Auth::user()->jabatan->jabatan}}</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0 text-capitalize">Rekap Absen {{Auth::user()->jabatan->jabatan}} </h5>
            <div class="col-8">
                <div class="row needs-validation" novalidate>

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
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bulan</th>
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
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#tahun").on("change", function() {
            let tahun = $("#tahun").val();

            if (tahun) {
                $.ajax({
                    url: "{{ route('rekap.filterAll') }}",
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
            }
        });
        let tahunSekarang = new Date().getFullYear();
        $("#tahun").val(tahunSekarang).trigger("change");
    });
</script>
@endsection