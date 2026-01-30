function renderPagination(meta, container, maxPages = 5) {
    let html = '';
    const current = meta.current_page;
    const last = meta.last_page;

    const pageItem = (page, active = false, disabled = false, label = null) => `
        <li class="page-item ${active ? 'active' : ''} ${disabled ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${page}">
                ${label ?? page}
            </a>
        </li>
    `;

    html += pageItem(current - 1, false, current === 1, '&lsaquo;');

    const half = Math.floor(maxPages / 2);

    let start = current - half;
    let end = current + half;

    if (start < 2) {
        end += (2 - start);
        start = 2;
    }

    if (end > last - 1) {
        start -= (end - (last - 1));
        end = last - 1;
    }

    if (start < 2) start = 2;

    html += pageItem(1, current === 1);

    if (start > 2) {
        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    }

    for (let i = start; i <= end; i++) {
        html += pageItem(i, i === current);
    }

    if (end < last - 1) {
        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    }

    if (last > 1) {
        html += pageItem(last, current === last);
    }

    html += pageItem(current + 1, false, current === last, '&rsaquo;');

    container.html(html);
}
