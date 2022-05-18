<template>
    <div class="container">
        <h1 class="d-flex align-content-center">
            <button class="btn btn-light pb-2 mr-2" @click="retour">
                <i class="fas fa-reply fa-lg text-primary-dark"></i>
            </button>
            Modification de la formation
        </h1>
        <div class="row mt-2">
            <v-stepper v-model="step" class="w-100">
                <v-stepper-header>
                    <v-stepper-step :complete="step > 1" step="1">Généralités</v-stepper-step>

                    <v-divider class="light"></v-divider>

                    <v-stepper-step step="2">Généralités (suite)</v-stepper-step>
                </v-stepper-header>
                <v-stepper-items>
                    <!-- Contenu partie 1 : les généralités-->
                    <v-stepper-content step="1">
                        <div class="step-content mb-3" v-if="loading === true">
                            <form >
                                <div class="row row-cols-2">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Nom*">
                                            <b-form-input v-model="formFormation.nom" type="text"
                                                          :state="checkNom && validationMinNom && validationMaxNom"
                                                          class="rounded-right" :class="{ 'is-invalid': formFormation.errors.has('nom') }"
                                                          name="nom" id="nom" placeholder="Nom de la formation">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="nom-feedback" v-show="checkNom === false">
                                                Le nom de la formation est requis.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="nom-min-feedback" v-show="validationMinNom === false">
                                                Le nom doit &ecirc;tre compos&eacute; d'au moins 3 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="nom-max-feedback" v-show="validationMaxNom === false">
                                                Le nom doit &ecirc;tre compos&eacute; de moins 190 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="nom"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Abréviation*">
                                            <b-form-input v-model="formFormation.abreviation" type="text"
                                                          :state="checkAbreviation && validationMinAbreviation && validationMaxAbreviation"
                                                          class="rounded-right" :class="{ 'is-invalid': formFormation.errors.has('abreviation') }"
                                                          name="abreviation" id="abreviation" placeholder="Abréviation de la formation">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="abreviation-feedback" v-show="checkAbreviation === false">
                                                L'abréviation de la formation est requise.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="abreviation-min-feedback" v-show="validationMinAbreviation === false">
                                                L'abréviation de la formation doit &ecirc;tre compos&eacute; d'au moins 3 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="abreviation-max-feedback" v-show="validationMaxAbreviation === false">
                                                L'abréviation de la formation doit &ecirc;tre compos&eacute; de moins 21 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="abreviation"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row row-cols-2">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Session">
                                            <b-form-input v-model="formFormation.session" type="text" :state="validationMaxSession"
                                                          class="rounded-right" :class="{ 'is-invalid': formFormation.errors.has('session') }"
                                                          name="session" id="session" placeholder="Session de la formation">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="abreviation-max-feedback" v-show="validationMaxSession === false">
                                                La session de la formation doit &ecirc;tre compos&eacute; de moins 190 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="session"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Salle*">
                                            <b-form-select v-model="formFormation.salle_id" :state="checkSalle"
                                                           class="rounded-right" :class="{ 'is-invalid': formFormation.errors.has('salle_id') }"
                                                           name="salle" id="salle" placeholder="Salle de la formation">
                                                <b-form-select-option :value="null">Choisissez</b-form-select-option>
                                                <b-form-select-option v-for="salle in salles" :key="salle.id" :value="salle.id">{{ salle.nom }}</b-form-select-option>
                                            </b-form-select>
                                            <b-form-invalid-feedback id="abreviation-max-feedback" v-show="checkSalle === false">
                                                La salle est requise.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="salle"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row row-cols-2">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Début*">
                                            <b-form-datepicker v-model="formFormation.date_debut" type="date" local="fr-BE" :hide-header="hideHeader"
                                                               :start-weekday="weekday" :date-disabled-fn="dateDisabled" :min="start_min"
                                                               :state="checkDateDebut && validationDatesDebutFin" no-flip
                                                               :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                                               class="rounded-right" :class="{ 'is-invalid': formFormation.errors.has('date_debut')}"
                                                               name="date_debut" id="date_debut" placeholder="Début de la formation">
                                            </b-form-datepicker>
                                            <b-form-invalid-feedback id="date-debut-feedback" v-show="checkDateDebut === false">
                                                La date de début de formation est requise.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="dates-debut-fin-feedback" v-show="validationDatesDebutFin === false">
                                                La date de fin de formation ne peut &ecirc;tre avant la date de d&eacute;but de la formation.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="date_debut"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Fin*">
                                            <b-form-datepicker v-model="formFormation.date_fin" type="date" local="fr-BE" :hide-header="hideHeader"
                                                               :start-weekday="weekday" :date-disabled-fn="dateDisabled" :min="dateTodayOrDebut"
                                                               :state="checkDateFin && validationDatesDebutFin" no-flip
                                                               :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                                               class="rounded-right" :class="{ 'is-invalid': formFormation.errors.has('date_fin')}"
                                                               name="date_fin" id="date_fin" placeholder="Fin de la formation">
                                            </b-form-datepicker>
                                            <b-form-invalid-feedback id="date-fin-feedback" v-show="checkDateFin === false">
                                                La date de fin de formation est requise.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="dates-fin-debut-feedback" v-show="validationDatesDebutFin === false">
                                                La date de fin de formation ne peut &ecirc;tre avant la date de d&eacute;but de la formation.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="date_fin"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row row-cols-2">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Min. stagiaires*">
                                            <b-form-input v-model.number="formFormation.min_stagiaires" type="number" min="0"
                                                          :state="checkMinStagiaires && validationMinStagiaires && validationMinMaxStagiaires"
                                                          class="rounded-right" :class="{ 'is-invalid': formFormation.errors.has('min_stagiaires')}"
                                                          name="min_stagiaires" id="min_stagiaires" placeholder="Minimum de stagiaires">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="check-min-stagiaire-feedback" v-show="checkMinStagiaires === false">
                                                Le nombre minimum de stagiaires est requis.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="min-stagiaire-feedback" v-show="validationMinStagiaires === false">
                                                Le nombre minimum de stagiaires doit &ecirc;tre sup&eacute;rieur &agrave; 0.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="min-max-stagiaire-feedback" v-show="validationMinMaxStagiaires  === false">
                                                Le nombre min. de stagiaires doit &ecirc;tre inf&eacute;rieur au nombre max. de stagiaires.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="min_stagiaires"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Max. stagiaires*">
                                            <b-form-input v-model.number="formFormation.max_stagiaires" type="number" min="0"
                                                          :state="checkMaxStagiaires && validationMaxStagiaires && validationMinMaxStagiaires"
                                                          class="rounded-right" :class="{ 'is-invalid': formFormation.errors.has('max_stagiaires')}"
                                                          name="max_stagiaires" id="max_stagiaires" placeholder="Maximum de stagiaires">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="check-max-stagiaire-feedback" v-show="checkMaxStagiaires === false">
                                                Le nombre maximum de stagiaires est requis.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="max-stagiaire-feedback" v-show="validationMaxStagiaires === false">
                                                Le nombre maximum de stagiaires doit &ecirc;tre sup&eacute;rieur &agrave; 0.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="max-min-stagiaire-feedback" v-show="validationMinMaxStagiaires  === false">
                                                Le nombre min. de stagiaires doit &ecirc;tre inf&eacute;rieur au nombre max. de stagiaires.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="max_stagiaires"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row row-cols-2">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Nombre d'heures*">
                                            <b-form-input v-model.number="formFormation.nbre_heures" type="number" min="0"
                                                          :state="checkNbreHeures && validationNbreHeures"
                                                          class="rounded-right" :class="{ 'is-invalid': formFormation.errors.has('nbre_heures')}"
                                                          name="nbre_heures" id="nbre_heures" placeholder="Nombre d'heures">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="check-nbre-heures-feedback" v-show="checkNbreHeures === false">
                                                Le nombre d'heures de formation est requis.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="nbre-heures-feedback" v-show="validationNbreHeures === false">
                                                Le nombre d'heures doit &ecirc;tre sup&eacute;rieur &agrave; 0.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="nbre_heures"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Prix*">
                                            <b-form-input v-model.number="formFormation.prix" type="number" min="0"
                                                          :state="checkPrix && validationPrix"
                                                          :class="{ 'is-invalid': formFormation.errors.has('prix')}"
                                                          name="prix" id="prix" placeholder="Prix">
                                            </b-form-input>
                                            <b-input-group-append is-text>
                                                <span class="text-primary-dark font-weight-bold">€</span>
                                            </b-input-group-append>
                                            <b-form-invalid-feedback id="check-nbre-heures-feedback" v-show="checkPrix === false">
                                                Le prix de la formation est requis.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="nbre-heures-feedback" v-show="validationPrix === false">
                                                Le prix de la formation doit &ecirc;tre sup&eacute;rieur &agrave; 0.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="prix"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                            </form>
                            <div class="mt-3 d-flex justify-content-between">
                                <span class="text-left font-weight-light font-italic text-primary-dark mt-2"><small>Tous les champs avec * sont obligatoires</small></span>
                                <v-btn class="btn-primary" @click="validationStepOne()">Continuer</v-btn>
                            </div>
                        </div>
                        <Spinner v-else />
                    </v-stepper-content>
                    <!-- Contenu partie 2 : les généralités suite-->
                    <v-stepper-content step="2">
                        <div class="step-content mb-6">
                            <form>
                                <div class="row row-cols-2">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Tuteur·rice*">
                                            <b-form-select v-model="formFormation.user_id" :state="checkTuteur"
                                                           class="rounded-right" :class="{ 'is-invalid': formFormation.errors.has('user_id') }"
                                                           name="user_id" id="user_id">
                                                <b-form-select-option v-for="user in users.users" :key="user.id" :value="user.id">{{ user.firstname + ' ' + user.lastname }}</b-form-select-option>
                                            </b-form-select>
                                            <b-form-invalid-feedback id="check-tuteur-feedback" v-show="checkTuteur === false">
                                                Le tuteur·trice de la formation est requis·e.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="user_id"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Durée RGPD*">
                                            <b-form-input v-model.number="formFormation.duree_rgpd" type="number" min="0" :state="checkDureeRgpd && validationDureeRgpd"
                                                          class="rounded-right" :class="{ 'is-invalid': formFormation.errors.has('duree_rgpd')}"
                                                          name="duree_rgpd" id="duree_rgpd" placeholder="Durée durant laquelle garder les infos (RGPD)">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="check-rgpd-feedback" v-show="checkDureeRgpd === false">
                                                Le durée pour le RGPD est requis.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="min-rgpd-feedback" v-show="validationDureeRgpd === false">
                                                La durée pour le RGPD doit &ecirc;tre sup&eacute;rieur &agrave; 0.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formFormation" field="duree_rgpd"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Commentaire(s)">
                                            <b-form-textarea v-model="formFormation.commentaire_formation" rows="5" class="form-control"
                                                             name="commentaire_formation" id="commentaire_formation" placeholder="Entrez  un commentaire...">
                                            </b-form-textarea>
                                            <has-error :form="formFormation" field="commentaire_formation"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mt-5 d-flex justify-content-between">
                            <v-btn class="btn-danger" @click="step = 1">Retour</v-btn>
                            <span class="text-champs-obligatoires text-primary-dark"><small>Tous les champs avec * sont obligatoires</small></span>
                            <v-btn class="btn-success" @click="validationStepTwo()">&Eacute;diter</v-btn>
                        </div>
                    </v-stepper-content>
                </v-stepper-items>
            </v-stepper>
        </div>
    </div>
</template>

<script>
    import {Form} from "vform";
    import Spinner from "../../elements/SpinnerStepper";

    export default {
        name: "EditFormation",
        components: {
            Spinner,
        },
        data () {
            const now = new Date()
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
            const minDate = new Date(today)
            return {
                step: 1,
                hideHeader: true,
                weekday: 1,
                start_min: minDate,
                end_min: minDate,
                formation: {},
                loading: false,
                formFormation : new Form({
                    id: '',
                    nom: '',
                    session: '',
                    date_debut: null,
                    date_fin: null,
                    min_stagiaires: '',
                    max_stagiaires: '',
                    nbre_heures: '',
                    salle_id: null,
                    prix: '',
                    commentaire_formation: '',
                    user_id: null,
                    statut: '',
                    abreviation: '',
                    duree_rgpd: '',
                }),
                users: [],
                salles: [],
                event: null,
            }
        },
        mounted() {
            console.log('CreateFormation component mounted');
            this.getFormation();
            this.getUsers();
            this.getSalles();
        },
        computed: {
            checkNom() {
                return this.formFormation.nom !== '';
            },
            validationMinNom() {
                if(this.checkNom){
                    return this.formFormation.nom.length > 2;
                }
            },
            validationMaxNom() {
                return this.formFormation.nom.length < 190;
            },
            checkAbreviation() {
                return this.formFormation.abreviation !== '';
            },
            validationMinAbreviation() {
                if(this.checkAbreviation){
                    return this.formFormation.abreviation.length > 2;
                }
            },
            validationMaxAbreviation() {
                return this.formFormation.abreviation.length < 20;
            },
            validationMaxSession() {
                if(this.formFormation.session !== '' && this.formFormation.session !== null) {
                    return this.formFormation.session.length < 190;
                }
            },
            checkSalle() {
                return this.formFormation.salle_id != null;
            },
            checkDateDebut() {
                return this.formFormation.date_debut != null;
            },
            checkDateFin() {
                return this.formFormation.date_fin != null;
            },
            dateTodayOrDebut() {
                if(this.formFormation.date_debut === null) {
                    let now = new Date();
                    let today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                    return new Date(today);
                } else {
                    return this.formFormation.date_debut
                }
            },
            validationDatesDebutFin() {
                if(this.formFormation.date_debut !== null && this.formFormation.date_fin !== null) {
                    return this.formFormation.date_debut <= this.formFormation.date_fin;
                }
            },
            checkMinStagiaires() {
                return this.formFormation.min_stagiaires !== '';
            },
            validationMinStagiaires() {
                if(this.formFormation.min_stagiaires !== '') {
                    return Number(this.formFormation.min_stagiaires) > 0;
                }
            },
            checkMaxStagiaires() {
                return this.formFormation.max_stagiaires !== '';
            },
            validationMaxStagiaires() {
                if(this.formFormation.max_stagiaires !== '') {
                    return Number(this.formFormation.max_stagiaires) > 0;
                }
            },
            validationMinMaxStagiaires() {
                if(this.formFormation.min_stagiaires !== '' && this.formFormation.max_stagiaires !== '') {
                    return Number(this.formFormation.max_stagiaires) >= Number(this.formFormation.min_stagiaires);
                }
            },
            checkNbreHeures() {
                return this.formFormation.nbre_heures !== '';
            },
            validationNbreHeures() {
                if(this.formFormation.nbre_heures !== '') {
                    return Number(this.formFormation.nbre_heures) > 0;
                }
            },
            checkPrix() {
                return this.formFormation.prix !== '';
            },
            validationPrix() {
                if(this.formFormation.prix !== '') {
                    return Number(this.formFormation.prix) >= 0;
                }
            },
            checkTuteur() {
                return this.formFormation.user_id != null;
            },
            checkDureeRgpd() {
                return this.formFormation.duree_rgpd !== ''
            },
            validationDureeRgpd() {
                if(this.formFormation.duree_rgpd !== '') {
                    return Number(this.formFormation.duree_rgpd) > 0;
                }
            },
        },
        methods: {
            retour() {
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Si vous continuez, vous retournez à la liste des formations!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3CB521',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<strong>RETOUR</strong>'
                }).then((result) => {
                    if (result.value) {
                        return this.$router.go(-1);
                    }
                });
            },
            twoDigit(n) {
                return (n < 10 ? '0' : '') + n
            },
            dateDisabled(ymd, date) {
                const weekday = date.getDay()
                return weekday === 0 || weekday === 6
            },
            validation(test, message){
                if(test) {
                    Snackbar.fire(message);
                    return true;
                }
                return false;
            },

            getFormation() {
                this.$Progress.start();
                this.loading = false;
                axios.get(`/api/formations/show/${this.$route.params.id}`)
                    .then((response) => {
                        this.formation = response.data.formation;
                        this.getFormFormation(this.formation);
                        this.$Progress.finish();
                        this.loading = true;
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération des infos de la formation !',
                            timer: undefined,
                        })
                    })
            },
            getFormFormation(formation) {
                this.formFormation.reset();
                this.formFormation.clear();
                this.formFormation.fill(formation);
            },
            getUsers() {
                axios.get('api/users/')
                    .then(response => {
                        this.users = response.data;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération des tuteurs !',
                            timer: undefined,
                        })
                    })
            },
            getSalles() {
                axios.get('api/salles/all')
                    .then(response => {
                        this.salles = response.data.salles;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération des salles !',
                            timer: undefined,
                        })
                    })
            },
            validationStepOne() {
                if(this.checkNom && this.checkAbreviation && this.checkSalle && this.checkDateDebut && this.checkDateFin &&
                    this.checkMinStagiaires && this.checkMaxStagiaires && this.checkNbreHeures && this.checkPrix) {
                    if(this.validationMinStagiaires && this.validationMaxStagiaires && this.validationNbreHeures) {
                        if(this.validation(!this.validationMaxNom, "Le nom doit être composé de moins de 100 caractères !")) {
                        } else if(this.validation(!this.validationMaxAbreviation, "L'abréviation de la formation doit être composé de moins de 20 caractères !")) {
                        } else if(this.validation(this.formFormation.session !== '' && this.formFormation.session !== null && !this.validationMaxSession, "La session de formation doit être composé de moins de 190 caractères !")) {
                        } else {
                            if(this.twoDigit(this.formFormation.min_stagiaires) <= this.twoDigit(this.formFormation.max_stagiaires)) {
                                if(this.formFormation.date_fin >= this.formFormation.date_debut) {
                                    this.step = 2;
                                } else {
                                    Snackbar.fire('La fin de formation ne peut être avant le début de la formation !');
                                }
                            } else {
                                Snackbar.fire('Nombre de stagiaires : Le minimum doit être plus petit que le max !');
                            }
                        }
                    } else {
                        Snackbar.fire('Vous avez rempli des champs avec un nombre négatif et/ou nul !');
                    }
                } else {
                    Snackbar.fire('Vous n\'avez pas rempli tous les champs obligatoires !');
                }
            },
            validationStepTwo() {
                if(this.checkTuteur && this.checkDureeRgpd) {
                    if(this.validationDureeRgpd) {
                        this.editFormation();
                    } else {
                        Snackbar.fire('La durée RGPD doit être supérieur à 0 !');
                    }
                } else {
                    Snackbar.fire('Vous n\'avez pas rempli tous les champs obligatoires !');
                }
            },
            editFormation() {
                this.$Progress.start();
                this.formFormation
                    .put(`/api/formations/edit/${this.$route.params.id}`)
                    .then(response => {
                        if(this.formFormation.successful) {
                            this.$Progress.finish();
                            Toast.fire('Formation éditée');
                            this.$router.push('/formations/show/' + this.formation.id)
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error);
                        Snackbar.fire('Problème avec la modification de la formation');
                    })
            },
        }
    }
</script>

<style scoped>

</style>
