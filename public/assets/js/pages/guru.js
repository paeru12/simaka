let currentPage = 1;

function loadGuru(page = 1) {
    $.ajax({
        url: "/guru/filter",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            search: $("#search").val(),
            page,
        },

        beforeSend() { 
            $("#tableBody").html(`
                <tr>
                    <td colspan="9" class="text-center">Loading...</td>
                </tr>
            `);
        },

        success(res) {
            let rows = "";

            if (!res.data.length) {
                rows = `
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data</td>
                    </tr>
                `;
            } else {
                res.data.forEach((item, i) => {
                    rows += renderGuruRow(
                        item,
                        i,
                        res.current_page,
                        res.per_page
                    );
                });
            }

            $("#tableBody").html(rows);

            renderPagination(res, $("#pagination"));
            renderDataInfo(res, $("#dataInfo"));

            currentPage = res.current_page;
        },
        error(xhr) {
            const res = xhr.responseJSON;
            Swal.fire("Gagal", res.message, "error");
        }
    });
}

$(document).ready(function () {
    loadGuru();

    $("#search").on("keyup", debounce(() => loadGuru(1)));

    $(document).on("click", ".page-link", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        if (page) loadGuru(page);
    });
});

/* ==================== QR GENERATE ==================== */
$(document).on("click", ".btn-generate", function () {
    let id = $(this).data("id");

    Swal.fire({
        title: "Processing...",
        text: "Membuat QR Guru",
        didOpen: () => Swal.showLoading(),
        allowOutsideClick: false,
    });

    $.post("/qr-guru", {
        _token: $('meta[name="csrf-token"]').attr("content"),
        guru_id: id,
    }).done(res => {
        Swal.close();
        Swal.fire("Berhasil", res.message, "success");
        loadGuru(currentPage);
    });
});

/* ==================== DELETE ==================== */
$(document).on("click", ".btn-delete", function () {
    let id = $(this).data("id");

    Swal.fire({
        title: 'Hapus Guru?',
        text: 'Semua data terkait akan ikut terhapus!',
        icon: 'warning',
        showCancelButton: true
    }).then((r) => {
        if (r.isConfirmed) {
            deleteGuru(id, false);
        }
    });
});

function deleteGuru(id, forceDelete = false) {
    $.ajax({
        url: `/guru/${id}`,
        type: "DELETE",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            force: forceDelete
        },

        success(res) {
            Swal.fire("Berhasil", res.message, "success");
            loadGuru(currentPage);
        },

        error(xhr) {
            const res = xhr.responseJSON;

            if (res.need_confirmation) {
                Swal.fire({
                    title: 'PERINGATAN!',
                    text: res.message + ' Lanjutkan?',
                    icon: 'warning',
                    showCancelButton: true
                }).then(r => {
                    if (r.isConfirmed) deleteGuru(id, true);
                });
            } else {
                Swal.fire("Gagal", res.message, "error");
            }
        }
    });
}
