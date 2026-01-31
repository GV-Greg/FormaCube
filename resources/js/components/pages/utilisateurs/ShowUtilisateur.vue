<template>
    <div>
        <h1 class="ml-5">
            <router-link to="/users">
                <v-btn variant="flat" color="grey-lighten-3" class="mt-n2">
                    <v-icon color="primary" size="large">mdi-reply</v-icon>
                </v-btn>
            </router-link>
            Fiche d'utilisateur
        </h1>
        <v-row justify="center" v-if="loading === true">
            <v-col cols="12">
                <v-card class="mx-auto" max-width="500" shaped>
                    <v-card-text>
                        <div class="d-flex align-items-start">
                            <v-avatar class="mr-3">
                                <img :src="'storage/images/avatars/'+user.avatar" class="img-circle elevation-2" alt="User Image">
                            </v-avatar>
                            <div>
                        <span class="text-h4 text-primary font-weight-bold">
                            {{ user.firstname }} {{ upperCase(user.lastname) }}<br>
                        </span>
                                <p><i>Fiche créée depuis le {{ formatDate(user.created_at) }}.</i></p>
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
    import moment from 'moment';

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
        },
        methods: {
            formatDate(date) {
                return moment(date).format('DD/MM/YYYY');
            },
            upperCase(value) {
                return value ? value.toUpperCase() : '';
            },
        }
    }
</script>

<style scoped>

</style>
