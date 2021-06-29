<template>
    <div class="container mt-n5">
        <div class="row justify-content-center" v-if="currentUser.role === 'superAdmin' || currentUser.role === 'master'">
            <div class="col-lg-12">
                <router-view></router-view>
            </div>
        </div>
        <div v-else>
            <page-not-found></page-not-found>
        </div>
    </div>
</template>

<script>
    import PageNotFound from "../../elements/PageNotFound";

    export default {
        name: "utilisateurs-main",
        components: {
            PageNotFound,
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            }
        },
    }
</script>

<style scoped>

</style>
