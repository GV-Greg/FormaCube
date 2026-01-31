<template>
    <div class="container">
        <h1 class="d-flex align-content-center">Liste des Utilisateurs</h1>
        <div v-if="loading === true">
            <v-row align="center" class="mt-n2">
                <v-col cols="12" lg="2">
                    <div class="btn-wrapper">
                        <router-link to="/users/create">
                            <v-btn color="success" class="px-3">
                                <v-icon start>mdi-account-cog</v-icon>
                                Nouveau
                            </v-btn>
                        </router-link>
                    </div>
                </v-col>
                <v-col cols="12" lg="2" class="text-right text-light">
                    <span>Recherche par :</span>
                </v-col>
                <v-col cols="12" lg="2">
                    <v-select :items="searchColonnes" v-model="colonne" color="blue-grey-darken-4" class="mySelect bg-light text-dark" variant="outlined" density="compact" hide-details="auto"></v-select>
                </v-col>
                <v-col cols="12" lg="4">
                    <v-text-field v-model="search" label="Recherche" color="blue-grey-darken-4" class="mySearch bg-light" variant="outlined" density="compact" hide-details="auto" append-inner-icon="mdi-magnify"></v-text-field>
                </v-col>
                <v-col cols="12" lg="2" class="d-flex align-items-end flex-column">
                    <div class="btn-wrapper">
                        <router-link to="/users/archives">
                            <v-btn color="primary" class="px-3">
                                <v-icon start>mdi-archive</v-icon>
                                Archives
                            </v-btn>
                        </router-link>
                    </div>
                </v-col>
            </v-row>
            <v-table fixed-header>
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
                        {{ upperCase(user.lastname) }}
                    </td>
                    <td>
                        {{ user.email }}
                    </td>
                    <td>
                        {{ capitalize(user.role) }}
                    </td>
                    <td>
                        {{ formatDate(user.created_at) }}
                    </td>
                    <td>
                        <router-link :to="`/users/${user.id}`">
                            <v-icon color="blue" size="large">mdi-eye</v-icon>
                        </router-link>
                        |
                        <router-link :to="`/users/edit/${user.id}`">
                            <v-icon color="green" size="large">mdi-pencil</v-icon>
                        </router-link>
                        |
                        <v-btn variant="text" size="small" class="ml-1" @click="destroy(user)">
                            <v-icon color="red" size="large">mdi-delete</v-icon>
                        </v-btn>
                    </td>
                </tr>
                <!-- Si le tableau est chargé mais qu'il n'y a pas de données -->
                <tr v-show="!users.length && loading" class="justify-content-center">
                    <td colspan="12" class="pt-4">
                        <v-alert type="warning" variant="tonal" icon="mdi-database-search">
                            {{ search ? 'Aucune donnée ne correspond à votre recherche.' : 'Aucune donnée correspondante.' }}
                        </v-alert>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <PaginationComponent class="mt-3" v-if="pagination.last_page > 1"
                                 :pagination="pagination" :offset="5"
                                 @paginate="search === '' ? getData() : searchData()" />
        </div>
        <Spinner v-else />
    </div>
</template>

<script>
    import PaginationComponent from "../../elements/PaginationComponent.vue";
    import Spinner from "../../elements/SpinnerStepper.vue";
    import {Form} from "vform";
    import moment from 'moment';

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
            formatDate(date) {
                return moment(date).format('DD/MM/YYYY');
            },
            upperCase(value) {
                return value ? value.toUpperCase() : '';
            },
            capitalize(value) {
                if (!value) return '';
                return value.charAt(0).toUpperCase() + value.slice(1);
            },
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
