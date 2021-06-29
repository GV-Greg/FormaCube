<template>
    <div class="container mt-n5">
        <div class="row justify-content-center" v-if="currentUser.role === 'admin' || currentUser.role === 'master'">
            <div class="col-lg-12">
                <h1>Liste des Pouvoirs Subsidiants</h1>
                <div class="row align-items-center mt-2">
                    <div class="col-lg-4">
                        <button type="button" class="btn btn-success" @click="create">
                            <i class="fas fa-hand-holding-usd"></i>
                            <span class="ml-1">Nouveau</span>
                        </button>
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
                <v-simple-table fixed-header class="mt-2">
                    <thead class>
                    <tr>
                        <th>N°</th>
                        <th class="text-center">NOM</th>
                        <th>CRÉATION</th>
                        <th>DERNIÈRE MODIFICATION</th>
                        <th>ACTIONS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-show="pouvsubs.length" v-for="pouvsub in pouvsubs" :key="pouvsub.id">
                        <th scope="row">
                            {{ pouvsub.id }}
                        </th>
                        <td>
                            {{ pouvsub.nom }}
                        </td>
                        <td>
                            {{ pouvsub.created_at | newDate }}
                        </td>
                        <td>
                            {{ pouvsub.updated_at | newDate }}
                        </td>
                        <td>
                            <div class="d-flex flex-row">
                                <button type="button" class="mx-1" @click="show(pouvsub)">
                                    <i class="fas fa-eye fa-lg text-blue "></i>
                                </button>
                                |
                                <button type="button" class="mx-1" @click="edit(pouvsub)">
                                    <i class="fas fa-edit fa-lg text-green "></i>
                                </button>
                                |
                                <button type="button" class="ml-1" @click="destroy(pouvsub)">
                                    <i class="fas fa-trash fa-lg text-red"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Si le tableau est en train d'être chargé -->
                    <tr v-show="!pouvsubs.length && loading === false">
                        <td colspan="12" class="pt-4">
                            <v-row class="text-center text-interface mt-10">
                                <v-col class="d-flex flex-column justify-center align-center">
                                    <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                                    <span class="mt-5">Chargement...</span>
                                </v-col>
                            </v-row>
                        </td>
                    </tr>
                    <!-- Si le tableau est chargé mais qu'il n'y a pas de données -->
                    <tr v-show="!pouvsubs.length && loading === true" class="justify-content-center">
                        <td colspan="12" class="pt-4">
                            <div class="alert alert-danger" role="alert">
                                Oups ! Il n'y a aucune correspondance dans la base de données.
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
        </div>
        <div v-else>
            <page-not-found></page-not-found>
        </div>
        <!-- Create & Edit Modal -->
        <div class="modal fade" id="pouvsubModal" tabindex="-1" role="dialog" aria-labelledby="pouvsubModalTitle" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pouvsubModalTitle">{{ editMode ? "Modification d'un" : "Cr&eacute;ation d'un nouveau" }} Pouvoir Subsidiant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="row row-cols-2">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface"><i class="fas fa-hand-holding-usd"></i></span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model="form.nom" type="text"
                                                      :state="checkNom && validationMinNom && validationMaxNom"
                                                      class="form-control rounded-r-lg" :class="{ 'is-invalid': form.errors.has('nom') }"
                                                      name="nom" id="nom" placeholder="Nom du pouvoir subsidiant">
                                        </b-form-input>
                                        <b-form-invalid-feedback id="tag-min-feedback" class="pl-5" v-show="checkNom === false">
                                            Le nom du pouvoir subsidiant est requis.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="tag-min-feedback" class="pl-5" v-show="validationMinNom === false">
                                            Le nom doit &ecirc;tre compos&eacute; d'au moins 3 caract&egrave;res.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="tag-max-feedback" class="pl-5" v-show="validationMaxNom === false">
                                            Le nom doit &ecirc;tre compos&eacute; de moins 190 caract&egrave;res.
                                        </b-form-invalid-feedback>
                                        <has-error :form="form" field="nom" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                                <div class="col">
                                    <b-form-file
                                        v-model="file"
                                        :state="checkLogo"
                                        placeholder="LOGO : Choisir un fichier ou le glisser"
                                        drop-placeholder="Déposer un fichier ici..."
                                        accept="image/jpeg, image/jpg, image/gif, image/png" @change="onFileSelected"
                                        class="rounded-lg">
                                    </b-form-file>
                                    <has-error :form="form" field="logo"></has-error>
                                </div>
                            </div>
                            <div class="row row-cols-2">
                                <div class="col">
                                    <v-checkbox v-model="form.adresse" label="ADRESSE" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="form.age" label="ÂGE" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="form.num_national" label="NUMÉRO NATIONAL" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="form.diplome" label="DIPLOME" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="form.source_info" label="SOURCE INFO" class="myLabel" color="success" hide-details></v-checkbox>
                                </div>
                                <div class="col">
                                    <v-checkbox v-model="form.date_naissance" label="DATE DE NAISSANCE" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="form.email" label="EMAIL" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="form.statut_legal" label="STATUT LÉGAL" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="form.duree_chomage" label="DURÉE DE CHÔMAGE" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="form.groupe_social" label="GROUPE SOCIAL" class="myLabel" color="success" hide-details></v-checkbox>
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
        <!-- Show Modal -->
        <div class="modal fade" id="showPouvsubModal" tabindex="-1" role="dialog" aria-labelledby="showPouvsubModalTitle" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showPouvsubModalTitle">{{ form.nom }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div v-if="form.logo !== ''" class="col-3">
                                <b-img :src="getLogo(form.logo)" fluid alt="logo"></b-img>
                            </div>
                            <div v-else class="col-3"></div>
                            <div class="col-9">
                                <v-chip class="ma-2" :color="choixCouleur(form.adresse)" text-color="white">
                                    <v-avatar left>
                                        <v-icon>mdi-account-circle</v-icon>
                                    </v-avatar>
                                    <div class="pr-1">
                                        ADRESSE
                                    </div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(form.date_naissance)" text-color="white">
                                    <v-avatar left>
                                        <v-icon>mdi-account-circle</v-icon>
                                    </v-avatar>
                                    <div class="pr-1">
                                        DATE DE NAISSANCE
                                    </div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(form.age)" text-color="white">
                                    <v-avatar left>
                                        <v-icon>mdi-account-circle</v-icon>
                                    </v-avatar>
                                    <div class="pr-1">
                                        ÂGE
                                    </div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(form.email)" text-color="white">
                                    <v-avatar left>
                                        <v-icon>mdi-account-circle</v-icon>
                                    </v-avatar>
                                    <div class="pr-1">
                                        EMAIL
                                    </div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(form.num_national)" text-color="white">
                                    <v-avatar left>
                                        <v-icon>mdi-account-circle</v-icon>
                                    </v-avatar>
                                    <div class="pr-1">
                                        NUMÉRO NATIONAL
                                    </div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(form.statut_legal)" text-color="white">
                                    <v-avatar left>
                                        <v-icon>mdi-account-circle</v-icon>
                                    </v-avatar>
                                    <div class="pr-1">
                                        STATUT LÉGAL
                                    </div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(form.diplome)" text-color="white">
                                    <v-avatar left>
                                        <v-icon>mdi-account-circle</v-icon>
                                    </v-avatar>
                                    <div class="pr-1">
                                        DIPLÔME
                                    </div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(form.duree_chomage)" text-color="white">
                                    <v-avatar left>
                                        <v-icon>mdi-account-circle</v-icon>
                                    </v-avatar>
                                    <div class="pr-1">
                                        DUREE DE CHÔMAGE
                                    </div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(form.source_info)" text-color="white">
                                    <v-avatar left>
                                        <v-icon>mdi-account-circle</v-icon>
                                    </v-avatar>
                                    <div class="pr-1">
                                        SOURCE INFO
                                    </div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(form.groupe_social)" text-color="white">
                                    <v-avatar left>
                                        <v-icon>mdi-account-circle</v-icon>
                                    </v-avatar>
                                    <div class="pr-1">
                                        GROUPE SOCIAL
                                    </div>
                                </v-chip>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PaginationComponent from "../elements/PaginationComponent";
    import PageNotFound from "../elements/PageNotFound";
    import { Form } from 'vform';

    export default {
        name: "PouvsubsContainer",
        components: {
            PaginationComponent,
            PageNotFound,
        },
        data() {
            return {
                loading: false,
                editMode: false,
                searchColonnes: ['nom'],
                colonne: 'nom',
                search: '',
                pouvsubs: [],
                form : new Form({
                    id: '',
                    nom: '',
                    logo: '',
                    adresse: false,
                    date_naissance: false,
                    age: false,
                    email: false,
                    num_national: false,
                    statut_legal: false,
                    diplome: false,
                    duree_chomage: false,
                    source_info: false,
                    groupe_social: false,
                }),
                pagination: {
                    current_page: 1,
                },
                file: null,
                error: '',
                pouvsubLogo: '',
                green: 'green',
                default: 'default',
                fileName: '',
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
            console.log('Pouvoirs Subsidiants component mounted');
            this.getData();
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            checkNom() {
                return this.form.nom !== '';
            },
            validationMinNom() {
                if(this.form.nom !== '') {
                    return this.form.nom.length > 2;
                }
            },
            validationMaxNom() {
                return this.form.nom.length < 190;
            },
            checkLogo() {
                if(this.file !== null) {
                    return Boolean(this.file);
                }
            }

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
                axios.get('api/pouvsubs?page=' + this.pagination.current_page)
                    .then(response => {
                        this.pouvsubs = response.data.data
                        this.pagination = response.data.meta
                        this.$Progress.finish();
                        this.loading= true;
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des pouvoirs subsidiants !',
                            timer: undefined,
                        })
                    })
            },
            searchData() {
                this.$Progress.start();
                axios.get('api/search/pouvsubs/' + this.colonne +'/'+ this.search +'?page=' + this.pagination.current_page)
                    .then(response => {
                        this.pouvsubs = response.data.data
                        this.pagination = response.data.meta
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la recherche de pouvoir subsidiant !');
                    })
            },
            show(pouvsub) {
                this.form.reset();
                this.form.fill(pouvsub);
                $('#showPouvsubModal').modal('show');
            },
            getLogo(logo){
                this.pouvsubLogo = "images/logos/" + logo;

                return this.pouvsubLogo;
            },
            choixCouleur(item) {
                if(item === 1) {
                    return this.green
                } else {
                    return this.default
                }
            },
            create() {
                this.editMode = false;
                this.form.reset();
                this.form.clear();
                this.file = null;
                $('#pouvsubModal').modal('show');
            },
            onFileSelected(e) {
                const files = e.target.files;
                if(files[0] !== undefined) {
                    let file = e.target.files[0];
                    this.form.logo = files[0].name;
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = (file) => {
                        this.fileName = reader.result;
                    }
                }
            },
            store() {
                if(this.validation(!this.checkNom, 'Le nom du pouvoir subsidiant est requis')) {
                } else if(this.validation(!this.validationMinNom, 'Le nom doit être composé d\'au moins 3 caractères')) {
                } else if(this.validation(!this.validationMaxNom, 'Le nom doit être composé de moins de 190 caractères')) {
                } else {
                    if(this.form.logo !== '') {
                        this.form.logo = this.fileName;
                    }
                    this.$Progress.start();
                    this.form.busy = true;
                    this.form
                        .post('api/pouvsubs')
                        .then(response => {
                            this.getData()
                            if(this.form.successful) {
                                this.$Progress.finish();
                                Toast.fire('Pouvoir subsidiant créé');
                                $('#pouvsubModal').modal('hide');
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            }
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            if (error.response.status === 422) {
                                Snackbar.fire('Pouvoir subsidiant déjà existant');
                            } else {
                                Snackbar.fire('Problème avec la création du pouvoir subsidiant !');
                            }
                        })
                }
            },
            edit(pouvsub) {
                this.editMode = true;
                this.form.reset();
                this.form.clear();
                this.file = null;
                this.form.fill(pouvsub);
                $('#pouvsubModal').modal('show');
            },
            update() {
                if(this.validation(!this.checkNom, 'Le nom du pouvoir subsidiant est requis')) {
                } else if(this.validation(!this.validationMinNom, 'Le nom doit être composé d\'au moins 3 caractères')) {
                } else if(this.validation(!this.validationMaxNom, 'Le nom doit être composé de moins de 190 caractères')) {
                } else {
                    if(this.form.logo !== '') {
                        this.form.logo = this.fileName;
                    }
                    this.$Progress.start();
                    this.form.busy = true;
                    this.form
                        .put('api/pouvsubs/' + this.form.id)
                        .then(response => {
                            this.getData()
                            if(this.form.successful) {
                                this.$Progress.finish();
                                Toast.fire('Pouvoir subsidiant édité');
                                $('#pouvsubModal').modal('hide');
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            }
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            if (error.response.status === 422) {
                                Snackbar.fire('Pouvoir subsidiant déjà existant');
                            } else {
                                Snackbar.fire('Problème avec la modification du pouvoir subsidiant !');
                            }
                        })
                }
            },
            destroy(pouvsub) {
                this.$Progress.start();
                Suppression.fire().then((result) => {
                    if (result.value) {
                        axios.delete('api/pouvsubs/' + pouvsub.id)
                            .then(response => {
                                this.getData();
                                this.$Progress.finish();
                                Confirm.fire('Pouvoir Subsidiant supprimé!');
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.log(error.response);
                                Snackbar.fire('Problème avec la suppression du pouvoir subsidiant !');
                            })
                    }
                });
            }
        },
    }
</script>

<style scoped>

</style>
