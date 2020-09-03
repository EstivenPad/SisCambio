/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/*************** VueJS *****************/
window.Vue = require('vue');
Vue.component('App', require('./components/App.vue').default);

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

const app = new Vue({
    el: '#app',
    router
});
