<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="sideNav">
        <button class="navbar-toggler" type="button" @click="goBack">
            <i class="fas fa-long-arrow-alt-left fa-lg"></i>
        </button>
        <router-link class="navbar-brand" :to="'/'">
            <span class="d-block d-lg-none">Bezuhlyi Denys</span>
            <span class="d-none d-lg-block">
            <img class="img-fluid img-profile rounded-circle mx-auto mb-2" src="/img/tennis-ball.jpg" alt="">
        </span>
        </router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <router-link class="nav-link" :to="'/tournaments/list'">Tournaments</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="'/schedule'">Schedule</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="'/players/list'">Players</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="'/info'">Info</router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        methods: {
            goBack() {
                this.$router.go(-1);
            }
        }
    }
</script>

<style scoped>

</style>