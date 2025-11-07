<div class="modal-body">
    <section class="section text-center">
        <h5 class="card-title">Absensi Harian</h5>
        <p class="card-text">Scan QR dan ambil foto untuk absen datang.</p>

        <video id="preview" class="border border-2 rounded w-100" style="max-height: 300px;" autoplay playsinline></video>
        <canvas id="canvas" class="d-none"></canvas>

        <div id="photoPreview" class="mt-3 d-none">
            <h6>Foto Bukti</h6>
            <img id="capturedImage" src="" alt="Foto bukti" class="img-thumbnail" style="max-width: 200px;">
        </div>
    </section>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
    <button id="captureBtn" type="button" class="btn btn-purple">Ambil Foto Bukti</button>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const videoHarian = document.getElementById('preview');
    const canvasHarian = document.getElementById('canvas');
    const ctxHarian = canvasHarian.getContext("2d", {
        willReadFrequently: true
    });
    const captureBtnHarian = document.getElementById('captureBtn');
    const photoPreviewHarian = document.getElementById('photoPreview');
    const capturedImageHarian = document.getElementById('capturedImage');

    let qrTokenHarian = null;
    let streamHarian = null;
    let scanningHarian = true;
    let lokasiHarian = null;

    function startCameraHarian() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment"
                    }
                })
                .then(s => {
                    streamHarian = s;
                    videoHarian.srcObject = streamHarian;
                    videoHarian.play();
                    requestAnimationFrame(scanQRCodeHarian);
                })
                .catch(err => {
                    Swal.fire("Error", "Kamera tidak bisa diakses: " + err.message, "error");
                });
        } else {
            Swal.fire("Error", "Browser tidak mendukung kamera", "error");
        }
    }

    function stopCameraHarian() {
        if (streamHarian) {
            streamHarian.getTracks().forEach(track => track.stop());
            streamHarian = null;
        }
        scanningHarian = false;
    }

    function scanQRCodeHarian() {
        if (!scanningHarian) return;

        if (videoHarian.readyState === videoHarian.HAVE_ENOUGH_DATA) {
            canvasHarian.width = videoHarian.videoWidth;
            canvasHarian.height = videoHarian.videoHeight;
            ctxHarian.drawImage(videoHarian, 0, 0, canvasHarian.width, canvasHarian.height);

            const imageData = ctxHarian.getImageData(0, 0, canvasHarian.width, canvasHarian.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                qrTokenHarian = code.data;
                validGuru = false;

                $.ajax({
                    url: "{{ route('absensi.validateGuru') }}",
                    method: "POST",
                    data: JSON.stringify({
                        token: qrTokenHarian
                    }),
                    contentType: "application/json",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    success: function(res) {
                        if (res.status === 'ok') {
                            scanning = false;
                            validGuru = true;
                            Swal.fire({
                                icon: 'info',
                                title: 'Konfirmasi Guru',
                                html: `
                        <b>Nama:</b> ${res.guru.nama}<br><br>
                        Lanjut absen harian?
                    `,
                                showCancelButton: true,
                                confirmButtonText: 'Ya, lanjut',
                                cancelButtonText: 'Batal'
                            }).then(result => {
                                if (!result.isConfirmed) {
                                    validGuru = false;
                                    scanning = true;
                                    requestAnimationFrame(scanQRCodeHarian);
                                } else {
                                    Swal.fire('Silakan ambil foto bukti!', '', 'info');
                                }
                            });
                        } else {
                            qrTokenHarian = null;
                            validGuru = false;
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: res.message,
                            }).then(() => {
                                scanning = true;
                                requestAnimationFrame(scanQRCodeHarian);
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessages = "";
                        if (xhr.responseJSON?.errors) {
                            errorMessages = Object.values(xhr.responseJSON.errors).flat().join("\n");
                        } else if (xhr.responseJSON?.message) {
                            errorMessages = xhr.responseJSON.message;
                        } else {
                            errorMessages = "Terjadi kesalahan tidak diketahui.";
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: errorMessages
                        }).then(() => {
                            scanning = true;
                            requestAnimationFrame(scanQRCodeHarian);
                        });
                    }
                });
                return;
            }

        }
        if (scanningHarian) requestAnimationFrame(scanQRCodeHarian);
    }

    // 🔹 Ambil Foto & Kirim via AJAX
    captureBtnHarian.addEventListener('click', () => {
        if (!qrTokenHarian) {
            Swal.fire("Peringatan", "QR Code belum terbaca!", "warning");
            return;
        }

        canvasHarian.width = videoHarian.videoWidth;
        canvasHarian.height = videoHarian.videoHeight;
        ctxHarian.drawImage(videoHarian, 0, 0, canvasHarian.width, canvasHarian.height);
        const dataURL = canvasHarian.toDataURL('image/jpeg', 0.7);

        capturedImageHarian.src = dataURL;
        photoPreviewHarian.classList.remove('d-none');

        const formData = new FormData();
        formData.append("qr_token", qrTokenHarian);
        formData.append("lokasi", lokasiHarian || "");
        formData.append("foto", dataURLtoFileHarian(dataURL, "bukti.jpg"));

        $.ajax({
            url: "{{ route('absensi.harian.datang') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            beforeSend: () => {
                Swal.fire({
                    title: "Mengirim Absensi...",
                    didOpen: () => Swal.showLoading()
                });
            },
            success: res => {
                Swal.close();
                Swal.fire({
                    icon: res.status === "success" ? "success" : "warning",
                    title: res.status === "success" ? "Berhasil" : "Gagal",
                    text: res.message,
                });
                if (res.status === "success") setTimeout(() => location.reload(), 800);
                console.log(res);
            },
            error: xhr => {
                Swal.close();
                Swal.fire("Error", xhr.responseJSON?.message || "Terjadi kesalahan!", "error");
            }
        });
    });

    // 🔹 Fungsi minta izin Kamera + Lokasi
    async function requestPermissionHarian() {
        return Swal.fire({
            title: 'Izin Dibutuhkan',
            html: 'Untuk absen harian, aplikasi butuh akses <b>kamera</b> dan <b>lokasi</b> Anda.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Izinkan',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    // 🔹 Akses Kamera
                    streamHarian = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "environment"
                        }
                    });
                    videoHarian.srcObject = streamHarian;
                    videoHarian.play();
                    scanningHarian = true;
                    requestAnimationFrame(scanQRCodeHarian);

                    // 🔹 Akses Lokasi
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            pos => lokasiHarian = pos.coords.latitude + "," + pos.coords.longitude,
                            err => Swal.fire("Peringatan", "Lokasi tidak bisa diakses: " + err.message, "warning")
                        );
                    }
                    return true;
                } catch (err) {
                    Swal.fire("Error", "Gagal mengakses kamera/lokasi: " + err.message, "error");
                    return false;
                }
            } else {
                return false;
            }
        });
    }

    // 🔹 Event Modal Dibuka
    $('#absenharians').on('shown.bs.modal', async function() {
        const izin = await requestPermissionHarian();
        if (!izin) {
            // Jika user menolak → langsung tutup modal
            $('#absenharians').modal('hide');
        }
    });

    // 🔹 Event Modal Ditutup
    $('#absenharians').on('hidden.bs.modal', function() {
        stopCameraHarian();
        photoPreviewHarian.classList.add('d-none');
        capturedImageHarian.src = "";
        qrTokenHarian = null;
    });


    function dataURLtoFileHarian(dataurl, filename) {
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