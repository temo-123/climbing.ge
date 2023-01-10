//https://levelup.gitconnected.com/advanced-vue-js-internationalization-i18n-and-localization-l10n-using-vue-router-a94ecd83fecd
//https://www.youtube.com/watch?v=KHpfBNtwZVs&list=PLL4UjuufFpKsLfSOtLhX1ICwBhH8S-FmV&index=23&t=756s

import Vue from "vue";

import en from "../../../lang/i18n/en.json";
import ka from "../../../lang/i18n/ka.json";
import ru from "../../../lang/i18n/ru.json";

import VueI18n from "vue-i18n";
Vue.use(VueI18n);

const locale = localStorage.getItem('lang') || 'en'
const currency = localStorage.getItem("currency") || "USD";

const numberFormats = {
    en: {
        currency: {
            style: "currency",
            currency: currency,
            // currency: 'USD'
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
            currency: currency,
            // currency: 'GEL',
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
    ru: {
        currency: {
            style: "currency",
            currency: currency,
            // currency: 'EUR'
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

export default new VueI18n({
    locale: locale,
    fallbeckLocale: "ka",
    numberFormats,
    messages: {
        en,
        ka,
        ru,
    },
});
