@extends('layout.layout')
@section('title', 'Gaji Guru & Staff')
@section('content')

<div class="pagetitle">
    <h1>Gaji Guru & Staff</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Gaji Guru & Staff</li>
        </ol>
    </nav>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Gaji Guru & Staff</h5>
            <div class="row">
                <div class="col-12">
                    <div class="row needs-validation" novalidate>
                        <div class="col-md-4 mb-2">
                            <div class="form-floating">
                                <select class="form-select" id="bulan">
                                    <option value="">-- Pilih Bulan --</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                        @endfor
                                </select>
                                <label for="bulan">Pilih Bulan</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
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
                        <div class="col-md-4 mb-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="search" placeholder="Search">
                                <label for="search">Search</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Gapok</th>
                                <th>Total Hadir Mapel</th>
                                <th>Total Masuk</th>
                                <th>Total Gaji</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                    @include('utils.pagination')
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
@section('scripts')
<script>
    const BASE_URL = "{{ asset('') }}";
    const IS_ADMIN = {{ Auth::user()->jabatan->jabatan === 'admin' ? 'true' : 'false' }};
</script>

{{-- UTILS --}}
<script src="{{ asset('assets/js/utils/date.js') }}"></script>
<script src="{{ asset('assets/js/utils/debounce.js') }}"></script>
<script src="{{ asset('assets/js/utils/pagination.js') }}"></script>
<script src="{{ asset('assets/js/utils/datainfo.js') }}"></script>

{{-- PAGE --}}
<script src="{{ asset('assets/js/pages/laporan-gaji.js') }}"></script>
@endsection