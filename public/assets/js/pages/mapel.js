// pages/mapel.js

let currentPage = 1;

function loadMapel(page = 1) {
    $.ajax({
        url: "/mapel/filter",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            search: $("#search").val(),
            page
        },

        beforeSend() {
            $("#tableBody").html(`
                <tr><td colspan="3" class="text-center">Loading...</td></tr>
            `);
        },

        success(res) {
            let rows = "";

            if (!res.data.length) {
                rows = `<tr><td colspan="3" class="text-center">Tidak ada data</td></tr>`;
            } else {
                res.data.forEach((item, idx) => {
                    rows += renderMapelRow(
                        item,
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

    loadMapel();

    $("#search").on("keyup", debounce(() => loadMapel(1)));

    $(document).on("click", ".page-link", function (e) {
        e.preventDefault();
        let p = $(this).data("page");
        if (p) loadMapel(p);
    });
});

/* ============ EDIT ============ */
$(document).on("click", ".editBtn", function () {
    let id = $(this).data("id");

    $.get(`/mapel/${id}`, (data) => {
        $("#edit_id").val(data.id);
        $("#edit_nama_mapel").val(data.nama_mapel);
        $("#editMapelModal").modal("show");
    });
});

/* ============ DELETE ============ */
$(document).on("click", ".deleteBtn", function () {
    let id = $(this).data("id");

    Swal.fire({
        title: "Hapus Mapel?",
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true
    }).then(r => {
        if (r.isConfirmed) deleteMapel(id);
    });
});

function deleteMapel(id) {

    $.ajax({
        url: `/mapel/${id}`,
        type: "DELETE",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content")
        },

        success(res) {
            Swal.fire("Berhasil", res.message, "success");
            loadMapel(currentPage);
        },

        error(xhr) {
            Swal.fire("Gagal", xhr.responseJSON.message, "error");
        }
    });
}
