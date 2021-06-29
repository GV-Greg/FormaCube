<template>
    <div>
        <h1>Liste des Utilisateurs</h1>
        <div class="row align-items-center mt-2">
            <div class="col-lg-4">
                <div class="btn-wrapper">
                    <router-link to="/users/create" class="btn btn-success px-3">
                        <i class="fas fa-users-cog"></i>
                        Nouveau
                    </router-link>
                </div>
            </div>
        </div>
        <div v-if="loading === true">
            <v-simple-table>
                <thead class="thead-dark">
                <tr>
                    <th scope="col" class="th">N°</th>
                    <th scope="col">Pr&eacute;nom</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Email</th>
                    <th scope="col">R&ocirc;le</th>
                    <th scope="col">Cr&eacute;ation</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <template v-if="users.length > 0">
                    <tr v-for="user in users" :key="user.id">
                        <th scope="row">
                            {{ user.id }}
                        </th>
                        <td>
                            {{ user.firstname }}
                        </td>
                        <td>
                            {{ user.lastname | UpperCase }}
                        </td>
                        <td>
                            {{ user.email }}
                        </td>
                        <td>
                            {{ user.role | Capitalize }}
                        </td>
                        <td>
                            {{ user.created_at  | newDate }}
                        </td>
                        <td>
                            <router-link :to="`/users/${user.id}`">
                                <i class="fas fa-eye fa-lg text-blue "></i>
                            </router-link>
                            |
                            <router-link :to="`/users/edit/${user.id}`">
                                <i class="fas fa-edit fa-lg text-green"></i>
                            </router-link>
                            |
                            <button type="button" class="ml-1" @click="destroy(user)">
                                <i class="fas fa-trash fa-lg text-red"></i>
                            </button>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <tr class="text-center">
                        Pas d'utilisateurs enregistr&eacute;s
                    </tr>
                </template>
                <template v-if="loading !== true">
                    <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                    <span class="mt-5 text-interface">Chargement...</span>
                </template>
                </tbody>
            </v-simple-table>
        </div>
        <div v-else class="d-flex flex-column justify-center align-center mt-5">
            <v-progress-circular :size="70" :width="10" color="white" indeterminate></v-progress-circular>
            <span class="mt-5 text-white">Chargement...</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "utilisateurs-list",
        data() {
            return {
                loading: false,
            }
        },
        mounted() {
            this.getUsers();
        },
        computed: {
            users() {
                return this.$store.getters.users;
            },
        },
        methods: {
            getUsers() {
                this.loading = false;
                this.$store.dispatch('getUsers');
                this.loading = true;
            },
            destroy(user) {
                this.$Progress.start();
                Suppression.fire().then((result) => {
                    if (result.value) {
                        axios.delete('api/users/' + user.id)
                            .then(response => {
                                this.$Progress.finish();
                                this.$store.dispatch('getUsers');
                                Confirm.fire('Utilisateur supprimé!');
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.log(error.response);
                                Snackbar.fire('Problème avec la suppression de l\'utilisateur !');
                            })
                    }
                });
            }
        }
    }
</script>

<style scoped>
    .btn-wrapper{
        text-align: left;
        margin-bottom: 10px;
        margin-top: 10px;
    }
</style>
