@extends('layout.layout')
@section('title', 'Pengaturan')
@section('content')

<div class="pagetitle">
    <h1>Pengaturan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Pengaturan</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Pengaturan</h5>
            <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#addDataModal">
                Add Data
            </button>

            {{-- Modal Add --}}
            <div class="modal fade" id="addDataModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="dataForm" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add Data</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating mb-3">
                                    <select name="key" class="form-select text-capitalize" id="floatingSelect">
                                        <option selected disabled>Pilih Opsi</option>
                                        <option value="logo">Logo</option>
                                        <option value="nama">Nama</option>
                                        <option value="kop_surat">Kop Surat</option>
                                    </select>
                                    <label for="floatingSelect">Pilih Jenis</label>
                                </div>

                                <!-- input file -->
                                <div class="mb-3 d-none" id="file-input-container">
                                    <div class="row align-items-center">
                                        <div class="col-3">
                                            <img src="{{ asset('assets/img/blank.jpg') }}" class="w-100 shadow" alt="" id="gam">
                                        </div>
                                        <div class="col-9">
                                            <div class="input-group mb-3">
                                                <input type="file" class="form-control" id="input-type-file" name="value" onchange="readUrl(this)">
                                                <button class="btn btn-purple" type="button" onclick="hapusGambar()">
                                                    <i class="ri ri-delete-bin-6-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- input text -->
                                <div class="form-floating mb-3 d-none" id="text-input-container">
                                    <input type="text" class="form-control" name="value" placeholder="Value">
                                    <label>Value</label>
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
                            <th scope="col">No</th>
                            <th scope="col">Value</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-capitalize">
                        @foreach($settings as $s)
                        <tr>
                            <th scope="row">{{$loop->iteration}}.</th>
                            <td>
                                @if(in_array($s->key, ['logo','kop_surat']))
                                <img src="{{ asset($s->value) }}" alt="" class="img-fluid mt-2" style="max-height: 50px;">
                                @else
                                {{ $s->value }}
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-purple btn-sm edit-button" data-bs-toggle="modal" data-bs-target="#UpdateDataModal{{$s->id}}"><i class="ri ri-edit-line"></i> Edit</button>
                                {{-- Modal Update --}}
                                <div class="modal fade" id="UpdateDataModal{{$s->id}}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form class="updateDataForm" enctype="multipart/form-data" novalidate>
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="edit_id" value="{{$s->id}}">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update <span class="text-capitalize">{{$s->key}}</span></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">                                                   
                                                    {{-- input file --}}
                                                    <div class="mb-3 {{ in_array($s->key, ['logo','kop_surat']) ? '' : 'd-none' }}">
                                                        <div class="row align-items-center">
                                                            <div class="col-3">
                                                                <img src="{{ asset($s->value) }}" class="w-100 shadow" id="gam" alt="">
                                                            </div>
                                                            <div class="col-9">
                                                                <div class="input-group mb-3">
                                                                    <input type="file" class="form-control" name="value" onchange="readUrl(this)">
                                                                    <button class="btn btn-purple" type="button" onclick="hapusGambar()">
                                                                        <i class="ri ri-delete-bin-6-line"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- input text --}}
                                                    <div class="form-floating mb-3 {{ $s->key == 'nama' ? '' : 'd-none' }}">
                                                        <input type="text" class="form-control" name="value" value="{{ $s->value }}" placeholder="Value">
                                                        <label>Value</label>
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
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    const selectElement = document.getElementById('floatingSelect');
    const fileInputContainer = document.getElementById('file-input-container');
    const textInputContainer = document.getElementById('text-input-container');

    selectElement.addEventListener('change', function() {
        const value = this.value;

        // sembunyikan dulu
        fileInputContainer.classList.add('d-none');
        textInputContainer.classList.add('d-none');

        if (value === 'nama') {
            textInputContainer.classList.remove('d-none');
        } else if (value === 'logo' || value === 'kop_surat') {
            fileInputContainer.classList.remove('d-none');
        }
    });

    $(function() {
        // CREATE
        $('#dataForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('setting.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Menyimpan data ',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false
                    });
                },
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        $('#dataForm')[0].reset();
                        $('#addDataModal').modal('hide');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        Swal.fire("Gagal", res.message, "error");
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message
                    });
                }
            });
        });

        // UPDATE (gunakan class agar semua modal tertangani)
        $(document).on('submit', '.updateDataForm', function(e) {
            e.preventDefault();
            let form = this;
            let id = $(form).find('input[name="edit_id"]').val();
            let formData = new FormData(form);

            $.ajax({
                url: "{{ url('setting') }}/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                },
                beforeSend: () => Swal.fire({
                    title: 'Processing...',
                    didOpen: () => Swal.showLoading(),
                    allowOutsideClick: false
                }),
                success: function(res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire("Berhasil", res.message, "success");
                        form.reset();
                        $(form).closest('.modal').modal('hide');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        Swal.fire("Gagal", res.message, "error");
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message
                    });
                }
            });
        })
    });
</script>
@endsection