let currentPage = 1;

function loadData(page = 1) {
    $.ajax({
        url: '/gajians/filter',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            page,
            bulan: $('#bulan').val(),
            tahun: $('#tahun').val(),
            search: $('#search').val()
        },
        beforeSend() {
            $('#tableBody').html(
                `<tr><td colspan="8" class="text-center">Loading...</td></tr>`
            );
        },
        success(res) {
            let html = '';
            let no = (res.current_page - 1) * res.per_page;

            if (res.data.length === 0) {
                html = `<tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>`;
            } else {
                res.data.forEach((item, i) => {
                    html += `
                        <tr>
                            <th>${no + i + 1}.</th>
                            <td class="text-capitalize">${item.nama}</td>
                            <td class="text-capitalize">${item.jabatan}</td>
                            <td>Rp ${item.gapok.toLocaleString('id-ID')}</td>
                            <td>${item.total_mapel}</td>
                            <td>${item.total_hadir} Kali</td>
                            <td>Rp ${item.total_gaji.toLocaleString('id-ID')}</td>
                            <td>
                                <a href="/gaji/slip-gaji/${item.guru_id}/slip/${$('#bulan').val()}/${$('#tahun').val()}"
                                   class="btn btn-sm btn-purple">
                                   <i class="ri-bar-chart-horizontal-fill"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });
            }

            $('#tableBody').html(html);
            renderPagination(res, $("#pagination"));
            renderDataInfo(res, $("#dataInfo"));
            currentPage = res.current_page;
        }
    });
}


/* ========= INIT ========= */
$(document).ready(function () {
    let now = new Date();

    for (let i = 1; i <= 12; i++) {
        $('#bulan').append(`<option value="${i}">${new Date(2020, i-1).toLocaleString('id-ID',{month:'long'})}</option>`);
    }

    for (let i = now.getFullYear(); i >= 2020; i--) {
        $('#tahun').append(`<option value="${i}">${i}</option>`);
    }

    $('#bulan').val(now.getMonth() + 1);
    $('#tahun').val(now.getFullYear());

    loadData();

    $('#bulan, #tahun').change(() => loadData(1));
    $('#search').keyup(debounce(() => loadData(1), 400));

    $(document).on('click', '.page-link', function () {
        let page = $(this).data('page');
        if (page) loadData(page);
    });
});

/* ========= DEBOUNCE ========= */
function debounce(fn, delay) {
    let timer;
    return function () {
        clearTimeout(timer);
        timer = setTimeout(fn, delay);
    };
}
