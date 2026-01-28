@extends('layout.layout')
@section('title', 'Slip Gaji Guru')
@section('content')
@php
$bulanTahun = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');
@endphp
<div class="pagetitle">
    <h1>Slip Gaji {{ ucfirst($guru->jabatan->jabatan) }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{route('laporan-gaji.index')}}">Laporan Gaji</a></li>
            <li class="breadcrumb-item active">Slip Gaji</li>
        </ol>
    </nav>
    <button type="button" id="printBtn" class="btn btn-purple btn-sm mt-2 d-print-none" onclick="downloadPDF()">Download
        PDF</button>
</div>
<section class="section">
    <div class="card">
        <div class="card-body" id="printArea">
            <div style="text-align: center; padding-bottom: 10px; margin-bottom: 20px; margin-top:10;">
                <img src="{{ asset('assets/img/kopnew.jpg') }}" alt="Kop Surat" style="width:100%;">
            </div>
            <h5 class="card-title text-black text-center text-uppercase mb-0 pb-0">Slip Gaji</h5>
            <p class="card-text text-center">{{$bulanTahun}}</p>
            <table style="width:100%; border:none;">

                <tr>
                    <td>Nama</td>
                    <td class="text-capitalize">: {{ $guru->nama }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td class="text-capitalize">: {{ $guru->jabatan->jabatan }}</td>
                </tr>

                <tr>
                    <td colspan="2">
                        <h6 class="text-black fw-semibold">Penghasilan (A)</h6>
                    </td>
                </tr>

                <tr>
                    <td>Gaji Pokok</td>
                    <td>: Rp. {{ number_format($gapok, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Tunjangan</td>
                    <td>: Rp. 0</td>
                </tr>
                <tr>
                    <td>Total Penghasilan (A)</td>
                    <td>: Rp. {{ number_format($gapok, 0, ',', '.') }}</td>
                </tr>
            </table>
            @if($guru->jabatan->jabatan == 'guru')
            <h6 class="text-black fw-semibold">Penghasilan Mengajar (B)</h6>
            <table style="width:100%; border:none;" class="mt-2">
                <thead>
                    <th>No.</th>
                    <th>Mata Pelajaran</th>
                    <th>Total Masuk x Honor per Mapel</th>
                    <th>Total Honor</th>
                </thead>
                <tbody>
                    @forelse($absensi as $item)
                    <tr>
                        <th>{{ $loop->iteration }}.</th>
                        <td class="text-capitalize">{{ $item->mataPelajaran->nama_mapel }}</td>
                        <td>{{ $item->hadir }} x {{ number_format($gajiMengajar, 0, ',', '.') }}</td>
                        <td>Rp. {{ number_format($item->hadir * $gajiMengajar, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                    @if($absensi)
                    <tr>
                    <tr>
                        <td colspan="3">Total Penghasilan (B)</td>
                        <td>Rp. {{ number_format($totalMengajar, 0, ',', '.') }}</td>
                    </tr>

                    </tr>
                    @endif
                </tbody>
            </table>
            @endif
            <div class="potongan">
                @if($guru->jabatan->jabatan == 'guru')
                <h6 class="text-black fw-semibold mt-3">Potongan (C)</h6>
                @else
                <h6 class="text-black fw-semibold mt-3">Potongan (B)</h6>
                @endif
                <table style="width:100%; border:none;" class="mt-2">
                    <thead>
                        <th>Jenis Potongan</th>
                        <th>Jumlah</th>
                    </thead>
                    <tbody>
                        @forelse($potongan as $item)
                        <tr>
                            <td class="text-capitalize">{{ $item->nama_potongan }}</td>
                            <td>Rp. {{ number_format($item->jumlah_potongan, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                        @if($potongan)
                        <tr>
                        <tr>
                            @if($guru->jabatan->jabatan == 'guru')
                            <td>Total Potongan (C)</td>
                            @else
                            <td>Total Potongan (B)</td>
                            @endif
                            <td>Rp. {{ number_format($totalPotongan, 0, ',', '.') }}</td>
                        </tr>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @if($guru->jabatan->jabatan == 'guru')
            <h6 class="text-black fw-bold">
                Total Penghasilan (A + B - C): Rp. {{ number_format($totalAkhir, 0, ',', '.') }}
            </h6>
            @else
            <h6 class="text-black fw-bold">
                Total Penghasilan (A - B): Rp. {{ number_format($totalAkhir, 0, ',', '.') }}
            </h6>
            @endif
            <div class="d-flex justify-content-center mt-5">
                <table style="width: 80%; border: none;">
                    <tr class="d-flex justify-content-between align-items-end">
                        <td style="text-align: left; border: none;">
                            
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
                            
                        </td>
                        <td style="text-align: right; border: none;" class="text-capitalize">
                            {{$jabatan->nama}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadPDF() {
        const element = document.getElementById('printArea');
        const opt = {
            margin: [0, 0, 0, 0],
            filename: 'slip-gaji-{{$guru->nama}}-{{$bulanTahun}}.pdf',
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

        html2pdf().set(opt).from(element).save();
    }
</script>
@endsection