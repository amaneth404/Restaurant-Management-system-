import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import { Form, HasError, AlertError } from 'vform';
import "@fortawesome/fontawesome-free/css/all.min.css";
import "mdi/css/materialdesignicons.min.css";
import { BootstrapVue, BootstrapVueIcons } from "bootstrap-vue";
Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import birthDatepicker from "vue-birth-datepicker";
import "vue-birth-datepicker/dist/vueBirthDatepicker.css";
Vue.component("birth-datepicker", birthDatepicker);
import { VuejsDatatableFactory } from 'vuejs-datatable';
import Swal from 'sweetalert2';
import tigrga from "./languages/tg.json"
import english from "./languages/en.json"
import VueI18n from 'vue-i18n'
import VFToFD from './plugins/vformconvertToFrd'
import vformconvertor from './plugins/vformconvertor'
import excel from 'vue-excel-export'
Vue.use(excel)
Vue.component('pagination', require('laravel-vue-pagination'));
window.VFToFD = VFToFD
window.VFToF = vformconvertor
Vue.use(VueI18n)
window.Swal = Swal;
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})
window.Toast = Toast;
Vue.use(VuejsDatatableFactory);
import moment from 'moment-timezone';
Vue.filter('myDate', function(createdat) {
    moment().tz('Africa/Nairobi')
    return moment(createdat).format("MMMM DD YYYY")
})
import VueTelInput from 'vue-tel-input'
Vue.use(VueTelInput)
if (localStorage.getItem('base_url')) {
    axios.defaults.baseURL = localStorage.getItem('base_url')
} else {
    axios.defaults.baseURL = "http://127.0.0.1:8000/store/api/v1/";
}
// axios.defaults.headers['Content-Type'] = 'application/x-www-form-urlencoded';
const token = localStorage.getItem("token_store_token_identifyer")
if (token) {
    axios.defaults.headers.common['Authorization'] = 'Bearer' + token
}

window.axios = axios;
if (localStorage.getItem('base_image_url')) {
    window.image_url = localStorage.getItem('base_image_url');
} else {
    window.image_url = "http://127.0.0.1:8000/storage/uploads/images/";
}
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requireslicence)) {
        if (store.getters.isLoggedIn) {
            console.log(store.getters.isLoggedIn)
            next()
        } else {
            next('/login')
        }
    } else {
        if (to.matched.some(record => record.meta.localaccess)) {
            localStorage.setItem('base_url', 'http://127.0.0.1:8000/store/api/v1/')
            localStorage.setItem('base_image_url', 'http://127.0.0.1:8000/storage/uploads/images/')
            axios.defaults.baseURL = localStorage.getItem('base_url')
            window.image_url = localStorage.getItem('base_image_url');
            next('/login')
        } else if (to.matched.some(record => record.meta.publicaccess)) {
            localStorage.setItem('base_url', 'http://192.168.1.20:8000/store/api/v1/')
            localStorage.setItem('base_image_url', 'http://192.168.1.20:8000/storage/uploads/images/')
            axios.defaults.baseURL = localStorage.getItem('base_url')
            window.image_url = localStorage.getItem('base_image_url');
            next('/login')
        }
        next()
    }
})
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
window.Form = Form;
window.Fire = new Vue();
Vue.config.productionTip = false
const messages = {
    en: english,
    tg: tigrga,

};
const i18n = new VueI18n({
    locale: 'en',
    messages
});
new Vue({
    router,
    store,
    i18n,
    render: h => h(App)
}).$mount('#app')