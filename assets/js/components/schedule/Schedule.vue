<template>
    <div>
        <h2 class="pl-3">Schedule</h2>
        <div class="form-inline justify-content-between px-3">
            <div class="form-inline">
                <input type="text" v-model="courtNew" class="form-control mr-sm-2 mt-2" placeholder="Court name">
                <button
                        class="btn btn-success mt-2"
                        @click="addCourt"
                        :disabled="(tournament === 'empty' || courtNew === '')"
                >Add court
                </button>
            </div>
            <select
                    name="court-view-type"
                    id="court-view-type"
                    class="selectpicker mt-2"
                    v-model="courtViewType"
                    data-style="btn-info"
                    @change="selectViewType(courtViewType)"
            >
                <option value="byCourt">By court</option>
                <option value="all">All</option>
            </select>
            <select
                    name="tournament-select"
                    id="tournament-select"
                    class="selectpicker mt-2"
                    data-live-search="true"
                    data-style="btn-info"
                    v-model="tournament"
                    @change="selectTournament(tournament)"
            >
                <option class="d-none" value="empty" disabled>Select tournament</option>
                <option
                        v-for="tournament in tournaments"
                        :key="tournament.id"
                        :value="tournament.id">{{ tournament.name }}
                </option>
            </select>
        </div>

        <div class="row m-3">
            <ul class="nav nav-tabs">
                <li
                        class="nav-item"
                        v-for="court in courts"
                        :key="court.id"
                >
                    <a
                            :class="{'active': court.id ===  selectedCourt}"
                            @click="selectCourt(court.id, true)"
                            class="nav-link"
                            href="#"
                    >{{ court.name }}</a>
                </li>
            </ul>
        </div>
        <div class="m-3">
            <app-match-list
                    :tournament="tournament"
                    :court="selectedCourt"
            ></app-match-list>
        </div>
    </div>
</template>

<script>
    import MatchList from './MatchList';
    import {CourtViewType, eventEmitter} from '../../custom'

    export default {
        data() {
            return {
                tournament: 'empty',
                courtNew: '',
                selectedCourt: '',
                courtViewType: CourtViewType.byCourt,
            }
        },
        computed: {
            tournaments() {
                return this.$store.getters.tournaments.reverse();
            },
            courts() {
                if (this.$store.getters.courts.length > 0) {
                    this.selectCourt(this.$store.getters.courts[0].id, true);
                } else {
                    this.selectedCourt = '';
                }

                return this.$store.getters.courts
            }
        },
        methods: {
            addCourt() {
                this.$store.dispatch('saveCourt', {
                    court: this.courtNew,
                    tournament: this.tournament
                });
            },
            selectTournament(t) {
                this.$store.dispatch('fetchCourts', {id: t});
            },
            selectCourt(courtId, afterTournament = false) {
                this.selectedCourt = courtId;
            },
            selectViewType(viewType) {

            }
        },
        watch: {
            selectedCourt(val) {
                eventEmitter.$emit('selectCourt', {
                    court: val
                });
            }
        },
        created() {
            this.$store.dispatch('fetchTournaments');
        },
        updated() {
            $('.selectpicker').selectpicker('refresh');
        },
        mounted() {
            if (this.$route.query.t) {
                this.tournament = this.$route.query.t;
            }
            $('.selectpicker').selectpicker('refresh');
        },
        components: {
            appMatchList: MatchList,
        }
    }
</script>

<style scoped>

</style>