function renderGuruRow(item, index, currentPage, perPage) {
    const no = (currentPage - 1) * perPage + index + 1;

    const foto = item.foto
        ? `${BASE_URL}${item.foto}`
        : `${BASE_URL}assets/img/blank.jpg`;

    const jabatan = item.jabatan?.jabatan ?? '-';

    const qrButton = item.qrguru
        ? `<a href="/qr-guru/${item.id}/download" class="btn btn-purple btn-sm">Download</a>`
        : `<button class="btn btn-primary btn-generate" data-id="${item.id}">Generate</button>`;

    return `
        <tr>
            <th>${no}.</th>

            <td>
                <img src="${foto}"
                     class="img-thumbnail p-0 border-none rounded-circle"
                     style="width: 40px; aspect-ratio: 1/1; object-fit: cover;">
            </td>

            <td class="text-capitalize">${item.nama}</td>
            <td>${item.users?.email ?? '-'}</td>
            <td class="text-capitalize">${jabatan}</td>
            <td>${item.jk}</td>
            <td>${item.no_hp}</td>

            <td>${qrButton}</td>

            <td>
                <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown">
                    <i class="ri-bar-chart-horizontal-fill"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                    <li>
                        <a class="dropdown-item d-flex align-items-center"
                           href="/profile/${item.id}/edit">
                           <i class="bi bi-person"></i>
                           <span>Profile</span>
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a href="javascript:void(0);" 
                           class="dropdown-item d-flex align-items-center btn-delete"
                           data-id="${item.id}">
                            <i class="ri ri-delete-bin-6-fill"></i>
                            <span>Delete</span>
                        </a>
                    </li>
                </ul>
            </td>
        </tr>
    `;
}
