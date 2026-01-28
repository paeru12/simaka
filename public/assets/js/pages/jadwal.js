let currentPage = 1;

function loadJadwal(page = 1) {
    $.ajax({
        url: "/jadwal/filter",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            search: $("#search").val(),
            page
        },

        beforeSend() {
            $("#tableBody").html(`
                <tr><td colspan="10" class="text-center">Loading...</td></tr>
            `);
        },

        success(res) {
            let rows = "";

            if (res.data.length === 0) {
                rows = `<tr><td colspan="10" class="text-center">No Data Available</td></tr>`;
            } else {
                res.data.forEach((j, idx) => {
                    rows += renderJadwalRow(
                        j,
                        idx,
                        res.current_page,
                        res.per_page,
                        res.is_admin
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

    loadJadwal();

    $("#search").on("keyup", debounce(() => loadJadwal(1)));

    $(document).on("click", ".page-link", function (e) {
        e.preventDefault();
        let p = $(this).data("page");
        if (p) loadJadwal(p);
    });
});

/* ============ EDIT ============ */
$(document).on("click", ".editBtn", function () {
    let id = $(this).data("id");

    $.get(`/jadwal/${id}`, (data) => {
        $("#edit_id").val(data.id);
        $("#edit_guru_id").val(data.guru_id);
        $("#edit_mapel_id").val(data.mapel_id);
        $("#edit_hari").val(data.hari);
        $("#edit_kelas_id").val(data.kelas_id);
        $("#edit_ruangan_id").val(data.ruangan_id);
        $("#edit_jam_mulai").val(data.jam_mulai.substring(0, 5));
        $("#edit_jam_selesai").val(data.jam_selesai.substring(0, 5));
        $("#edit_total_jam").val(data.total_jam);

        $("#editJadwalModal").modal("show");
    });
});

/* ============ DELETE ============ */
$(document).on("click", ".deleteBtn", function () {
    let id = $(this).data("id");

    Swal.fire({
        title: "Hapus Jadwal?",
        text: "Jadwal ini akan dihapus.",
        icon: "warning",
        showCancelButton: true
    }).then(r => {
        if (r.isConfirmed) deleteJadwal(id);
    });
});

function deleteJadwal(id, force = false) {

    $.ajax({
        url: `/jadwal/${id}`,
        type: "DELETE",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            force
        },

        success(res) {
            Swal.fire("Berhasil", res.message, "success");
            loadJadwal(currentPage);
        },

        error(xhr) {
            const res = xhr.responseJSON;

            if (res.need_confirmation) {

                Swal.fire({
                    title: "PERINGATAN!",
                    text: res.message,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Hapus Semua Absensi",
                }).then(x => {
                    if (x.isConfirmed) deleteJadwal(id, true);
                });

            } else {
                Swal.fire("Gagal", res.message, "error");
            }
        }
    });
}
