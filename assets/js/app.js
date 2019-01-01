import babelPolyfill from 'babel-polyfill';
import Vue from 'vue';
import Vuelidate from 'vuelidate/lib/index';
import VueRouter from 'vue-router';
import store from './store';
import router from './router';
import App from './components/App';
import ConfirmModal from './components/custom/confirmRejectModal';
import Loader from './components/custom/Loader';

Vue.use(Vuelidate);
Vue.use(VueRouter);
Vue.component('app-confirm-modal', ConfirmModal);
Vue.component('app-loader', Loader);


new Vue({
    el: '#app',
    router,
    store,
    components: {App},
    template: '<App/>',
    computed: {
        auth() {
            return this.$store.getters.auth
        }
    },
    created() {
        this.$store.dispatch('setAuth');
    }
});