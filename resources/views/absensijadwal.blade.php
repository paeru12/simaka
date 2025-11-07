<div class="modal-body">
    <section class="section">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Absensi QR</h5>
                <p class="card-text">Arahkan kamera ke QR Code kelas untuk melakukan absensi.</p>

                <video id="preview" class="border border-2 rounded w-100" style="max-height: 300px;" autoplay playsinline></video>
                <canvas id="canvas" class="d-none"></canvas>

                <div id="photoPreview" class="mt-3 d-none">
                    <h6>Foto Bukti</h6>
                    <img id="capturedImage" src="" alt="Foto bukti" class="img-thumbnail" style="max-width: 200px;">
                </div>
                <div id="statusMessage" class="mt-3"></div>
            </div>
        </div>
    </section>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
    <button id="captureBtn" type="button" class="btn btn-purple">Ambil Foto Bukti</button>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/absenjadwal.js') }}"></script>
@endpush