import Vue from 'vue';
import VueRouter from 'vue-router';
import router from  './router';
import App from './components/App';

Vue.use(VueRouter);
new Vue({
    el: '#app',
    router,
    components: { App },
    template: '<App/>',
});