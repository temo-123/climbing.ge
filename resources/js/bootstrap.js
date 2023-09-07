// const { default: router } = require('./routes/UserRoutes');

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
    if(err.response.status === 401 || err.response.status === 419){
        const token = localStorage.getItem('x_xsrf_token')
        if(token){
            localStorage.removeItem('x_xsrf_token')
        }
        router.push({name: "login"})
    }
    else if(err.response.status === 422){
        return Promise.reject(err)
    }
    else if(err.response.status === 404){
        window.location.href = process.env.MIX_APP_SSH + process.env.MIX_SITE_URL + "/404";
    }
    else{
        alert("Error log -> {" + err + "}")
        return Promise.reject(err)
    }
})

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
