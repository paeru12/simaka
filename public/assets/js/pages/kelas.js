// pages/kelas.js

let currentPage = 1;

function loadKelas(page = 1) {
    $.ajax({
        url: "/kelas/filter",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            search: $("#search").val(),
            page
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
                    rows += renderKelasRow(
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
    loadKelas();

    $("#search").on("keyup", debounce(() => loadKelas(1)));

    $(document).on("click", ".page-link", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        if (page) loadKelas(page);
    });
});

/* =========== EDIT =========== */
$(document).on("click", ".editBtnKelas", function () {
    let id = $(this).data("id");

    $.get(`/kelas/${id}`, (data) => {
        $("#edit_id").val(data.id);
        $("#edit_jurusan_id").val(data.jurusan_id);
        $("#edit_kelas").val(data.kelas);
        $("#edit_rombel").val(data.rombel);
        $("#editKelasModal").modal("show");
    });
});

/* =========== DELETE =========== */
$(document).on("click", ".deleteBtnKelas", function () {
    let id = $(this).data("id");

    Swal.fire({
        title: "Hapus Kelas?",
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
    }).then((r) => {
        if (r.isConfirmed) deleteKelas(id);
    });
});

function deleteKelas(id) {
    $.ajax({
        url: `/kelas/${id}`,
        type: "DELETE",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content")
        },

        success(res) {
            Swal.fire("Berhasil", res.message, "success");
            loadKelas(currentPage);
        },

        error(xhr) {
            Swal.fire("Error", xhr.responseJSON.message, "error");
        }
    });
}
