import './bootstrap';
import { createApp } from 'vue';
import router from './routes'
import axios from 'axios';

// Configure axios
// Use window location for base URL to handle different environments
// Check if we're in development (file:// or localhost) vs production
const isDevelopment = window.location.protocol === 'file:' ||
    window.location.hostname === 'localhost' ||
    window.location.hostname === '127.0.0.1';

if (isDevelopment && window.location.protocol === 'file:') {
    // If opening from file://, we need to use a server
    console.warn('Warning: File:// protocol detected. Please use a local server (http://localhost) to avoid CORS issues.');
    axios.defaults.baseURL = 'http://localhost/';
} else {
    axios.defaults.baseURL = window.location.origin + '/';
}

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

// Handle CORS properly - credentials disabled by default to match CORS config
axios.defaults.withCredentials = false;

// Add CSRF token handling for Laravel
const csrfToken = document.head.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
}

// Add token to requests if available
axios.interceptors.request.use(
    config => {
        const token = localStorage.getItem('admin_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

// Handle errors including CORS
axios.interceptors.response.use(
    response => response,
    error => {
        // Handle CORS errors
        if (!error.response) {
            // Network error or CORS error
            if (error.code === 'ERR_NETWORK' || error.message.includes('CORS') || error.message.includes('Network Error')) {
                console.error('CORS Error: Cross-Origin Request Blocked');
                console.error('Solution: Access the application via HTTP server (e.g., http://localhost or your domain)');
                console.error('Current URL:', window.location.href);

                // Show user-friendly error
                if (window.Swal) {
                    window.Swal.fire({
                        icon: 'error',
                        title: 'Connection Error',
                        text: 'Cannot connect to server. Please ensure you are accessing the application via HTTP (not file://) and the server is running.',
                        footer: 'If using XAMPP, access via: http://localhost/s_h_micro_control'
                    });
                }
            }
        }

        // Handle 401 errors
        if (error.response && error.response.status === 401) {
            localStorage.removeItem('admin_token');
            router.push({ name: 'AdminLogin' });
        }

        return Promise.reject(error);
    }
);

// Vuetify 
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
    components,
    directives,
})


// VueProgressBar
import VueProgressBar from "@aacassandra/vue3-progressbar";
const progressBarOptions = {
    color: '#66FE5E',
    failedColor: '#f44336',
    thickness: "4px",
    transition: {
        speed: "0.2s",
        opacity: "0.6s",
        termination: 300,
    },
    autoRevert: true,
    location: "top", // left, right, top, bottom
    inverse: false,
};

console.log('VueProgressBar plugin loaded:', VueProgressBar);

// VueSweetalert2
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import Swal from 'sweetalert2';
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
})
window.Swal = Swal;
window.Toast = Toast;



import App from './components/app.vue';

const app = createApp(App);

app.use(router)
app.use(vuetify)
app.use(VueProgressBar, progressBarOptions)
app.use(VueSweetalert2)

// Export router with app instance for progress bar access
router.app = app;

app.mount('#app');

// Helper function to access progress bar from router
router.getProgressBar = function () {
    if (this.app && this.app.config && this.app.config.globalProperties) {
        const progressBar = this.app.config.globalProperties.$Progress;
        console.log('Progress bar from router:', progressBar);
        return progressBar;
    }
    return null;
};

// Store progress bar access on window for easy access
window.getProgressBar = () => {
    if (router.getProgressBar) {
        return router.getProgressBar();
    }
    return null;
};

// Debug: Check if progress bar is available after mount
setTimeout(() => {
    const progressBar = router.getProgressBar();
    console.log('Progress bar after mount:', progressBar);
    if (progressBar) {
        console.log('Progress bar methods:', Object.keys(progressBar));
    }
}, 500);
