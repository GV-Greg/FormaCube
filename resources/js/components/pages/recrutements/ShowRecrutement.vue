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
                <h1 class="text-h4">Fiche de Recrutement</h1>
            </v-col>
        </v-row>

        <!-- Main Content -->
        <div v-if="loading">
            <!-- Recrutement Info Card -->
            <v-card class="mb-4">
                <v-card-text>
                    <v-row align="center">
                        <v-col cols="11">
                            <h3 class="font-weight-bold">
                                Pour la
                                <router-link :to="{ name: 'showFormation', params: { id: recrutement.formation_id }}" class="text-primary">
                                    formation {{ recrutement.formation?.toUpperCase() }}
                                </router-link>
                            </h3>
                            <span class="text-subtitle-1">{{ recrutement.session?.toUpperCase() }}</span>
                        </v-col>
                        <v-col cols="1" class="text-right">
                            <v-speed-dial location="bottom end" transition="fade-transition">
                                <template v-slot:activator="{ props: activatorProps }">
                                    <v-btn v-bind="activatorProps" icon color="primary" variant="elevated">
                                        <v-icon>mdi-cog</v-icon>
                                    </v-btn>
                                </template>
                                <v-btn icon color="success" size="small" @click="editRecrutement">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon color="error" size="small" @click="verifRecrutement(recrutement.id)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </v-speed-dial>
                        </v-col>
                    </v-row>

                    <v-row class="mt-2">
                        <v-col cols="12">
                            <span class="text-caption text-grey">Création : le {{ formatDate(recrutement.created_at) }}</span>
                        </v-col>
                    </v-row>

                    <v-row class="mt-4">
                        <v-col cols="12" md="4">
                            <v-icon color="primary" class="mr-2">mdi-calendar</v-icon>
                            Date : <strong>{{ formatDate(recrutement.date) }}</strong>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-icon color="primary" class="mr-2">mdi-account</v-icon>
                            <span v-if="recrutement.tuteur_genre === 'man.png'">Tuteur : </span>
                            <span v-else-if="recrutement.tuteur_genre === 'woman.png'">Tutrice : </span>
                            <span v-else>Tuteur·rice : </span>
                            <strong>{{ recrutement.tuteur_prenom }}</strong>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-btn v-if="date_today >= date_recrutement" color="primary" :to="{ name: 'selectionToFormation', params: { id: recrutement.id }}" prepend-icon="mdi-check-circle">
                                Sélection des candidats
                            </v-btn>
                        </v-col>
                    </v-row>

                    <template v-if="others_recrutements.length">
                        <v-row class="mt-4">
                            <v-col cols="12">
                                <em>Autres dates de recrutement :</em>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="auto" v-for="other in others_recrutements" :key="other.id">
                                <v-btn color="primary" :to="{ name: 'showRecrutement', params: { id: other.id }}" prepend-icon="mdi-calendar">
                                    {{ formatDate(other.date) }}
                                </v-btn>
                            </v-col>
                        </v-row>
                    </template>
                    <v-row v-else class="mt-2">
                        <v-col>
                            <em>Aucune autre date de recrutement.</em>
                        </v-col>
                    </v-row>

                    <v-row v-if="candidats.length > 0" class="mt-4">
                        <v-col>
                            <v-btn color="primary" :href="'mailto:?bcc=' + candidatsEmails" prepend-icon="mdi-email">
                                Envoyer un mail aux candidats
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

            <!-- Tabs -->
            <v-card>
                <v-tabs v-model="tab" bg-color="primary">
                    <v-tab value="listCandidats">Liste des candidat·e·s</v-tab>
                    <v-tab value="ajoutCandidats">Ajout de candidat·e·s</v-tab>
                    <v-tab value="documents">Documents</v-tab>
                </v-tabs>

                <v-tabs-window v-model="tab">
                    <!-- Tab: Liste des candidats -->
                    <v-tabs-window-item value="listCandidats">
                        <v-card-text>
                            <div v-if="!loadingDatas" class="text-center py-8">
                                <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
                                <div class="mt-4">Chargement...</div>
                            </div>
                            <div v-else-if="candidats.length > 0">
                                <v-table fixed-header>
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th class="text-center">Prénom</th>
                                            <th class="text-center">Nom</th>
                                            <th class="text-center">Tél/GSM</th>
                                            <th class="text-center">Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(candidat, index) in candidats" :key="candidat.id">
                                            <td><strong>{{ index + 1 }}</strong></td>
                                            <td class="text-center"><strong>{{ candidat.prenom }}</strong></td>
                                            <td class="text-center"><strong>{{ candidat.nom?.toUpperCase() }}</strong></td>
                                            <td class="text-center">
                                                <span v-if="candidat.tel">{{ candidat.tel }}</span>
                                                <br v-if="candidat.tel && candidat.gsm">
                                                <span v-if="candidat.gsm">{{ candidat.gsm }}</span>
                                                <span v-if="!candidat.tel && !candidat.gsm" class="text-grey">Non renseigné</span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="candidat.email" :href="'mailto:' + candidat.email" class="text-primary">{{ candidat.email }}</a>
                                                <span v-else class="text-grey">Non renseigné</span>
                                            </td>
                                            <td>
                                                <v-btn icon variant="text" size="small" color="primary" :to="{ name: 'showInscrit', params: { id: candidat.id }}">
                                                    <v-icon>mdi-eye</v-icon>
                                                </v-btn>
                                                <v-btn icon variant="text" size="small" color="warning" @click="verifReportCandidat(candidat.id)">
                                                    <v-icon>mdi-send</v-icon>
                                                </v-btn>
                                                <v-btn icon variant="text" size="small" color="error" @click="deleteCandidat(candidat.id)">
                                                    <v-icon>mdi-delete</v-icon>
                                                </v-btn>
                                            </td>
                                        </tr>
                                    </tbody>
                                </v-table>
                            </div>
                            <div v-else class="text-center py-8 text-grey">
                                Aucun·e candidat·e inscrit·e au recrutement
                            </div>
                        </v-card-text>
                    </v-tabs-window-item>

                    <!-- Tab: Ajout de candidats -->
                    <v-tabs-window-item value="ajoutCandidats">
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="query"
                                        label="Rechercher un·e inscrit·e"
                                        variant="outlined"
                                        prepend-inner-icon="mdi-magnify"
                                        clearable
                                    ></v-text-field>
                                    <v-list v-if="results.length > 0 && query" elevation="2" class="mt-n4">
                                        <v-list-item
                                            v-for="result in results.slice(0,10)"
                                            :key="result.id"
                                            @click="ajoutCandidat(result.searchable.id, result.searchable.nom, result.searchable.prenom)"
                                        >
                                            <template v-slot:prepend>
                                                <v-icon color="success">mdi-account-plus</v-icon>
                                            </template>
                                            <v-list-item-title>{{ result.title }}</v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <h6 class="mb-2">Liste des candidat·e·s à ajouter :</h6>
                                    <v-chip
                                        v-for="(personne, index) in listCandidats"
                                        :key="personne.id"
                                        class="ma-1"
                                        closable
                                        color="primary"
                                        @click:close="deleteListCandidats(index)"
                                    >
                                        {{ personne.nom?.toUpperCase() }} {{ personne.prenom }}
                                    </v-chip>
                                    <p v-if="!listCandidats.length" class="text-grey">Aucun</p>
                                </v-col>
                                <v-col cols="12" md="4" class="d-flex flex-column ga-2">
                                    <v-btn color="success" @click="startStoreInscriptionCandidats" :disabled="!listCandidats.length" prepend-icon="mdi-account-check">
                                        Inscrire les candidat·e·s
                                    </v-btn>
                                    <v-btn color="primary" :to="{ name: 'createInscritWithRecrutement', params: { recrutement_id: recrutement.id }}" prepend-icon="mdi-account-plus">
                                        Faire une inscription
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-tabs-window-item>

                    <!-- Tab: Documents -->
                    <v-tabs-window-item value="documents">
                        <v-card-text>
                            <v-btn color="primary" class="ma-1" @click="uploadListePresence" prepend-icon="mdi-file-pdf-box">
                                Liste des présences
                            </v-btn>
                            <v-btn color="primary" class="ma-1" @click="openModalAttestation" prepend-icon="mdi-file-pdf-box">
                                Attestation de participation
                            </v-btn>
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

        <!-- Dialog: Edition -->
        <v-dialog v-model="dialog_edition" persistent max-width="600">
            <v-card>
                <v-card-title>Modification de la date</v-card-title>
                <v-card-text>
                    <v-date-picker
                        v-model="formRecrutement.date"
                        :allowed-dates="allowedDays(listDatesOthersRecrutements)"
                        :min="min"
                        :max="max"
                        color="primary"
                    ></v-date-picker>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="dialog_edition = false">Fermer</v-btn>
                    <v-btn color="success" @click="updateRecrutement(recrutement.id)">Sauvegarder</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Dialog: Verif Recrutement -->
        <v-dialog v-model="dialog_verif_recrutement" persistent max-width="700">
            <v-card>
                <v-card-title>Vérification pour le recrutement du {{ formatDate(recrutement.date) }}</v-card-title>
                <v-card-text>
                    <v-checkbox v-model="transfertCandidat" label="Transfert de candidats"></v-checkbox>
                    <div v-if="transfertCandidat && candidatsTransfert.length > 0">
                        <v-table fixed-header>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th class="text-center">Prénom</th>
                                    <th class="text-center">Nom</th>
                                    <th>Transfert ?</th>
                                    <th style="width:175px;">Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(candidat, index) in candidatsTransfert" :key="index">
                                    <td><strong>{{ candidat[0] }}</strong></td>
                                    <td class="text-center"><strong>{{ candidat[1] }}</strong></td>
                                    <td class="text-center"><strong>{{ candidat[2]?.toUpperCase() }}</strong></td>
                                    <td>
                                        <v-checkbox v-model="candidat[4]" label="Transfert" hide-details density="compact"></v-checkbox>
                                    </td>
                                    <td v-if="candidat[4]">
                                        <v-select
                                            v-model="candidat[5]"
                                            :items="listDatesOthersRecrutements"
                                            label="Autre(s) date(s)"
                                            variant="outlined"
                                            density="compact"
                                            hide-details
                                        ></v-select>
                                    </td>
                                    <td v-if="candidat[4]">
                                        <v-btn icon variant="text" size="small" @click="reportCandidatTransfert(candidat[0], candidat[1] + ' ' + candidat[2], candidat[5])">
                                            <v-icon :color="candidat[6] ? 'success' : 'error'">mdi-send</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </div>
                    <div v-else-if="transfertCandidat" class="text-grey">Pas de candidat</div>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="dialog_verif_recrutement = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="destroyRecrutement(recrutement.id)">Supprimer</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Dialog: Report Candidat -->
        <v-dialog v-model="dialog_report_candidat" persistent max-width="700">
            <v-card>
                <v-card-title>Report de {{ candidat[1] }} {{ candidat[2] }}</v-card-title>
                <v-card-text>
                    <v-select
                        v-model="candidat[3]"
                        :items="listDatesOthersRecrutements"
                        label="Autre(s) date(s)"
                        variant="outlined"
                    ></v-select>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="dialog_report_candidat = false">Fermer</v-btn>
                    <v-btn color="success" @click="storeReportCandidat(candidat[3])">Sauvegarder</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Dialog: Attestation -->
        <v-dialog v-model="dialog_attestation_participation" max-width="550">
            <v-card>
                <v-card-title>Attestations de participation</v-card-title>
                <v-card-text>
                    <v-table v-if="candidats.length > 0" fixed-header>
                        <thead>
                            <tr>
                                <th class="text-center">Prénom</th>
                                <th class="text-center">Nom</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="candidat in candidats" :key="candidat.id">
                                <td><strong>{{ candidat.prenom }}</strong></td>
                                <td><strong>{{ candidat.nom?.toUpperCase() }}</strong></td>
                                <td class="text-center">
                                    <v-btn color="success" size="small" @click="uploadAttestation(candidat)">
                                        Télécharger
                                    </v-btn>
                                </td>
                            </tr>
                        </tbody>
                    </v-table>
                    <p v-else class="text-grey">Pas de candidat</p>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="dialog_attestation_participation = false">Fermer</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Dialog: Traitement -->
        <v-dialog v-model="dialog_recrutements_traitement" persistent max-width="400">
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
    name: "ShowRecrutement",
    data() {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const minDate = today.toISOString().substr(0, 10);
        return {
            loading: false,
            loadingDatas: false,
            tab: 'listCandidats',
            date_today: today,
            date_recrutement: null,
            min: minDate,
            max: null,
            recrutement: {},
            candidats: [],
            candidatsEmails: '',
            others_recrutements: [],
            listDatesOthersRecrutements: [],
            listCandidatsAllRecrutements: [],
            // Dialogs
            dialog_edition: false,
            dialog_verif_recrutement: false,
            dialog_report_candidat: false,
            dialog_attestation_participation: false,
            dialog_recrutements_traitement: false,
            // Forms
            formRecrutement: new Form({ formation_id: null, date: null }),
            // Data
            transfertCandidat: false,
            candidatsTransfert: [],
            candidat: [],
            newRecrutementId: null,
            checkNbreTransfertTrue: false,
            checkAllCandidatsOk: false,
            nbreTransfertFalse: 0,
            nbreTransfertTrue: 0,
            query: null,
            results: [],
            listCandidats: [],
            traitements: [],
            vitesse: 1000,
        }
    },
    watch: {
        query() {
            this.searchInscrits();
        },
        $route() {
            this.recrutement = {};
            this.getRecrutement();
        },
    },
    mounted() {
        console.log('Show Recrutement component mounted');
        this.getRecrutement();
    },
    methods: {
        formatDate(date) {
            if (!date) return '';
            return moment(date).format('DD/MM/YYYY');
        },
        goBack() {
            this.$router.go(-1);
        },
        allowedDays(a) {
            return val => a.indexOf(val) === -1 && ![0, 6].includes(new Date(val).getDay());
        },
        getRecrutement() {
            this.$Progress.start();
            this.loading = false;
            this.loadingDatas = false;
            axios.get(`/api/recrutements/show/${this.$route.params.id}`)
                .then(response => {
                    this.recrutement = response.data.recrutement;
                    this.candidats = response.data.candidats;
                    const dayBefore = new Date(response.data.formation.date_debut);
                    dayBefore.setDate(dayBefore.getDate() - 1);
                    this.max = dayBefore.toISOString().substr(0, 10);
                    this.date_recrutement = new Date(this.recrutement.date);
                    this.date_recrutement.setHours(0);
                    this.getOthersRecrutements();
                    this.getListCandidatsAllRecrutements();
                    this.getCandidatsEmails();
                    this.loading = true;
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération des infos du recrutement');
                });
        },
        getOthersRecrutements() {
            this.listDatesOthersRecrutements = [];
            axios.get(`/api/recrutements/others/${this.recrutement.id}/${this.recrutement.formation_id}`)
                .then(response => {
                    this.others_recrutements = response.data.othersRecrutements;
                    this.listDatesOthersRecrutements = this.others_recrutements.map(r => r.date);
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème avec les autres recrutements');
                });
        },
        getListCandidatsAllRecrutements() {
            axios.get(`/api/recrutements/recrutementsFormation/${this.recrutement.formation_id}`)
                .then(response => {
                    this.listCandidatsAllRecrutements = response.data.candidats;
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème avec la liste des candidats');
                });
        },
        getCandidatsEmails() {
            this.candidatsEmails = this.candidats.filter(c => c.email).map(c => c.email).join(',');
            this.loadingDatas = true;
        },
        deleteCandidat(id) {
            Suppression.fire({ text: "Le·la candidat·e ne participera plus au recrutement!" })
                .then(result => {
                    if (result.value) {
                        this.$Progress.start();
                        axios.delete('api/recrutements/deleteInscrit/' + this.recrutement.id + '/' + id)
                            .then(response => {
                                this.$Progress.finish();
                                Confirm.fire('Candidat·e supprimé·e');
                                this.getRecrutement();
                                Fire.$emit('RefreshPage');
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.log(error.response);
                                Snackbar.fire('Problème avec la suppression de candidat !');
                            });
                    }
                });
        },
        searchInscrits() {
            if (this.query) {
                axios.get('api/inscrits/search', { params: { query: this.query } })
                    .then(response => { this.results = response.data; })
                    .catch(error => { console.log(error.response); });
            }
        },
        ajoutCandidat(id, nom, prenom) {
            this.listCandidats.push({ id, nom, prenom });
            this.query = null;
        },
        deleteListCandidats(index) {
            this.listCandidats.splice(index, 1);
        },
        startStoreInscriptionCandidats() {
            this.traitements = [];
            this.dialog_recrutements_traitement = true;
            setTimeout(() => this.storeInscriptionCandidats(0), this.vitesse);
        },
        storeInscriptionCandidats(index) {
            const c = this.listCandidats[index];
            this.$Progress.start();
            axios.post('api/recrutements/addInscrit/' + this.recrutement.id + '/' + c.id)
                .then(response => {
                    this.$Progress.finish();
                    this.traitements.push(c.prenom + ' ' + c.nom + (response.data.message ? ' inscrit·e.' : ' déjà inscrit·e.'));
                })
                .catch(error => {
                    console.log(error.response);
                    this.traitements.push(c.prenom + ' ' + c.nom + ' non inscrit·e.');
                });
            if (index + 1 < this.listCandidats.length) {
                setTimeout(() => this.storeInscriptionCandidats(index + 1), this.vitesse);
            } else {
                setTimeout(() => {
                    this.listCandidats = [];
                    Toast.fire('Inscriptions effectuées !');
                    this.$Progress.finish();
                    this.dialog_recrutements_traitement = false;
                    this.query = null;
                    this.getRecrutement();
                }, this.vitesse);
            }
        },
        forceFileDownload(response, fichier) {
            const blob = new Blob([response.data], { type: response.headers['content-type'] });
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = fichier;
            link.click();
            link.remove();
        },
        uploadListePresence() {
            axios({ url: '/PDF/recrutement/presences/' + this.recrutement.id, method: 'GET', responseType: 'blob' })
                .then(response => { this.forceFileDownload(response, `${this.recrutement.date}_liste-presence.pdf`); })
                .catch(error => { console.log(error.response); Snackbar.fire('Problème avec la liste des présences !'); });
        },
        openModalAttestation() {
            this.dialog_attestation_participation = true;
        },
        uploadAttestation(candidat) {
            axios({ url: '/PDF/recrutement/participation/' + this.recrutement.id + '/' + candidat.id, method: 'GET', responseType: 'blob' })
                .then(response => { this.forceFileDownload(response, `attestation_${candidat.nom}_${candidat.prenom}.pdf`); })
                .catch(error => { console.log(error.response); Snackbar.fire("Problème avec l'attestation !"); });
        },
        editRecrutement() {
            this.formRecrutement.reset();
            this.formRecrutement.clear();
            this.formRecrutement.fill(this.recrutement);
            this.dialog_edition = true;
        },
        updateRecrutement(recrutement) {
            this.$Progress.start();
            this.formRecrutement.busy = true;
            this.formRecrutement.put('api/recrutements/' + recrutement)
                .then(response => {
                    if (this.formRecrutement.successful) {
                        this.$Progress.finish();
                        Toast.fire('Recrutement édité');
                        this.dialog_edition = false;
                        this.getRecrutement();
                        Fire.$emit('RefreshPage');
                    }
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la modification du recrutement !');
                });
        },
        verifRecrutement(recrutement) {
            this.candidatsTransfert = [];
            for (let i = 0; i < this.listCandidatsAllRecrutements.length; i++) {
                const list = this.listCandidatsAllRecrutements[i];
                if (Array.isArray(list) && list.length > 0) {
                    for (let x = 0; x < list.length; x++) {
                        if (list[x].recrutement_id === recrutement) {
                            this.candidatsTransfert.push([
                                list[x].inscrit_id,
                                list[x].prenom,
                                list[x].nom,
                                list[x].inscrit_recrutement_id,
                                false
                            ]);
                        }
                    }
                }
            }
            this.dialog_verif_recrutement = true;
            this.transfertCandidat = false;
        },
        reportCandidatTransfert(candidat_id, candidat, recrutement) {
            if (!recrutement) {
                Snackbar.fire("Vous n'avez pas encodé de date pour " + candidat);
                return;
            }
            const rec = this.others_recrutements.find(r => r.date === recrutement);
            if (rec) this.newRecrutementId = rec.id;
            axios.put('api/inscrits/recrutement/' + candidat_id + '/' + this.recrutement.id + '/' + this.newRecrutementId)
                .then(response => {
                    const c = this.candidatsTransfert.find(x => x[0] === candidat_id);
                    if (c) c.push(true);
                    Toast.fire('Transfert du candidat effectué');
                })
                .catch(error => {
                    const c = this.candidatsTransfert.find(x => x[0] === candidat_id);
                    if (c) c.push(false);
                    console.log(error.response);
                    Snackbar.fire('Transfert du candidat non effectué !');
                });
        },
        verifReportCandidat(candidatId) {
            this.dialog_report_candidat = true;
            this.candidat = [];
            const c = this.candidats.find(x => x.id === candidatId);
            if (c) {
                this.candidat = [c.id, c.prenom, c.nom];
            }
        },
        storeReportCandidat(recrutement) {
            if (!recrutement) {
                Snackbar.fire("Vous n'avez pas choisi une autre date de recrutement !");
                return;
            }
            const rec = this.others_recrutements.find(r => r.date === recrutement);
            if (rec) this.newRecrutementId = rec.id;
            axios.put('api/inscrits/recrutement/' + this.candidat[0] + '/' + this.recrutement.id + '/' + this.newRecrutementId)
                .then(response => {
                    Toast.fire('Transfert du candidat effectué');
                    this.dialog_report_candidat = false;
                    this.getRecrutement();
                    Fire.$emit('RefreshPage');
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Transfert du candidat non effectué !');
                });
        },
        destroyRecrutement(recrutement) {
            this.$Progress.start();
            this.nbreTransfertFalse = 0;
            this.checkNbreTransfertTrue = false;
            this.checkAllCandidatsOk = false;

            if (this.transfertCandidat) {
                for (let y = 0; y < this.candidatsTransfert.length; y++) {
                    if (!this.candidatsTransfert[y][4]) {
                        this.nbreTransfertFalse++;
                    } else if (this.candidatsTransfert[y][4]) {
                        if (!this.candidatsTransfert[y][5]) {
                            this.$Progress.fail();
                            Snackbar.fire("Vous n'avez pas encodé de date pour " + this.candidatsTransfert[y][1] + ' ' + this.candidatsTransfert[y][2]);
                            return;
                        } else if (this.candidatsTransfert[y][6] === false) {
                            this.$Progress.fail();
                            Snackbar.fire('Le transfert de ' + this.candidatsTransfert[y][1] + ' ' + this.candidatsTransfert[y][2] + " n'a pas été effectué. Réessayez !");
                            return;
                        } else if (!this.candidatsTransfert[y][6]) {
                            this.$Progress.fail();
                            Snackbar.fire("Vous n'avez pas effectué le transfert de " + this.candidatsTransfert[y][1] + ' ' + this.candidatsTransfert[y][2]);
                            return;
                        }
                    }
                }

                if (this.nbreTransfertFalse === this.candidatsTransfert.length) {
                    this.$Progress.fail();
                    Snackbar.fire("Vous avez transféré personne");
                    return;
                } else {
                    this.checkNbreTransfertTrue = true;
                }

                this.nbreTransfertTrue = this.candidatsTransfert.filter(c => c[4] && c[6]).length;
                this.checkAllCandidatsOk = this.nbreTransfertTrue === this.candidatsTransfert.length - this.nbreTransfertFalse;
            }

            if ((this.transfertCandidat && this.checkNbreTransfertTrue && this.checkAllCandidatsOk) || !this.transfertCandidat) {
                Suppression.fire().then(result => {
                    if (result.value) {
                        axios.delete('api/recrutements/' + recrutement)
                            .then(response => {
                                this.$Progress.finish();
                                Confirm.fire('Recrutement Supprimé!');
                                this.$router.push('/home');
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.log(error.response);
                                Snackbar.fire('Problème avec la suppression de recrutement !');
                            });
                    }
                });
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
