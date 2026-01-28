// pages/jurusan.js

let currentPage = 1;

function loadJurusan(page = 1) {
    $.ajax({
        url: "/jurusan/filter",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            search: $("#search").val(),
            page
        },

        beforeSend() {
            $("#tableBody").html(`
                <tr>
                    <td colspan="3" class="text-center">Loading...</td>
                </tr>
            `);
        },

        success(res) {
            let rows = "";

            if (!res.data.length) {
                rows = `
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data</td>
                    </tr>
                `;
            } else {
                res.data.forEach((item, index) => {
                    rows += renderJurusanRow(
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
    loadJurusan();

    $("#search").on("keyup", debounce(() => loadJurusan(1)));

    $(document).on("click", ".page-link", function (e) {
        e.preventDefault();
        const p = $(this).data("page");
        if (p) loadJurusan(p);
    });
});

/* =========== EDIT =========== */
$(document).on("click", ".editBtnJurusan", function () {
    let id = $(this).data("id");

    $.get(`/jurusan/${id}`, (res) => {
        $("#editJurusanId").val(res.id);
        $("#editJurusanNama").val(res.nama);
        $("#editJurusanModal").modal("show");
    });
});

/* =========== DELETE =========== */
$(document).on("click", ".deleteBtnJurusan", function () {
    let id = $(this).data("id");

    Swal.fire({
        title: "Hapus Jurusan?",
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
    }).then(result => {
        if (result.isConfirmed) deleteJurusan(id);
    });
});

function deleteJurusan(id) {
    $.ajax({
        url: `/jurusan/${id}`,
        type: "DELETE",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content")
        },

        success(res) {
            Swal.fire("Berhasil", res.message, "success");
            loadJurusan(currentPage);
        },

        error(xhr) {
            Swal.fire("Error", xhr.responseJSON.message, "error");
        }
    });
}
