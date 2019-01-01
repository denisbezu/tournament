<template>
    <div class="container">
        <app-header></app-header>
        <app-left-bar></app-left-bar>
        <app-main-content></app-main-content>
    </div>
</template>

<script>
    import Header from './layout/Header';
    import LeftBar from './layout/LeftBar';
    import MainContent from './layout/MainContent';
    export default {
        components: {
            appHeader: Header,
            appLeftBar: LeftBar,
            appMainContent: MainContent
        }
    }
</script>

<style scoped>

</style>