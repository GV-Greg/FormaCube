<template>
    <div class="container-fluid m-0">
        <Header v-if="!currentUser"></Header>
        <router-view></router-view>
    </div>
</template>

<script>
    import Header from './elements/Header.vue';

    export default {
        components: {
            Header
        },

        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            }
        },
    }
</script>

<style scoped>
    .container-fluid {
        padding-right: 0;
        padding-left: 0;
        height: 100vh;
    }
</style>
