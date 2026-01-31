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
    <button id="switchCameraBtn" type="button" class="btn btn-purple">
        <i class="ri ri-camera-switch-line"></i>
    </button>
    <button id="captureBtn" type="button" class="btn btn-purple">Ambil Foto Bukti</button>
</div>

@push('scripts')
@php
use App\Models\Setting;
$ipPrefix = Setting::where("key","alamat_ip")->value("value");  // contoh: 10.208.
@endphp

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
const videoHarian = document.getElementById('preview');
const canvasHarian = document.getElementById('canvas');
const ctxHarian = canvasHarian.getContext("2d", { willReadFrequently: true });
const captureBtnHarian = document.getElementById('captureBtn');

let qrTokenHarian = null;
let streamHarian = null;
let scanningHarian = false;

// ========================
//  CAMERA
// ========================
let currentFacingModeHarian = "user";

async function startCameraHarian() {
    if (streamHarian) {
        streamHarian.getTracks().forEach(t => t.stop());
    }

    try {
        streamHarian = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: currentFacingModeHarian }
        });

        videoHarian.srcObject = streamHarian;
        videoHarian.play();

        scanningHarian = true;
        requestAnimationFrame(scanQRCodeHarian);

    } catch (err) {
        Swal.fire("Error", "Kamera tidak dapat diakses: " + err.message, "error");
    }
}

document.getElementById('switchCameraBtn').addEventListener('click', () => {
    currentFacingModeHarian =
        currentFacingModeHarian === "environment" ? "user" : "environment";

    startCameraHarian();
});

// ========================
//  SCAN QR
// ========================
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
                data: JSON.stringify({ token: qrTokenHarian,ip_lan: ipLAN }),
                contentType: "application/json",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },

                success(res) {
                    if (res.status === 'ok') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Konfirmasi Guru',
                            html: `<b>Nama:</b> ${res.guru.nama}<br><br>Lanjut absen?`,
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                        });
                    } else {
                        Swal.fire("Error", res.message, "error");
                    }
                },

                error(xhr) {
                    Swal.fire("Error", xhr.responseJSON?.message ?? "Gagal validasi QR", "error");
                }
            });

            return;
        }
    }

    requestAnimationFrame(scanQRCodeHarian);
}

// ========================
//  FOTO BUKTI
// ========================
captureBtnHarian.addEventListener('click', () => {
    if (!qrTokenHarian) {
        Swal.fire("Warning", "QR belum terbaca!", "warning");
        return;
    }

    canvasHarian.width = videoHarian.videoWidth;
    canvasHarian.height = videoHarian.videoHeight;
    ctxHarian.drawImage(videoHarian, 0, 0, canvasHarian.width, canvasHarian.height);

    const dataURL = canvasHarian.toDataURL('image/jpeg', 0.7);

    const formData = new FormData();
    formData.append("qr_token", qrTokenHarian);
    formData.append("foto", dataURLtoFile(dataURL, "bukti.jpg"));

    $.ajax({
        url: "{{ route('absensi.harian.datang') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },

        success(res) {
            Swal.fire(res.status === "success" ? "Berhasil" : "Gagal", res.message, res.status);
            if (res.status === "success") setTimeout(() => location.reload(), 800);
        },
        error(xhr) {
            Swal.fire("Error", xhr.responseJSON?.message ?? "Gagal mengirim absensi!", "error");
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
    return new File([u8arr], filename, { type: mime });
}

// ========================
//  MODAL EVENT
// ========================
let ipLAN = null;

// Ambil IP LAN via WebRTC
async function getLocalIP() {
    return new Promise((resolve) => {
        let pc = new RTCPeerConnection({iceServers: []});
        pc.createDataChannel("");
        pc.createOffer().then(offer => pc.setLocalDescription(offer));

        pc.onicecandidate = (event) => {
            if (!event || !event.candidate) return;
            let ipRegex = /([0-9]{1,3}(\.[0-9]{1,3}){3})/;
            let ipMatch = ipRegex.exec(event.candidate.candidate);
            if (ipMatch) {
                resolve(ipMatch[1]);
                pc.close();
            }
        };
    });
}

// Panggil saat modal dibuka
$('#absenharians').on('shown.bs.modal', async function() {
    ipLAN = await getLocalIP();
    console.log("IP LAN guru:", ipLAN);

    startCameraHarian();
});

$('#absenharians').on('hidden.bs.modal', function() {
    if (streamHarian) {
        streamHarian.getTracks().forEach(t => t.stop());
    }

    scanningHarian = false;
    qrTokenHarian = null;
});
</script>

@endpush
