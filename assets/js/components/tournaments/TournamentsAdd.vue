<template>
    <div
        v-if="$root.auth"
    >
        <h2 class="pl-3">Add tournament</h2>
        <div class="row mt-3">
            <form class="col-12">
                <div class="form-group">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input
                                type="text"
                                class="form-control"
                                v-model="name"
                                :class="{'is-invalid': $v.name.$error}"
                                @blur="$v.name.$touch()"
                                id="name"
                                placeholder="Name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="date_start" class="col-sm-2 col-form-label">Start date</label>
                    <div class="col-sm-10">
                        <input
                                v-model="date_start"
                                :class="{'is-invalid': $v.date_start.$error}"
                                @blur="$v.date_start.$touch()"
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
                                v-model="date_end"
                                :class="{'is-invalid': $v.date_end.$error}"
                                @blur="$v.date_end.$touch()"
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
                        >Add
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import {required, minLength} from 'vuelidate/lib/validators';

    export default {
        data() {
            return {
                name: '',
                date_start: '',
                date_end: ''
            }
        },
        computed: {
            loading() {
                return this.$store.getters.loading
            },
            valid() {
                return this.$v.$invalid;
            }
        },
        methods: {
            saveTournament() {
                const tournament = {
                    name: this.name,
                    date_start: this.date_start,
                    date_end: this.date_end,
                };

                if (!this.$v.invalid) {
                    this.$store.dispatch('saveTournament', tournament)
                        .then(() => {
                            this.$router.push('/tournaments/list')
                        })
                        .catch(() => {
                        });
                }
            },
        },
        validations: {
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
    }
</script>

<style scoped>

</style>