
let video = document.getElementById('preview');
let canvas = document.getElementById('canvas');
let ctx = canvas.getContext("2d", {
    willReadFrequently: true
});
let captureBtn = document.getElementById('captureBtn');
let photoPreview = document.getElementById('photoPreview');
let capturedImage = document.getElementById('capturedImage');
let qrToken = null;
let stream = null;
let scanning = false;
let validJadwal = false;
$('#absenjadwal').on('shown.bs.modal', function () {
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
            .then(function (s) {
                stream = s;
                video.srcObject = stream;
                video.play();
                scanning = true;
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
});

// Saat modal ditutup → stop kamera
$('#absenjadwal').on('hidden.bs.modal', function () {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    video.srcObject = null;
    scanning = false;
    qrToken = null;
    validJadwal = false;
    photoPreview.classList.add("d-none");
});

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
                success: function (res) {
                    if (res.status === 'ok') {
                        scanning = false;
                        validJadwal = true;
                        Swal.fire({
                            icon: 'info',
                            title: 'Konfirmasi Jadwal',
                            html: `
                                <b>Mapel:</b> ${res.jadwal.mapel}<br>
                                <b>Kelas:</b> ${res.jadwal.kelas}<br>
                                <b>Ruangan:</b> ${res.jadwal.ruangan}<br>
                                <b>Jam:</b> ${res.jadwal.jam_mulai} - ${res.jadwal.jam_selesai}<br><br>
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
                        qrToken = null;
                        validJadwal = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: res.message,
                        }).then(() => {
                            scanning = true;
                            requestAnimationFrame(scanQRCode);
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan server'
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

captureBtn.addEventListener('click', function () {
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
        beforeSend: function () {
            Swal.fire({
                title: 'Processing...',
                text: 'Menyimpan Absensi Anda',
                didOpen: () => Swal.showLoading(),
                allowOutsideClick: false
            });
        },
        success: function (res) {
            Swal.fire({
                icon: 'success',
                title: 'Absensi Berhasil',
                text: res.message,
                timer: 1200,
                showConfirmButton: false
            }).then(() => {
                location.href = "{{ route('absensi.index') }}";
            });
        },
        error: function (err) {
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