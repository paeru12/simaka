$(document).ready(function () {
    const bulanSekarang = new Date().getMonth() + 1;
    const tahunSekarang = new Date().getFullYear();

    $('#bulan').val(bulanSekarang);
    $('#tahun').val(tahunSekarang);

    loadData();

    $('#bulan, #tahun').change(() => loadData());
    $('#search').keyup(() => loadData());

    $(document).on('click', '.pageBtn', function () {
        let page = $(this).data('page');
        if (page) loadData(page);
    });
});

function loadData(page = 1) {
    $.ajax({
        url: "rekapp/filter",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            page: page,
            bulan: $('#bulan').val(),
            tahun: $('#tahun').val(),
            search: $('#search').val()
        },
        beforeSend() {
            $('#tableBody').html(`
                <tr>
                    <td colspan="11" class="text-center">Loading...</td>
                </tr>
            `);
        },
        success(res) {
            let html = '';
            let no = (res.current_page - 1) * res.per_page;

            if (res.data.length === 0) {
                html = `<tr><td colspan="11" class="text-center">Data tidak ditemukan</td></tr>`;
            } else {
                res.data.forEach((item, i) => {
                    html += `
                        <tr>
                            <th>${no + i + 1}.</th>
                            <td class="text-capitalize">${item.nama}</td>
                            <td class="text-capitalize">${item.jabatan}</td>
                            <td>${$('#bulan option:selected').text()} ${$('#tahun').val()}</td>
                            <td>${item.total_mapel}</td>
                            <td>${item.total_hadir_harian}</td>
                            <td>${item.total_izin}</td>
                            <td>${item.total_sakit}</td>
                            <td>${item.total_alpha}</td>
                            <td>${item.total_hadir_mapel}</td>
                            <td>
                                <span class="badge bg-success">
                                    ${item.total_kehadiran}
                                </span>
                            </td>
                            <td>
                                <a href="/detail/${item.guru_id}/${$('#bulan').val()}/${$('#tahun').val()}"
                                   class="btn btn-sm btn-purple">
                                    <i class="ri-bar-chart-horizontal-fill"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });
            }

            $('#tableBody').html(html);
            renderPagination(res);
        }
    });
}

function renderPagination(res) {
    let pag = `
        <li class="page-item ${res.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link pageBtn" data-page="${res.current_page - 1}">&laquo;</a>
        </li>
    `;

    for (let i = 1; i <= res.last_page; i++) {
        pag += `
            <li class="page-item ${i === res.current_page ? 'active' : ''}">
                <a class="page-link pageBtn" data-page="${i}">${i}</a>
            </li>
        `;
    }

    pag += `
        <li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}">
            <a class="page-link pageBtn" data-page="${res.current_page + 1}">&raquo;</a>
        </li>
    `;

    $('#pagination').html(pag);
}
