@extends('layout.layout')
@section('title', 'Beranda')
@section('content')

    <div class="pagetitle">
        <h1 class="text-capitalize">Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="">Home</a></li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Welcome to the Dashboard</h5>
                        <p>This is your dashboard where you can manage your application.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection