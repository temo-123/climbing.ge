import { createI18n } from 'vue-i18n';

import en from "../../../lang/i18n/en.json";
import ka from "../../../lang/i18n/ka.json";

// The URL decides the language (/ka/... = Georgian, everything else English; app.js
// beforeEach keeps localStorage 'lang' in sync). Read it here too, so the first render
// is already right. Old / foreign values ('en', 'ru', '') used to become a locale with
// no messages, and every string then fell back to Georgian.
const path = window.location.pathname
const locale = (path === '/ka' || path.startsWith('/ka/')) ? 'ka'
    : (path === '/' && localStorage.getItem('lang') === 'ka') ? 'ka'
    : 'en'
const currency = localStorage.getItem("currency") || "USD";

const numberFormats = {
    en: {
        currency: {
            style: "currency",
            currency: 'USD',
        },
        decimal: {
            style: "decimal",
            minimumSignificantDigits: 3,
            maximumSignificantDigits: 5,
        },
        percent: {
            style: "percent",
            useGrouping: false,
        },
    },
    ka: {
        currency: {
            style: "currency",
            currency: 'GEL',
        },
        decimal: {
            style: "decimal",
            minimumSignificantDigits: 3,
            maximumSignificantDigits: 5,
        },
        percent: {
            style: "percent",
            useGrouping: false,
        },
    },
};

const i18n = createI18n({
    legacy: false,
    allowComposition: true,
    locale: locale,
    // English is the default site language — a key missing in one language shows English
    fallbackLocale: "en",
    numberFormats,
    messages: {
        en,
        ka,
    },
});

export default i18n;

