<template>
    <div>
        <h1>Liste des Salles</h1>
        <div v-if="loading === true">
            <div class="row align-items-center mt-2">
                <div class="col-lg-2"></div>
                <div class="col-lg-4">
                    <button type="button" class="btn btn-success" @click="create">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="ml-1">Nouveau</span>
                    </button>
                </div>
                <div class="col-lg-4">
                    <v-text-field v-model="search" label="Recherche" color="blue-grey darken-4" class="mySearch bg-light" outlined dense hide-details="auto" append-icon="fas fa-search"></v-text-field>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 mt-n1 pt-0">
                    <v-simple-table fixed-header>
                        <thead class>
                        <tr>
                            <th>N°</th>
                            <th>NOM</th>
                            <th class="text-uppercase">création</th>
                            <th class="text-uppercase">dernière modification</th>
                            <th>ACTIONS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-show="salles.length" v-for="salle in salles" :key="salle.id">
                            <th scope="row">
                                {{ salle.id }}
                            </th>
                            <td>
                                {{ salle.nom | UpperCase }}
                            </td>
                            <td>
                                {{ salle.created_at | newDate }}
                            </td>
                            <td>
                                {{ salle.updated_at | newDate }}
                            </td>
                            <td>
                                <div class="d-flex flex-row">
                                    <button type="button" class="mx-1" @click="edit(salle)">
                                        <i class="fas fa-edit fa-lg text-green "></i>
                                    </button>
                                    |
                                    <button type="button" class="ml-1" @click="destroy(salle)">
                                        <i class="fas fa-trash fa-lg text-red"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Si le tableau est chargé mais qu'il n'y a pas de données -->
                        <tr v-show="!salles.length" class="justify-content-center">
                            <td colspan="12" class="pt-4">
                                <div class="alert alert-danger" role="alert">
                                    Oups ! Il n'y a aucune correspondance dans la base de données.
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </v-simple-table>
                </div>
            </div>
            <PaginationComponent class="mt-3"
                                 v-if="pagination.last_page > 1"
                                 :pagination="pagination"
                                 :offset="5"
                                 @paginate="search === '' ? getData() : searchData()"
            ></PaginationComponent>
            <!-- Create & Edit Modal -->
            <div class="modal fade" id="salleModal" tabindex="-1" role="dialog" aria-labelledby="salleModalTitle" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="salleModalTitle">{{ editMode ? "Modification d'une" : "Cr&eacute;ation d'une nouvelle" }} Salle</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                            <b-input-group-prepend is-text>
                                                <span class="text-light-interface"><i class="fas fa-tag"></i></span>
                                            </b-input-group-prepend>
                                            <b-form-input v-model="form.nom" type="text"
                                                          :state="validationMinSalle && validationMaxSalle"
                                                          class="form-control rounded-r-lg" :class="{ 'is-invalid': form.errors.has('nom') }"
                                                          name="nom" id="nom" placeholder="Nom de la salle">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="salle-min-feedback" class="pl-5" v-show="validationMinSalle === false">
                                                Le nom de la salle doit &ecirc;tre compos&eacute; d'au moins 3 caract&egrave;res
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="salle-max-feedback" class="pl-5" v-show="validationMaxSalle === false">
                                                Le nom de la salle doit &ecirc;tre compos&eacute; de moins 200 caract&egrave;res
                                            </b-form-invalid-feedback>
                                            <has-error :form="form" field="nom" class="pl-5"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                                <button :disabled="form.busy" type="submit" class="btn btn-success">{{ editMode ? "&Eacute;diter" : "Cr&eacute;er" }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="row text-center text-light mt-10">
            <div class="col-lg-12 d-flex flex-column justify-center align-center">
                <v-progress-circular :size="70" :width="10" color="white" indeterminate></v-progress-circular>
                <span class="mt-5">Chargement...</span>
            </div>
        </div>
    </div>
</template>

<script>
    import PaginationComponent from "../../elements/PaginationComponent";
    import { Form } from "vform";

    export default {
        name: "ListSalles",
        components: {
            PaginationComponent,
        },
        data() {
            return {
                loading: false,
                editMode: false,
                colonne: 'nom',
                search: '',
                salles: [],
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
            console.log('List Salles component mounted');
            this.getData();
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            validationMinSalle() {
                return this.form.nom.length > 2;
            },
            validationMaxSalle() {
                return this.form.nom.length < 200;
            },
        },
        methods: {
            validation(test, message){
                if(test) {
                    Snackbar.fire(message);
                    return true;
                }
                return false;
            },
            getData(){
                this.$Progress.start();
                this.loading = false;
                axios.get('api/salles?page=' + this.pagination.current_page)
                    .then(response => {
                        this.salles = response.data.data;
                        this.pagination = response.data.meta;
                        this.$Progress.finish();
                        this.loading= true;
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des salles !',
                            timer: undefined,
                        })
                    })
            },
            searchData() {
                this.$Progress.start();
                axios.get('api/search/salles/' + this.colonne +'/'+ this.search +'?page=' + this.pagination.current_page)
                    .then(response => {
                        this.salles = response.data.data
                        this.pagination = response.data.meta
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error);
                        Snackbar.fire('Problème avec la recherche de salle !');
                    })
            },
            create() {
                this.editMode = false;
                this.form.reset();
                this.form.clear();
                $('#salleModal').modal('show');
            },
            store() {
                this.$Progress.start();
                if(this.validation(!this.validationMinSalle, 'Le nom de la salle doit être composé d\'au moins 3 caractères')) {
                } else if(this.validation(!this.validationMaxSalle, 'Le nom de la salle doit être composé de moins de 200 caractères')) {
                } else {
                    this.form.nom = this.form.nom.toLowerCase();
                    this.form.busy = true;
                    this.form
                        .post('api/salles/create')
                        .then(response => {
                            this.getData()
                            if(this.form.successful) {
                                this.$Progress.finish();
                                Toast.fire('Salle créée'); // affiche la notification de réussite
                                $('#salleModal').modal('hide');
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            }
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            if (error.response.status === 422) {
                                this.error = error.response.data.errors.nom[0];
                                Snackbar.fire('Salle déjà existante');
                            } else {
                                Snackbar.fire('Problème avec la création de la salle !');
                            }
                        })
                }
            },
            edit(salle) {
                this.editMode = true;
                this.form.reset();
                this.form.clear();
                this.form.fill(salle);
                $('#salleModal').modal('show');
            },
            update() {
                this.$Progress.start();
                if(this.validation(!this.validationMinSalle, 'Le nom de la salle doit être composé d\'au moins 3 caractères')) {
                } else if(this.validation(!this.validationMaxSalle, 'Le nom de la salle doit être composé de moins de 200 caractères')) {
                } else {
                    this.form.nom = this.form.nom.toLowerCase();
                    this.form.busy = true;
                    this.form
                        .put('api/salles/edit/' + this.form.id)
                        .then(response => {
                            this.getData()
                            if(this.form.successful) {
                                this.$Progress.finish();
                                Toast.fire('Salle éditée');
                                $('#salleModal').modal('hide');
                                Fire.$emit('RefreshPage');
                            }
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            if (error.response.status === 422) {
                                this.error = error.response.data.errors.nom[0];
                                Snackbar.fire('Salle déjà existante !');
                            } else {
                                Snackbar.fire('Problème avec la modification de salle !');
                            }
                        })
                }
            },
            destroy(salle) {
                this.$Progress.start();
                Suppression.fire().then((result) => {
                    if (result.value) {
                        axios.delete('api/salles/' + salle.id)
                            .then(response => {
                                this.getData();
                                this.$Progress.finish();
                                Confirm.fire('Salle supprimée!');
                                Fire.$emit('RefreshPage');
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.log(error.response);
                                Snackbar.fire('Problème avec la suppression de la salle !');
                            })
                    }
                });
            }
        },
    }
</script>

<style scoped>

</style>
