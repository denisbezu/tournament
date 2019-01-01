import Vue from 'vue';
import Router from 'vue-router';
import Home from '../components/home/Home';
import Info from '../components/info/Info';
import TournamentsList from '../components/tournaments/TournamentsList';
import TournamentsAdd from '../components/tournaments/TournamentsAdd';
import Schedule from '../components/schedule/Schedule';
import PlayersList from '../components/players/PlayersList';
import PlayersAdd from '../components/players/PlayerAdd';
import PlayersEdit from '../components/players/PlayerEdit';
import TournamentsEdit from "../components/tournaments/TournamentsEdit";
import MatchAdd from "../components/schedule/MatchAdd";
import MatchEdit from "../components/schedule/MatchEdit";

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
        {
            path: '/tournaments/add',
            name: 'tournaments-add',
            component: TournamentsAdd
        },
        {
            path: '/tournaments/edit/:id',
            props: true,
            name: 'tournaments-edit',
            component: TournamentsEdit
        },
        {
            path: '/schedule',
            name: 'schedule',
            component: Schedule
        },
        {
            path: '/players/list',
            name: 'players',
            component: PlayersList
        },
        {
            path: '/players/add',
            name: 'players-add',
            component: PlayersAdd
        },
        {
            path: '/players/edit/:id',
            name: 'players-edit',
            component: PlayersEdit,
            props: true
        },
        {
            path: '/match/add/:tournament/:court',
            name: 'match-add',
            component: MatchAdd,
            props: true
        },
        {
            path: '/match/edit/:id',
            name: 'match-edit',
            component: MatchEdit,
            props: true
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