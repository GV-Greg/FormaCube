<template>
    <div>
        <h1 class="ml-5 mb-n2">
            <a @click="goBack">
                <button class="btn btn-light mt-n2">
                    <i class="fas fa-reply fa-lg text-interface"></i>
                </button>
            </a>
            Fiche de Recrutement
        </h1>
        <v-row justify="center" class="mb-n3" v-if="loading === true">
            <v-col cols="12">
                <v-card class="mx-auto" shaped>
                    <v-card-text class="p-3 ml-2">
                        <v-row class="mt-n2">
                            <div class="col-11 d-flex pb-0">
                                <h4 class="font-weight-bolder">Pour la <router-link :to="{ name: 'showFormation', params: { id: recrutement.formation_id }}" class="myLink">formation {{ recrutement.formation | upperCase }}</router-link></h4><br/>
                                <span class="mt-2 ml-5">{{ recrutement.session | upperCase }}</span>
                            </div>
                            <v-speed-dial class="ml-3 mt-2"
                                v-model="fab"
                                :top="top"
                                :bottom="bottom"
                                :right="right"
                                :left="left"
                                :direction="direction"
                                :open-on-hover="hover"
                                :transition="transition"
                            >
                                <template v-slot:activator>
                                    <v-btn v-model="fab" color="darken-2" class="bg-interface" dark fab>
                                        <v-icon v-if="fab">
                                            mdi-close
                                        </v-icon>
                                        <v-icon v-else>
                                            mdi-settings
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <v-btn fab dark small color="green" @click.prevent="editRecrutement()" class="mt-n1">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn fab dark small color="red" @click.prevent="verifRecrutement(recrutement.id)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </v-speed-dial>
                        </v-row>
                        <v-row class="mt-n5">
                            <div class="col-11 pt-0">
                                <span class="mr-5"><i>Création : le {{ recrutement.created_at | newDate }}</i></span>
                            </div>
                        </v-row>
                        <v-row>
                            <v-col class="text-start align-baseline">
                                <h5>
                                    <i class="fas fa-calendar-day fa-lg text-interface mr-2"></i>Date : <span class="font-weight-bolder text-interface">{{ recrutement.date | newDate }}</span>
                                </h5>
                            </v-col>
                            <v-col class="text-start">
                                <i class="fas fa-user fa-lg text-interface ml-1 mr-2"></i>
                                <span v-if="recrutement.tuteur_genre === 'man.png'">Tuteur : </span>
                                <span v-show="recrutement.tuteur_genre === 'woman.png'">Tutrice : </span>
                                <span v-show="recrutement.tuteur_genre === 'user.png'">Tuteur·rice : </span>
                                <span class="font-weight-bolder text-interface">{{ recrutement.tuteur_prenom }}</span>
                            </v-col>
                            <v-col>
                                <router-link :to="{ name: 'selectionToFormation', params: { id: recrutement.id }}" class="myLink" v-show="date_today >= date_recrutement">
                                    <button class="btn btn-interface button-link mt-n2">
                                        Sélection des candidats
                                    </button>
                                </router-link>
                            </v-col>
                        </v-row>
                        <div v-if="others_recrutements.length !== 0">
                            <v-row>
                                <v-col >
                                    <i>Autres dates de recrutement :</i>
                                </v-col>
                            </v-row>
                            <v-row class="mt-n5">
                                <v-col cols="2" v-for="other in others_recrutements" :key="other.id" class="text-start align-baseline">
                                    <router-link :to="{ name: 'showRecrutement', params: { id: other.id }}" class="myLink">
                                        <i class="fas fa-calendar-day fa-lg mr-2"></i>Date : <span class="font-weight-bolder">{{ other.date | newDate }}</span>
                                    </router-link>
                                </v-col>
                            </v-row>
                        </div>
                        <div v-else>
                            <v-row>
                                <v-col >
                                    <i>Aucune autre date de recrutement.</i>
                                </v-col>
                            </v-row>
                        </div>
                        <v-row v-if="candidats.length > 0">
                            <v-col>
                                <a class="mailtoui btn btn-interface button-link mt-1" :href="'mailto:?bcc=' + candidatsEmails">Envoyer un mail aux candidats</a>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row v-else class="text-center text-light mt-10">
            <v-col class="d-flex flex-column justify-center align-center">
                <v-progress-circular :size="70" :width="10" color="white" indeterminate></v-progress-circular>
                <span class="mt-5">Chargement...</span>
            </v-col>
        </v-row>
        <v-row class="mb-n5" v-show="loading === true">
            <v-col>
                <v-tabs class="elevation-2" dark>
                    <v-tabs-slider></v-tabs-slider>
                    <v-tab href="#listCandidats">Liste des candidat·e·s</v-tab>
                    <v-tab href="#ajoutCandidats">Ajout de candidat·e·s</v-tab>
                    <v-tab href="#uploadPdf">Documents</v-tab>
                    <v-tab-item value="listCandidats">
                        <v-card flat tile>
                            <v-card-text>
                                <div class="row d-flex justify-content-center mt-2" v-if="loadingDatas === true && candidats.length > 0">
                                    <v-simple-table fixed-header>
                                        <template v-slot:default>
                                            <thead class>
                                            <tr>
                                                <th class="text-left">N°</th>
                                                <th class="text-center">Pr&eacute;nom</th>
                                                <th class="text-center">Nom</th>
                                                <th class="text-center">T&eacute;l/GSM</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-left">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="myTr" v-for="(candidat, index) in candidats" :key="candidat.id">
                                                <td class="text-left"><strong>{{ index + 1 }}</strong></td>
                                                <td class="text-center"><strong>{{ candidat.prenom }}</strong></td>
                                                <td class="text-center"><strong>{{ candidat.nom | upperCase }}</strong></td>
                                                <td class="text-center">
                                                    <span v-show="candidat.tel !== null">{{ candidat.tel }}</span><br v-show="candidat.tel !== null"/>
                                                    <span v-show="candidat.gsm !== null">{{ candidat.gsm }}</span>
                                                    <span v-show="candidat.tel === null && candidat.gsm === null" class="no-information">Non renseigné</span>
                                                </td>
                                                <td class="text-center">
                                                    <span v-if="candidat.email !== null"><a class="mailtoui link-interface" :href="'mailto:' + candidat.email">{{ candidat.email }}</a></span>
                                                    <span v-else class="no-information">Non renseigné</span>
                                                </td>
                                                <td>
                                                    <router-link :to="{ name: 'showInscrit', params: { id: candidat.id }}">
                                                        <i class="fas fa-eye fa-lg text-blue mr-1"></i>
                                                    </router-link>
                                                    <span>|</span>
                                                    <button type="button" @click="verifReportCandidat(candidat.id)">
                                                        <i class="fas fa-paper-plane fa-lg text-orange mx-1"></i>
                                                    </button>
                                                    <span>|</span>
                                                    <button type="button" class="ml-1" @click="deleteCandidat(candidat.id)">
                                                        <i class="fas fa-trash-alt fa-lg text-red"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </template>
                                    </v-simple-table>
                                </div>
                                <div v-show="loadingDatas === false">
                                    <v-row class="text-center text-interface mt-1">
                                        <v-col class="d-flex flex-column justify-center align-center">
                                            <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                                            <span class="mt-5">Chargement...</span>
                                        </v-col>
                                    </v-row>
                                </div>
                                <div class="row" v-if="loadingDatas === true && candidats.length === 0">
                                    <div class="col">
                                        Aucun·e candidat·e inscrit·e au recrutement
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item value="ajoutCandidats">
                        <v-card flat tile>
                            <v-card-text>
                                <div class="row row-cols-4">
                                    <div class="col col-3 mb-5">
                                        <b-form-input type="text" placeholder="Rechercher un·e inscrit·e" v-model="query"></b-form-input>
                                        <div v-if="results.length > 0 && query" class="list_inscrits elevation-4">
                                            <ul>
                                                <li v-for="result in results.slice(0,10)" :key="result.id">
                                                    <div class="d-inline-flex">
                                                        <span class="inscrit" v-on:click="ajoutCandidat(result.searchable.id, result.searchable.nom, result.searchable.prenom)">
                                                            <i class="fas fa-user-plus fa-lg myIcon"></i>
                                                            <span v-text="result.title" class="span_inscrit"></span>
                                                        </span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col col-3">
                                        <h6>Liste des candidat·e·s à ajouter:</h6>
                                        <div v-if="listCandidats.length > 0" class="list_candidats">
                                            <ul>
                                                <li v-for="(personne, index) in listCandidats" :key="personne.id">
                                                    <div class="d-inline-flex candidat">
                                                        <i class="fas fa-user fa-lg myIconCandidat"></i>
                                                        {{ personne.nom | upperCase }} {{ personne.prenom }}
                                                        <i class="fas fa-times fa-lg myIconDelete" v-on:click="deleteListCandidats(index)"></i>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div v-else>
                                            Aucun
                                        </div>
                                    </div>
                                    <div class="col col-3 text-right">
                                        <button class="btn btn-success text-light" @click="startStoreInscriptionCandidats()">Inscrire les candidat·e·s</button>
                                    </div>
                                    <div class="col col-3 text-left">
                                        <router-link :to="{ name: 'createInscritWithRecrutement', params: { recrutement_id: recrutement.id }}"
                                                     class="link default">
                                            <button class="btn btn-interface text-light">
                                                <b-img v-bind="images" center src="../images/BoutonAjoutPerso.png" alt="Center image"></b-img>
                                                Faire une inscription
                                            </button>
                                        </router-link>
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item value="uploadPdf">
                        <v-card flat tile>
                            <v-card-text>
                                <button class="btn btn-interface button-link" @click="uploadListePresence()">Liste des présences</button>
                                <button class="btn btn-interface button-link" @click="openModalAttestation()">Attestation de participation</button>
                                <button class="btn btn-interface button-link" @click="uploadFicheSignaletique()">Fiche signal&eacute;tique</button>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </v-col>
        </v-row>
        <!-- Modal d'édition -->
        <v-dialog v-model="dialog_edition" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Modification de la date</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row justify="center">
                            <v-menu
                                ref="menu" min-width="290px"
                                v-model="menu_date_recrutement"
                                :close-on-content-click="false"
                                :return-value.sync="formRecrutement.date"
                                transition="scale-transition"
                                offset-y
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="formRecrutement.date"
                                        label="Date du jour de recrutement"
                                        prepend-icon="mdi-calendar"
                                        readonly v-bind="attrs" v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="formRecrutement.date"
                                    :allowed-dates="allowedDays(listDatesOthersRecrutements)"
                                    :min="min"
                                    :max="max"
                                    :first-day-of-week="1"
                                    @change="save"
                                    locale="fr-BE"
                                >
                                    <v-spacer></v-spacer>
                                    <v-btn text color="primary" @click="menu_date_recrutement = false" class="mt-n12">
                                        Cancel
                                    </v-btn>
                                    <v-btn text color="primary" @click="$refs.menu.save(formRecrutement.date)"  class="mt-n12">
                                        OK
                                    </v-btn>
                                </v-date-picker>
                            </v-menu>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="dialog_edition = false">Close</v-btn>
                    <v-btn color="success" @click="updateRecrutement(recrutement.id)">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Modal de vérification pour pouvoir transférer les candidats lors de la suppression du recrutement -->
        <v-dialog v-model="dialog_verif_recrutement" persistent max-width="700px">
            <v-card>
                <v-card-title>
                    <span class="headline">Vérification pour le recrutement du {{ recrutement.date | newDate }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-checkbox
                                v-model="transfertCandidat"
                                :label="`Transfert de candidats`"
                            ></v-checkbox>
                        </v-row>
                        <div v-show="transfertCandidat === true">
                            <div v-if="candidats.length > 0">
                                <v-simple-table fixed-header>
                                    <template v-slot:default>
                                        <thead class>
                                        <tr>
                                            <th class="text-left" >ID</th>
                                            <th class="text-center">Pr&eacute;nom</th>
                                            <th class="text-center">Nom</th>
                                            <th class="text-left">Transfert ?</th>
                                            <th class="text-left" style="width:175px;">Date</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(candidat, index) in candidats" :key="index">
                                            <td class="text-left"><strong>{{ candidat[0] }}</strong></td>
                                            <td class="text-center"><strong>{{ candidat[1] }}</strong></td>
                                            <td class="text-center"><strong>{{ candidat[2] | upperCase }}</strong></td>
                                            <td>
                                                <v-checkbox
                                                    v-model="candidat[4]"
                                                    :label="`Transfert`"
                                                ></v-checkbox>
                                            </td>
                                            <td v-show="candidat[4] === true">
                                                <v-select
                                                    :items="listDatesOthersRecrutements"
                                                    v-model="candidat[5]"
                                                    label="Autre(s) date(s)"
                                                ></v-select>
                                            </td>
                                            <td v-show="candidat[4] === true">
                                                <button type="button" class="ml-1" @click="reportCandidat(candidat[0], candidat[1] + ' ' + candidat[2], candidat[5])">
                                                    <i class="fas fa-paper-plane fa-lg text-red mx-1" v-if="!candidat[6]"></i>
                                                    <i class="fas fa-paper-plane fa-lg text-green mx-1" v-else></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </div>
                            <div v-else>
                                Pas de candidat
                            </div>
                        </div>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn depressed color="warning" @click="dialog_verif_recrutement = false">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="destroyRecrutement(recrutement.id)">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Modal de report du candidat -->
        <v-dialog v-model="dialog_report_candidat" persistent max-width="700px">
            <v-card>
                <v-card-title>
                    <span class="headline">Report de {{ candidat[1] }} {{ candidat[2] }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-select
                            :items="listDatesOthersRecrutements"
                            v-model="candidat[3]"
                            label="Autre(s) date(s)"
                        ></v-select>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn depressed color="error" @click="dialog_report_candidat = false">Close</v-btn>
                    <v-btn color="success" @click="storeReportCandidat(candidat[3])">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Modal d'export d'attestation de participation au recrutement -->
        <v-dialog v-model="dialog_attestation_participation" persistent width="550">
            <v-card>
                <v-card-title>
                    <span class="headline">Attestations de participation</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <div v-if="candidats.length > 0">
                            <v-simple-table fixed-header>
                                <template v-slot:default>
                                    <thead class>
                                    <tr>
                                        <th class="text-center text-uppercase">Pr&eacute;nom</th>
                                        <th class="text-center text-uppercase">Nom</th>
                                        <th class="text-center text-uppercase">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(candidat, index) in candidats" :key="index">
                                        <td><strong>{{ candidat.prenom }}</strong></td>
                                        <td><strong>{{ candidat.nom | upperCase }}</strong></td>
                                        <td class="d-flex justify-center py-1">
                                            <v-btn depressed color="success" @click="uploadAttestation(candidat)">
                                                T&eacute;l&eacute;charger
                                            </v-btn>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </div>
                        <div v-else>
                            Pas de candidat
                        </div>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn depressed color="error" @click="dialog_attestation_participation = false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Modal de traitement -->
        <v-dialog v-model="dialog_recrutements_traitement" persistent width="400">
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
    import MailtoUI from 'mailtoui/dist/mailtoui-min.js';

    export default {
        name: "ShowRecrutement",
        data() {
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
            const minDate = new Date(today).toISOString().substr(0, 10);
            return {
                loading: false,
                loadingDatas: false,
                date_today: today,
                date_recrutement: null,
                menu_date_recrutement: false,
                weekday: 1,
                min: minDate,
                max: null,
                recrutement: {},
                listCandidats: {},
                candidat: [],
                candidats: {},
                candidatsEmails: '',
                formation_date: null,
                others_recrutements: {},
                listDatesOthersRecrutements: [],
                listCandidatsAllRecrutements: [],
                dayBeforeFormation: null,
                direction: 'bottom',
                fab: false,
                fling: false,
                hover: true,
                tabs: null,
                top: true,
                right: true,
                bottom: false,
                left: false,
                transition: 'slide-y-reverse-transition',
                dialog_edition: false,
                formRecrutement: new Form({
                    formation_id: null,
                    date: null,
                }),
                dialog_verif_recrutement: false,
                transfertCandidat: false,
                newRecrutementId: null,
                listCandidatsTemp: [],
                checkNbreTransfertTrue: false,
                checkAllCandidatsOk: false,
                nbreTransfertFalse: 0,
                nbreTransfertTrue: 0,
                query: null,
                results: [],
                dialog_report_candidat: false,
                images: { blank: false, width: 80, height: 55, class: 'mb-1 ombre' },
                dialog_recrutements_traitement: false,
                traitements: [],
                vitesse: 1000,
                dialog_attestation_participation: false,
            }
        },
        watch: {
            query() {
                this.searchInscrits();
            },
            $route () {
                this.recrutement = {}
                this.getRecrutement(this.$route.params.id, (err, recrutement) => {
                    if (err) {
                        this.error = err.toString()
                    } else {
                        this.recrutement = recrutement
                    }
                })
            },
            menu (val) {
                val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
            },
        },
        mounted() {
            console.log('Show Recrutement component mounted');
            this.getRecrutement();
        },
        methods: {
            goBack() {
                return this.$router.go(-1);
            },

            save (date) {
                this.$refs.menu.save(date)
            },

            allowedDays: function(a){
                return val => a.indexOf(val) === -1 && ![0,6].includes(new Date(val).getDay());
            },

            getRecrutement() {
                this.$Progress.start();
                this.loading = false;
                this.loadingDatas = false;
                axios.get(`/api/recrutements/show/${this.$route.params.id}`)
                    .then((response) => {
                        this.recrutement = response.data.recrutement;
                        this.formation_date = response.data.formation.date_debut;
                        this.candidats = response.data.candidats;
                        this.getOthersRecrutements();
                        this.loading= true;
                        this.getListCandidatsAllRecrutements();
                        this.dayBeforeFormation = new Date(response.data.formation.date_debut);
                        this.dayBeforeFormation = this.dayBeforeFormation.setDate(this.dayBeforeFormation.getDate() -1);
                        this.max = new Date(this.dayBeforeFormation).toISOString().substr(0, 10)
                        this.date_recrutement = new Date(this.recrutement.date)
                        this.date_recrutement.setHours(0);
                        this.getCandidatsEmails();
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la récupération des infos du recrutement');
                    })
            },

            getOthersRecrutements() {
                this.listDatesOthersRecrutements = [];
                axios.get(`/api/recrutements/others/` + this.recrutement.id + '/' + this.recrutement.formation_id)
                    .then((response) => {
                        this.others_recrutements = response.data.othersRecrutements;
                        for(let i=0; i < this.others_recrutements.length; i++) {
                            this.listDatesOthersRecrutements.push(this.others_recrutements[i].date);
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la récupération des infos des autres recrutements');
                    })
            },

            getListCandidatsAllRecrutements() {
                this.listCandidats = [];
                axios.get(`/api/recrutements/recrutementsFormation/` + this.recrutement.formation_id)
                    .then((response) => {
                        this.listCandidatsAllRecrutements = response.data.candidats;
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la liste des candidats de tous les recrutements pour la même formation');
                    })
            },
            getCandidatsEmails() {
                this.candidatsEmails = '';
                for(let i=0; i < this.candidats.length; i++) {
                    if(this.candidatsEmails === '' && this.candidats[i].email != null) {
                        this.candidatsEmails = this.candidats[i].email;
                    } else {
                        if(this.candidats[i].email != null) {
                            this.candidatsEmails = this.candidatsEmails + ',' + this.candidats[i].email;
                        }
                    }
                }
                this.loadingDatas = true;
            },

            deleteCandidat(id) {
                this.$Progress.start();
                Suppression.fire({
                    text: "Si vous continuez, le·la candidat·e ne participera plus au recrutement!",
                }).then((result) => {
                    if (result.value) {
                        axios.delete('api/recrutements/deleteInscrit/' + this.recrutement.id +'/'+ id)
                            .then(response => {
                                this.$Progress.finish();
                                Confirm.fire('Candidat·e supprimé·e');
                                this.getRecrutement()
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.log(error.response)
                                Snackbar.fire('Problème avec la suppression de candidat !');
                            })
                    }
                });

            },

            searchInscrits() {
                if(this.query != null) {
                    axios.get('api/inscrits/search', { params: { query: this.query } })
                        .then(response => this.results = response.data)
                        .catch(error => { console.log(error.response) } );
                }
            },

            ajoutCandidat: function(id, nom, prenom) {
                this.candidat['id'] = id;
                this.candidat['nom'] = nom;
                this.candidat['prenom'] = prenom;
                this.ajoutListCandidats(this.candidat);
                this.candidat= {};
            },

            ajoutListCandidats(candidat) {
                this.listCandidats.push(candidat);
            },

            deleteListCandidats: function (index) {
                this.listCandidats.splice(index, 1);
            },

            startStoreInscriptionCandidats() {
                this.traitements = [];
                this.dialog_recrutements_traitement = true;
                setTimeout(() => this.storeInscriptionCandidats(this.listCandidats[0].id, this.listCandidats[0].prenom + ' ' + this.listCandidats[0].nom, 0), this.vitesse);
            },

            storeInscriptionCandidats(id, candidat, compteur) {
                this.$Progress.start();
                axios.post('api/recrutements/addInscrit/' + this.recrutement.id +'/'+ id)
                    .then(response => {
                        this.$Progress.finish();
                        if(response.data.message != null) {
                            this.traitements.push(candidat + ' inscrit·e.');
                        } else if (response.data.error != null) {
                            this.traitements.push(candidat + ' déjà inscrit·e.');
                        }
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.traitements.push(candidat + ' non inscrit·e.');
                    })
                compteur += 1;
                if(compteur < this.listCandidats.length) {
                    setTimeout(() => this.storeInscriptionCandidats(this.listCandidats[compteur].id, this.listCandidats[compteur].prenom + ' ' + this.listCandidats[compteur].nom, compteur), this.vitesse);
                } else {
                    this.listCandidats = [];
                    Toast.fire('Inscriptions effectuées !');
                    this.$Progress.finish();
                    this.dialog_recrutements_traitement = false;
                    this.query = null;
                    this.getRecrutement();
                    window.reload();
                }
            },

            forceFileDownload(response, fichier) {
                let headers = response.headers;
                let blob = new Blob([response.data], {type: headers['content-type']});
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = fichier;
                link.click();
                link.remove();
            },

            uploadListePresence() {
                axios({
                    url: '/PDF/recrutement/presences/' + this.recrutement.id,
                    method: 'GET',
                    responseType: 'blob',
                })
                    .then(response => {
                        this.forceFileDownload(response, `${this.recrutement.date}_liste-presence.pdf`);
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire('Problème avec la liste des présences à télécharger !');
                    })
            },

            openModalAttestation() {
                this.dialog_attestation_participation = true;
            },

            uploadAttestation(candidat) {
                axios({
                    url: '/PDF/recrutement/participation/' + this.recrutement.id + '/' + candidat.id,
                    method: 'GET',
                    responseType: 'blob',
                })
                    .then(response => {
                        this.forceFileDownload(response, `attestation_${candidat.nom}_${candidat.prenom}.pdf`);
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire("Problème avec l'attestation à télécharger !");
                    })
            },

            uploadFicheSignaletique() {
                axios({
                    url: '/PDF/recrutement/fiche/' + this.recrutement.id,
                    method: 'GET',
                    responseType: 'blob',
                })
                    .then(response => {
                        this.forceFileDownload(response, `${this.recrutement.date}_fiche-signaletique.pdf`);
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire('Problème avec la a fiche signalétique à télécharger !');
                    })
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
                this.formRecrutement
                    .put('api/recrutements/' + recrutement)
                    .then(response => {
                        if(this.formRecrutement.successful) {
                            this.$Progress.finish();
                            Toast.fire('Recrutement édité');
                            this.dialog_edition = false;
                            this.getRecrutement()
                            Fire.$emit('RefreshPage'); // Rafraichit la page
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la modification du recrutement !');
                    })
            },

            verifRecrutement(recrutement) {
                this.listCandidatsTemp = [];
                this.candidats = [];
                this.candidat = [];
                this.otherRecrutements = [];
                for (let i=0; i < this.listCandidatsAllRecrutements.length; i++) {
                    this.listCandidatsTemp = this.listCandidatsAllRecrutements[i];
                    if(this.listCandidatsTemp.length > 1) {
                        for (let x=0; x < this.listCandidatsTemp.length; x++) {
                            if(this.listCandidatsTemp[x].recrutement_id === recrutement) {
                                this.candidat.push(this.listCandidatsTemp[x].inscrit_id);
                                this.candidat.push(this.listCandidatsTemp[x].prenom);
                                this.candidat.push(this.listCandidatsTemp[x].nom);
                                this.candidat.push(this.listCandidatsTemp[x].inscrit_recrutement_id);
                                this.candidat.push(false);
                                this.candidats.push(this.candidat);
                                this.candidat = [];
                            }
                        }
                    } else if (this.listCandidatsTemp.length === 1) {
                        if(this.listCandidatsTemp[0].recrutement_id === recrutement) {
                            this.candidat.push(this.listCandidatsTemp[0].inscrit_id);
                            this.candidat.push(this.listCandidatsTemp[0].prenom);
                            this.candidat.push(this.listCandidatsTemp[0].nom);
                            this.candidat.push(this.listCandidatsTemp[0].inscrit_recrutement_id);
                            this.candidats.push(this.candidat);
                            this.candidat = [];}
                    }
                    this.listCandidatsTemp = [];
                }
                this.dialog_verif_recrutement = true;
                this.transfertCandidat = false;
            },

            reportCandidat(candidat_id, candidat, recrutement) {
                if(recrutement === undefined) {
                    Snackbar.fire('Vous n\'avez pas encodé de date pour ' + candidat);
                } else {
                    for (let i=0; i < this.others_recrutements.length; i++) {
                        if(this.others_recrutements[i].date === recrutement) {
                            this.newRecrutementId = this.others_recrutements[i].id;
                        }
                    }
                    axios.put('api/inscrits/recrutement/' + candidat_id +'/'+ this.recrutement.id +'/'+ this.newRecrutementId)
                        .then(response => {
                            for (let x=0; x < this.candidats.length; x++) {
                                if(this.candidats[x][0] === candidat_id) {
                                    this.candidats[x].push(true);
                                }
                            }
                            Toast.fire('Transfert du candidat effectué');
                        })
                        .catch(error => {
                            for (let x=0; x < this.candidats.length; x++) {
                                if(this.candidats[x][0] === candidat_id) {
                                    this.candidats[x].push(false);
                                }
                            }
                            console.log(error.response);
                            Snackbar.fire('Transfert du candidat non effectué !');
                        })
                }
            },

            verifReportCandidat(candidat) {
                this.dialog_report_candidat = true;
                this.candidat = [];
                for (let i=0; i < this.candidats.length; i++) {
                    if(this.candidats[i].id === candidat) {
                        this.candidat[0] = this.candidats[i].id;
                        this.candidat[1] = this.candidats[i].prenom;
                        this.candidat[2] = this.candidats[i].nom;
                    }
                }
            },

            storeReportCandidat(candidat) {
                if(candidat === undefined) {
                    Snackbar.fire('Vous n\'avez pas choisi une autre date de recrutement !');
                } else {
                    for (let i=0; i < this.others_recrutements.length; i++) {
                        if(this.others_recrutements[i].date === candidat) {
                            this.newRecrutementId = this.others_recrutements[i].id;
                        }
                    }
                    axios.put('api/inscrits/recrutement/' + this.candidat[0] +'/'+ this.recrutement.id +'/'+ this.newRecrutementId)
                        .then(response => {
                            Toast.fire('Transfert du candidat effectué');
                            this.dialog_report_candidat = false;
                            this.getRecrutement();
                            Fire.$emit('RefreshPage');
                        })
                        .catch(error => {
                            console.log(error.response);
                            Snackbar.fire('Transfert du candidat non effectué !');
                        })
                }
            },

            destroyRecrutement(recrutement) {
                this.$Progress.start();
                this.nbreTransfertFalse = 0;
                this.checkNbreTransfertTrue = false;
                this.checkAllCandidatsOk = false;
                if(this.transfertCandidat === true) {
                    for(let y=0; y < this.candidats.length; y++ ) {
                        if(!this.candidats[y][4]) {
                            this.nbreTransfertFalse = this.nbreTransfertFalse+1;
                        } else if(this.candidats[y][4] === true) {
                            if(!this.candidats[y][5]) {
                                this.$Progress.fail();
                                Snackbar.fire('Vous n\'avez pas encodé de date pour ' + this.candidats[y][1] + ' ' + this.candidats[y][2]);
                            } else if (this.candidats[y][6] === false) {
                                this.$Progress.fail();
                                Snackbar.fire('Le transfert de ' + this.candidats[y][1] + ' ' + this.candidats[y][2] + ' n\'a pas été effectué. Réessayez !');
                            } else if (!this.candidats[y][6]) {
                                this.$Progress.fail();
                                Snackbar.fire('Vous n\'avez pas effectué le transfert de ' + this.candidats[y][1] + ' ' + this.candidats[y][2]);
                            }
                        }
                    }

                    if (this.nbreTransfertFalse === this.candidats.length) {
                        this.$Progress.fail();
                        Snackbar.fire('Vous avez transféré personne');
                        this.checkNbreTransfertTrue = false;
                    } else {
                        this.checkNbreTransfertTrue = true;
                    }

                    this.nbreTransfertTrue = 0;
                    for(let z=0; z < this.candidats.length; z++ ) {
                        if(this.candidats[z][4] === true && this.candidats[z][6] === true) {
                            this.nbreTransfertTrue = this.nbreTransfertTrue + 1;
                        }
                    }

                    if(this.nbreTransfertTrue === this.candidats.length - this.nbreTransfertFalse) {
                        this.checkAllCandidatsOk = true;
                    }
                }
                if((this.transfertCandidat === true && this.checkNbreTransfertTrue === true && this.checkAllCandidatsOk === true) || this.transfertCandidat === false) {
                    Suppression.fire().then((result) => {
                        if(result.value) {
                            if(this.transfertCandidat === true && this.checkNbreTransfertTrue === true && this.checkAllCandidatsOk === true) {
                                axios.delete('api/recrutements/' + recrutement)
                                    .then(response => {
                                        this.$Progress.finish();
                                        Confirm.fire('Recrutement Supprimé! \n Transfert du candidat effectué');
                                        this.$router.push('/home');
                                    })
                                    .catch(error => {
                                        this.$Progress.fail();
                                        console.log(error.response);
                                        Snackbar.fire('Problème avec la suppression de recrutement avec transfert de candidat !');
                                    });
                            } else if(this.transfertCandidat === false) {
                                axios.delete('api/recrutements/' + recrutement)
                                    .then(response => {
                                        this.$Progress.finish();
                                        Confirm.fire('Recrutement Supprimé!');
                                        this.$router.push('/home');
                                    })
                                    .catch(error => {
                                        this.$Progress.fail();
                                        console.log(error.response);
                                        Snackbar.fire('Problème avec la suppression de recrutement sans transfert de candidat !');
                                    });
                            }
                        }
                    });
                }
            },
        },
    }
</script>

<style scoped>
    h2 {
        color: #193340;
    }

    .button-link {
        color: white !important;
    }

    .myLink {
        color: #317283 !important;
    }

    .link-interface {
        color: #254F5D !important;
    }

    .myLink:hover, .link-interface:hover {
        color: #1976d2 !important;
    }

    .link:hover, .myLink:hover, .button-link:hover {
        color: #ffd05c !important;
    }

    .bloc {
        padding: 0.5em 0.5em;
        border-radius: 10px;
        background-color: #389fad;
    }

    .bloc:hover {
        color: #193340;
    }

    .theme--light.v-data-table.v-data-table--fixed-header thead th {
        background-color: #317283 !important;
    }

    .list_inscrits {
        z-index: 100;
        background-color: #fff;
        padding: 10px 0;
        border-radius: 5px;
        margin-top: 5px;
    }

    .list_inscrits > ul > li > a {
        color: #193340 !important;
    }

    .list_inscrits > ul {
        margin: 0;
        padding: 0;
    }

    .list_inscrits > ul > li {
        list-style-type: none;
        margin: 3px 0 2px 0;
        padding-top: 2px;
        cursor: pointer;
    }

    .list_inscrits > ul > li:hover {
        background-color: #389fad !important;
    }

    .inscrit {
        color: #193340 !important;
        padding: 3px 50px 7px 10px;
    }

    .myIcon {
        vertical-align: -0.03em !important;
        margin-right: 2px;
    }

    .span_inscrit {
        font-size: large;
    }

    .list_candidats > ul > li {
        list-style-type: none;
        margin-bottom: 2px;
    }

    .candidat {
        background-color: #389fad;
        border-radius: 10px;
        padding: 5px 10px;
        color: #fff;
    }

    .myIconCandidat {
        vertical-align: -0.2em !important;
        margin-right: 5px;
    }

    .myIconDelete {
        vertical-align: -0.2em !important;
        margin-left: 5px;
        cursor: pointer;
    }

    .myIconDeleteCandidat {
        margin-left: 15px;
        cursor: pointer;
        color: #fff;
    }

    .myIconDeleteCandidat:hover {
        color: #e3342f;
    }
</style>
