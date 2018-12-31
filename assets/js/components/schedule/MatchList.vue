<template>
    <div>
        <div
                v-if="tournament !== 'empty'"
                class="form-inline justify-content-between">
            <router-link
                    v-if="tournament !== 'empty'"
                    :to="'/match/add/' + tournament + '/' + getCourt" class="btn btn-primary mt-2" role="button"
                    aria-pressed="true"
                    :class="{'disabled': court === ''}"
                    :disabled="court === ''"
            >Add
                match
            </router-link>
            <input type="text" v-model="search" class="form-control mt-2" placeholder="Find player..."/>
        </div>
        <div class="row mt-3 px-3">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col" class="text-center">Position</th>
                    <th scope="col">First player</th>
                    <th scope="col">Second player</th>
                    <th class="d-none d-md-table-cell" scope="col">Score</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr
                        v-for="(match, index) in matches"
                        :key="match.id"
                        :class="{'bg-light-rose': match.winner !== ''}"

                >
                    <th scope="row" class="text-center">
                        <span class="d-flex justify-content-around">
                            <i class="fas fa-chevron-up btn-up mt-1"></i>
                            <span>{{ match.position }}</span>
                            <i class="fas fa-chevron-down btn-down mt-1"></i>
                        </span>
                    </th>
                    <th
                            scope="row"
                            :class="{'text-dark': match.p1.id === match.winner}"
                    >{{ match.p1.lastname }} {{ match.p1.firstname }}</th>
                    <th
                            scope="row"
                            :class="{'text-dark': match.p2.id === match.winner}"
                    >{{ match.p2.lastname }} {{ match.p2.firstname }}</th>
                    <th class="d-none d-md-table-cell"  scope="row">{{ match.score }}</th>
                    <td class="text-right">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <router-link
                                    type="button"
                                    class="btn btn-warning"
                                    :to="{ name: 'match-edit', params: { id: match.id }, query: { tournamentId: match.tournament, courtId: match.court } }"

                            >Edit</router-link>
                            <button
                                    @click="setObjectToDelete(tournament.id)"
                                    type="button"
                                    class="btn btn-danger"
                                    data-toggle="modal"
                                    data-target="#confirmModal"
                            >Delete</button>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import {eventEmitter} from "../../custom";

    export default {
        props: ['court', 'tournament'],
        data() {
            return {
                search: ''
            }
        },
        computed: {
            getCourt() {
                return this.court === '' ? 'all' : this.court;
            },
            matches() {
                const matches = this.$store.getters.matches;
                matches.forEach(m => {
                    m.p1 = this.$store.getters.playerById(m.player1);
                    m.p2 = this.$store.getters.playerById(m.player2);
                });
                return matches.filter(p => {
                    return p.p1.lastname.toLowerCase().includes(this.search.toLowerCase())
                        || p.p2.lastname.toLowerCase().includes(this.search.toLowerCase());
                }).sort((p1, p2) => {
                    return parseInt(p1.position) - parseInt(p2.position);
                });
            },
            players() {
                return this.$store.getters.players;
            }
        },
        created() {
            this.$store.dispatch('fetchPlayers');
            eventEmitter.$on('selectCourt', (data) => {
                this.$store.dispatch('fetchMatches', {court: data.court})
            });
        }
    }
</script>

<style scoped>

</style>