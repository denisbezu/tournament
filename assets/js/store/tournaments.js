import axios from 'axios';

class Tournament {
    constructor(name, date_start, date_end, id = null) {
        this.name = name;
        this.date_start = date_start;
        this.date_end = date_end;
        this.id = id;
    }
}

export default {
    state: {
        tournaments: []
    },
    mutations: {
        saveTournament(state, payload) {
            state.tournaments.push(payload);
        },
        loadTournaments(state, payload) {
            state.tournaments = payload;
        },
        updateTournament(state, {name, date_start, date_end, id}) {
            const tournament = state.tournaments.find(t => {
                return t.id.toString() === id.toString();
            });

            tournament.name = name;
            tournament.date_start = date_start;
            tournament.date_end = date_end;
        },
        removeTournament(state, {id}) {
            for (var i = 0; i < state.tournaments.length; i++) {
                if (state.tournaments[i].id === id) {
                    state.tournaments.splice(i, 1);
                    break;
                }
            }

        }
    },
    actions: {
        async saveTournament({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                const newTournament = new Tournament(
                    payload.name,
                    payload.date_start,
                    payload.date_end
                );
                const tournament = await axios.post('/tournaments/add-new', newTournament);

                commit('setLoading', false);
                commit('saveTournament', {
                    ...newTournament,
                    id: tournament.id,
                })
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
        async updateTournament({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                const updateTournament = new Tournament(
                    payload.name,
                    payload.date_start,
                    payload.date_end,
                    payload.id
                );
                const tournament = await axios.post('/tournaments/update', updateTournament);

                commit('setLoading', false);
                commit('updateTournament', {
                    ...updateTournament
                })
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
        async removeTournament({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                await axios.post('/tournaments/delete', {id: payload});

                commit('setLoading', false);
                commit('removeTournament', {
                    id: payload
                })
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
        async fetchTournaments({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                const resultTournaments = [];

                const tournamentsJson = await axios.get('/tournaments/get-list');
                const tournaments = JSON.parse(tournamentsJson.data);
                Object.keys(tournaments).forEach(key => {
                    const tournament = tournaments[key];
                    const date_start = tournament.date_start.split('-');
                    const date_end = tournament.date_end.split('-');
                    resultTournaments.push(
                        new Tournament(
                            tournament.name,
                            date_start[2] + '-' + date_start[1] + '-' + date_start[0],
                            date_end[2] + '-' + date_end[1] + '-' + date_end[0],
                            tournament.id
                        )
                    )
                });

                commit('loadTournaments', resultTournaments);
                commit('setLoading', false)
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
    },
    getters: {
        tournaments(state) {
            return state.tournaments;
        },
        tournamentById(state) {
            return tId => {
                return state.tournaments.find(t => {

                    return t.id.toString() === tId.toString();
                });
            }
        }
    }
}