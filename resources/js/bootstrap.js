// const { default: router } = require('./routes/UserRoutes');

if (window.location.hostname == process.env.MIX_SITE_URL) {
    const { default: router } = require('./routes/SiteRoutes');
} else if (window.location.hostname == process.env.MIX_SHOP_URL) {
    const { default: router } = require('./routes/ShopRoutes');
} else if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {
    const { default: router } = require('./routes/UserRoutes');
} else if (window.location.hostname == process.env.MIX_FILMS_URL) {
    // const { default: router } = require('./routes/UserRoutes');
} else if (window.location.hostname == process.env.MIX_FORUM_URL) {
    // const { default: router } = require('./routes/UserRoutes');    
} else {
    // homeComponent = Error;
}

window._ = require('lodash');

try {
    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */


window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.whithCredentials = true;

window.axios.interceptors.response.use({}, err => {
    if(err && err.response){
        if(err.response.status === 401 || err.response.status === 419){
            const token = localStorage.getItem('x_xsrf_token')
            if(token){
                localStorage.removeItem('x_xsrf_token')
            }

            if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {
                router.push({name: "login"})
            }

            return Promise.reject(err)
        }
        else if(err.response.status === 422){
            return Promise.reject(err)
        }
        else if(err.response.status === 403){
            // Check if user is banned
            if (err.response.data && err.response.data.is_banned === true) {
                // Show ban alert
                if (err.response.data.alert) {
                    alert(err.response.data.alert.title + '\n\n' + err.response.data.alert.message);
                } else {
                    alert('Your account has been banned.');
                }
                
                // Clear local storage and close page
                localStorage.removeItem('x_xsrf_token');
                localStorage.removeItem('user');
                window.close();
                return Promise.reject(err);
            }
            else{
                alert("You don't have permission to perform this action.")
                return Promise.reject(err)
            }
            // return Promise.reject(err)
        }
        // else if(err.response.status === 404){
            // window.location.href = process.env.MIX_APP_SSH + process.env.MIX_SITE_URL + "/404";
        // }
        else{
            alert("Error -> {" + err + "}")
            return Promise.reject(err)
        }
    }
})

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

