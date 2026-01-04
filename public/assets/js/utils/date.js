/**
 * Format tanggal Indonesia
 * Senin, 12 Jan 2025
 */
function formatTanggalIndo(dateStr) {
    if (!dateStr) return '-';

    const date = new Date(dateStr);
    if (isNaN(date)) return '-';

    return date.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

/**
 * Format waktu Indonesia
 * 07:15 WIB
 */
function formatWaktuIndo(timeStr) {
    if (!timeStr) return '-';

    // support "07:15" atau "07:15:00"
    const [h, m] = timeStr.split(':');
    if (!h || !m) return '-';

    return `${h.padStart(2, '0')}:${m.padStart(2, '0')} WIB`;
}

/**
 * Format tanggal + waktu
 * Senin, 12 Jan 2025 • 07:15 WIB
 */
function formatTanggalWaktuIndo(dateStr, timeStr) {
    if (!dateStr && !timeStr) return '-';

    const tanggal = dateStr ? formatTanggalIndo(dateStr) : '';
    const waktu = timeStr ? formatWaktuIndo(timeStr) : '';

    if (tanggal && waktu) return `${tanggal} • ${waktu}`;
    return tanggal || waktu;
}
