// render/jabatanRow.js
function renderJabatanRow(item, index, currentPage, perPage) {
    const no = (currentPage - 1) * perPage + index + 1;

    return `
        <tr>
            <th>${no}.</th>
            <td class="text-capitalize">${item.jabatan}</td>
            <td>Rp.${new Intl.NumberFormat('id-ID').format(item.nominal_gaji)}</td>
            <td>
                <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown">
                    <i class="ri-bar-chart-horizontal-fill"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                    <li>
                        <button class="dropdown-item d-flex align-items-center editBtn"
                            data-id="${item.id}">
                            <i class="bi bi-pencil-square"></i>
                            <span>Update</span>
                        </button>
                    </li>

                    ${item.jabatan.toLowerCase() !== 'admin' ? `
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item d-flex align-items-center deleteBtn"
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
