let currentPage = 1;

function loadAbsensiMapel(page = 1) {
    $.ajax({
        url: '/absensi/filter',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            page,
            search: $('#search').val(),
            bulan: $('#bulan').val(),
            tahun: $('#tahun').val()
        },
        beforeSend() {
            $('#tableBody').html(`
                <tr>
                    <td colspan="12" class="text-center">Loading...</td>
                </tr>
            `);
        },
        success(res) {
            let rows = '';

            if (!res.data.length) {
                rows = `<tr><td colspan="12" class="text-center">Data tidak ditemukan</td></tr>`;
            } else {
                res.data.forEach((item, i) => {
                    rows += renderAbsensiMapelRow(
                        item,
                        i,
                        res.current_page,
                        res.per_page,
                        IS_ADMIN
                    );
                });
            }

            $('#tableBody').html(rows);
            renderPagination(res, $('#pagination'));
            renderDataInfo(res, $('#dataInfo'));

            currentPage = res.current_page;
        }
    });
}

/* ================= INIT ================= */
$(document).ready(function () {
    initFilter();
    loadAbsensiMapel();

    $('#search').on('keyup', debounce(() => loadAbsensiMapel(1)));
    $('#bulan, #tahun').on('change', () => loadAbsensiMapel(1));

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) loadAbsensiMapel(page);
    });
});

/* ================= DELETE ================= */
$(document).on('click', '.deleteBtn', function () {
    const id = $(this).data('id');

    Swal.fire({
        title: 'Hapus absensi mapel?', 
        text: 'Data yang dihapus tidak bisa dikembalikan',
        icon: 'warning',
        showCancelButton: true
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({
                url: `/absensi-mapel/${id}`,
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success(res) {
                    Swal.fire('Berhasil', res.message, 'success');
                    loadAbsensiMapel(currentPage);
                }
            });
        }
    });
});

/* ================= FILTER INIT ================= */
function initFilter() {
    const now = new Date();
    $('#bulan').val(now.getMonth() + 1);
    $('#tahun').val(now.getFullYear());
}
