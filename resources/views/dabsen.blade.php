@extends('layout.layout')
@section('title', 'Detail Rekap Absen Guru')
@section('content')

<div class="pagetitle">
    <h1>Detail Rekap Absen Guru {{ucfirst($guru->nama)}}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Detail Rekap Absen Guru</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Detail Rekap Absen Guru {{ucfirst($guru->nama)}}</h5>
            <p class="card-text">{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</p>

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
                                <th>Alfa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensi as $d)
                            <tr>
                                <th>{{$loop->iteration}}.</th>
                                <td class="text-capitalize">{{ $d->mataPelajaran->nama_mapel }}</td>
                                <td>{{ $d->hadir }}</td>
                                <td>{{ $d->izin }}</td>
                                <td>{{ $d->sakit }}</td>
                                <td>{{ $d->alfa }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection