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
    <button id="switchCameraBtn" type="button" class="btn btn-purple" data-bs-toggle="tooltip" data-bs-placement="top" title="Ubah Kamera" data-bs-custom-class="tooltip-ungu"><i class="ri ri-camera-switch-line"></i></button>
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
    let scanningHarian = false;
    let lokasiHarian = null;

    // 🔄 Mode kamera default belakang
    let currentFacingModeHarian = "environment";

    // ======================================
    // 🚀 MULAI / SWITCH KAMERA ABSENSI HARIAN
    // ======================================
    async function startCameraHarian() {

        // Stop kamera sebelumnya bila ada
        if (streamHarian) {
            streamHarian.getTracks().forEach(track => track.stop());
        }

        try {
            streamHarian = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: currentFacingModeHarian
                }
            });

            videoHarian.srcObject = streamHarian;
            videoHarian.play();

            scanningHarian = true;
            requestAnimationFrame(scanQRCodeHarian);

        } catch (err) {
            Swal.fire("Error", "Kamera tidak dapat diakses: " + err.message, "error");
        }
    }

    // 🔘 Tombol switch kamera
    document.getElementById('switchCameraBtn').addEventListener('click', () => {
        currentFacingModeHarian =
            currentFacingModeHarian === "environment" ? "user" : "environment";

        startCameraHarian(); // restart kamera dengan mode baru
    });

    // ======================================
    // 🔍 SCAN QR KODE ABSENSI HARIAN
    // ======================================
    function scanQRCodeHarian() {
        if (!scanningHarian) return;

        if (videoHarian.readyState === videoHarian.HAVE_ENOUGH_DATA) {
            canvasHarian.width = videoHarian.videoWidth;
            canvasHarian.height = videoHarian.videoHeight;

            ctxHarian.drawImage(videoHarian, 0, 0, canvasHarian.width, canvasHarian.height);

            const imageData = ctxHarian.getImageData(0, 0, canvasHarian.width, canvasHarian.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                scanningHarian = false;
                qrTokenHarian = code.data;

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
                            Swal.fire({
                                icon: 'info',
                                title: 'Konfirmasi Guru',
                                html: `<b>Nama:</b> ${res.guru.nama}<br><br>Lanjut absen harian?`,
                                showCancelButton: true,
                                confirmButtonText: 'Ya, lanjut',
                                cancelButtonText: 'Batal'
                            }).then(result => {
                                if (!result.isConfirmed) {
                                    scanningHarian = true;
                                    requestAnimationFrame(scanQRCodeHarian);
                                } else {
                                    Swal.fire('Silakan ambil foto bukti!', '', 'info');
                                }
                            });
                        } else {
                            Swal.fire("Error", res.message, "error").then(() => {
                                scanningHarian = true;
                                requestAnimationFrame(scanQRCodeHarian);
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire("Error", xhr.responseJSON?.message ?? "Gagal memvalidasi QR!", "error")
                            .then(() => {
                                scanningHarian = true;
                                requestAnimationFrame(scanQRCodeHarian);
                            });
                    }
                });
                return;
            }
        }

        requestAnimationFrame(scanQRCodeHarian);
    }

    // ================================
    // 📸 AMBIL FOTO BUKTI HARIAN
    // ================================
    captureBtnHarian.addEventListener('click', () => {
        if (!qrTokenHarian) {
            Swal.fire("Warning", "QR belum terbaca!", "warning");
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
            beforeSend: () => Swal.fire({
                title: "Mengirim Absensi...",
                didOpen: () => Swal.showLoading()
            }),
            success: res => {
                Swal.close();
                Swal.fire({
                    icon: res.status === "success" ? "success" : "warning",
                    title: res.status === "success" ? "Berhasil" : "Gagal",
                    text: res.message,
                });
                if (res.status === "success") setTimeout(() => location.reload(), 800);
            },
            error: xhr => {
                Swal.close();
                Swal.fire("Error", xhr.responseJSON?.message ?? "Gagal mengirim absensi!", "error");
            }
        });
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

    // ================================
    // 🚪 EVENT MODAL ABSEN HARIAN
    // ================================
    $('#absenharians').on('shown.bs.modal', async function() {
        startCameraHarian();

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => lokasiHarian = pos.coords.latitude + "," + pos.coords.longitude
            );
        }
    });

    $('#absenharians').on('hidden.bs.modal', function() {
        if (streamHarian) {
            streamHarian.getTracks().forEach(t => t.stop());
        }
        scanningHarian = false;
        qrTokenHarian = null;
        photoPreviewHarian.classList.add('d-none');
        capturedImageHarian.src = "";
    });
</script>
@endpush