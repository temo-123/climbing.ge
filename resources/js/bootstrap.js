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

// CSRF token is handled dynamically in the axios request interceptor (app.js)
// by reading the XSRF-TOKEN cookie on every request — no static header needed here.

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

