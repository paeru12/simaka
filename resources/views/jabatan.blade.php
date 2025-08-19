@extends('layout.layout')
@section('title', 'Jabatan')
@section('content')

<div class="pagetitle">
    <h1>Absensi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Jabatan</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Data Jabatan</h5>
            <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addJabatanModal">
                Add Jabatan
            </button>

            {{-- Modal Add --}}
            <div class="modal fade" id="addJabatanModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="jabatanForm" novalidate>
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add Jabatan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="jabatan" placeholder="Jabatan">
                                    <label>Jabatan</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="gapok" placeholder="Gaji Pokok">
                                    <label>Gaji Pokok</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="tunjangan" placeholder="Tunjangan">
                                    <label>Tunjangan</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-purple" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jabatan</th>
                            <th>Gapok</th>
                            <th>Tunjangan</th>
                            <th>Alfa</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection