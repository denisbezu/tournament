<template>
    <div class="px-3">
        <h2>All tournaments</h2>
        <div class="form-inline justify-content-between">
            <router-link
                    v-if="$root.auth"
                    :to="'/tournaments/add'" class="btn btn-primary btn-lg" role="button" aria-pressed="true">Add
                tournament
            </router-link>
            <input type="text" v-model="search" class="form-control mt-2" placeholder="Search tournament.."/>
        </div>
        <div class="row mt-3 px-3">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th class="d-none d-md-table-cell" scope="col">Start date</th>
                    <th class="d-none d-md-table-cell" scope="col">End date</th>
                    <th v-if="$root.auth"></th>
                </tr>
                </thead>
                <tbody>
                <tr
                        v-for="(tournament, index) in tournaments"
                        :key="tournament.id"
                >
                    <th scope="row">{{ index + 1 }}</th>
                    <td>{{ tournament.name }}</td>
                    <td class="d-none d-md-table-cell">{{ tournament.date_start }}</td>
                    <td class="d-none d-md-table-cell">{{ tournament.date_end }}</td>
                    <td
                            v-if="$root.auth"
                            class="text-right">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <router-link type="button" class="btn btn-warning"
                                         :to="'/tournaments/edit/' + tournament.id">Edit
                            </router-link>
                            <button
                                    @click="setObjectToDelete(tournament.id)"
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
            tournaments() {
                const ts = this.$store.getters.tournaments;
                return ts.filter(t => {
                    return t.name.toLowerCase().includes(this.search.toLowerCase());
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
            this.$store.dispatch('fetchTournaments');

            eventEmitter.$on('confirmModal', () => {
                this.$store.dispatch('removeTournament', this.objectId)
                    .then(() => {
                        this.$router.push('/tournaments/list')
                    })
                    .catch(() => {
                    });
            });
        }
    }
</script>

<style scoped>

</style>