<template>
    <div>
        <h1>Liste des Formations</h1>
        <div class="row align-items-center mt-2">
            <div class="col-lg-4" >
                <div class="btn-wrapper" v-show="currentUser.role === 'admin' || currentUser.role === 'master'">
                    <router-link to="/formations/create" class="btn btn-success px-3">
                        <i class="fas fa-plus"></i>
                        <span class="ml-1">Nouveau</span>
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
        </div>
        <v-simple-table fixed-header>
            <thead class>
            <tr>
                <th>N°</th>
                <th class="text-center">NOM</th>
                <th style="width:95px;">SESSION</th>
                <th>TUTEUR·RICE</th>
                <th style="width:80px;">P. SUBS.</th>
                <th>DÉBUT</th>
                <th>FIN</th>
                <th style="width:115px;">SALLE</th>
                <th>ACTIONS</th>
            </tr>
            </thead>
            <tbody>
            <tr v-show="formations.length" v-for="formation in formations" :key="formation.id">
                <th scope="row">
                    {{ formation.id }}
                </th>
                <td>
                    {{ formation.nom }}
                </td>
                <td>
                    {{ formation.session }}
                </td>
                <td>
                    {{ formation.tuteur_prenom }}
                </td>
                <td>
                    {{ formation.pouvsub }}
                </td>
                <td>
                    {{ formation.date_debut | newDate }}
                </td>
                <td>
                    {{ formation.date_fin | newDate }}
                </td>
                <td>
                    <span v-if="formation.salle != null">{{ formation.salle }}</span>
                    <span v-else class="font-italic">Pas de salle</span>
                </td>
                <td>
                    <div class="d-flex flex-row">
                        <router-link :to="{ name: 'showFormation', params: { id: formation.id }}">
                            <i class="fas fa-eye fa-lg text-blue mr-1"></i>
                        </router-link>
                        <span v-show="currentUser.role === 'admin' || currentUser.role === 'master'">|</span>
                        <router-link :to="{ name: 'editFormation', params: { id: formation.id }}"
                            v-show="currentUser.role === 'admin' || currentUser.role === 'master'">
                            <i class="fas fa-edit fa-lg text-green mx-1"></i>
                        </router-link>
                        <span v-show="currentUser.role === 'admin' || currentUser.role === 'master'">|</span>
                        <button type="button" class="ml-1" @click="destroy(formation)"
                            v-show="currentUser.role === 'admin' || currentUser.role === 'master'">
                            <i class="fas fa-trash fa-lg text-red"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr v-show="!formations.length && loading === false">
                <td colspan="12" class="pt-4">
                    <v-row class="text-center text-interface mt-10">
                        <v-col class="d-flex flex-column justify-center align-center">
                            <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                            <span class="mt-5">Chargement...</span>
                        </v-col>
                    </v-row>
                </td>
            </tr>
            <tr v-show="!formations.length && loading === true" class="justify-content-center">
                <td colspan="12" class="pt-4">
                    <div class="alert alert-danger" role="alert">
                        Oups ! Rien ne correspond à votre recherche.
                    </div>
                </td>
            </tr>
            </tbody>
        </v-simple-table>
        <PaginationComponent class="mt-3"
                             v-if="pagination.last_page > 1"
                             :pagination="pagination"
                             :offset="5"
                             @paginate="search === '' ? getData() : searchData()"
        ></PaginationComponent>
    </div>
</template>

<script>
    import PaginationComponent from "../../elements/PaginationComponent";
    import {Form} from "vform";

    export default {
        name: "ListFormations",
        components: {
            PaginationComponent,
        },
        data() {
            return {
                loading: false,
                searchColonnes: ['nom'],
                colonne: 'nom',
                search: '',
                formations: [],
                pagination: {
                    current_page: 1,
                },
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
            console.log('List Formations component mounted');
            this.getData();
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            }
        },
        methods: {
            getData(){
                this.$Progress.start();
                this.loading = false;
                axios.get('api/formations?page=' + this.pagination.current_page)
                    .then(response => {
                        this.formations = response.data.data;
                        this.pagination = response.data.meta;
                        this.$Progress.finish();
                        this.loading= true;
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des formations !',
                            timer: undefined,
                        })
                    })
            },
            searchData() {
                this.$Progress.start();
                axios.get('api/search/formations/' + this.colonne +'/'+ this.search +'?page=' + this.pagination.current_page)
                    .then(response => {
                        this.formations = response.data.data
                        this.pagination = response.data.meta
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la recherche de formations !');
                    })
            },
            destroy(formation) {
                this.$Progress.start();
                Suppression.fire().then((result) => {
                    if (result.value) {
                        axios.delete('api/formations/' + formation.id)
                            .then(response => {
                                this.getData();
                                this.$Progress.finish();
                                Confirm.fire('Formation supprimée!');
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.log(error.response);
                                Snackbar.fire('Problème avec la suppression de la formation !');
                            })
                    }
                });
            }
        },
    }
</script>

<style scoped>
    .v-list-item {
        color: #0a0a17 !important;
    }
</style>
