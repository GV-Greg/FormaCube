<template>
    <div class="container">
        <h1 class="d-flex align-content-center">Liste des Tags</h1>
        <div v-if="loading === true">
            <div class="row align-items-center mt-2">
                <div class="col-lg-3"></div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-success" @click="create">
                        <i class="fas fa-tag"></i>
                        <span class="ml-1">Nouveau</span>
                    </button>
                </div>
                <div class="col-lg-4">
                    <v-text-field v-model="search" label="Recherche" color="blue-grey darken-4" class="mySearch bg-light" outlined dense hide-details="auto" append-icon="fas fa-search"></v-text-field>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6 mt-n1 pt-0">
                    <v-simple-table fixed-header>
                        <thead class>
                        <tr>
                            <th>N°</th>
                            <th>NOM</th>
                            <th>ACTIONS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-show="tags.length" v-for="tag in tags" :key="tag.id">
                            <th scope="row">
                                {{ tag.id }}
                            </th>
                            <td>
                                {{ tag.tag | LowerCase }}
                            </td>
                            <td>
                                <div class="d-flex flex-row">
                                    <button type="button" class="mx-1" @click="edit(tag)">
                                        <i class="fas fa-edit fa-lg text-green "></i>
                                    </button>
                                    |
                                    <button type="button" class="ml-1" @click="destroy(tag)">
                                        <i class="fas fa-trash fa-lg text-red"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Si le tableau est chargé mais qu'il n'y a pas de données -->
                        <tr v-show="!tags.length" class="justify-content-center">
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
            <PaginationComponent class="mt-3" v-if="pagination.last_page > 1"
                                 :pagination="pagination" :offset="5"
                                 @paginate="search === '' ? getData() : searchData()" />
        </div>
        <!-- Animation de chargement de page -->
        <Spinner v-else />
        <!-- Create & Edit Modal -->
        <div class="modal fade" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="tagModalTitle" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tagModalTitle">{{ editMode ? "Modification d'un" : "Cr&eacute;ation d'un nouveau" }} Tag</h5>
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
                                        <b-form-input v-model="form.tag" type="text"
                                                      :state="validationMinTag && validationMaxTag"
                                                      class="form-control rounded-r-lg" :class="{ 'is-invalid': form.errors.has('tag') }"
                                                      name="tag" id="tag" placeholder="Nom du tag">
                                        </b-form-input>
                                        <b-form-invalid-feedback id="tag-min-feedback" class="pl-5" v-show="validationMinTag === false">
                                            Le tag doit &ecirc;tre compos&eacute; d'au moins 3 caract&egrave;res.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="tag-max-feedback" class="pl-5" v-show="validationMaxTag === false">
                                            Le tag doit &ecirc;tre compos&eacute; de moins 100 caract&egrave;res.
                                        </b-form-invalid-feedback>
                                        <has-error :form="form" field="tag" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <v-btn class="btn-danger" data-dismiss="modal">Fermer</v-btn>
                            <v-btn :disabled="form.busy" type="submit" class="btn-success">{{ editMode ? "&Eacute;diter" : "Cr&eacute;er" }}</v-btn>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PaginationComponent from "../../elements/PaginationComponent";
    import { Form } from 'vform';
    import Spinner from "../../elements/SpinnerStepper";

    export default {
        name: "TagsComponenent",
        components: {
            PaginationComponent,
            Spinner,
        },
        data() {
            return {
                loading: false,
                editMode: false,
                colonne: 'tag',
                search: '',
                tags: [],
                form : new Form({
                    id: '',
                    tag: '',
                }),
                pagination: {
                    current_page: 1,
                },
                error: '',
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
            console.log('Tags component mounted');
            this.getData();
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            validationMinTag() {
                return this.form.tag.length > 2;
            },
            validationMaxTag() {
                return this.form.tag.length < 100;
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
                axios.get('api/tags?page=' + this.pagination.current_page)
                    .then(response => {
                        this.tags = response.data.data;
                        this.pagination = response.data.meta;
                        this.$Progress.finish();
                        this.loading= true;
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des tags !',
                            timer: undefined,
                        })
                    })
            },
            searchData() {
                this.$Progress.start();
                axios.get('api/search/tags/' + this.colonne +'/'+ this.search +'?page=' + this.pagination.current_page)
                    .then(response => {
                        this.tags = response.data.data
                        this.pagination = response.data.meta
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la recherche de tag !');
                    })
            },
            create() {
                this.editMode = false;
                this.form.reset();
                this.form.clear();
                $('#tagModal').modal('show');
            },
            store() {
                this.$Progress.start();
                if(this.validation(!this.validationMinTag, 'Le tag doit être composé d\'au moins 3 caractères')) {
                } else if(this.validation(!this.validationMaxTag, 'Le tag doit être composé de moins de 100 caractères')) {
                } else {
                    this.form.tag = this.form.tag.toLowerCase();
                    this.form.busy = true;
                    this.form
                        .post('api/tags/create')
                        .then(response => {
                            this.getData()
                            if(this.form.successful) {
                                this.$Progress.finish();
                                Toast.fire('Tag créé');     // affiche la notification de réussite
                                $('#tagModal').modal('hide');   // ferme la modal
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            }
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            if(error.response.status === 422) {
                                // statut 422 = donnée déjà existante, récupération de l'erreur dans la réponse du serveur
                                this.error = error.response.data.errors.tag[0];
                                Snackbar.fire('Tag déjà existant');
                            } else {
                                Snackbar.fire('Problème avec la création du tag !');
                            }
                        })
                }

            },
            edit(tag) {
                this.editMode = true;
                this.form.reset();
                this.form.clear();
                this.form.fill(tag);
                $('#tagModal').modal('show');
            },
            update() {
                if(this.validation(!this.validationMinTag, 'Le tag doit être composé d\'au moins 3 caractères')) {
                } else if(this.validation(!this.validationMaxTag, 'Le tag doit être composé de moins de 100 caractères')) {
                } else {
                    this.$Progress.start();
                    this.form.tag = this.form.tag.toLowerCase();
                    this.form.busy = true;
                    this.form
                        .put('api/tags/edit/' + this.form.id)
                        .then(response => {
                            this.getData()
                            if(this.form.successful) {
                                this.$Progress.finish();
                                Toast.fire('Tag édité');
                                $('#tagModal').modal('hide');
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            }
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            if(error.response.status === 422) {
                                this.error = error.response.data.errors.tag[0];
                                Snackbar.fire('Tag déjà existant');
                            } else {
                                Snackbar.fire('Problème avec la modification du tag !');
                            }
                        })
                }
            },
            destroy(tag) {
                this.$Progress.start();
                Suppression.fire().then((result) => {
                    if (result.value) {
                        axios.delete('api/tags/' + tag.id)
                            .then(response => {
                                this.getData();
                                this.$Progress.finish();
                                Confirm.fire('Tag supprimé!');
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.log(error.response);
                                Snackbar.fire('Problème avec la suppression du tag !');
                            })
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>
