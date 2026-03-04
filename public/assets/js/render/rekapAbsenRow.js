function renderRekapAbsenRow(item, index, page, perPage, bulanText, tahun) {
    let no = (page - 1) * perPage + index + 1;

    return `
        <tr>
            <th>${no}.</th>
            <td class="text-capitalize">${item.nama}</td>
            <td class="text-capitalize">${item.jabatan}</td>

            <td>${bulanText} ${tahun}</td>

            <td>${item.total_mapel}</td>
            <td>${item.total_hadir_harian}</td>
            <td>${item.total_izin}</td>
            <td>${item.total_sakit}</td>
            <td>${item.total_alpha}</td>
            <td>${item.total_hadir_mapel}</td>

            <td>
                <span class="badge ${item.total_kehadiran > 0 ? 'bg-success' : 'bg-danger'}">
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
}
