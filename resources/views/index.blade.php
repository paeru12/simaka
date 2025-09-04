@extends('layout.layout')
@section('title', 'Beranda')
@section('content')

<div class="pagetitle mt-3">
    <h1 class="text-capitalize">Hi, {{Auth::user()->name}}.</h1>
    <p>Welcome to your dashboard. Here you can manage your application.</p>
</div>

<section class="section dashboard">
    <div class="row">
        <div class="col-lg-6">
            <div class="card ">
                <div class="card-body">
                    <h5 class="card-title">Welcome to the Dashboard</h5>
                    <p>This is your dashboard where you can manage your application.</p>
                    @if(Auth::user()->role != 'admin')
                    <a href="{{route('absenqr.index')}}" class="btn btn-purple btn-sm"><i class="ri ri-qr-scan-line"></i> Absen QR Code</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-0 pb-1">Jumlah Guru & Staff</h6>
                            <h3 class="mb-0">10</h3>
                            <p class="mb-0">Total Guru & Staff</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-0 pb-1">Jumlah Kelas</h6>
                            <h3 class="mb-0">25</h3>
                            <p class="mb-0">Total Kelas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection