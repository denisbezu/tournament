import Vue from 'vue';
import Router from 'vue-router';
import Home from '../components/home/Home';
import Info from '../components/info/Info';
import TournamentsList from '../components/tournaments/TournamentsList';

Vue.use(Router);

export default new Router({
    routes: [
        {
            path: '',
            name: 'home',
            component: Home
        },
        {
            path: '/info',
            name: 'info',
            component: Info
        },
        {
            path: '/tournaments/list',
            name: 'tournaments',
            component: TournamentsList
        },
        // {
        //     path: '/list',
        //     name: 'list',
        //     component: AdList,
        //     beforeEnter: AuthGuard
        // },

    ],
    mode: 'history'
})