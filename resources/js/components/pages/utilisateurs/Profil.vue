<template>
    <v-container class="justify-item-center">
        <v-row justify="center">
            <v-col cols="12" class="mt-3">
                <v-card class="mx-auto" max-width="500" shaped>
                    <v-card-text>
                        <div class="d-flex align-items-start">
                            <v-avatar class="mr-3">
                                <img :src="getAvatar()" class="img-circle elevation-2" alt="User Image">
                            </v-avatar>
                            <div>
                                <span class="display-1 text--primary font-weight-bold">
                                    {{ currentUser.firstname }} {{ currentUser.lastname | UpperCase }}<br>
                                </span>
                                <p><i>Fiche créée depuis le {{ currentUser.created_at | newDate }}.</i></p>
                            </div>
                        </div>
                        <v-row>
                            <v-col lg="2">

                            </v-col>
                            <v-col>
                                <strong>EMAIL :</strong> {{ currentUser.email }}<br>
                                <strong>FONCTION :</strong> {{ currentUser.fonction }}<br>
                                <strong>ROLE :</strong> {{ currentUser.role }}<br>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row justify="center" class="ml-5">
            <v-col cols="2" v-if="currentUser.id !== 2">
                <router-link :to="{ name: 'editProfil', params: { id: currentUser.id }}">
                    <button class="btn btn-success">Modifier</button>
                </router-link>
            </v-col>
            <v-col class="d-flex justify-content-center">
                <span class="bg-danger text-light px-4 py-2 rounded-xl">Ce compte ne peut &ecirc;tre modifi&eacute;</span>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        data() {
            return {
                userAvatar: '',
            }
        },
        mounted() {
            console.log('Profil Component mounted.');
        },
        computed: {
            currentUser: {
                get() {
                    return this.$store.getters.currentUser;
                }
            },
        },
        created() {
            this.getAvatar();
        },
        methods: {
            getAvatar() {
                this.userAvatar = "storage/images/avatars/" + this.$store.getters.currentUser.avatar;

                return this.userAvatar;
            },
        }
    }
</script>
