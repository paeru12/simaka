function renderAdminRow(admin, index, currentPage, perPage) {
    console.log(admin);
    const no = (currentPage - 1) * perPage + index + 1;

    const fotoSrc = admin.guru?.foto ?? "assets/img/blank.jpg";

    const qr = admin.guru?.qrguru
        ? `<a href="/qr-guru/${admin.guru_id}/download" class="btn btn-purple btn-sm">Download</a>`
        : `<button class="btn btn-primary btn-generate" data-id="${admin.guru_id}">
                Generate
           </button>`;

    return `
        <tr>
            <th>${no}.</th>
            <td>
                <img src="${fotoSrc}" class="img-thumbnail p-0 border-none rounded-circle"
                     style="width: 40px; aspect-ratio: 1/1; object-fit: cover;">
            </td>
            <td class="text-capitalize">${admin.guru.nama}</td>
            <td>${admin.email}</td>
            <td>${admin.guru.jk}</td>
            <td>${admin.guru.no_hp}</td>
            <td>${qr}</td>
            <td>
                <button class="btn btn-purple btn-sm" data-bs-toggle="dropdown">
                    <i class="ri-bar-chart-horizontal-fill"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li>
                        <a class="dropdown-item d-flex align-items-center"
                           href="/profile/${admin.guru_id}/edit">
                            <i class="bi bi-person"></i>
                            <span>Profile</span>
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <button class="dropdown-item d-flex align-items-center btn-delete"
                                data-id="${admin.guru_id}">
                            <i class="ri ri-delete-bin-6-fill"></i>
                            <span>Delete</span>
                        </button>
                    </li>
                </ul>
            </td>
        </tr>
    `;
}
