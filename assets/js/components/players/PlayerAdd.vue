<template>
    <div>
        <h2 class="pl-3">Add player</h2>
        <div class="row mt-3">
            <form class="col-12">
                <div class="form-group">
                    <label for="firstname" class="col-sm-2 col-form-label">Firstname</label>
                    <div class="col-sm-10">
                        <input
                                type="text"
                                class="form-control"
                                v-model="firstname"
                                :class="{'is-invalid': $v.firstname.$error}"
                                @blur="$v.firstname.$touch()"
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
                                v-model="lastname"
                                :class="{'is-invalid': $v.lastname.$error}"
                                @blur="$v.lastname.$touch()"
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
                                v-model="surname"
                                id="surname"
                                placeholder="Surname">
                    </div>
                </div>
                <div class="form-group">
                    <label for="birthday" class="col-sm-2 col-form-label">Birthday</label>
                    <div class="col-sm-10">
                        <input
                                v-model="birthday"
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
                                v-model="phone"
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
                firstname: '',
                lastname: '',
                surname: '',
                birthday: '',
                phone: '',
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
            savePlayer() {
                const player = {
                    firstname: this.firstname,
                    lastname: this.lastname,
                    surname: this.surname,
                    birthday: this.birthday,
                    phone: this.phone,
                };

                if (!this.$v.invalid) {
                    this.$store.dispatch('savePlayer', player)
                        .then(() => {
                            this.$router.push('/players/list')
                        })
                        .catch(() => {
                        });
                }
            },
        },
        validations: {
            firstname: {
                required,
                minLength: minLength(3)
            },
            lastname: {
                required,
            }
        }
    }
</script>

<style scoped>

</style>