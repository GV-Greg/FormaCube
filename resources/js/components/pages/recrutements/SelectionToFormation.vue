<template>
    <v-container fluid>
        <!-- Header -->
        <v-row align="center" class="mb-4">
            <v-col cols="auto">
                <v-btn icon variant="text" @click="goBack">
                    <v-icon>mdi-arrow-left</v-icon>
                </v-btn>
            </v-col>
            <v-col>
                <h1 class="text-h4">Sélection des stagiaires</h1>
            </v-col>
        </v-row>

        <!-- Main Content -->
        <div v-if="loading">
            <!-- Info Card -->
            <v-card class="mb-4">
                <v-card-text>
                    <em>Recrutement du {{ formatDate(recrutement.date) }}</em><br>
                    <em>Pour la formation {{ recrutement.formation }}</em>
                </v-card-text>
            </v-card>

            <!-- Tabs -->
            <v-card>
                <v-tabs v-model="tab" bg-color="primary">
                    <v-tab value="listCandidats">Liste des candidat·e·s</v-tab>
                </v-tabs>

                <v-tabs-window v-model="tab">
                    <v-tabs-window-item value="listCandidats">
                        <v-card-text v-if="date_today >= date_recrutement">
                            <!-- Phase Sélection -->
                            <div v-if="recrutement.selection === 0">
                                <h4 class="text-center text-primary mb-4">Sélection des candidats repris pour la formation</h4>

                                <v-alert type="info" variant="tonal" class="mb-4">
                                    Il reste <strong>{{ nbrePlaces }}</strong> places.
                                </v-alert>

                                <p class="mb-4">Choisissez les candidats repris pour la formation :</p>

                                <v-row v-if="candidats.length > 0" class="mb-4">
                                    <v-col cols="12" md="3" v-for="(candidat, index) in candidats" :key="index">
                                        <v-checkbox
                                            v-model="selected"
                                            :value="candidat.id"
                                            :label="candidat.fullname"
                                            color="primary"
                                            hide-details
                                        ></v-checkbox>
                                    </v-col>
                                </v-row>
                                <p v-else class="text-grey">Aucun·e candidat·e au recrutement</p>

                                <v-alert v-if="nbrePlaces < 0" type="error" variant="tonal" class="mb-4">
                                    <strong class="text-uppercase">Attention !</strong> Vous avez choisi trop de candidats par rapport au nombre maximum de stagiaires autorisés pour cette formation.<br>
                                    Veuillez en décocher pour pouvoir exécuter la sélection...
                                </v-alert>

                                <div class="text-center">
                                    <v-btn color="primary" @click="startTraitementSelection" :disabled="nbrePlaces < 0">
                                        Exécuter
                                    </v-btn>
                                </div>
                            </div>

                            <!-- Phase Prospection -->
                            <div v-else-if="recrutement.selection === 1 && recrutement.prospection === 0">
                                <h4 class="text-center text-primary mb-4">Liste des candidats non repris</h4>

                                <div v-if="candidatsNonSelected.length > 0">
                                    <p class="mb-4">
                                        Vous pouvez ajouter des tags de centres d'intérêts à chaque personne (cliquez sur le bouton
                                        <v-icon size="small">mdi-tag</v-icon>) :
                                    </p>

                                    <v-row justify="center">
                                        <v-col cols="12" md="8">
                                            <v-table fixed-header>
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>PRÉNOM</th>
                                                        <th>NOM</th>
                                                        <th class="text-center">ACTIONS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(item, index) in candidatsNonSelected" :key="item.id">
                                                        <td>{{ index + 1 }}</td>
                                                        <td>{{ item.prenom }}</td>
                                                        <td>{{ item.nom?.toUpperCase() }}</td>
                                                        <td class="text-center">
                                                            <v-btn v-if="!item.traitement" color="primary" size="small" @click="openModalChoiceTags(item.id)">
                                                                <v-icon>mdi-tag</v-icon>
                                                            </v-btn>
                                                            <v-icon v-else color="success">mdi-check</v-icon>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </v-table>
                                        </v-col>
                                    </v-row>

                                    <v-alert type="warning" variant="tonal" class="my-4 text-center">
                                        Si vous avez fini, cliquez :
                                    </v-alert>

                                    <div class="text-center">
                                        <v-btn color="primary" @click="startProspection">Exécuter</v-btn>
                                    </div>
                                </div>
                                <p v-else class="text-grey">Aucun·e candidat·e non sélectionné</p>
                            </div>

                            <!-- Phase Terminée -->
                            <div v-else-if="recrutement.selection === 1 && recrutement.prospection === 1">
                                <v-alert type="success" variant="tonal">
                                    Sélection & prospection déjà effectuées
                                </v-alert>
                            </div>
                        </v-card-text>

                        <v-card-text v-else>
                            <v-alert type="error" variant="tonal">
                                Impossible de sélectionner les candidats si le jour du recrutement n'a pas eu lieu
                            </v-alert>
                        </v-card-text>
                    </v-tabs-window-item>
                </v-tabs-window>
            </v-card>
        </div>

        <!-- Loading -->
        <div v-else class="text-center py-16">
            <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
            <div class="mt-4">Chargement...</div>
        </div>

        <!-- Dialog: Choice Tags -->
        <v-dialog v-model="dialog_choice_tags" persistent max-width="700">
            <v-card>
                <v-card-title>Liste et choix de tag(s) pour {{ formTags.prenom }} {{ formTags.nom?.toUpperCase() }}</v-card-title>
                <v-card-text>
                    <v-select
                        v-model="formTags.tags"
                        :items="listTags"
                        item-title="tag"
                        item-value="tag"
                        label="Sélectionner un/des centre(s) d'intérêt(s)"
                        variant="outlined"
                        multiple
                        chips
                        closable-chips
                        class="mb-4"
                    ></v-select>

                    <v-text-field
                        v-model="newTagInput"
                        label="Créer un nouveau tag (min 3 caractères)"
                        variant="outlined"
                        hint="Appuyez sur Entrée pour créer le tag"
                        @keyup.enter="createTag"
                    ></v-text-field>
                    <v-btn color="primary" class="mt-2" @click="createTag" :disabled="!newTagInput || newTagInput.length < 3">
                        Créer le tag
                    </v-btn>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="dialog_choice_tags = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="storeTags">Sauvegarder</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Dialog: Traitement -->
        <v-dialog v-model="dialog_selection_traitement" persistent max-width="400">
            <v-card color="primary">
                <v-card-text class="text-white">
                    <div class="mb-4">Traitement...</div>
                    <v-progress-linear indeterminate color="white"></v-progress-linear>
                    <div v-if="traitements.length" class="mt-4">
                        <div v-for="(traitement, index) in traitements" :key="index">{{ traitement }}</div>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import { Form } from "vform";
import moment from 'moment';

export default {
    name: "SelectionToFormation",
    data() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        return {
            loading: false,
            tab: 'listCandidats',
            date_today: today,
            date_recrutement: null,
            recrutement: {},
            formation: {},
            candidats: [],
            nbrePlaces: null,
            selected: [],
            // Dialogs
            dialog_selection_traitement: false,
            dialog_choice_tags: false,
            // Data
            traitements: [],
            listSelected: [],
            listNonSelected: [],
            vitesse: 1000,
            candidatsNonSelected: [],
            formTags: new Form({
                inscrit_id: null,
                prenom: '',
                nom: '',
                traitement: '',
                tags: [],
                currentUser: null,
            }),
            listTags: [],
            newTagInput: '',
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
            if (newValue.length > oldValue.length) {
                this.nbrePlaces--;
            } else {
                this.nbrePlaces++;
            }
        }
    },
    methods: {
        formatDate(date) {
            if (!date) return '';
            return moment(date).format('DD/MM/YYYY');
        },
        goBack() {
            this.$router.push('/recrutements/show/' + this.recrutement.id);
        },
        getRecrutement() {
            this.$Progress.start();
            this.loading = false;
            axios.get(`/api/recrutements/show/${this.$route.params.id}`)
                .then(response => {
                    this.recrutement = response.data.recrutement;
                    this.formation = response.data.formation;
                    this.candidats = response.data.candidats;
                    this.nbrePlaces = response.data.formation.max_stagiaires - response.data.formation.inscrits.length;
                    for (let i = 0; i < this.candidats.length; i++) {
                        this.candidats[i].fullname = this.candidats[i].prenom + ' ' + this.candidats[i].nom.toUpperCase();
                    }
                    if (this.recrutement.selection === 1 && this.recrutement.prospection === 0) {
                        this.getCandidatsNonSelected();
                    } else {
                        this.date_recrutement = new Date(this.recrutement.date);
                        this.date_recrutement.setHours(0);
                        this.$Progress.finish();
                        this.loading = true;
                    }
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération des infos du recrutement !');
                });
        },
        getCandidatsNonSelected() {
            axios.get(`/api/recrutements/selection/${this.$route.params.id}`)
                .then(response => {
                    this.candidatsNonSelected = response.data.candidatsNotSelected;
                    for (let i = 0; i < this.candidatsNonSelected.length; i++) {
                        this.candidatsNonSelected[i].traitement = false;
                    }
                    if (this.candidatsNonSelected.length > 0) {
                        this.getTags();
                    } else {
                        this.$Progress.finish();
                        this.loading = true;
                    }
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération des candidats non sélectionnés !');
                });
        },
        getTags() {
            axios.get(`/api/tags/all`)
                .then(response => {
                    this.listTags = response.data.tags;
                    this.$Progress.finish();
                    this.loading = true;
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération de la liste des tags !');
                });
        },
        startTraitementSelection() {
            this.traitements = [];
            this.listSelected = [];
            this.listNonSelected = [];
            this.dialog_selection_traitement = true;
            for (let y = 0; y < this.candidats.length; y++) {
                if (this.selected.includes(this.candidats[y].id)) {
                    this.listSelected.push(this.candidats[y].id);
                } else {
                    this.listNonSelected.push(this.candidats[y].id);
                }
            }
            if (this.listSelected.length > 0) {
                setTimeout(() => this.traitementCandidat(this.listSelected[0], 0), this.vitesse);
            } else {
                this.storeSelection();
            }
        },
        traitementCandidat(id, compteur) {
            axios.post(`/api/formations/candidatToStagiaire/${this.recrutement.id}/${this.recrutement.formation_id}/${id}`)
                .then(response => {
                    if (response.data.message) {
                        this.traitements.push(response.data.message);
                        Toast.fire({ title: "Inscription effectuée", timer: 1000 });
                    } else if (response.data.error) {
                        this.traitements.push(response.data.error);
                        Toast.fire({ icon: 'error', title: 'Déjà inscrit à cette formation', timer: 1000 });
                    }
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire("Problème avec l'inscription du candidat à la formation !");
                });
            compteur++;
            if (compteur < this.listSelected.length) {
                setTimeout(() => this.traitementCandidat(this.listSelected[compteur], compteur), this.vitesse);
            } else {
                if (this.listNonSelected.length === 0) {
                    axios.put(`/api/recrutements/prospection/${this.recrutement.id}`)
                        .then(response => { Toast.fire({ title: response.message, timer: 1000 }); })
                        .catch(error => { console.log(error); Snackbar.fire("Problème avec l'enregistrement de la phase de prospection !"); });
                }
                this.storeSelection();
            }
        },
        storeSelection() {
            axios.put(`/api/recrutements/selection/${this.recrutement.id}`)
                .then(response => {
                    Toast.fire({ title: response.data.message, timer: 1500 });
                    if (this.listSelected.length > 0) {
                        for (let i = 0; i < this.candidats.length; i++) {
                            for (let x = 0; x < this.listSelected.length; x++) {
                                if (this.listSelected[x] === this.candidats[i].id) {
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
                    Snackbar.fire("Problème avec l'enregistrement de la phase de sélection des candidats !");
                });
        },
        startProspection() {
            Suppression.fire({ text: "Sinon il faudra aller sur chaque fiche par vous-même!" })
                .then(result => {
                    if (result.value) {
                        this.traitements = [];
                        this.dialog_selection_traitement = true;
                        setTimeout(() => this.traitementNonCandidat(this.candidatsNonSelected[0].id, 0, this.candidatsNonSelected[0].traitement), this.vitesse);
                    }
                });
        },
        traitementNonCandidat(id, compteur, traitement) {
            axios.put(`/api/inscrits/candidatToProspect/${id}/${traitement}/${this.candidatsNonSelected[compteur].prospect}`)
                .then(response => {
                    this.traitements.push(response.data.message);
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème durant le traitement des prospects !');
                });
            compteur++;
            if (compteur < this.candidatsNonSelected.length) {
                setTimeout(() => this.traitementNonCandidat(this.candidatsNonSelected[compteur].id, compteur, this.candidatsNonSelected[compteur].traitement), this.vitesse);
            } else {
                setTimeout(() => this.storeProspection(), this.vitesse);
            }
        },
        storeProspection() {
            axios.put(`/api/recrutements/prospection/${this.recrutement.id}`)
                .then(response => {
                    Toast.fire({ title: response.data.message, timer: 1000 });
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire("Problème avec l'enregistrement de la phase de prospection !");
                });
            this.dialog_selection_traitement = false;
            this.getRecrutement();
            Fire.$emit('RefreshPage');
        },
        openModalChoiceTags(inscrit) {
            this.formTags.reset();
            this.formTags.clear();
            for (let i = 0; i < this.candidatsNonSelected.length; i++) {
                if (inscrit === this.candidatsNonSelected[i].id) {
                    this.formTags.inscrit_id = this.candidatsNonSelected[i].id;
                    this.formTags.prenom = this.candidatsNonSelected[i].prenom;
                    this.formTags.nom = this.candidatsNonSelected[i].nom;
                    this.formTags.traitement = this.candidatsNonSelected[i].traitement;
                    if (this.candidatsNonSelected[i].tags?.length > 0) {
                        for (let y = 0; y < this.candidatsNonSelected[i].tags.length; y++) {
                            this.formTags.tags.push(this.candidatsNonSelected[i].tags[y].tag);
                        }
                    }
                }
            }
            this.dialog_choice_tags = true;
        },
        createTag() {
            if (!this.newTagInput || this.newTagInput.length < 3) {
                Snackbar.fire('Le tag doit avoir au moins 3 caractères');
                return;
            }
            const tag = this.newTagInput.toLowerCase();
            if (this.listTags.find(t => t.tag === tag)) {
                Snackbar.fire('Tag déjà existant');
                this.newTagInput = '';
                return;
            }
            this.$Progress.start();
            axios.post('api/tags/create', { tag })
                .then(response => {
                    Toast.fire('Tag créé');
                    this.getTags();
                    this.formTags.tags.push(tag);
                    this.newTagInput = '';
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.error(error.response);
                    Snackbar.fire('Problème avec la création de tag');
                });
        },
        storeTags() {
            this.$Progress.start();
            this.changeTraitementCandidat(this.formTags.inscrit_id);
            this.formTags.currentUser = this.currentUser.id;
            this.formTags.post('api/inscrits/addTagsAfterRecrutement')
                .then(response => {
                    this.$Progress.finish();
                    Toast.fire({ title: response.data, timer: 1000 });
                    this.dialog_choice_tags = false;
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire("Problème avec l'enregistrement des tags");
                });
        },
        changeTraitementCandidat(candidat) {
            let listTagsCandidat = [];
            for (let i = 0; i < this.candidatsNonSelected.length; i++) {
                if (candidat === this.candidatsNonSelected[i].id) {
                    for (let y = 0; y < this.candidatsNonSelected[i].tags.length; y++) {
                        listTagsCandidat.push(this.candidatsNonSelected[i].tags[y].tag);
                    }
                    this.candidatsNonSelected[i].traitement = JSON.stringify(listTagsCandidat) !== JSON.stringify(this.formTags.tags);
                    this.formTags.traitement = JSON.stringify(listTagsCandidat) !== JSON.stringify(this.formTags.tags);
                    if (this.formTags.tags.length === 0) {
                        this.candidatsNonSelected[i].prospect = 0;
                    } else {
                        this.candidatsNonSelected[i].prospect = 1;
                    }
                }
            }
        },
    },
}
</script>

<style scoped>
.v-table th {
    background-color: rgb(var(--v-theme-primary)) !important;
    color: white !important;
}
</style>
