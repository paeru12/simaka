@extends('layout.layout')
@section('title', 'Absensi QR')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-body text-center">
            <h5 class="card-title">Absensi QR</h5>
            <p class="card-text">Arahkan kamera ke QR Code kelas untuk melakukan absensi.</p>

            <video id="preview" class="border border-2 rounded w-100" style="max-height: 300px;" autoplay playsinline></video>
            <canvas id="canvas" class="d-none"></canvas>

            <button id="captureBtn" class="btn btn-purple mt-3">Ambil Foto Bukti</button>

            <div id="photoPreview" class="mt-3 d-none">
                <h6>Foto Bukti</h6>
                <img id="capturedImage" src="" alt="Foto bukti" class="img-thumbnail" style="max-width: 200px;">
            </div>

            <div id="statusMessage" class="mt-3"></div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const video = document.getElementById('preview');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext("2d", {
        willReadFrequently: true
    });
    const captureBtn = document.getElementById('captureBtn');
    const photoPreview = document.getElementById('photoPreview');
    const capturedImage = document.getElementById('capturedImage');
    let qrToken = null;
    let stream;
    let scanning = true;

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: {
                        ideal: "environment"
                    }
                }
            })
            .catch(() => navigator.mediaDevices.getUserMedia({
                video: true
            }))
            .then(function(s) {
                stream = s;
                video.srcObject = stream;
                video.play();
                requestAnimationFrame(scanQRCode);
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Kamera tidak dapat diakses',
                    text: err.message
                });
            });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Browser tidak mendukung akses kamera'
        });
    }

    function scanQRCode() {
        if (!scanning) return;

        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "dontInvert"
            });

            if (code) {
                qrToken = code.data;
                validJadwal = false;
                $.ajax({
                    url: "{{ route('absensi.validate') }}",
                    method: "POST",
                    data: JSON.stringify({
                        token: qrToken
                    }),
                    contentType: "application/json",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    success: function(res) {
                        if (res.status === 'ok') {
                            scanning = false;
                            validJadwal = true;
                            Swal.fire({
                                icon: 'info',
                                title: 'Konfirmasi Jadwal',
                                html: `
                                <b>Mapel:</b> <span class="text-capitalize">${res.jadwal.mapel}</span><br>
                                <b>Kelas:</b> <span class="text-uppercase">${res.jadwal.kelas}</span><br>
                                <b>Ruangan:</b> <span class="text-uppercase">${res.jadwal.ruangan}</span><br>
                                <b>Jam:</b> <span class="text-capitalize">${res.jadwal.jam_mulai} - ${res.jadwal.jam_selesai} WIB</span><br><br>
                                Lanjut absen untuk jadwal ini?
                            `,
                                showCancelButton: true,
                                confirmButtonText: 'Ya, lanjut',
                                cancelButtonText: 'Batal'
                            }).then(result => {
                                if (!result.isConfirmed) {
                                    validJadwal = false;
                                    scanning = true;
                                    requestAnimationFrame(scanQRCode);
                                } else {
                                    Swal.fire('Silakan ambil foto bukti!', '', 'info');
                                }
                            });
                        } else {
                            validJadwal = false;
                            qrToken = null;
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: res.message,
                                confirmButtonText: 'Ya, ulang',
                            }).then(() => {
                                scanning = true;
                                requestAnimationFrame(scanQRCode);
                            }); 
                        }
                    },
                    error: function(xhr) {
                        let msg = "Terjadi kesalahan server.";
                        try {
                            const json = JSON.parse(xhr.responseText);
                            if (json.message) msg = json.message;
                        } catch (e) {
                            console.warn("Response bukan JSON, tampilkan pesan default");
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg,
                            confirmButtonText: 'Ya, ulang',
                        }).then(() => {
                            scanning = true;
                            requestAnimationFrame(scanQRCode);
                        });
                    }
                });
                return;
            }
        }
        requestAnimationFrame(scanQRCode);
    }

    captureBtn.addEventListener('click', function() {
        if (!qrToken || !validJadwal) {
            Swal.fire({
                icon: 'warning',
                title: 'QR Belum Terbaca',
                text: 'Silakan arahkan kamera ke QR!'
            });
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL("image/png");

        capturedImage.src = dataURL;
        photoPreview.classList.remove("d-none");

        const formData = new FormData();
        formData.append("token", qrToken);
        formData.append("foto", dataURLtoFile(dataURL, "bukti.png"));

        $.ajax({
            url: "{{ route('absensi.scan') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Menyimpan Absensi Anda',
                    didOpen: () => Swal.showLoading(),
                    allowOutsideClick: false
                });
            },
            success: function(res) {
                if (res.message) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Absensi Berhasil',
                        text: res.message,
                        timer: 1000,
                        showConfirmButton: false
                    }).then(() => {
                        location.href = "{{ route('absensi.index') }}";
                    });
                }
            },
            error: function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Kirim Absensi',
                    text: err.responseJSON?.message || 'Terjadi kesalahan saat mengirim absensi'
                });
            }
        });

    });

    function dataURLtoFile(dataurl, filename) {
        const arr = dataurl.split(',');
        const mime = arr[0].match(/:(.*?);/)[1];
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);
        while (n--) u8arr[n] = bstr.charCodeAt(n);
        return new File([u8arr], filename, {
            type: mime
        });
    }
</script>
@endpush