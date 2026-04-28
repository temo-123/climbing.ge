import { createI18n } from 'vue-i18n';

import en from "../../../lang/i18n/en.json";
import ka from "../../../lang/i18n/ka.json";

const locale = (localStorage.getItem('lang') === 'us' ? 'en' : localStorage.getItem('lang')) || 'en'
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
    fallbackLocale: "ka",
    numberFormats,
    messages: {
        en,
        ka,
    },
});

export default i18n;

