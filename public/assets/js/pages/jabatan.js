// pages/jabatan.js

let currentPage = 1;

function loadJabatan(page = 1) {
    $.ajax({
        url: "/jabatan/filter",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
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
                res.data.forEach((item, i) => {
                    rows += renderJabatanRow(
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
        }
    });
}

$(document).ready(function () {
    loadJabatan();

    $("#search").on("keyup", debounce(() => loadJabatan(1)));

    $(document).on("click", ".page-link", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        if (page) loadJabatan(page);
    });
});

/* ====== DELETE ====== */
$(document).on("click", ".deleteBtn", function () {
    const id = $(this).data("id");

    Swal.fire({
        title: 'Hapus Jabatan?',
        text: 'Data yang dihapus tidak bisa dikembalikan!',
        icon: 'warning', 
        showCancelButton: true
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/jabatan/${id}`,
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success(res) {
                    if (!res.success) {
                        return Swal.fire("Gagal", res.message, "error");
                    }
                    Swal.fire("Berhasil", res.message, "success");
                    loadJabatan(currentPage);
                }
            });
        }
    });
});

/* ====== EDIT ====== */
$(document).on("click", ".editBtn", function () {
    const id = $(this).data("id");

    $.get(`/jabatan/${id}`, (data) => {
        $("#edit_id").val(data.id);
        $("#edit_jabatan").val(data.jabatan);
        $("#edit_nominal_gaji").val(
            new Intl.NumberFormat('id-ID').format(data.nominal_gaji)
        );

        $("#editJabatanModal").modal("show");
    });
});
