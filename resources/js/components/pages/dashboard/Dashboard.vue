<template>
    <v-layout class="bg-interface">
        <!-- Progress bar -->
        <v-progress-linear
            v-if="$progressState.loading"
            indeterminate
            color="primary"
            class="position-fixed"
            style="top: 0; left: 0; right: 0; z-index: 9999;"
        ></v-progress-linear>

        <v-navigation-drawer expand-on-hover rail class="elevation-4 bg-secondary">
            <v-list nav>
                <v-list-item :to="'/profil'" prepend-avatar="/storage/images/avatars/man.png" :title="currentUser?.firstname || 'User'"></v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-list density="compact" nav>
                <v-list-item to="/home" prepend-icon="mdi-home" title="Accueil" color="primary"></v-list-item>
                <v-list-item to="/formations" prepend-icon="mdi-school" title="Formations" color="primary"></v-list-item>
                <v-list-item to="/recrutements" prepend-icon="mdi-calendar-plus" title="Recrutements" color="primary"></v-list-item>
                <v-list-item to="/inscrits" prepend-icon="mdi-account-school" title="Inscrits" color="primary"></v-list-item>
                <v-list-item to="/salles" prepend-icon="mdi-map-marker" title="Salles" color="primary"></v-list-item>
            </v-list>

            <template v-slot:append>
                <v-list density="compact" nav>
                    <v-list-item to="/tags" prepend-icon="mdi-tag-multiple" title="Tags" color="primary"></v-list-item>
                    <v-list-item to="/prospects" prepend-icon="mdi-account-clock" title="Prospects" color="primary"></v-list-item>
                    <v-list-item to="/users" prepend-icon="mdi-account-cog" title="Utilisateurs" color="primary" v-if="currentUser?.role === 'superAdmin' || currentUser?.role === 'master'"></v-list-item>
                    <v-list-item @click="logout" prepend-icon="mdi-power" title="Logout" base-color="error"></v-list-item>
                </v-list>
            </template>
        </v-navigation-drawer>

        <v-main>
            <v-container fluid class="pa-4">
                <router-view></router-view>
            </v-container>
        </v-main>

        <v-footer app class="d-flex justify-center bg-secondary">
            <span class="text-white">Version 6.0 &bull; <strong>Copyright &copy; 2019-{{ currentYear }} <a href="http://www.creacube.be/" target="_blank" rel="noopener noreferrer" class="text-warning">CreaCube</a>.</strong> All rights reserved.</span>
        </v-footer>
    </v-layout>
</template>

<script>
    import moment from 'moment';

    export default {
        methods: {
            logout() {
                this.$store.commit('logout');
                this.$router.push('/login');
            },
            getProfileAvatar(){
                if (this.$store.state?.currentUser?.avatar) {
                    return "storage/images/avatars/" + this.$store.state.currentUser.avatar;
                }
                return "storage/images/avatars/man.png";
            },
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            currentYear() {
                return moment().format('YYYY');
            }
        }
    }
</script>

<style scoped>
    .v-navigation-drawer {
        background: linear-gradient(180deg, #264653 0%, #1d3640 100%);
    }

    .v-list-item {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .v-list-item:hover {
        color: #2A9D8F !important;
    }

    .v-list-item--active {
        color: #2A9D8F !important;
        background-color: rgba(42, 157, 143, 0.1);
    }

    .v-divider {
        margin: 0.5rem 0;
        border-color: rgba(255, 255, 255, 0.2) !important;
    }

    .bg-interface {
        background-color: #f5f5f5;
        min-height: 100vh;
    }
</style>
