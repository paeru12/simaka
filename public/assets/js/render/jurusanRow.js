// render/jurusanRow.js

function renderJurusanRow(item, index, currentPage, perPage) {
    const no = (currentPage - 1) * perPage + index + 1;

    const allowDelete = item.kelas_count == 0;

    return `
        <tr>
            <th>${no}.</th>
            <td class="text-uppercase">${item.nama}</td>

            <td>
                <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown">
                    <i class="ri-bar-chart-horizontal-fill"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                    <li>
                        <button class="dropdown-item d-flex align-items-center editBtnJurusan"
                            data-id="${item.id}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Update</span>
                        </button>
                    </li>

                    ${allowDelete ? `
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item d-flex align-items-center deleteBtnJurusan"
                                data-id="${item.id}">
                                <i class="ri ri-delete-bin-6-fill"></i>
                                <span>Delete</span>
                            </button>
                        </li>
                    ` : ''}

                </ul>
            </td>
        </tr>
    `;
}
