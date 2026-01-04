function renderAbsensiMapelRow(item, index, currentPage, perPage, isAdmin) {
    const no = (currentPage - 1) * perPage + index + 1;

    const badge = {
        Alpha: `<span class="badge bg-danger">Alpha</span>`,
        Izin: `<span class="badge bg-warning text-dark">Izin</span>`,
        Sakit: `<span class="badge bg-info text-dark">Sakit</span>`
    }[item.status] ?? `<span class="badge bg-success">Hadir</span>`;

    const hariWaktu = item.jadwal
        ? `${item.jadwal.hari}
           (${formatWaktuIndo(item.jadwal.jam_mulai)} -
            ${formatWaktuIndo(item.jadwal.jam_selesai)})`
        : '-';

    return `
        <tr>
            <th scope="row">${no}.</th>
            <td class="text-capitalize">${item.guru?.nama ?? '-'}</td>
            <td class="text-uppercase">${item.jadwal?.kelas?.kelas ?? '-'} ${item.jadwal?.kelas?.rombel ?? ''}</td>
            <td class="text-uppercase">${item.jadwal?.ruangan?.nama ?? '-'}</td>
            <td class="text-capitalize">${item.mata_pelajaran?.nama_mapel ?? '-'}</td>
            <td>${hariWaktu}</td>
            <td>${formatTanggalIndo(item.tanggal)}</td>
            <td>${formatWaktuIndo(item.jam_absen)}</td>
            <td>${badge}</td>
            <td>${item.keterangan ?? '-'}</td>
            <td>
                <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#foto${item.id}">
                    <i class="ri-bar-chart-horizontal-fill"></i>
                </button>
                <div class="modal fade" id="foto${item.id}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Absensi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <img src="${asset(item.foto)}" class="w-100">
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            ${isAdmin ? `
            <td>
                <button class="btn btn-danger btn-sm deleteBtn" data-id="${item.id}">
                    <i class="ri-delete-bin-3-line"></i>
                </button>
            </td>` : ''}
        </tr>
    `;
}
