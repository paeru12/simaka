let currentPage = 1;
let isLoading = false;

/* ================= LOAD DATA ================= */
function loadData(page = 1) {

    if (isLoading) return;
    isLoading = true;
    currentPage = page;

    const bulanText = $('#bulan option:selected').text();
    const tahun = $('#tahun').val();

    $.ajax({
        url: "/rekapp/filter",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            page: page,
            bulan: $('#bulan').val(),
            tahun: tahun,
            search: $('#search').val()
        },
        beforeSend() {
            $('#tableBody').html(`
                <tr>
                    <td colspan="100%" class="text-center text-muted">Loading...</td>
                </tr>
            `);
        },
        success(res) {

            let rows = "";

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
                    rows += renderRekapAbsenRow(
                        item,
                        i,
                        res.current_page,
                        res.per_page,
                        bulanText,
                        tahun
                    );
                });
            }

            $('#tableBody').html(rows);

            renderPagination(res, $("#pagination"));
            renderDataInfo(res, $("#dataInfo"));
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

    const bulanSekarang = new Date().getMonth() + 1;
    const tahunSekarang = new Date().getFullYear();

    $('#bulan').val(bulanSekarang);
    $('#tahun').val(tahunSekarang);

    loadData();

    // SEARCH
    $('#search').on('keyup', debounce(() => loadData(1)));

    // FILTER
    $('#bulan, #tahun').on('change', () => loadData(1));

    // PAGINATION CLICK
    $(document).on('click', '#pagination .page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page && page !== currentPage) {
            loadData(page);
        }
    });
});
