let currentPage = 1;

function loadPotongan(page = 1) {

    $.ajax({
        url: "/potongan-gaji/filter",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            search: $("#search").val(),
            page
        },

        beforeSend() {
            $("#tableBody").html(`
                <tr><td colspan="5" class="text-center">Loading...</td></tr>
            `);
        },

        success(res) {

            let rows = "";

            if (res.data.length === 0) {
                rows = `<tr><td colspan="5" class="text-center">No Data Available</td></tr>`;
            } else {
                res.data.forEach((p, idx) => {
                    rows += renderPotonganRow(
                        p,
                        idx,
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

    loadPotongan();

    $("#search").on("keyup", debounce(() => loadPotongan(1), 300));

    $(document).on("click", ".page-link", function (e) {
        e.preventDefault();
        const p = $(this).data("page");
        if (p) loadPotongan(p);
    });

    /* ================= EDIT ================= */

    $(document).on("click", ".editBtn", function () {
        let id = $(this).data("id");

        $.get(`/potongan-gaji/${id}`, (p) => {
            $("#edit_id").val(p.id);
            $("#edit_nama_potongan").val(p.nama_potongan);
            $("#edit_jumlah_potongan").val(
                new Intl.NumberFormat("id-ID").format(p.jumlah_potongan)
            );
            $("#edit_keterangan").val(p.keterangan);
            $("#editPotonganModal").modal("show");
        });
    });

    /* ================= DELETE ================= */

    $(document).on("click", ".deleteBtn", function () {
        let id = $(this).data("id");

        Swal.fire({
            title: "Hapus Data?",
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true
        }).then(r => {
            if (r.isConfirmed) deletePotongan(id);
        });
    });

    function deletePotongan(id) {
        $.ajax({
            url: `/potongan-gaji/${id}`,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content")
            },

            beforeSend() {
                Swal.fire({ title: "Processing...", didOpen: () => Swal.showLoading() });
            },

            success(res) {
                Swal.fire("Berhasil", res.message, "success");
                loadPotongan(currentPage);
            },

            error(xhr) {
                Swal.fire("Gagal", xhr.responseJSON.message, "error");
            }
        });
    }
});
