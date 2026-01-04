function renderDataInfo(meta, container) {
    let from = (meta.current_page - 1) * meta.per_page + 1;
    let to = Math.min(meta.current_page * meta.per_page, meta.total);

    container.text(`Showing ${from} to ${to} of ${meta.total} entries`);
}
