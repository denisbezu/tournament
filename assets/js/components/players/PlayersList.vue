<template>
    <div>
        <h2>All players</h2>
        <div class="form-inline justify-content-between">
            <router-link :to="'/players/add'" class="btn btn-primary btn-lg" role="button" aria-pressed="true">Add
                player
            </router-link>
            <input type="text" v-model="search" class="form-control mt-2" placeholder="Search player.."/>
        </div>
        <div class="row mt-3 px-3">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th class="d-none d-md-table-cell" scope="col">FirstName</th>
                    <th scope="col">Lastname</th>
                    <th class="d-none d-md-table-cell" scope="col">Surname</th>
                    <th class="d-none d-md-table-cell" scope="col">Birthday</th>
                    <th class="d-none d-md-table-cell" scope="col">Phone</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr
                        v-for="(player, index) in players"
                        :key="player.id"
                >
                    <th scope="row">{{ index + 1 }}</th>
                    <td class="d-none d-md-table-cell">{{ player.firstname }}</td>
                    <td>{{ player.lastname }}</td>
                    <td class="d-none d-md-table-cell">{{ player.surname }}</td>
                    <td class="d-none d-md-table-cell">{{ player.birthday }}</td>
                    <td class="d-none d-md-table-cell">{{ player.phone }}</td>
                    <td class="text-right">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <router-link type="button" class="btn btn-warning" :to="'/players/edit/' + player.id">Edit</router-link>
                            <button
                                    @click="setObjectToDelete(player.id)"
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
        <app-confirm-modal modal-text="Are you sure you want delete this tournament?"></app-confirm-modal>
    </div>
</template>

<script>
    import {eventEmitter} from '../../custom';

    export default {
        data() {
            return {
                search: '',
                objectId: null
            }
        },
        computed: {
            players() {
                const pl = this.$store.getters.players;
                return pl.filter(t => {
                    return t.lastname.toLowerCase().includes(this.search.toLowerCase());
                }).reverse();
            },
            loading() {
                return this.$store.getters.loading
            }
        },
        methods: {
            setObjectToDelete(id) {
                this.objectId = id;
            }
        },
        created() {
            this.$store.dispatch('fetchPlayers');

            eventEmitter.$on('confirmModal', () => {
                this.$store.dispatch('removePlayer', this.objectId)
                    .then(() => {
                        this.$router.push('/players/list')
                    })
                    .catch(() => {});
            });
        }
    }
</script>

<style scoped>

</style>