import babelPolyfill from 'babel-polyfill';
import Vue from 'vue';
import Vuelidate from 'vuelidate/lib/index';
import VueRouter from 'vue-router';
import store from './store';
import router from  './router';
import App from './components/App';
import ConfirmModal from './components/custom/confirmRejectModal';

Vue.use(Vuelidate);
Vue.use(VueRouter);
Vue.component('app-confirm-modal', ConfirmModal);

new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template: '<App/>',
});