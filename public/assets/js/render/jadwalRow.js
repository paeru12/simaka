// render/jadwalRow.js

function renderJadwalRow(j, index, currentPage, perPage, isAdmin = false) {
    console.log(j);
    const no = (currentPage - 1) * perPage + index + 1;

    const aksi = isAdmin
        ? `
        <td>
            <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown"><i class="ri-bar-chart-horizontal-fill"></i></button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                <li>
                    <button class="dropdown-item d-flex align-items-center editBtn" data-id="${j.id}">
                        <i class="bi bi-pencil-square"></i>
                        <span>Update</span>
                    </button>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <button class="dropdown-item d-flex align-items-center deleteBtn" data-id="${j.id}">
                        <i class="ri ri-delete-bin-6-fill"></i>
                        <span>Delete</span>
                    </button>
                </li>
            </ul>
        </td>
      `
        : "";

    return `
        <tr>
            <th>${no}.</th>
            <td>${j.guru.nama}</td>
            <td>${j.mata_pelajaran.nama_mapel}</td>
            <td>${j.hari}</td>
            <td>${j.kelas.kelas} <span class="text-uppercase">${j.kelas.jurusan.nama}</span> ${j.kelas.rombel}</td>
            <td class="text-uppercase">${j.ruangan.nama}</td>
            <td>${formatWaktuIndo(j.jam_mulai)}</td>
            <td>${formatWaktuIndo(j.jam_selesai)}</td>
            <td>${j.total_jam}</td>
            ${aksi}
        </tr>
    `;
}
