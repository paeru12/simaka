@extends('layout.layout')
@section('title', 'Data Kelas')
@section('content')

<div class="pagetitle">
    <h1>Data Mapel</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Kelas</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                Data Kelas
            </h5>
            <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addKelasModal">
                Add Kelas
            </button>
            {{-- Modal Add --}}
            <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addMapelModal">
                Add Mapel
            </button>

            {{-- Modal Add --}}
            <div class="modal fade" id="addMapelModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="kelasForm" class="need-validation" novalidate>
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add Kelas</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="kelas" placeholder="Kelas">
                                    <label>Kelas</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="rombel" placeholder="Rombel">
                                    <label>Rombel</label>
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
                            <th>Kelas</th>
                            <th>Rombel</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelas as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $k->kelas }}</td>
                            <td>{{ $k->rombel }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editKelasModal{{ $k->id }}">Edit</button>
                                <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>

                                {{-- Modal Edit --}}
                                <div class="modal fade" id="editKelasModal{{ $k->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('kelas.update', $k->id) }}" method="POST" class="need-validation" novalidate>
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Kelas</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="kelas" value="{{ $k->kelas }}" placeholder="Kelas" required>
                                                        <label>Kelas</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="rombel" value="{{ $k->rombel }}" placeholder="Rombel" required>
                                                        <label>Rombel</label>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection