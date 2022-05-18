<template>
    <div class="container">
        <h1 class="d-flex align-content-center">Liste des Inscrits</h1>
        <div class="row align-items-center mt-n2">
            <div class="col-lg-4">
                <div class="btn-wrapper">
                    <router-link to="/inscrits/create" class="btn btn-success px-3">
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
        <div v-if="colonne === 'date_naissance'" class="d-flex justify-content-end mb-2 ml-16">
            <div class="font-weight-bold bg-danger text-light text-end px-5 rounded-xl">
                Le format de date doit &ecirc;tre : YYYY-MM-JJ
            </div>
        </div>
        <v-simple-table fixed-header>
            <thead class>
            <tr>
                <th>N°</th>
                <th>NOM</th>
                <th>PRÉNOM</th>
                <th>NÉ·E LE</th>
                <th>ADRESSE</th>
                <th>TÉL/GSM</th>
                <th>ACTIONS</th>
            </tr>
            </thead>
            <tbody>
            <tr v-show="inscrits.length" v-for="inscrit in inscrits" :key="inscrit.id">
                <th scope="row">
                    {{ inscrit.id }}
                </th>
                <td>
                    {{ inscrit.nom | upperCase }}
                </td>
                <td>
                    {{ inscrit.prenom }}
                </td>
                <td>
                    <span v-if="inscrit.date_naissance !== null">{{ inscrit.date_naissance | newDate }}</span>
                </td>
                <td>
                    <span v-if="inscrit.rue !== null">{{ inscrit.rue }}, {{ inscrit.numero }} <span v-if="inscrit.boite !== null">bte</span> {{ inscrit.boite }} - </span>
                    {{ inscrit.cp }} {{ inscrit.ville }}
                </td>
                <td>
                    <span v-if="inscrit.tel !== null">{{ inscrit.tel }}</span><br/>
                    <span v-if="inscrit.gsm !== null">{{ inscrit.gsm }}</span>
                </td>
                <td>
                    <div class="d-flex flex-row">
                        <router-link :to="{ name: 'showInscrit', params: { id: inscrit.id }}">
                            <i class="fas fa-eye fa-lg text-blue mr-1"></i>
                        </router-link>
                        |
                        <router-link :to="{ name: 'editInscrit', params: { id: inscrit.id }}">
                            <i class="fas fa-edit fa-lg text-green mx-1"></i>
                        </router-link>
                        |
                        <button type="button" class="ml-1" @click="destroy(inscrit)">
                            <i class="fas fa-trash fa-lg text-red"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr v-show="!inscrits.length && loading === false">
                <td colspan="12" class="pt-4">
                    <v-row class="text-center text-interface mt-10">
                        <v-col class="d-flex flex-column justify-center align-center">
                            <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                            <span class="mt-5">Chargement...</span>
                        </v-col>
                    </v-row>
                </td>
            </tr>
            <tr v-show="!inscrits.length && loading === true" class="justify-content-center">
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

    export default {
        name: "ListInscrits",
        components: {
            PaginationComponent,
        },
        data() {
            return {
                loading: false,
                searchColonnes: ['nom', 'prenom', 'rue', 'tel', 'date_naissance'],
                colonne: 'nom',
                search: '',
                inscrits: [],
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
            console.log('List Inscrits component mounted');
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
                axios.get('api/inscrits?page=' + this.pagination.current_page)
                    .then(response => {
                        this.inscrits = response.data.data
                        this.pagination = response.data.meta
                        this.$Progress.finish();
                        this.loading= true;
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la récupération de la liste des personnes !');
                    })
            },
            searchData() {
                this.$Progress.start();
                axios.get('api/search/inscrits/' + this.colonne +'/'+ this.search +'?page=' + this.pagination.current_page)
                    .then(response => {
                        this.inscrits = response.data.data
                        this.pagination = response.data.meta
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la récupération de la liste des personnes après la recherche !');
                    })
            },
            destroy(inscrit) {
                this.$Progress.start();
                Suppression.fire().then((result) => {
                    if (result.value) {
                        axios.delete('api/inscrits/' + inscrit.id)
                            .then(response => {
                                this.$Progress.finish();
                                Confirm.fire('Personne Supprimée!');
                                this.getData();
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.log(error.response);
                                Snackbar.fire('Problème avec la suppression de la personne !');
                            })
                    }
                });
            }
        },
    }
</script>

<style scoped>

</style>
