import axios from 'axios';

class Court {
    constructor(name, tournamentId, id = null) {
        this.name = name;
        this.tournamentId = tournamentId;
        this.id = id;
    }
}

export default {
    state: {
        courts: []
    },
    mutations: {
        saveCourt(state, payload) {
            state.courts.push(payload);
        },
        loadCourts(state, payload) {
            state.courts = payload;
        },
        removeCourt(state, {id}) {
            for (var i = 0; i < state.courts.length; i++) {
                if (state.courts[i].id === id) {
                    state.courts.splice(i, 1);
                    break;
                }
            }
        }
    },
    actions: {
        async saveCourt({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                const newCourt = new Court(
                    payload.court,
                    payload.tournament
                );
                const court = await axios.post('/courts/add-new', newCourt);
                commit('setLoading', false);
                commit('saveCourt', {
                    ...newCourt,
                    id: court.data.id,
                })
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
        async removeCourt({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                await axios.post('/courts/delete', {id: payload});

                commit('setLoading', false);
                commit('removeCourt', {
                    id: payload
                })
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
        async fetchCourts({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                const resultCourts = [];

                const courtsResponse = await axios.post('/courts/get-list', {tournamentId: payload.id});
                const courts = courtsResponse.data;
                Object.keys(courts).forEach(key => {
                    const court = courts[key];

                    resultCourts.push(
                        new Court(
                            court.name,
                            court.tournamentId,
                            court.id
                        )
                    )
                });
                commit('loadCourts', resultCourts);
                commit('setLoading', false)
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
    },
    getters: {
        courts(state) {
            return state.courts;
        },
        courtById(state) {
            return cId => {
                return state.courts.find(c => {
                    return c.id.toString() === cId.toString();
                });
            }
        }
    }
}