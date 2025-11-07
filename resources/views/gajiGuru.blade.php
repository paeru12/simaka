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
            <p>{{ Auth::user()->jabatan->jabatan }}</p>

            <div class="row">
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

                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bulan</th>
                                <th>Gapok</th>
                                <th>Total Mapel</th>
                                <th>Total Masuk</th>
                                <th>Total Gaji</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Total Gaji Tahun ini:</th>
                                <th colspan="2" id="total-gaji-seluruh-bulan">Rp.0</th>
                            </tr>
                        </tfoot>
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
                    url: "{{ route('gajiGuru.filter') }}",
                    type: "POST",
                    data: {
                        tahun: tahun,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        let tbody = "";
                        let totalTahunan = 0;

                        if (res.length > 0) {
                            res.forEach((item, index) => {
                                totalTahunan += item.total_gaji;

                                tbody += `
                                    <tr>
                                        <th>${index + 1}.</th>
                                        <td>${item.bulan}</td>
                                        <td>Rp.${item.gapok.toLocaleString('id-ID')}</td>
                                        <td>${item.total_mapel} Mapel</td>
                                        <td>${item.total_hadir} Kali</td>
                                        <td>Rp.${item.total_gaji.toLocaleString('id-ID')}</td>
                                        <td>
                                            <a href="/gaji/slip-gaji/${item.guru_id}/slip/${item.bulan_index}/${tahun}" 
                                               class="btn btn-sm btn-purple">
                                               Slip Gaji
                                            </a>
                                        </td>
                                    </tr>`;
                            });

                            // Tambahkan baris total di dalam tbody
                            tbody += `
                                <tr style="font-weight: bold; background-color: #f9f9f9;">
                                    <td colspan="5" class="text-end">Total Gaji Tahun ini:</td>
                                    <td colspan="2">Rp.${totalTahunan.toLocaleString('id-ID')}</td>
                                </tr>`;
                        } else {
                            tbody = `<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>`;
                        }

                        $("table tbody").html(tbody);
                        $("#total-gaji-seluruh-bulan").text(`Rp.${totalTahunan.toLocaleString('id-ID')}`);
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

        // Auto set tahun sekarang
        let tahunSekarang = new Date().getFullYear();
        $("#tahun").val(tahunSekarang).trigger("change");
    });
</script>
@endsection
