function renderPagination(meta, container) {
    let html = '';

    // Prev
    html += `
        <li class="page-item ${meta.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${meta.current_page - 1}">‹</a>
        </li>
    `;

    for (let i = 1; i <= meta.last_page; i++) {
        html += `
            <li class="page-item ${i === meta.current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
    }

    // Next
    html += `
        <li class="page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${meta.current_page + 1}">›</a>
        </li>
    `;

    container.html(html);
}
