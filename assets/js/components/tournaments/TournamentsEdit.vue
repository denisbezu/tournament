<template>
    <div
        v-if="$root.auth"
    >
        <h2 class="pl-3">Edit tournament</h2>
        <div class="row mt-3">
            <form class="col-12">
                <input type="hidden" id="tournament-id" v-model="id">
                <div class="form-group">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input
                                type="text"
                                class="form-control"
                                v-model="tournament.name"
                                :class="{'is-invalid': $v.tournament.name.$error}"
                                @blur="$v.tournament.name.$touch()"
                                id="name"
                                placeholder="Name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_start" class="col-sm-2 col-form-label">Start date</label>
                    <div class="col-sm-10">
                        <input
                                v-model="tournament.date_start"
                                :class="{'is-invalid': $v.tournament.date_start.$error}"
                                @blur="$v.tournament.date_start.$touch()"
                                type="date"
                                class="form-control"
                                id="date_start"
                                placeholder="Start date">
                    </div>
                </div>
                <div class="form-group ">
                    <label for="date_end" class="col-sm-2 col-form-label">End date</label>
                    <div class="col-sm-10">
                        <input
                                v-model="tournament.date_end"
                                :class="{'is-invalid': $v.tournament.date_end.$error}"
                                @blur="$v.tournament.date_end.$touch()"
                                type="date"
                                class="form-control"
                                id="date_end"
                                placeholder="End date">
                    </div>
                </div>
                <div class="form-group text-right">
                    <div class="col-sm-10">
                        <button
                                :loading="loading"
                                :disabled="valid"
                                @click="saveTournament"
                                type="button"
                                class="btn btn-primary"
                        >Edit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import {minLength, required} from 'vuelidate/lib/validators';

    export default {
        props: ['id'],
        data() {
            return {

            }
        },
        computed: {
            loading() {
                return this.$store.getters.loading;
            },
            valid () {
                return this.$v.$invalid;
            },
            tournament() {
                return this.$store.getters.tournamentById(this.id);
            }
        },
        methods: {
            saveTournament() {
                const tournament = {
                    id: this.id,
                    name: this.tournament.name,
                    date_start: this.tournament.date_start,
                    date_end: this.tournament.date_end,
                };

                if (!this.$v.invalid) {
                    this.$store.dispatch('updateTournament', tournament)
                        .then(() => {
                            this.$router.push('/tournaments/list')
                        })
                        .catch(() => {});
                }
            },
        },
        validations: {
            tournament: {
                name: {
                    required,
                    minLength: minLength(3)
                },
                date_start: {
                    required,
                },
                date_end: {
                    required,
                }
            }
        },
        created() {
            this.$store.dispatch('fetchTournaments');
        }
    }
</script>

<style scoped>

</style>