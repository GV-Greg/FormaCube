<template>
    <v-container fluid class="px-5">
        <v-row justify="end" class="mt-3">
            <v-col cols="12" lg="11">
                <v-row justify="center" v-if="currentUser.role === 'superAdmin' || currentUser.role === 'master'">
                    <v-col cols="12">
                        <router-view></router-view>
                    </v-col>
                </v-row>
                <div v-else>
                    <page-not-found></page-not-found>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import PageNotFound from "../../elements/PageNotFound.vue";

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
