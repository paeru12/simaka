// pages/ruangan.js

let currentPage = 1;

function loadRuangan(page = 1) {
    $.ajax({
        url: "/ruangan/filter",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            search: $("#search").val(),
            page,
        },

        beforeSend() {
            $("#tableBody").html(`
                <tr>
                    <td colspan="4" class="text-center">Loading...</td>
                </tr>
            `);
        },

        success(res) {
            let rows = "";

            if (!res.data.length) {
                rows = `
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                `;
            } else {
                res.data.forEach((item, index) => {
                    rows += renderRuanganRow(
                        item,
                        index,
                        res.current_page,
                        res.per_page
                    );
                });
            }

            $("#tableBody").html(rows);

            renderPagination(res, $("#pagination"));
            renderDataInfo(res, $("#dataInfo"));

            currentPage = res.current_page;
        }
    });
}

$(document).ready(function () {
    loadRuangan();

    $("#search").on("keyup", debounce(() => loadRuangan(1)));

    $(document).on("click", ".page-link", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        if (page) loadRuangan(page);
    });
});

/* =========== QR GENERATE =========== */
$(document).on("click", ".btn-generate", function () {
    let id = $(this).data("id");

    Swal.fire({
        title: "Processing...",
        text: "Membuat QR Ruangan",
        didOpen: () => Swal.showLoading(),
        allowOutsideClick: false,
    });

    $.post("/qr-kelas", {
        _token: $('meta[name="csrf-token"]').attr("content"),
        ruangan_id: id
    }).done(res => {
        Swal.close();
        Swal.fire("Berhasil", res.message, "success");
        loadRuangan(currentPage);
    });
});

/* =========== EDIT =========== */
$(document).on("click", ".editBtn", function () {
    let id = $(this).data("id");

    $.get(`/ruangan/${id}`, data => {
        $("#edit_id").val(data.id);
        $("#edit_nama").val(data.nama);
        $("#editRuanganModal").modal("show");
    });
});

/* =========== DELETE =========== */
$(document).on("click", ".deleteBtn", function () {
    let id = $(this).data("id");

    Swal.fire({
        title: "Hapus Ruangan?",
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
    }).then(r => {
        if (r.isConfirmed) {
            deleteRuangan(id);
        }
    });
});

function deleteRuangan(id) {
    $.ajax({
        url: `/ruangan/${id}`,
        type: "DELETE",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: function () {
            Swal.fire({
                title: 'Processing...',
                text: 'Menghapus data Ruangan',
                didOpen: () => Swal.showLoading(),
                allowOutsideClick: false
            });
        },
        success(res) {
            if (!res.success) {
                return Swal.fire("Gagal", res.message, "error");
            }
            Swal.fire("Berhasil", res.message, "success");
            loadRuangan(currentPage);
        },

        error(xhr) {
            Swal.fire("Error", xhr.responseJSON.message, "error");
        }
    });
}
