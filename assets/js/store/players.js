import axios from 'axios';

class Player {
    constructor(firstname, lastname, surname = '', birthday = null, phone = '', id = null) {
        this.firstname = firstname;
        this.lastname = lastname;
        this.surname = surname;
        this.birthday = birthday;
        this.phone = phone;
        this.id = id;
    }
}

export default {
    state: {
        players: []
    },
    mutations: {
        savePlayer(state, payload) {
            state.players.push(payload);
        },
        loadPlayers(state, payload) {
            state.players = payload;
        },
        updatePlayer(state, {firstname, lastname, surname, birthday, phone, id}) {
            const player = state.players.find(p => {
                return p.id.toString() === id.toString();
            });

            player.firstname = firstname;
            player.lastname = lastname;
            player.surname = surname;
            player.birthday = birthday;
            player.phone = phone;
        },
        removePlayer(state, {id}) {
            for (var i = 0; i < state.players.length; i++) {
                if (state.players[i].id === id) {
                    state.players.splice(i, 1);
                    break;
                }
            }
        }
    },
    actions: {
        async savePlayer({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                const newPlayer = new Player(
                    payload.firstname,
                    payload.lastname,
                    payload.surname,
                    payload.birthday,
                    payload.phone
                );
                const player = await axios.post('/players/add-new', newPlayer);

                commit('setLoading', false);
                commit('savePlayer', {
                    ...newPlayer,
                    id: player.id,
                })
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
        async updatePlayer({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                const updatePlayer = new Player(
                    payload.firstname,
                    payload.lastname,
                    payload.surname,
                    payload.birthday,
                    payload.phone,
                    payload.id
                );
                const player = await axios.post('/players/update', updatePlayer);

                commit('setLoading', false);
                commit('updatePlayer', {
                    ...updatePlayer
                })
            } catch (error) {
                console.log(error);
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
        async removePlayer({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                await axios.post('/players/delete', {id: payload});

                commit('setLoading', false);
                commit('removePlayer', {
                    id: payload
                })
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
        async fetchPlayers({commit, getters}, payload) {
            commit('clearError');
            commit('setLoading', true);
            try {
                const resultPlayers = [];

                const playersJson = await axios.get('/players/get-list');
                const players = JSON.parse(playersJson.data);
                Object.keys(players).forEach(key => {
                    const player = players[key];
                    const birthday = player.birthday === '' ? player.birthday : player.birthday.split('-');

                    resultPlayers.push(
                        new Player(
                            player.firstname,
                            player.lastname,
                            player.surname,
                            player.birthday === '' ? '' : birthday[2] + '-' + birthday[1] + '-' + birthday[0],
                            player.phone,
                            player.id
                        )
                    )
                });

                commit('loadPlayers', resultPlayers);
                commit('setLoading', false)
            } catch (error) {
                commit('setError', error.message);
                commit('setLoading', false);
                throw error;
            }
        },
    },
    getters: {
        players(state) {
            return state.players;
        },
        playerById(state) {
            return tId => {
                return state.players.find(t => {
                    return t.id.toString() === tId.toString();
                });
            }
        }
    }
}