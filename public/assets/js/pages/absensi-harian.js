let currentPage = 1;
let isLoading = false;

/* ================= LOAD DATA ================= */
function loadData(page = 1) {
    if (isLoading) return;

    isLoading = true;
    currentPage = page;

    $.ajax({
        url: '/absensih/data',
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
                    <td colspan="100%" class="text-center text-muted">
                        Loading...
                    </td>
                </tr>
            `);
        },
        success(res) {
            let rows = '';

            if (!res.data || res.data.length === 0) {
                rows = `
                    <tr>
                        <td colspan="100%" class="text-center text-muted">
                            Data tidak ditemukan
                        </td>
                    </tr>
                `;
            } else {
                res.data.forEach((item, i) => {
                    rows += renderAbsensiRow(
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
        },
        error(xhr) {
            console.error(xhr);
            $('#tableBody').html(`
                <tr>
                    <td colspan="100%" class="text-center text-danger">
                        Gagal memuat data
                    </td>
                </tr>
            `);
        },
        complete() {
            isLoading = false;
        }
    });
}

/* ================= INIT ================= */
$(document).ready(function () {
    initFilter();
    loadData();

    $('#search').on('keyup', debounce(() => loadData(1)));
    $('#bulan, #tahun').on('change', () => loadData(1));

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page && page !== currentPage) {
            loadData(page);
        }
    });
});

/* ================= DELETE ================= */
$(document).on('click', '.deleteBtn', function () {
    const id = $(this).data('id');

    Swal.fire({
        title: 'Hapus absensi?',
        text: 'Data yang dihapus tidak bisa dikembalikan',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus'
    }).then(result => {
        if (!result.isConfirmed) return;

        $.ajax({
            url: `/absensih/${id}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success(res) {
                Swal.fire('Berhasil', res.message, 'success');
                loadData(currentPage);
            },
            error() {
                Swal.fire('Error', 'Gagal menghapus data', 'error');
            }
        });
    });
});

/* ================= FILTER INIT ================= */
function initFilter() {
    const now = new Date();
    const currentMonth = now.getMonth() + 1;
    const currentYear = now.getFullYear();

    $('#bulan').empty();
    $('#tahun').empty();

    for (let i = 1; i <= 12; i++) {
        const monthName = new Date(2020, i - 1).toLocaleString('id-ID', { month: 'long' });
        $('#bulan').append(`<option value="${i}">${monthName}</option>`);
    }

    for (let y = currentYear; y >= 2020; y--) {
        $('#tahun').append(`<option value="${y}">${y}</option>`);
    }

    $('#bulan').val(currentMonth);
    $('#tahun').val(currentYear);
}
