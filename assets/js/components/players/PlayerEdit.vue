<template>
    <div
        v-if="$root.auth"
    >
        <h2 class="pl-3">Edit player</h2>
        <div class="row mt-3">
            <form class="col-12">
                <input type="hidden" id="player-id" v-model="id">
                <div class="form-group">
                    <label for="firstname" class="col-sm-2 col-form-label">Firstname</label>
                    <div class="col-sm-10">
                        <input
                                type="text"
                                class="form-control"
                                v-model="player.firstname"
                                :class="{'is-invalid': $v.player.firstname.$error}"
                                @blur="$v.player.firstname.$touch()"
                                id="firstname"
                                placeholder="Firstname">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 col-form-label">Lastname</label>
                    <div class="col-sm-10">
                        <input
                                type="text"
                                class="form-control"
                                v-model="player.lastname"
                                :class="{'is-invalid': $v.player.lastname.$error}"
                                @blur="$v.player.lastname.$touch()"
                                id="lastname"
                                placeholder="Lastname">
                    </div>
                </div>
                <div class="form-group">
                    <label for="surname" class="col-sm-2 col-form-label">Surname</label>
                    <div class="col-sm-10">
                        <input
                                type="text"
                                class="form-control"
                                v-model="player.surname"
                                id="surname"
                                placeholder="Surname">
                    </div>
                </div>
                <div class="form-group">
                    <label for="birthday" class="col-sm-2 col-form-label">Birthday</label>
                    <div class="col-sm-10">
                        <input
                                v-model="player.birthday"
                                type="date"
                                class="form-control"
                                id="birthday"
                                placeholder="Birthday">
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                    <div class="col-sm-10">
                        <input
                                type="text"
                                class="form-control"
                                v-model="player.phone"
                                id="phone"
                                placeholder="Phone">
                    </div>
                </div>
                <div class="form-group text-right">
                    <div class="col-sm-10">
                        <button
                                :loading="loading"
                                :disabled="valid"
                                @click="savePlayer"
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
    import {required, minLength} from 'vuelidate/lib/validators';

    export default {
        props: ['id'],
        data() {
            return {

            }
        },
        computed: {
            loading() {
                return this.$store.getters.loading
            },
            valid() {
                return this.$v.$invalid;
            },
            player() {
                return this.$store.getters.playerById(this.id);
            }
        },
        methods: {
            savePlayer() {
                const player = {
                    id: this.id,
                    firstname: this.player.firstname,
                    lastname: this.player.lastname,
                    surname: this.player.surname,
                    birthday: this.player.birthday,
                    phone: this.player.phone,
                };

                if (!this.$v.invalid) {
                    this.$store.dispatch('updatePlayer', player)
                        .then(() => {
                            this.$router.push('/players/list')
                        })
                        .catch(() => {
                        });
                }
            },
        },
        validations: {
            player: {
                firstname: {
                    required,
                    minLength: minLength(3)
                },
                lastname: {
                    required,
                }
            }
        },
        mounted() {
            this.$store.dispatch('fetchPlayers');
        }
    }
</script>

<style scoped>

</style>