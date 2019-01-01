<template>
    <div
            v-if="$root.auth"
    >
        <h2 class="pl-3">Add match</h2>
        <div class="row mt-3">
            <form class="col-12">
                <div class="form-group">
                    <label for="tournament" class="col-sm-2 col-form-label">Tournament</label>
                    <div class="col-sm-10">
                        <input
                                type="text"
                                readonly
                                class="form-control"
                                v-model="currentTournament"
                                id="Tournament"
                                placeholder="Tournament">
                    </div>
                </div>
                <div class="form-group">
                    <label for="court-select" class="col-sm-2 col-form-label">Court</label>
                    <div class="col-sm-10">
                        <select
                                name="court-select"
                                id="court-select"
                                :class="{'is-invalid': $v.selectedCourt.$error}"
                                @blur="$v.selectedCourt.$touch()"
                                class="selectpicker mt-2 col-12 px-0"
                                data-live-search="true"
                                data-style="btn-info"
                                v-model="selectedCourt"
                        >
                            <option class="d-none" value="empty" disabled>Select court</option>
                            <option
                                    v-for="c in courts"
                                    :key="c.id"
                                    :value="c.id">{{ c.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="player1-select" class="col-sm-2 col-form-label">Player 1</label>
                    <div class="col-sm-10">
                        <select
                                name="player1-select"
                                id="player1-select"
                                class="selectpicker mt-2 col-12 px-0"
                                data-live-search="true"
                                data-style="btn-info"
                                :class="{'is-invalid': $v.player1.$error}"
                                @blur="$v.player1.$touch()"
                                v-model="player1"
                        >
                            <option class="d-none" value="empty" disabled>Select player 1</option>
                            <option
                                    v-for="p in players"
                                    :key="p.id"
                                    :value="p.id">{{ p.lastname }} {{ p.firstname }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="player2-select" class="col-sm-2 col-form-label">Player 2</label>
                    <div class="col-sm-10">
                        <select
                                name="player2-select"
                                id="player2-select"
                                class="selectpicker mt-2 col-12 px-0"
                                data-live-search="true"
                                data-style="btn-info"
                                :class="{'is-invalid': $v.player2.$error}"
                                @blur="$v.player2.$touch()"
                                v-model="player2"
                        >
                            <option class="d-none" value="empty" disabled>Select player 2</option>
                            <option
                                    v-for="p in players"
                                    :key="p.id"
                                    :value="p.id">{{ p.lastname }} {{ p.firstname }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="score" class="col-sm-2 col-form-label">Score</label>
                    <div class="col-sm-10">
                        <input
                                type="text"
                                class="form-control"
                                v-model="score"
                                id="score"
                                placeholder="Score">
                    </div>
                </div>
                <div class="form-group">
                    <label for="winner-select" class="col-sm-2 col-form-label">Winner</label>
                    <div class="col-sm-10">
                        <select
                                name="winner-select"
                                id="winner-select"
                                class="selectpicker mt-2 col-12 px-0"
                                data-style="btn-info"
                                v-model="winner"
                        >
                            <option value="empty"></option>
                            <option
                                    v-for="p in currentPlayers"
                                    :key="p.id"
                                    :value="p.id">{{ p.lastname }} {{ p.firstname }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group text-right">
                    <div class="col-sm-10">
                        <button
                                :loading="loading"
                                :disabled="valid"
                                @click="saveMatch"
                                type="button"
                                class="btn btn-primary"
                        >Add
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators';

    export default {
        props: ['tournament', 'court'],
        data() {
            return {
                selectedCourt: '',
                player1: '',
                player2: '',
                winner: '',
                score: ''
            }
        },
        computed: {
            loading() {
                return this.$store.getters.loading
            },
            valid() {
                return this.$v.$invalid;
            },
            currentTournament() {
                const tournament = this.$store.getters.tournamentById(this.tournament);
                if (tournament) {
                    return tournament.name;
                }
                return '';
            },
            courts() {
                return this.$store.getters.courts
            },
            players() {
                return this.$store.getters.players
            },
            currentPlayers() {
                const pls = [];
                if (this.player1 !== '') {
                    pls.push(this.$store.getters.playerById(this.player1));
                }
                if (this.player2 !== '') {
                    pls.push(this.$store.getters.playerById(this.player2));
                }
                return pls;
            }
        },
        methods: {
            saveMatch() {
                const match = {
                    tournament: this.tournament,
                    court: this.selectedCourt,
                    player1: this.player1,
                    player2: this.player2,
                    score: this.score,
                    winner: this.winner,
                };

                if (!this.$v.invalid) {
                    this.$store.dispatch('saveMatch', match)
                        .then(() => {
                            this.$router.push('/schedule?t=' + match.tournament)
                        })
                        .catch(() => {
                        });
                }
            },
        },
        validations: {
            selectedCourt: {
                required
            },
            player1: {
                required
            },
            player2: {
                required
            },
        },
        created() {
            this.$store.dispatch('fetchTournaments');
            this.$store.dispatch('fetchPlayers');
            this.$store.dispatch('fetchCourts', {id: this.tournament});
        },
        updated() {
            $('.selectpicker').selectpicker('refresh');
        },
        mounted() {
            this.selectedCourt = this.court === 'all' ? '' : this.court;
            $('.selectpicker').selectpicker('refresh');
        },
    }
</script>

<style scoped>

</style>