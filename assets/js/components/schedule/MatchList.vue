<template>
    <div v-if="tournament !== 'empty'">
        <div
                v-if="tournament !== 'empty'"
                class="form-inline justify-content-between">
            <router-link
                    v-if="tournament !== 'empty' && $root.auth"
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
                    <th scope="col"
                        :class="{'d-none d-md-table-cell': !$root.auth}"
                        class="text-center">Position</th>
                    <th scope="col">First player</th>
                    <th scope="col">Second player</th>
                    <th
                            :class="{'d-none d-md-table-cell': $root.auth}"
                            scope="col">Score</th>
                    <th
                            v-if="$root.auth"
                    ></th>
                </tr>
                </thead>
                <tbody>
                <tr
                        v-for="(match, index) in matches"
                        :key="match.id"
                        :class="{'bg-light-rose': match.winner !== ''}"

                >
                    <th scope="row" class="text-center" :class="{'d-none d-md-table-cell': !$root.auth}">
                        <span class="d-flex justify-content-around">
                            <i
                                    v-if="$root.auth"
                                    class="fas fa-chevron-up btn-up mt-1"
                                    @click="updatePosition(match.id, 'up')"
                            ></i>
                            <span>{{ match.position }}</span>
                            <i
                                    v-if="$root.auth"
                                    class="fas fa-chevron-down btn-down mt-1"
                                    @click="updatePosition(match.id, 'down')"
                            ></i>
                        </span>
                    </th>
                    <th
                            scope="row"
                            :class="{'text-dark': match.p1.id === match.winner}"
                    >{{ match.p1.lastname }} {{ match.p1.firstname }}
                    </th>
                    <th
                            scope="row"
                            :class="{'text-dark': match.p2.id === match.winner}"
                    >{{ match.p2.lastname }} {{ match.p2.firstname }}
                    </th>
                    <th
                            :class="{'d-none d-md-table-cell': $root.auth}"
                            scope="row">{{ match.score }}</th>
                    <td
                            v-if="$root.auth"
                            class="text-right">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <router-link
                                    type="button"
                                    class="btn btn-warning"
                                    :to="{ name: 'match-edit', params: { id: match.id }, query: { tournamentId: match.tournament, courtId: match.court } }"

                            >Edit
                            </router-link>
                            <button
                                    @click="setObjectToDelete(match.id)"
                                    type="button"
                                    class="btn btn-danger"
                                    data-toggle="modal"
                                    data-target="#confirmModal"
                            >Delete
                            </button>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
        <app-confirm-modal modal-text="Are you sure you want delete this match?"></app-confirm-modal>
    </div>
</template>

<script>
    import {CourtViewType,eventEmitter} from "../../custom";

    export default {
        props: ['court', 'tournament', 'courtViewType'],
        data() {
            return {
                search: '',
                objectId: null
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
        methods: {
            setObjectToDelete(id) {
                this.objectId = id;
            },
            updatePosition(id, type) {
                this.$store.dispatch('updatePosition', {
                    id: id,
                    type: this.courtViewType,
                    position: type
                })
            }
        },
        watch: {
            courtViewType (val) {
                if (val === CourtViewType.byCourt) {
                    this.$store.dispatch('fetchMatches', {court: this.court})
                } else {
                    this.$store.dispatch('fetchMatchesByTournament', {tournament: this.tournament})
                }
            }
        },
        created() {
            this.$store.dispatch('fetchPlayers');
            eventEmitter.$on('selectCourt', (data) => {
                this.$store.dispatch('fetchMatches', {court: data.court})
            });

            eventEmitter.$on('confirmModal', () => {
                this.$store.dispatch('removeMatch', this.objectId)
                    .then(() => {
                        this.$router.push('/schedule')
                    })
                    .catch(() => {});
            });
        }
    }
</script>

<style scoped>

</style>