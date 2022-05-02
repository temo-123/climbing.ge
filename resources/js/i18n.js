import en from '../lang/i18n/en.json'
import ka from '../lang/i18n/ka.json'
import ru from '../lang/i18n/ru.json'
import Vue from 'vue';
// import VueI18n from 'vue-lang-router'  // https://www.npmjs.com/package/vue-lang-router
import VueI18n from 'vue-i18n';
Vue.use(VueI18n)


const locale = localStorage.getItem('leng') || 'en'
const currency = localStorage.getItem('currency') || 'USD'

console.log(currency);
const numberFormats = {
    'en-US': {
        currency: {
            style: 'currency', 
            currency: currency
            // currency: 'USD'
        },
        decimal: {
          style: 'decimal', minimumSignificantDigits: 3, maximumSignificantDigits: 5
        },
        percent: {
          style: 'percent', useGrouping: false
        }
    },
    'ka': {
        currency: {
            style: 'currency', 
            currency: currency,
            // currency: 'GEL',
        },
        decimal: {
          style: 'decimal', minimumSignificantDigits: 3, maximumSignificantDigits: 5
        },
        percent: {
          style: 'percent', useGrouping: false
        }
    },
    'ru': {
        currency: {
            style: 'currency', 
            currency: currency
            // currency: 'EUR'
        },
        decimal: {
          style: 'decimal', minimumSignificantDigits: 3, maximumSignificantDigits: 5
        },
        percent: {
          style: 'percent', useGrouping: false
        }
    },
    // locale: {
    //     currency: {
    //         style: 'currency', 
    //         currency: currency
    //     }
    // }
}

// const num = 2

// numberFormats.ka.push( num );

// console.log(numberFormats);

export default new VueI18n({
    locale: localStorage.getItem('lang') || 'en',
    fallbeckLocale: 'ka',
    numberFormats,
    messages: {
        en: en,
        ka: ka,
        ru: ru
    }
})






// import Vue from 'vue'
// import VueI18n from 'vue-i18n'
// import en from '@/locales/en.json'

// const defaultImpl = VueI18n.prototype.getChoiceIndex

// VueI18n.prototype.getChoiceIndex = function(choice, choicesLength) {
//   // this === VueI18n instance, so the locale property also exists here
//   if (this.locale !== 'ru') {
//     // proceed to the default implementation
//     return defaultImpl.apply(this, arguments)
//   }

//   if (choice === 0) {
//     return 0;
//   }

//   const teen = choice > 10 && choice < 20;
//   const endsWithOne = choice % 10 === 1;

//   if (!teen && endsWithOne) {
//     return 1;
//   }

//   if (!teen && choice % 10 >= 2 && choice % 10 <= 4) {
//     return 2;
//   }

//   return (choicesLength < 4) ? 2 : 3;
// }

// Vue.use(VueI18n)

// const dateTimeFormats = {
//   'en': {
//     short: {
//       year: 'numeric',
//       month: 'short',
//       day: 'numeric'
//     }
//   },
//   'ru': {
//     short: {
//       year: 'numeric',
//       month: 'short',
//       day: 'numeric'
//     }
//   }
// }

// const numberFormats = {
//   'en': {
//     currency: {
//       style: 'currency', currency: 'USD'
//     }
//   },
//   'ru': {
//     currency: {
//       style: 'currency', currency: 'RUB'
//     }
//   }
// }

// export const i18n = new VueI18n({
//   locale: process.env.VUE_APP_I18N_LOCALE || 'en',
//   fallbackLocale: process.env.VUE_APP_I18N_FALLBACK_LOCALE || 'en',
//   messages: { en },
//   dateTimeFormats,
//   numberFormats
// })
