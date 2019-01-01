import Vue from 'vue';
import Vuex from 'vuex';
import tournaments from './tournaments';
import players from './players';
import court from './court';
import match from './match';
import shared from './shared';
import auth from './auth';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        tournaments, shared, players, court, match, auth
    }
});