<template>
    <div class="mx-5 d-flex flex-column justify-content-center">
        <div class="mx-5">
            <div class="row justify-content-end mt-3">
                <div class="col col-lg-11">
                    <div class="row justify-content-center" v-if="currentUser.role === 'superAdmin' || currentUser.role === 'master'">
                        <router-view></router-view>
                    </div>
                    <div v-else>
                        <page-not-found></page-not-found>
                    </div>
                </div>
            </div>
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
