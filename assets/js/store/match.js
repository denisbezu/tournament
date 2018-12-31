import axios from 'axios';

class Match {
    constructor(tournament, court, player1, player2, score, winner, position = null, id = null) {
        this.tournament = tournament;
        this.court = court;
        this.player1 = player1;
        this.player2 = player2;
        this.score = score;
        this.winner = winner;
        this.position = position;
        this.id = id;
    }
}

export default {
    state: {
        matches: []
    },
    mutations: {
        saveMatch(state, payload) {
            state.matches.push(payload);
        },
        loadMatches(state, payload) {
            state.matches = payload;
        },
        updateMatch(state, {tournament, court, player1, player2, score, winner, position, id}) {
            const match = state.matches.find(t => {
                return t.id.toString() === id.toString();
            });

            match.tournament = tournament;
            match.court = court;
            match.player1 = player1;
            match.player2 = player2;
            match.score = score;
            match.winner = winner;
            match.position = position;
        },
        removeMatch(state, {id}) {
            for (var i = 0; i < state.matches.length; i++) {
                if (state.matches[i].id === id) {
                    state.matches.splice(i, 1);
                    break;
                }
            }
        }
    },
    actions: {
        async saveMatch({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                const newMatch = new Match(
                    payload.tournament,
                    payload.court,
                    payload.player1,
                    payload.player2,
                    payload.score,
                    payload.winner,
                );
                const match = await axios.post('/matches/add-new', newMatch);
                commit('setLoading', false);
                commit('saveMatch', {
                    ...newMatch,
                    id: match.data.id,
                })
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
        async updateMatch({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                console.log(payload);
                const newMatch = new Match(
                    payload.tournament,
                    payload.court,
                    payload.player1,
                    payload.player2,
                    payload.score,
                    payload.winner,
                    payload.position,
                    payload.id
                );
                const match = await axios.post('/matches/update', newMatch);

                commit('setLoading', false);
                commit('updateMatch', {
                    ...newMatch
                })
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
        async removeMatch({commit, getters}, payload) {
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
        async fetchMatches({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                const resultMatches = [];
                const matchesResponse = await axios.post('/matches/get-list', {court: payload.court});
                const matches = matchesResponse.data;
                Object.keys(matches).forEach(key => {
                    const match = matches[key];

                    resultMatches.push(
                        new Match(
                            match.tournament,
                            match.court,
                            match.player1,
                            match.player2,
                            match.score,
                            match.winner,
                            match.position,
                            match.id
                        )
                    )
                });

                commit('loadMatches', resultMatches);
                commit('setLoading', false)
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
    },
    getters: {
        matches(state) {
            return state.matches;
        },
        matchById(state) {
            return mId => {
                return state.matches.find(m => {
                    return m.id.toString() === mId.toString();
                });
            }
        }
    }
}