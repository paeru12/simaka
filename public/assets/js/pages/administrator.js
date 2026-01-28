let currentPage = 1;

function loadAdmin(page = 1) {
    $.ajax({
        url: "/administrator/filter",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            search: $("#search").val() || "",
            page
        },

        beforeSend() {
            $("#tableBody").html(`
                <tr><td colspan="8" class="text-center">Loading...</td></tr>
            `);
        },

        success(res) {
            let rows = "";

            if (res.data.length === 0) {
                rows = `<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>`;
            } else {
                res.data.forEach((adm, idx) => {
                    rows += renderAdminRow(adm, idx, res.current_page, res.per_page);
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

    /** LOAD DEFAULT */
    loadAdmin();

    /** SEARCH */
    $("#search").on("keyup", debounce(() => loadAdmin(1)));

    /** PAGINATION */
    $(document).on("click", ".page-link", function (e) {
        e.preventDefault();
        let p = $(this).data("page");
        if (p) loadAdmin(p);
    });

    /** DELETE ADMIN */
    $(document).on("click", ".btn-delete", function () {
        let id = $(this).data("id");

        Swal.fire({
            title: "Hapus Admin?",
            text: "Data tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true
        }).then(r => {
            if (r.isConfirmed) deleteAdmin(id);
        });
    });

    function deleteAdmin(id, force = false) {

        $.ajax({
            url: `/administrator/${id}`,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                force
            },

            success(res) {
                Swal.fire("Berhasil", res.message, "success");
                loadAdmin(currentPage);
            },

            error(xhr) {
                let res = xhr.responseJSON;

                if (res.need_confirmation) {
                    Swal.fire({
                        title: "PERINGATAN!",
                        text: res.message,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Hapus Semua"
                    }).then(ret => {
                        if (ret.isConfirmed) deleteAdmin(id, true);
                    });
                } else {
                    Swal.fire("Gagal", res.message, "error");
                }
            }
        });
    }

    /** GENERATE QR */
    $(document).on("click", ".btn-generate", function () {
        let id = $(this).data("id");

        $.ajax({
            url: "/qr-guru",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                guru_id: id
            },

            beforeSend() {
                Swal.fire({ title: "Processing...", didOpen: () => Swal.showLoading() });
            },

            success(res) {
                Swal.fire("Berhasil", res.message, "success");
                loadAdmin(currentPage);
            },

            error(err) {
                Swal.fire("Error", err.responseJSON.message, "error");
            }
        });
    });
});
