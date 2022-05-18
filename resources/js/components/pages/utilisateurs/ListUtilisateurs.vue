<template>
    <div class="container">
        <h1 class="d-flex align-content-center">Liste des Utilisateurs</h1>
        <div v-if="loading === true">
            <div class="row align-items-center mt-n2">
                <div class="col-lg-2" >
                    <div class="btn-wrapper">
                        <router-link to="/users/create" class="btn btn-success px-3">
                            <i class="fas fa-users-cog"></i>
                            Nouveau
                        </router-link>
                    </div>
                </div>
                <div class="col-lg-2 text-right text-light">
                    <span>Recherche par :</span>
                </div>
                <div class="col-lg-2">
                    <v-select :items="searchColonnes" v-model="colonne" color="blue-grey darken-4" class="mySelect bg-light text-dark" outlined dense hide-details="auto"></v-select>
                </div>
                <div class="col-lg-4">
                    <v-text-field v-model="search" label="Recherche" color="blue-grey darken-4" class="mySearch bg-light" outlined dense hide-details="auto" append-icon="fas fa-search"></v-text-field>
                </div>
                <div class="col-lg-2 d-flex align-items-end flex-column">
                    <div class="btn-wrapper">
                        <router-link to="/users/archives" class="btn btn-primary px-3">
                            <i class="fas fa-archive"></i>
                            Archives
                        </router-link>
                    </div>
                </div>
            </div>
            <v-simple-table fixed-header>
                <thead>
                <tr>
                    <th scope="col" class="th">N°</th>
                    <th scope="col">Pr&eacute;nom</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Email</th>
                    <th scope="col">R&ocirc;le</th>
                    <th scope="col">Cr&eacute;ation</th>
                    <th scope="col">ACTIONS</th>
                </tr>
                </thead>
                <tbody>
                <tr v-show="users.length" v-for="user in users" :key="user.id">
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
                <!-- Si le tableau est chargé mais qu'il n'y a pas de données -->
                <tr v-show="!users.length" class="justify-content-center">
                    <td colspan="12" class="pt-4">
                        <div class="alert alert-danger" role="alert">
                            Oups ! Il n'y a aucune correspondance dans la base de données.
                        </div>
                    </td>
                </tr>
                </tbody>
            </v-simple-table>
            <PaginationComponent class="mt-3" v-if="pagination.last_page > 1"
                                 :pagination="pagination" :offset="5"
                                 @paginate="search === '' ? getData() : searchData()" />
        </div>
        <Spinner v-else />
    </div>
</template>

<script>
    import PaginationComponent from "../../elements/PaginationComponent";
    import Spinner from "../../elements/SpinnerStepper";
    import {Form} from "vform";

    export default {
        name: "ListUtilisateurs",
        components: {
            PaginationComponent,
            Spinner,
        },
        data() {
            return {
                loading: false,
                searchColonnes: ['prenom', 'nom', 'email'],
                colonne: 'nom',
                search: '',
                users: [],
                pagination: {
                    current_page: 1,
                },
                error: '',
                form : new Form({
                    id: '',
                    nom: '',
                }),
            }
        },
        watch: {
            search: function(newSearch) {
                if (newSearch === '') {
                    this.getData();
                } else {
                    this.searchData();
                }
            }
        },
        mounted() {
            console.log('List Utilisateurs component mounted');
            this.getData();
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
        },
        methods: {
            getData(){
                this.$Progress.start();
                this.loading = false;
                axios.get('api/users?page=' + this.pagination.current_page)
                    .then(response => {
                        this.users = response.data.users.data;
                        this.pagination = response.data.users;
                        this.$Progress.finish();
                        this.loading= true;
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.error(error);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des salles !',
                            timer: undefined,
                        })
                    })
            },
            searchData() {
                this.$Progress.start();
                axios.get('api/search/users/' + this.colonne +'/'+ this.search +'?page=' + this.pagination.current_page)
                    .then(response => {
                        this.users = response.data.users.data;
                        this.pagination = response.data.users;
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.error(error);
                        Snackbar.fire('Problème avec la recherche de l\'utilisateur !');
                    })
            },
            destroy(user) {
                this.$Progress.start();
                Suppression.fire({
                    text: "Si vous continuez, l'utilisateur sera archivé!",
                    confirmButtonText: 'Oui, archivez!'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('api/users/' + user.id)
                            .then(response => {
                                this.$Progress.finish();
                                this.getData();
                                Confirm.fire(response.data.message);
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.error(error.response);
                                Snackbar.fire('Problème avec l\'archivage de l\'utilisateur !');
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
