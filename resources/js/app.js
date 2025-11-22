import './bootstrap';
import { createApp } from 'vue';
import router from './routes'
import axios from 'axios';

// Configure axios
axios.defaults.baseURL = '/';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

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

// Handle 401 errors
axios.interceptors.response.use(
    response => response,
    error => {
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
const options = {
    color: '#66FE5E',
    failedColor: 'red',
    thickness: "5px",
    transition: {
        speed: "0.2s",
        opacity: "0.6s",
        termination: 300,
    },
    autoRevert: true,
    location: "top", // left, right, top, bottom
    inverse: false,
};

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



const app = createApp({
    data() {
        return {
            // For Preloader
            preloader: false
        }
    }
});

import IndexComponent from './components/app.vue';
app.component('index-component', IndexComponent);

app.use(router)
app.use(vuetify)
app.use(VueProgressBar, options)
app.use(VueSweetalert2)
app.mount('#app');
