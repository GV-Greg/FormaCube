<template>
    <div class="container">
        <h1 class="d-flex align-content-center">
            <button class="btn btn-light pb-2 mr-2" @click="goBack">
                <i class="fas fa-reply fa-lg text-primary-dark"></i>
            </button>
            S&eacute;lection des stagiaires
        </h1>
        <div class="row mt-2" v-if="loading === true">
            <v-card class="mx-auto w-100" shaped>
                <v-card-text class="px-3 ml-2 pt-5 pb-n5">
                    <v-row class="mt-n2">
                        <v-col>
                            <i>Recrutement du {{ recrutement.date | newDate }}</i><br/>
                            <i>Pour la formation {{ recrutement.formation }}</i>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
            <v-tabs class="w-100 elevation-2 mt-2 mb-5" dark>
                <v-tabs-slider></v-tabs-slider>
                <v-tab href="#listCandidats">Liste des candidat·e·s</v-tab>
                <v-tab-item value="listCandidats" v-if="date_today >= date_recrutement">
                    <h4 v-if="recrutement.selection === 0" class="text-light-interface text-center mt-3">Sélection des candidats repris pour la formation</h4>
                    <h4 v-if="recrutement.selection === 1 && recrutement.prospection === 0" class="text-light-interface text-center mt-3 mb-3">Liste des candidats non repris</h4>
                    <v-card flat tile>
                        <v-card-text v-if="recrutement.selection === 0">
                            <v-row>
                                <v-col cols="12">
                                    <h5 class="text-center font-weight-bold text-red">Il reste {{ nbrePlaces }} places.</h5>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12">
                                    Choisissez les candidats repris pour la formation :
                                </v-col>
                            </v-row>
                            <v-row class="ml-5" v-if="candidats.length > 0">
                                <v-col cols="3" v-for="(candidat, index) in candidats" :key="index">
                                    <v-checkbox
                                        v-model="selected"
                                        :hide-details="index !== candidats.length - 1"
                                        :value="candidat.id"
                                        :label="candidat.fullname"
                                        color="interface"
                                    ></v-checkbox>
                                </v-col>
                            </v-row>
                            <v-row v-else>
                                <v-col>
                                    Aucun·e candidat·e au recrutement
                                </v-col>
                            </v-row>
                            <v-row v-if="nbrePlaces < 0">
                                <v-col class="d-flex justify-center" >
                                    <div class="font-weight-bold text-red text-center">
                                        <span class="text-uppercase">Attention !</span> Vous avez choisi trop de candidats par rapport au nombre maximum de stagiaires autorisés pour cette formation.<br>
                                        Veuillez en décocher pour pouvoir exécuter la sélection...
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col class="d-flex justify-center">
                                    <v-btn class="btn btn-primary" @click="startTraitementSelection()" v-if="nbrePlaces >= 0">Ex&eacute;cuter</v-btn>
                                    <v-btn class="btn btn-primary" v-else disabled>Ex&eacute;cuter</v-btn>
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-text v-if="recrutement.selection === 1 && recrutement.prospection === 0">
                            <v-row class="ml-5" v-if="candidatsNonSelected.length > 0">
                                <v-col cols="12">
                                    <v-row>
                                        Vous pouvez ajouter des tags de centres d'int&eacute;r&ecirc;ts à chaque personne (cliquez sur le bouton <i class="fas fa-tag px-1 pt-1"></i>) :
                                    </v-row>
                                    <v-row class="d-flex justify-center mt-1">
                                        <v-col cols="6">
                                            <v-simple-table fixed-header style="width:700px;">
                                                <thead class>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>PRÉNOM</th>
                                                        <th>NOM</th>
                                                        <th class="text-center">ACTIONS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-show="candidatsNonSelected.length" v-for="(item, index) in candidatsNonSelected" :key="item.id">
                                                        <th scope="row">
                                                            {{ index + 1 }}
                                                        </th>
                                                        <td>
                                                            {{ item.prenom }}
                                                        </td>
                                                        <td>
                                                            {{ item.nom | upperCase }}
                                                        </td>
                                                        <td class="text-center" v-if="item.traitement === false">
                                                            <v-btn class="btn btn-primary my-2" @click="openModalChoiceTags(item.id)">
                                                                <i class="fas fa-tag fa-lg text-white"></i>
                                                            </v-btn>
                                                        </td>
                                                        <td class="text-center" v-else>
                                                            <i class="fas fa-check fa-lg text-success"></i>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </v-simple-table>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="d-flex justify-center">
                                            <h6 class="text-red">Si vous avez fini, cliquez :</h6>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col class="mt-n5 d-flex justify-center">
                                            <v-btn class="btn btn-primary" @click="startProspection()">Ex&eacute;cuter</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                            <v-row v-else>
                                <v-col>
                                    Aucun·e candidat·e non s&eacute;lectionn&eacute;
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-text v-if="recrutement.selection === 1 && recrutement.prospection === 1">
                            <v-row class="ml-5">
                                <v-col cols="3">
                                    S&eacute;lection & prospection d&eacute;j&agrave; effectu&eacute;es
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item value="listCandidats" v-else>
                    <h4 class="text-red font-weight-bold text-center mt-3">Impossible de sélectionner les candidats si le jour du recrutement n'a pas eu lieu</h4>
                </v-tab-item>
            </v-tabs>
        </div>
        <Spinner v-else />
        <!-- Modal pour le choix de tags par candidat -->
        <v-dialog v-model="dialog_choice_tags" persistent max-width="700px">
            <v-card>
                <v-card-title>
                    <span class="headline">Liste et choix de tag(s) pour {{ this.formTags.prenom }} {{ this.formTags.nom | upperCase }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col>
                                <b-form-group label="Supprimer et/ou sélectionner un/des centre(s) d'intérêt(s)">
                                    <b-form-tags input-id="tags" v-model="formTags.tags" size="lg" add-on-change no-outer-focus>
                                        <template v-slot="{ tags, inputAttrs, inputHandlers, disabled, removeTag }">
                                            <ul v-if="formTags.tags.length > 0" class="list-inline d-inline-block mb-2">
                                                <li v-for="tag in formTags.tags" :key="tag" class="list-inline-item">
                                                    <b-form-tag
                                                        @remove="removeTag(tag)"
                                                        :title="tag"
                                                        :disabled="disabled"
                                                        variant="success"
                                                    >{{ tag }}</b-form-tag>
                                                </li>
                                            </ul>
                                            <b-form-select v-bind="inputAttrs" v-on="inputHandlers">
                                                <template v-slot:first>
                                                    <!-- This is required to prevent bugs with Safari -->
                                                    <option disabled value="">Choisissez un tag...</option>
                                                    <option v-for="tag in listTags" :key="tag.id" :value="tag.tag">{{ tag.tag }}</option>
                                                </template>
                                            </b-form-select>
                                        </template>
                                    </b-form-tags>
                                </b-form-group>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <b-form-group label="Si vous ne trouvez pas le centre d'intérêt dans la liste ci-dessus, créez-le :">
                                    <b-form-tags
                                        input-id="tags"
                                        v-model="newTags"
                                        :tag-validator="validator"
                                        tag-variant="success"
                                        tag-pills
                                        size="md"
                                        separator=",;"
                                        @tag-state="onTagState"
                                        placeholder="Entrez un new tag séparé d'une virgule ou d'un point-virgule"
                                    ></b-form-tags>
                                    <template #description>
                                        <div id="tags-validation-help">
                                            Un nouveau tag doit être compris entre 3 et 50 caractères.<br>Séparer les nouveaux tags par une virgule ou un point ou appuyer sur Add.
                                        </div>
                                    </template>
                                </b-form-group>
                                <v-btn class="btn btn-primary" @click="createTags()">Créer les tags</v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn depressed color="warning" @click="dialog_choice_tags = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="storeTags()">Sauvegarder</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Modal de traitement -->
        <v-dialog v-model="dialog_selection_traitement" persistent width="400">
            <v-card color="bg-light-interface" dark class="pt-4">
                <v-card-text>
                    Traitement...
                    <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                    ></v-progress-linear>
                    <div v-show="traitements.length > 0" class="mt-5 d-flex flex-column">
                        <span v-for="(traitement, index) in traitements" :key="index">
                            {{ traitement }}
                        </span>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import {Form} from "vform";
    import Spinner from "../../elements/Spinner";

    export default {
        name: "SelectionToFormation",
        components: {
            Spinner,
        },
        data() {
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
            return {
                loading: false,
                loadingDatas: false,
                date_today: today,
                recrutement: [],
                date_recrutement: null,
                formation: [],
                candidats: [],
                nbrePlaces: null,
                selected: [],
                dialog_selection_traitement: false,
                traitements: [],
                traitement: 0,
                compteurSelected: 0,
                compteurNonSelected: 0,
                traitementSelected: 0,
                traitementNonSelected: 0,
                listSelected: [],
                listNonSelected: [],
                vitesse: 1000,
                candidatsNonSelected: [],
                dialog_choice_tags: false,
                formTags: new Form({
                    inscrit_id: null,
                    prenom: '',
                    nom: '',
                    traitement: '',
                    tags: [],
                    currentUser: null,
                }),
                listTags: [],
                newTags: [],
                formTag : new Form({
                    tag: null,
                }),
            }
        },
        mounted() {
            console.log('Selection To Formation component mounted');
            this.getRecrutement();
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            }
        },
        watch: {
            selected(newValue, oldValue) {
                if(newValue > oldValue) {
                    this.nbrePlaces--;
                } else {
                    this.nbrePlaces++;
                }
            }
        },
        methods: {
            goBack() {
                return this.$router.push('/recrutements/show/' + this.recrutement.id);
            },
            getRecrutement() {
                this.$Progress.start();
                this.loading = false;
                axios.get(`/api/recrutements/show/${this.$route.params.id}`)
                    .then((response) => {
                        this.recrutement = response.data.recrutement;
                        this.formation = response.data.formation;
                        this.candidats = response.data.candidats;
                        this.nbrePlaces = response.data.formation.max_stagiaires - response.data.formation.inscrits.length;
                        for(let i=0; i < this.candidats.length; i++) {
                            this.candidats[i].fullname = this.candidats[i].prenom + ' ' + this.candidats[i].nom.toUpperCase();
                        }
                        if(this.recrutement.selection === 1 && this.recrutement.prospection === 0) {
                            this.getCandidatsNonSelected();
                        } else {
                            this.date_recrutement = new Date(this.recrutement.date)
                            this.date_recrutement.setHours(0);
                            this.$Progress.finish();
                            this.loading= true;
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la récupération des infos du recrutement !');
                    })
            },
            getCandidatsNonSelected() {
                axios.get(`/api/recrutements/selection/${this.$route.params.id}`)
                    .then((response) => {
                        this.candidatsNonSelected = response.data.candidatsNotSelected;
                        for(let i=0; i < this.candidatsNonSelected.length; i++) {
                            this.candidatsNonSelected[i].traitement = false;
                        }
                        if(this.candidatsNonSelected.length > 0) {
                            this.getTags();
                        } else {
                            this.$Progress.finish();
                            this.loading= true;
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response)
                        Snackbar.fire('Problème avec la récupération des candidats non sélectionnés !');
                    })
            },
            getTags() {
                axios.get(`/api/tags/all`)
                    .then((response) => {
                        this.listTags = response.data.tags;
                        this.$Progress.finish();
                        this.loading= true;
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response)
                        Snackbar.fire('Problème avec la récupération de la liste des tags !');
                    })
            },
            startTraitementSelection() {
                this.traitement = 0;
                this.traitements = [];
                this.dialog_selection_traitement = true;
                for(let y=0; y < this.candidats.length; y++) {
                    if(this.selected.includes(this.candidats[y].id)) {
                        this.listSelected.push(this.candidats[y].id);
                    } else {
                        this.listNonSelected.push(this.candidats[y].id);
                    }
                }
                if(this.listSelected.length > 0) {
                    setTimeout(() => this.traitementCandidat(this.listSelected[0], 0), this.vitesse);
                } else {
                    this.storeSelection();
                }
            },
            traitementCandidat(id, compteur) {
                axios.post(`/api/formations/candidatToStagiaire/` + this.recrutement.id + '/' + this.recrutement.formation_id + '/' + id)
                    .then((response) => {
                        if(response.data.message != null) {
                            this.traitements.push(response.data.message);
                            Toast.fire({
                                title: "Inscription effectuée",
                                timer: 1000,
                            });
                        } else if (response.data.error != null) {
                            this.traitements.push(response.data.error);
                            Toast.fire({
                                icon: 'error',
                                title: 'Déjà inscrit à cette formation',
                                timer: 1000,
                            });
                        }
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire('Problème avec l\'inscription du candidat à la formation !');

                    })
                compteur += 1;
                if(compteur < this.listSelected.length) {
                    setTimeout(() => this.traitementCandidat(this.listSelected[compteur], compteur), this.vitesse);
                } else {
                    if(this.listNonSelected.length === 0) {
                        axios.put(`/api/recrutements/prospection/` + this.recrutement.id)
                            .then((response) => {
                                Toast.fire({
                                    title: response.message,
                                    timer: 1000,
                                });
                            })
                            .catch(error => {
                                console.log(error);
                                Snackbar.fire('Problème avec l\'enregistrement de la phase de prospection sans candidat-prospect !');
                            })
                    }
                    this.storeSelection();
                }
            },

            storeSelection() {
                axios.put(`/api/recrutements/selection/` + this.recrutement.id)
                    .then((response) => {
                        Toast.fire({
                            title: response.data.message,
                            timer: 1500,
                        });
                        if(this.listSelected.length > 0) {
                            for(let i=0; i < this.candidats.length; i++) {
                                for(let x=0; x < this.listSelected.length; x++) {
                                    if(this.listSelected[x] === this.candidats[i].id) {
                                        this.candidats.splice(i, 1);
                                    }
                                }
                            }
                        }
                        this.dialog_selection_traitement = false;
                        this.getRecrutement();
                        this.getCandidatsNonSelected();
                        Fire.$emit('RefreshPage');
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire('Problème avec l\'enregistrement de la phase de sélection des candidats !');

                    })
            },

            startProspection() {
                Suppression.fire({
                    text: "Sinon il faudra aller sur chaque fiche par vous-même!",
                }).then((result) => {
                    if (result.value) {
                        this.traitements = [];
                        this.dialog_selection_traitement = true;
                        setTimeout(() => this.traitementNonCandidat(this.candidatsNonSelected[0].id, 0, this.candidatsNonSelected[0].traitement), this.vitesse);
                    }
                });
            },

            traitementNonCandidat(id, compteur, traitement) {
                axios.put(`/api/inscrits/candidatToProspect/` + id + '/' + traitement + '/' + this.candidatsNonSelected[compteur].prospect)
                    .then((response) => {
                        this.traitements.push(response.data.message);
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire('Problème durant le traitement des prospects !');
                    })
                compteur += 1;
                if(compteur < this.candidatsNonSelected.length) {
                    setTimeout(() => this.traitementNonCandidat(this.candidatsNonSelected[compteur].id, compteur, this.candidatsNonSelected[compteur].traitement), this.vitesse);
                } else {
                    setTimeout(() => this.storeProspection(), this.vitesse);
                }
            },

            storeProspection() {
                axios.put(`/api/recrutements/prospection/` + this.recrutement.id)
                    .then((response) => {
                        Toast.fire({
                            title: response.data.message,
                            timer: 1000,
                        });
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire('Problème avec l\'enregistrement de la phase de prospection avec candidats-prospects !');
                    })
                this.dialog_selection_traitement = false;
                this.getRecrutement();
                Fire.$emit('RefreshPage');
            },

            openModalChoiceTags(inscrit) {
                this.formTags.reset();
                this.formTags.clear();
                for(let i=0; i < this.candidatsNonSelected.length; i++) {
                    if(inscrit === this.candidatsNonSelected[i].id) {
                        this.formTags.inscrit_id = this.candidatsNonSelected[i].id;
                        this.formTags.prenom = this.candidatsNonSelected[i].prenom;
                        this.formTags.nom = this.candidatsNonSelected[i].nom;
                        this.formTags.traitement = this.candidatsNonSelected[i].traitement;
                        if(this.candidatsNonSelected[i].tags.length > 0) {
                            for(let y=0; y < this.candidatsNonSelected[i].tags.length; y++) {
                                this.formTags.tags.push(this.candidatsNonSelected[i].tags[y].tag)
                            }
                        }
                    }
                }
                this.dialog_choice_tags = true;
            },

            onTagState(valid, invalid, duplicate) {
                this.validTags = valid;
                this.invalidTags = invalid;
                this.duplicateTags = duplicate;
            },

            validator(tag) {
                return tag.length > 2 && tag.length < 50
            },

            createTags() {
                if (this.newTags.length < 1) {
                    Snackbar.fire('Pas de nouveau tag à créer');
                } else {
                    for(let x = 0; x < this.newTags.length; x++) {
                        this.test_tag = 0;
                        for(let y = 0; y < this.listTags.length; y++) {
                            if(this.newTags[x] !== this.listTags[y].tag) {
                                this.test_tag++;
                            } else {
                                Snackbar.fire('Tag déjà existant');
                                this.newTags.splice([x],0);
                            }
                        }
                        if(this.test_tag === this.listTags.length) {
                            this.formTag = new Form({
                                tag: this.newTags[x],
                            });
                            this.storeTag();
                        }
                    }
                }
            },

            storeTag() {
                this.$Progress.start();
                this.formTag.tag = this.formTag.tag.toLowerCase();
                this.formTag
                    .post('api/tags/create')
                    .then(response => {
                        if (this.formTag.successful) {
                            Toast.fire('Tag(s) créé(s)');
                            this.getTags();
                            this.newTags = [];
                            this.formTags.tags.push(this.formTag.tag);
                            this.$Progress.finish();
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.error(error.response);
                        Snackbar.fire('Problème avec la création de tag');
                    })
            },

            storeTags() {
                this.$Progress.start();
                this.changeTraitementCandidat(this.formTags.inscrit_id);
                this.formTags.currentUser = this.currentUser.id;
                this.formTags
                    .post('api/inscrits/addTagsAfterRecrutement')
                    .then(response => {
                        this.$Progress.finish();
                        Toast.fire({
                            title: response.data,
                            timer: 1000,
                        });
                        this.dialog_choice_tags = false;
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec l\'enregistrement des tags');
                    })
            },

            changeTraitementCandidat(candidat) {
                let listTagsCandidat = [];
                for(let i=0; i < this.candidatsNonSelected.length; i++) {
                    if(candidat === this.candidatsNonSelected[i].id) {
                        for(let y=0; y < this.candidatsNonSelected[i].tags.length; y++) {
                            listTagsCandidat.push(this.candidatsNonSelected[i].tags[y].tag);
                        }
                        this.candidatsNonSelected[i].traitement = JSON.stringify(listTagsCandidat) !== JSON.stringify(this.formTags.tags);
                        this.formTags.traitement = JSON.stringify(listTagsCandidat) !== JSON.stringify(this.formTags.tags);
                        if(this.formTags.tags.length === 0) {
                            this.candidatsNonSelected[i].prospect = 0
                        } else {
                            this.candidatsNonSelected[i].prospect = 1
                        }
                    }
                }
            },
        },
    }
</script>

<style scoped>
    .v-input--selection-controls {
        margin-top: 0 !important;
    }

    .col-3 {
        padding: 2px !important;
    }
</style>
