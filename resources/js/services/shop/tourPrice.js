// Tour price calculator — same rule as App\Services\TourPriceService::quote()
// (docs/FUNCTIONS_DOCS/shop/tour_price_calculator.md). Used for the live preview in
// the tour page form and the admin Prices tab; the server recalculates on save.

export const CURRENCY_SYMBOLS = { GEL: '₾', USD: '$', EUR: '€' };

// rules: [{ days, persons_min, persons_max (null = and more), price, price_type }]
export function quote_price(rules, days, persons) {
    days = parseInt(days, 10);
    persons = parseInt(persons, 10);
    if (!days || !persons || !Array.isArray(rules)) return null;
    const width = r => (r.persons_max === null || r.persons_max === undefined || r.persons_max === '') ? Infinity : r.persons_max - r.persons_min;
    const match = rules
        .filter(r => Number(r.days) === days
            && persons >= Number(r.persons_min)
            && (r.persons_max === null || r.persons_max === undefined || r.persons_max === '' || persons <= Number(r.persons_max)))
        .sort((a, b) => width(a) - width(b))[0];
    if (!match) return null;
    const price = Number(match.price);
    const total = match.price_type === 'per_group' ? price : price * persons;
    return {
        days, persons,
        price_type: match.price_type,
        unit_price: price,
        total: Math.round(total * 100) / 100,
        per_person: Math.round((total / persons) * 100) / 100,
        persons_min: match.persons_min,
        persons_max: match.persons_max,
    };
}

// 1200 → "1 200 ₾"
export function money(value, currency = 'EUR') {
    if (value === null || value === undefined || value === '' || isNaN(value)) return '';
    const n = Number(value);
    const text = n.toLocaleString('en-US', { minimumFractionDigits: n % 1 ? 2 : 0, maximumFractionDigits: 2 }).replace(/,/g, ' ');
    return text + ' ' + (CURRENCY_SYMBOLS[currency] || currency || '');
}

// smallest / largest group size the rules know (for the persons stepper)
export function persons_range(rules) {
    if (!Array.isArray(rules) || !rules.length) return { min: 1, max: 100 };
    const min = Math.min(...rules.map(r => Number(r.persons_min) || 1));
    const open = rules.some(r => r.persons_max === null || r.persons_max === undefined || r.persons_max === '');
    const max = open ? 100 : Math.max(...rules.map(r => Number(r.persons_max) || 1));
    return { min, max };
}
