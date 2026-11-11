// Gear rental formatting shared by the admin page and its modals.

// 12 → "12 ₾", 12.5 → "12.50 ₾" (all rental money is GEL)
export function rental_money(v) {
    if (v === null || v === undefined || v === '') return '—'
    const n = Number(v)
    if (isNaN(n)) return '—'
    return (Number.isInteger(n) ? String(n) : n.toFixed(2)) + ' ₾'
}

// 1 → "1 day", 3 → "3 days" (vue-i18n plural)
export function period_label(t, days) {
    return t('admin.gear_rental.days', { n: days }, Number(days))
}

// <input type="datetime-local"> value for a Date (local time)
export function to_local_input(date) {
    const d = date instanceof Date ? date : new Date(String(date).replace(' ', 'T'))
    if (isNaN(d.getTime())) return ''
    const p = n => String(n).padStart(2, '0')
    return d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate()) + 'T' + p(d.getHours()) + ':' + p(d.getMinutes())
}

export const PERIOD_PRESETS = [1, 2, 3, 5, 7, 14]
