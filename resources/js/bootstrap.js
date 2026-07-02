import _ from 'lodash';
window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach on every request manually.
 */

// CSRF token is handled dynamically in the axios request interceptor below
// by reading the XSRF-TOKEN cookie on every request — no static header needed here.

function getCsrfCookie() {
    const match = document.cookie.match(/(?:^|; )XSRF-TOKEN=([^;]*)/);
    return match ? decodeURIComponent(match[1]) : null;
}

axios.interceptors.request.use(config => {
    // Read XSRF-TOKEN cookie fresh on every request so stale tokens never cause 419s
    const xsrf = getCsrfCookie();
    if (xsrf) {
        config.headers['X-XSRF-TOKEN'] = xsrf;
    }
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers['Authorization'] = 'Bearer ' + token;
    }
    return config;
});

/**
 * Global response error handling — this runs for every axios request made
 * anywhere in the app (no per-component try/catch needed). 401/419/422/403
 * get special handling (session refresh, redirects); everything else
 * (500, 429, etc.) falls through to a generic toast via the global $bus.
 *
 * This file loads before the Vue app/router are created (bootstrap.js is
 * imported at the top of app.js), so we reach them via window.$bus /
 * window.router, which app.js assigns as soon as they exist. By the time
 * any real HTTP error fires, app.js has already finished its synchronous
 * setup, so those globals are populated.
 */
let csrfRefreshPromise = null;
let isHandlingAuthError = false;

axios.interceptors.response.use(response => response, async err => {
    if (err && err.response) {
        const status = err.response.status;
        const originalRequest = err.config;

        if (status === 419 && !originalRequest._csrfRetry) {
            // Refresh the CSRF cookie once, then retry the original request.
            // All concurrent 419s share the same refresh promise so we only hit /csrf-cookie once.
            originalRequest._csrfRetry = true;
            if (!csrfRefreshPromise) {
                csrfRefreshPromise = axios.get(window.location.origin + '/sanctum/csrf-cookie').finally(() => {
                    csrfRefreshPromise = null;
                });
            }
            try {
                await csrfRefreshPromise;
                const xsrf = getCsrfCookie();
                if (xsrf) originalRequest.headers['X-XSRF-TOKEN'] = xsrf;
                return axios(originalRequest);
            } catch (e) {
                // CSRF refresh failed — fall through to auth error handling
            }
        }

        if (status === 401 || status === 419) {
            if (!isHandlingAuthError) {
                isHandlingAuthError = true;
                localStorage.removeItem('x_xsrf_token');
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_permissions');
                if (window.location.hostname == process.env.MIX_USER_PAGE_URL && window.router) {
                    window.router.push({ name: 'login' });
                }
                setTimeout(() => { isHandlingAuthError = false; }, 3000);
            }
            return Promise.reject(err);
        }
        else if (status === 422) {
            return Promise.reject(err);
        }
        else if (status === 403) {
            if (err.response.data && err.response.data.is_banned === true) {
                localStorage.removeItem('x_xsrf_token');
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_permissions');
                sessionStorage.setItem('banned_redirect', '1');
                if (window.location.hostname == process.env.MIX_USER_PAGE_URL && window.router) {
                    window.router.push({ name: 'banned' });
                } else {
                    window.location.href = (process.env.MIX_APP_SSH || '') + '/banned';
                }
                return Promise.reject(err);
            }
            else {
                window.$bus?.$emit('toast', {
                    type: 'warning',
                    message: "You don't have permission to perform this action.",
                });
                return Promise.reject(err);
            }
        }
        else {
            // Covers 500, 429, and every other unhandled status code.
            window.$bus?.$emit('toast', {
                type: 'danger',
                message: "Error " + err.response.status + ': ' + err.response.statusText,
            });
            return Promise.reject(err);
        }
    }
    return Promise.reject(err);
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

// Only initialize Echo if Pusher key and cluster are properly configured
const pusherKey = process.env.MIX_PUSHER_APP_KEY;
const pusherCluster = process.env.MIX_PUSHER_APP_CLUSTER;

// Check if Pusher is properly configured (not placeholder values)
const isPusherConfigured = pusherKey && pusherKey.length > 0 && 
                            pusherCluster && 
                            pusherCluster !== 'your_pusher_cluster' &&
                            !pusherCluster.includes('your_');

if (isPusherConfigured) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: pusherKey,
        cluster: pusherCluster,
        forceTLS: true
    });
} else {
    if (pusherKey && !pusherCluster || pusherCluster === 'your_pusher_cluster') {
        console.warn('Pusher cluster not properly configured. Real-time features will be disabled.');
    } else if (!pusherKey) {
        console.warn('Pusher app key not configured. Real-time features will be disabled.');
    }
    // Initialize Echo with a mock to prevent errors
    window.Echo = {
        channel: () => ({ listen: () => {}, notification: () => {}, subscribe: () => ({}) }),
        private: () => ({ listen: () => {}, notification: () => {}, subscribe: () => ({}) }),
        disconnect: () => {}
    };
}

