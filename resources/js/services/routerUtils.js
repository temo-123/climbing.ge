export function getCurrentLocale() {
    // Get from URL param, localStorage, or default
    const locale = localStorage.getItem('lang') || 'us';
    return locale;
}

export function setCurrentLocale(locale) {
    localStorage.setItem('lang', locale);
    // Optionally update i18n.locale here if accessible
}

