@extends('layout.layout')
@section('title', 'Dashboard')
@section('content')

<div class="pagetitle mt-3">
    <h1 class="text-capitalize">Hi, {{Auth::user()->name}}.</h1>
    <p>Welcome to your dashboard. Here you can manage your application.</p>
</div>

<section class="section dashboard">
    <div class="row">
        <div class="col-lg-9">
            <div class="card ">
                <div class="card-body">
                    <h5 class="card-title">Welcome to the Dashboard</h5>
                    <p>This is your dashboard where you can manage your application.</p>
                    @if(Auth::user()->role != 'admin')
                    <a href="{{route('absenqr.index')}}" class="btn btn-purple btn-sm"><i class="ri ri-qr-scan-line"></i> Absen QR Code</a>
                    @endif


                </div>
            </div>
            <div class="col-12">
                <div class="card recent-sales">

                    <div class="card-body">
                        <h5 class="card-title">Absensi <span>| Hari Ini</span></h5>                       
                        <div class="table-responsive">
                            <table class="table table-hover datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Guru</th>
                                        <th scope="col">Mata Pelajaran</th>
                                        <th scope="col">Kelas</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize">
                                    @foreach($data as $a)
                                    <tr>
                                        <th scope="row">{{$loop->iteration}}.</th>
                                        <td>{{ $a->user->name }}</td>
                                        <td>{{ $a->mataPelajaran->nama_mapel }}</td>
                                        <td class="text-uppercase">{{ $a->jadwal->kelas->kelas }} {{ $a->jadwal->kelas->jurusan->nama }} {{ $a->jadwal->kelas->rombel }}</td>
                                        <td>
                                            @if($a->status == 'Alpha')
                                            <span class="badge bg-danger">Alpha</span>
                                            @elseif($a->status == 'Izin')
                                            <span class="badge bg-warning text-dark">Izin</span>
                                            @elseif($a->status == 'Sakit')
                                            <span class="badge bg-info text-dark">Sakit</span>
                                            @else
                                            <span class="badge bg-success">Hadir</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div><!-- End Recent Sales -->

        </div>
        <div class="col-lg-3">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-0 pb-1">Jumlah Guru & Staff</h6>
                            <h3 class="mb-0">{{ $guru }}</h3>
                            <p class="mb-0">Total Guru & Staff</p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-0 pb-1">Jumlah Kelas</h6>
                            <h3 class="mb-0">{{ $kelas }}</h3>
                            <p class="mb-0">Total Kelas</p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-0 pb-1">Jumlah Ruangan</h6>
                            <h3 class="mb-0">{{ $ruangan }}</h3>
                            <p class="mb-0">Total Ruangan</p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-0 pb-1">Jumlah Mapel</h6>
                            <h3 class="mb-0">{{ $mapel }}</h3>
                            <p class="mb-0">Total Mapel</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection