@extends('layout.layout')
@section('title', 'Slip Gaji Guru')
@section('content')
@php
$bulanTahun = \Carbon\Carbon::createFromDate(2025, 10, 1)->translatedFormat('F Y');
@endphp

<style>
    .signatures {
        page-break-inside: avoid;
        padding-top: 20px;
    }
</style>
<div class="pagetitle">
    <h1>Slip Gaji Guru</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{route('gaji.index')}}">Laporan Gaji</a></li>
            <li class="breadcrumb-item active">Slip Gaji Guru</li>
        </ol>
    </nav>
    <button type="button" id="printBtn" class="btn btn-purple btn-sm mt-2 d-print-none" onclick="downloadPDF()">Download
        PDF</button>
</div>
<section class="section dashboard">
    <div class="card recent-sales container">
        <div class="card-body" id="printArea" style="font-size: 13px;">
            <h5 class="card-title text-black text-center text-uppercase mb-0 pb-0">Slip Gaji guru</h5>
            <p class="card-text text-center">{{$bulanTahun}}</p>
            <table class="table-borderless mb-0">
                <tr>
                    <td>Nama</td>
                    <td class="text-capitalize">: Andriyani S.pd</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td class="text-capitalize">: Guru</td>
                </tr>
            </table>

            <h5 class="text-black card-title">Absensi Harian</h5>
            <table class="table mb-0">
                <thead>
                    <th scope="col">No.</th>
                    <th scope="col">Total Masuk</th>
                    <th scope="col">Total Izin</th>
                    <th scope="col">Total Sakit</th>
                    <th scope="col">Total Alpa</th>
                </thead>
                <tbody>
                    <!-- ulang -->
                    <tr>
                        <th scope="row">1.</th>
                        <td>20 x 25.000</td>
                        <td>0 x 10.000</td>
                        <td>0 x 15.000</td>
                        <td>0 x -25.000</td>
                    </tr>
                    <!-- end ulang -->
                    <tr>
                        <th scope="row">Penghasilan</th>
                        <td>500.000</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <th scope="row">Total Penghasilan</th>
                        <th colspan="4">500.000</th>
                    </tr>
                </tbody>
            </table>

            <h5 class="text-black card-title">Absensi Mata Pelajaran</h5>
            <table class="table mb-0">
                <thead>
                    <th>No.</th>
                    <th>Mata Pelajaran</th>
                    <th>Total Masuk</th>
                    <th>Total Izin</th>
                    <th>Total Sakit</th>
                    <th>Total Alpa</th>
                </thead>
                <tbody>
                    <!-- ulang -->
                    @for($i = 1; $i <= 20; $i++)
                        <tr>
                        <th>{{ $i }}.</th>
                        <td class="text-capitalize">jaringan</td>
                        <td>20 x 25.000</td>
                        <td>0 x 10.000</td>
                        <td>0 x 15.000</td>
                        <td>0 x -25.000</td>
                        </tr>
                        @endfor
                        <!-- end ulang -->

                        <tr>
                            <th colspan="2">Penghasilan</th>
                            <td>500.000</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <th colspan="2">Total Penghasilan</th>
                            <th colspan="4">500.000</th>
                        </tr>
                </tbody>
            </table>
            <div class="signatures">
                <h5 class="card-title text-dark">Penghasilan</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Gaji Pokok</th>
                            <th scope="col">Tunjangan</th>
                            <th scope="col">Absensi Harian</th>
                            <th scope="col">Absensi Mapel</th>
                            <th scope="col">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Rp.500.000</td>
                            <td>Rp.200.000</td>
                            <td>Rp.500.000</td>
                            <td>Rp.20.000</td>
                            <th>Rp.1.220.000</th>
                        </tr>
                    </tbody>

                </table>

                <div class="d-flex justify-content-center">
                    <table style="width: 80%; border: none;">
                        <tr class="d-flex justify-content-between align-items-end">
                            <td style="text-align: left; border: none;">
                                Diterima,
                            </td>
                            <td style="text-align: right; border: none;">
                                Malang, {{ now()->translatedFormat('d F Y') }}<br>
                                Bendahara Sekolah
                            </td>
                        </tr>
                        <tr class="d-flex justify-content-between align-items-end">
                            <td style="height: 80px; border: none;"></td>
                            <td style="border: none;"></td>
                        </tr>
                        <tr class="d-flex justify-content-between align-items-end">
                            <td style="text-align: left; border: none;" class="text-capitalize">
                                And S.Pd
                            </td>
                            <td style="text-align: right; border: none;" class="text-capitalize">
                                Sart S.Pd
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadPDF() {
        const element = document.getElementById('printArea');

        const opt = {
            margin: [40, 15, 15, 15], // Increased top margin to 40
            filename: 'slip-gaji.pdf',
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 4
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait'
            }
        };


        html2pdf().set(opt).from(element).toPdf().get('pdf').then(function(pdf) {
            const imgUrl = '{{ asset("assets/img/kopnew.jpg") }}'; // lokasi file kop surat
            const pageWidth = pdf.internal.pageSize.getWidth();

            // Menyesuaikan rasio 2560x487 agar tetap proporsional
            const imgWidth = 160;
            const imgHeight = (487 / 2560) * imgWidth; // hitung otomatis proporsi tinggi
            const xPos = (pageWidth - imgWidth) / 2; // Agar kop selalu di tengah halaman
            const yPos = 6;

            // Loop through all pages and add the header on each
            const totalPages = pdf.internal.pages.length;

            for (let i = 1; i <= totalPages; i++) {
                pdf.setPage(i);
                pdf.addImage(imgUrl, 'JPEG', xPos, yPos, imgWidth, imgHeight);
            }

            pdf.save();
        });
    }
</script>

@endsection