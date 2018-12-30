import Vue from 'vue';
import Vuex from 'vuex';
import tournaments from './tournaments';
import players from './players';
import shared from './shared';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        tournaments, shared, players
    }
});