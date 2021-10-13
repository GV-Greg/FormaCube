<template>
    <v-app class="bg-interface">
        <v-navigation-drawer app expand-on-hover class="elevation-4">
            <v-list>
                <v-list-item class="px-2" link  href="https://www.interface3namur.be" target="_blank" rel="noopener noreferrer">
                    <v-img aspect-ratio="1" src="/images/Interface3Namur-favicon.png" alt="Logo" class="v-responsive__content-logo"></v-img>
                    <v-list-item-content>
                        <v-list-item-title>Interface3.Namur</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item class="px-2" link to="/profil">
                    <v-list-item-avatar>
                        <v-img :src="getProfileAvatar()" class="img-circle elevation-2" alt="User Image"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>{{ currentUser.firstname }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-divider></v-divider>
                <v-list-item link to="/home">
                    <v-list-item-action>
                        <i class="fas fa-home fa-lg"></i>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Accueil</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="/pouvsubs" v-show="currentUser.role === 'admin' || currentUser.role === 'master'">
                    <v-list-item-action>
                        <i class="fas fa-hand-holding-usd fa-lg"></i>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Pouvoirs Subsidiants</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="/formations">
                    <v-list-item-action>
                        <i class="fas fa-chalkboard-teacher fa-lg"></i>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Formations</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="/recrutements">
                    <v-list-item-action>
                        <i class="fas fa-calendar-plus fa-lg"></i>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Recrutements</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="/inscrits">
                    <v-list-item-action>
                        <i class="fas fa-user-graduate fa-lg"></i>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Inscrits</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="/salles">
                    <v-list-item-action>
                        <i class="fas fa-map-marker-alt fa-lg"></i>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Salles</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <template v-slot:append>
                <v-list>
                    <v-list-item link to="/tags">
                        <v-list-item-action>
                            <i class="fas fa-tags fa-lg"></i>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Tags</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item link to="/prospects">
                        <v-list-item-action>
                            <i class="fas fa-user-clock fa-lg"></i>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Prospects</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item link to="/archives">
                        <v-list-item-action>
                            <i class="fas fa-box-open fa-lg"></i>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Archives</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item link to="/users" v-show="currentUser.role === 'superAdmin' || currentUser.role === 'master'">
                        <v-list-item-action>
                            <i class="fas fa-users-cog fa-lg"></i>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Utilisateurs</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item link @click="logout">
                        <v-list-item-action>
                            <i class="fas fa-power-off fa-lg text-pink"></i>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Logout</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </template>
        </v-navigation-drawer>

        <v-main>
            <v-container class="fill-height" fluid>
                <router-view class="ml-5"></router-view>
            </v-container>
        </v-main>
        <v-footer app class="d-flex justify-content-center">
            <span class="white--text">Version 4.5.3 &bull; <strong>Copyright &copy; 2019-{{ this.currentYear }} <a href="http://www.creacube.be" target="_blank" rel="noopener noreferrer" class="myLink">CreaCube</a>.</strong> All rights reserved. <strong><a :href="bugsLink" target="_blank" rel="noopener noreferrer" class="myLink ml-1">D&eacute;clarer un bug</a>.</strong></span>
        </v-footer>
    </v-app>

</template>

<script>
    import moment from 'moment';



    export default {
        data() {
            return {
                bugsLink: 'https://docs.google.com/spreadsheets/d/1XS8YiWqcgHokWDhwznSKFyYC5F9oPuH3/edit?usp=sharing&ouid=113711131617140508701&rtpof=true&sd=true'
            }
        },

        methods: {
            logout() {
                this.$store.commit('logout');
                this.$router.push('/login');
            },
            getProfileAvatar(){
                this.currentUserAvatar = "images/avatars/" + this.$store.state.currentUser.avatar;

                return this.currentUserAvatar;
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
    .v-icon {
        color: #fff;
        margin: 0 !important;
    }

    .v-list:hover {
        color: #307e8c;
    }

    .v-list .v-list-item--active {
        color: #389fad;
    }

    .v-list-item__action:first-child {
        margin-right: 20px;
    }

    .v-responsive__content-logo{
        opacity: .8;
        width: 40px;
        height: 40px;
        min-width: 40px;
        max-width: 40px;
        margin-right: 1rem;
    }

    .v-divider {
        margin-top: 0.25rem;
        margin-bottom: 0.25rem;
        border-color: #254F5D !important;
    }

    .myLink {
        color: #f6993f !important;
    }
</style>
