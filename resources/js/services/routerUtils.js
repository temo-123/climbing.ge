import i18n from './localization/i18n.js';

export function getCurrentLocale() {
  const lang = localStorage.getItem('lang') || i18n.locale || 'en';
  return lang === 'us' ? 'en' : lang;
}

export function getBackendLocale() {
  const lang = localStorage.getItem('lang') || 'en';
  return lang === 'us' ? 'en' : lang;
}

export function setLocale(locale) {
  localStorage.setItem('lang', locale);
  i18n.locale = locale;
}

export function buildLocalePath(path) {
  const locale = getCurrentLocale();
  if (locale === 'en') {
    return path;
  }
  return `/${locale}${path}`;
}

export function getLocalePrefix() {
  const locale = getCurrentLocale();
  return locale === 'en' ? '' : `/${locale}`;
}

// For route redirect: default to en if no lang
export function getDefaultLocalePath(path) {
  return buildLocalePath(path);
}

