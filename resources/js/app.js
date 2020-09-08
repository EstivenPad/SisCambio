
require('./bootstrap');

window.Vue = require('vue');

/*************** Vue Router *****************/
import router from './routes';

/*************** VTooltip *****************/
import VTooltip from 'v-tooltip';
Vue.use(VTooltip);

/*************** Element *****************/
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
window.Vue.use(ElementUI);

/*************** SweetAler2 *****************/
import Swal from 'sweetalert2';
window.Swal = Swal;

Vue.component('App', require('./components/App.vue').default);

Vue.component('Auth', require('./components/Auth.vue').default);

const app = new Vue({
    el: '#app',
    router
});