const videoHarian = document.getElementById('preview');
const canvasHarian = document.getElementById('canvas');
const ctxHarian = canvasHarian.getContext("2d", { willReadFrequently: true });
const captureBtnHarian = document.getElementById('captureBtn');
const photoPreviewHarian = document.getElementById('photoPreview');
const capturedImageHarian = document.getElementById('capturedImage');

let qrTokenHarian = null;
let streamHarian = null;
let scanningHarian = true;
let lokasiHarian = null;

function startCameraHarian() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
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
            scanningHarian = false;
            Swal.fire("QR Code Terdeteksi", "Silahkan ambil foto bukti absensi", "info");
        }
    }
    if (scanningHarian) requestAnimationFrame(scanQRCodeHarian);
}

// 🔹 Ambil Lokasi
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        pos => lokasiHarian = pos.coords.latitude + "," + pos.coords.longitude,
        err => console.warn("Lokasi tidak bisa diambil: " + err.message)
    );
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
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        beforeSend: () => {
            Swal.fire({ title: "Mengirim Absensi...", didOpen: () => Swal.showLoading() });
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

// 🔹 Event Modal
$('#absenharians').on('shown.bs.modal', function () {
    scanningHarian = true;
    startCameraHarian();
});

$('#absenharians').on('hidden.bs.modal', function () {
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
    return new File([u8arr], filename, { type: mime });
}
