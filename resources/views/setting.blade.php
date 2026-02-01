@extends('layout.layout')
@section('title', 'Pengaturan')
@section('content')
<style>
    .setting-card {
        transition: all .25s ease;
        border-radius: 14px !important;
        background: white;
        border: 1px solid #eeeeee;
    }

    .setting-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        border-color: #b184f5 !important;
    }

    .setting-icon {
        font-size: 24px;
        color: #6d28d9;
        background: #f3e8ff;
        padding: 10px;
        border-radius: 8px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 45px;
        height: 45px;
    }
</style>

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

    <div class="row g-3">

        @foreach($settings as $s)

        @php
        // Daftar icon setiap key
        $icons = [
        'nama' => 'ri-user-3-line',
        'alamat_ip' => 'ri-wifi-line',
        'gaji_mengajar' => 'ri-money-dollar-circle-line',
        'minggu' => 'ri-calendar-2-line',
        'jp' => 'ri-time-line',
        'logo' => 'ri-image-line',
        'kop_surat' => 'ri-file-paper-2-line',
        ];

        $icon = $icons[$s->key] ?? 'ri-settings-3-line';
        @endphp

        <div class="col-md-4 col-lg-3">
            <div class="setting-card shadow-sm border p-3 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex gap-2 align-items-center">
                    {{-- Icon --}}
                    <div class="mb-2">
                        <i class="{{ $icon }} setting-icon"></i>
                    </div>

                    {{-- Nama --}}
                    <h6 class="fw-bold mt-2 text-capitalize">
                        {{ ucwords(str_replace('_', ' ', $s->key)) }}
                    </h6>
                </div>
                
                {{-- Value --}}
                <div class="mt-2 mb-3">

                    @if(in_array($s->key, ['logo','kop_surat']))
                    <img src="{{ asset($s->value) }}"
                        class="img-fluid rounded shadow-sm"
                        style="max-height:70px; object-fit:contain;">
                    @else
                    @if($s->key == 'gaji_mengajar')
                    <span class="fw-semibold text-dark">Rp. {{ number_format($s->value, 0, ',', '.') }}</span>
                    @else
                    <span class="fw-semibold text-dark">{{ $s->value }}</span>
                    @endif
                    @endif

                </div>

                {{-- Edit Button --}}
                <button class="btn btn-purple btn-sm w-100 mt-auto"
                    data-bs-toggle="modal"
                    data-bs-target="#UpdateDataModal{{$s->id}}">
                    <i class="ri-edit-2-line me-1"></i> Edit
                </button>


            </div>
        </div>

        @endforeach
    </div>

</section>
@foreach($settings as $s)
{{-- Modal Update --}}
<div class="modal fade" id="UpdateDataModal{{$s->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="updateDataForm" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_id" value="{{$s->id}}">
                <input type="hidden" name="key" value="{{$s->key}}">

                <div class="modal-header">
                    <h5 class="modal-title">Update
                        <span class="text-capitalize">{{ ucwords(str_replace('_', ' ', $s->key)) }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- Input File --}}
                    @if(in_array($s->key, ['logo','kop_surat']))
                    <div class="mb-3">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <img src="{{ asset($s->value) }}" class="w-100 shadow" id="preview-{{ $s->id }}" alt="">
                            </div>
                            <div class="col-9">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" name="value" onchange="previewImage(this, '{{ $s->id }}')">
                                    <button class="btn btn-purple" type="button" onclick="hapusGambar('{{ $s->id }}')">
                                        <i class="ri ri-delete-bin-6-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Input Text --}}
                    @elseif(in_array($s->key, ['nama','gaji_mengajar','minggu','jp', 'alamat_ip']))
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="value"
                            value="{{ $s->value }}" placeholder="Value">
                        <label>Value</label>
                    </div>
                    @endif

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-purple" type="submit">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endforeach
<script>
    function previewImage(input, id) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = e => {
                document.getElementById("preview-" + id).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function hapusGambar(id) {
        document.getElementById("preview-" + id).src = "";
        let fileInput = document.querySelector(`input[name="value"][onchange="previewImage(this, '${id}')"]`);
        if (fileInput) {
            fileInput.value = "";
        }
    }
    $(function() {

        // ==== Simpan Data Baru ====
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
                        text: 'Menyimpan data',
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
                    Swal.fire("Error", xhr.responseJSON.message, "error");
                }
            });
        });

        // ==== Update Data ====
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
                    Swal.fire("Error", xhr.responseJSON.message, "error");
                }
            });
        });

    });
</script>

@endsection