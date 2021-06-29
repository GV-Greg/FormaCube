<template>
    <div>
        <h1 class="ml-5">
            <router-link to="/users">
                <button class="btn btn-light mt-n2">
                    <i class="fas fa-reply fa-lg text-interface"></i>
                </button>
            </router-link>
            Fiche d'utilisateur
        </h1>
        <v-row justify="center" v-if="loading === true">
            <v-col cols="12">
                <v-card class="mx-auto" max-width="500" shaped>
                    <v-card-text>
                        <div class="d-flex align-items-start">
                            <v-avatar class="mr-3">
                                <img :src="'images/avatars/'+user.avatar" class="img-circle elevation-2" alt="User Image">
                            </v-avatar>
                            <div>
                        <span class="display-1 text--primary font-weight-bold">
                            {{ user.firstname }} {{ user.lastname | UpperCase }}<br>
                        </span>
                                <p><i>créé depuis le {{ user.created_at | newDate }}</i></p>
                            </div>
                        </div>
                        <v-row>
                            <v-col lg="2">

                            </v-col>
                            <v-col>
                                <strong>EMAIL :</strong> {{ user.email }}<br>
                                <strong>FONCTION :</strong> {{ user.fonction }}<br>
                                <strong>ROLE :</strong> {{ user.role }}<br>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row v-else class="d-flex flex-column justify-center align-center mt-5">
            <v-progress-circular :size="70" :width="10" color="blue-grey" indeterminate></v-progress-circular>
            <span class="mt-5 text-interface">Chargement...</span>
        </v-row>
    </div>
</template>

<script>
    export default {
        name: "show",
        data() {
            return {
                loading: false,
                user: null,
            }
        },
        mounted() {
            this.loading = false;
        },
        created() {
            this.$Progress.start();
            axios.get(`/api/users/${this.$route.params.id}`)
                .then((response) => {
                    this.user = response.data.user;
                    this.$Progress.finish();
                    this.loading = true;
                })
                .catch((error) => {
                    console.log(error.response);
                    this.$Progress.fail();
                    Snackbar.fire('Problème avec la récupération des infos de l\'utilisateur !');
                });
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            users() {
                return this.$store.getters.users;
            }
        }
    }
</script>

<style scoped>

</style>
