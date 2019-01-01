import axios from 'axios';

export default {
    state: {
        auth: false,
    },
    mutations: {
        setAuth(state, payload) {
            state.auth = payload
        }
    },
    actions: {
        async setAuth({commit}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                const authResponse = await axios.post('/login/check/access');
                console.log(authResponse.data.result);
                commit('setAuth', authResponse.data.result);
                commit('setLoading', false)
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        }
    },
    getters: {
        auth(state) {
            return state.auth
        }
    }
}
