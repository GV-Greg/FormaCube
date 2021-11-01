<template>
    <div>
        <h1>
            <a @click="goBack">
                <button class="btn btn-light mt-n2">
                    <i class="fas fa-reply fa-lg text-interface"></i>
                </button>
            </a>
            Nouvelle inscription <span id="sous-titre">pour le recrutement du {{ recrutement.date | newDate }}</span>
        </h1>
        <v-stepper v-model="step">
            <v-stepper-header>
                <v-stepper-step :complete="step > 1" step="1">Généralités</v-stepper-step>

                <v-divider class="light"></v-divider>

                <v-stepper-step :complete="step > 2" step="2">Généralités (suite)</v-stepper-step>

                <v-divider class="light"></v-divider>

                <v-stepper-step step="3">Finalisation</v-stepper-step>
            </v-stepper-header>
            <v-stepper-items class="pb-1">
                <!-- Contenu partie 1 : les généralités -->
                <v-stepper-content step="1">
                    <div class="step-content mb-3" v-show="loading === true">
                        <form>
                            <div class="row row-cols-2">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Nom*</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model="formInscrit.nom" type="text"
                                                      :state="validationNom"
                                                      class="form-control rounded-r" :class="{ 'is-invalid': formInscrit.errors.has('nom') || validationMaxNom === false}"
                                                      name="nom" id="nom" placeholder="Nom">
                                        </b-form-input>
                                        <has-error :form="formInscrit" field="nom"></has-error>
                                    </b-input-group>
                                    <p v-show="validationNom === false" class="text-danger small mt-1 mb-0">Le nom doit &ecirc;tre compos&eacute; d'au moins 2 lettres.</p>
                                    <p v-show="validationMaxNom === false" class="text-danger small mt-1 mb-0">Le champ nom ne peut contenir plus de 190 caract&egrave;res.</p>
                                </div>
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Pr&eacute;nom*</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model="formInscrit.prenom" type="text"
                                                      :state="validationPrenom"
                                                      class="form-control rounded-r" :class="{ 'is-invalid': formInscrit.errors.has('prenom') || validationMaxPrenom === false }"
                                                      name="prenom" id="prenom" placeholder="Prénom">
                                        </b-form-input>
                                        <has-error :form="formInscrit" field="prenom"></has-error>
                                    </b-input-group>
                                    <p v-show="validationPrenom === false" class="text-danger small mt-1 mb-0">Le pr&eacute;nom doit &ecirc;tre compos&eacute; d'au moins 2 lettres.</p>
                                    <p v-show="validationMaxPrenom === false" class="text-danger small mt-1 mb-0">Le champ pr&eacute;nom ne peut contenir plus de 190 caract&egrave;res.</p>
                                </div>
                            </div>
                            <div class="row row-cols-2 mt-n3">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0 mt-6">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Genre*</span>
                                        </b-input-group-prepend>
                                        <b-form-select v-model="formInscrit.genre"
                                                       :state="validationGenre"
                                                       class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('genre') }"
                                                       name="genre" id="genre">
                                            <b-form-select-option :value="null">Choisissez</b-form-select-option>
                                            <b-form-select-option value="homme">Homme</b-form-select-option>
                                            <b-form-select-option value="femme">Femme</b-form-select-option>
                                            <b-form-select-option value="3e genre">3e genre</b-form-select-option>
                                        </b-form-select>
                                        <b-input-group-append is-text>
                                            <b-form-checkbox v-model="champsObligatoires.genre" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                        </b-input-group-append>
                                        <has-error :form="formInscrit" field="genre"></has-error>
                                    </b-input-group>
                                    <p v-show="formInscrit.genre === '' && champsObligatoires.genre === false" class="text-danger small mt-n3 mb-0">Le genre est requis.</p>
                                </div>
                                <div class="col">
                                    <label for="date_naissance" class="text-light-interface font-weight-bold">Date de naissance*</label>
                                    <b-input-group class="mt-n2 mb-3">
                                        <b-input-group-prepend>
                                            <b-form-datepicker
                                                v-model="formInscrit.date_naissance" locale="fr"
                                                min="1950-01-01" :max="new Date().toISOString().substr(0, 10)"
                                                button-only
                                                left
                                                aria-controls="date_naissance"
                                                @context="onContext"
                                            ></b-form-datepicker>
                                        </b-input-group-prepend>
                                        <b-form-input
                                            id="date_naissance" type="date"
                                            v-model="formInscrit.date_naissance"
                                            min="1950-01-01" :max="new Date().toISOString().substr(0, 10)"
                                            :state="validationNaissance && !validDateNaissance"
                                            placeholder="YYYY-MM-DD"
                                            autocomplete="off"
                                        ></b-form-input>
                                        <has-error :form="formInscrit" field="date_naissance"></has-error>
                                        <b-input-group-append is-text>
                                            <b-form-checkbox v-model="champsObligatoires.date_naissance" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                        </b-input-group-append>
                                    </b-input-group>
                                    <p v-show="!validDateNaissance === false" class="text-danger small mt-1 mb-n1">La date de naissance ne peut être une date future ou datant après {{ yearsAgo }}.</p>
                                </div>
                            </div>
                            <div class="row row-cols-2 mt-n4">
                                <div class="col col-lg-9">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Rue</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model="formInscrit.rue" type="text"
                                                      class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('rue') || validationMaxRue === false }"
                                                      name="rue" id="rue" placeholder="Rue">
                                        </b-form-input>
                                        <has-error :form="formInscrit" field="rue"></has-error>
                                    </b-input-group>
                                    <p v-show="validationMaxRue === false" class="text-danger small mt-1 mb-n1">Le champ rue ne peut contenir plus de 190 caract&egrave;res.</p>
                                </div>
                                <div class="col col-lg-3">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">N°</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model.number="formInscrit.numero" type="number" min="0"
                                                      class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('numero') || (formInscrit.numero !== '' && validationMaxNumero === false) }"
                                                      name="numero" id="numero" placeholder="Numéro">
                                        </b-form-input>
                                        <has-error :form="formInscrit" field="numero"></has-error>
                                    </b-input-group>
                                    <p v-show="formInscrit.numero !== '' && validationMaxNumero === false" class="text-danger small ml-n4 mt-1 mb-n1">Le champ num&eacute;ro doit être compris entre 1 et 9999.</p>
                                </div>
                            </div>
                            <div class="row row-cols-2">
                                <div class="col col-lg-3">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Boite</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model="formInscrit.boite" type="text"
                                                      class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('boite') || validationMaxBoite === false }"
                                                      name="boite" id="boite" placeholder="Complément">
                                        </b-form-input>
                                        <has-error :form="formInscrit" field="boite"></has-error>
                                    </b-input-group>
                                </div>
                                <div class="col col-lg-9">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">CP & Ville*</span>
                                        </b-input-group-prepend>
                                        <b-form-select v-model="formInscrit.ville_id" :state="validationVille"
                                                       class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('ville_id') }"
                                                       name="ville_id" id="ville_id">
                                            <b-form-select-option :value="null">Choisissez</b-form-select-option>
                                            <b-form-select-option v-for="ville in villes.villes" :key="ville.id" :value="ville.id">{{ ville.code_postal + ' ' + ville.ville }}</b-form-select-option>
                                        </b-form-select>
                                        <b-input-group-append is-text>
                                            <b-form-checkbox v-model="champsObligatoires.ville_id" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                        </b-input-group-append>
                                        <has-error :form="formInscrit" field="ville_id"></has-error>
                                    </b-input-group>
                                </div>
                                <p v-show="validationMaxBoite === false" class="text-danger small ml-4 mt-n2">Le champ boite ne peut contenir plus de 10 caract&egrave;res.</p>
                            </div>
                            <div class="row row-cols-2">
                                <div class="col col-lg-8">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Email*</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model="formInscrit.email" :state="(validationEmail && checkEmail && validationMaxEmail) && (checkNewsletter && checkEmail && validationMaxEmail)" type="email"
                                                      class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('email'), 'rounded-r': formInscrit.newsletter === true }"
                                                      name="email" id="email" placeholder="Email">
                                        </b-form-input>
                                        <b-input-group-append is-text v-show="formInscrit.newsletter !== true">
                                            <b-form-checkbox v-model="champsObligatoires.email" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                        </b-input-group-append>
                                        <has-error :form="formInscrit" field="email"></has-error>
                                    </b-input-group>
                                    <p v-show="champsObligatoires.email !== true && checkEmail === false" class="text-danger small mt-1 mb-n1">L'adresse mail n'est pas valide.</p>
                                    <p v-show="champsObligatoires.email !== true && validationMaxEmail === false" class="text-danger small mt-1 mb-n1">L'email ne peut contenir plus de 190 caract&egrave;res.</p>
                                    <p v-show="checkNewsletter === false" class="text-danger small mt-1 mb-n1">L'email est requis pour la newsletter.</p>
                                </div>
                                <div class="col col-lg-4">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Newsletter</span>
                                        </b-input-group-prepend>
                                        <b-form-select v-model="formInscrit.newsletter"
                                                       class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('newsletter') }"
                                                       name="newsletter" id="newsletter">
                                            <b-form-select-option :value="false">Non</b-form-select-option>
                                            <b-form-select-option :value="true">Oui</b-form-select-option>
                                        </b-form-select>
                                        <has-error :form="formInscrit" field="newsletter"></has-error>
                                    </b-input-group>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-lg-6">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">GSM*</span>
                                        </b-input-group-prepend>
                                        <b-form-input type="tel" v-model="gsm_national" :state="validationGsm_national"
                                                      name="gsm_national" id="gsm_national" placeholder="+32"
                                                      pattern="[\+]\d{2}" style="max-width:85px;" maxlength="4">
                                        </b-form-input>
                                        <b-form-input type="tel" v-model="formInscrit.gsm" :state="validationGsm || checkGsm"
                                                      name="gsm" id="gsm" placeholder="0xxx/xxx.xxx"
                                                      pattern="([\/\.\s\(\)]?[0-9]){9,16}" maxlength="16"
                                                      class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('gsm') || validationGsm === false || checkGsm === false }">
                                        </b-form-input>
                                        <b-input-group-append is-text>
                                            <b-form-checkbox v-model="champsObligatoires.gsm" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                        </b-input-group-append>
                                    </b-input-group>
                                    <p v-show="validationGsm_national === false" class="text-danger small mt-1 mb-n1">Le préfixe doit contenir au minimum 3 caract&egrave;res.</p>
                                    <p v-show="validationGsm === false" class="text-danger small mt-1 mb-0">Le numéro de gsm doit contenir au minimum 9 caract&egrave;res.</p>
                                </div>
                                <div class="col col-lg-6">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">T&eacute;l. fix</span>
                                        </b-input-group-prepend>
                                        <b-form-input type="tel" v-model="tel_national" :state="validationTel_national"
                                                      name="tel_national" id="tel_national" placeholder="+32"
                                                      pattern="[\+]\d{2}" style="max-width:85px;" maxlength="4">
                                        </b-form-input>
                                        <b-form-input type="tel" v-model="formInscrit.tel" :state="validationTel || checkTel"
                                                      name="tel" id="tel" placeholder="0xx/xxx.xxx"
                                                      pattern="([\/\.\s\(\)]?[0-9]){8,15}" maxlength="15"
                                                      class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('tel') || validationTel === false || checkTel === false }">
                                        </b-form-input>
                                        <b-input-group-append is-text>
                                            <b-form-checkbox v-model="champsObligatoires.tel" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                        </b-input-group-append>
                                    </b-input-group>
                                    <p v-show="validationTel_national === false" class="text-danger small mt-1 mb-n1">Le préfixe doit contenir au minimum 3 caract&egrave;res.</p>
                                    <p v-show="validationTel === false" class="text-danger small mt-1 mb-0">Le numéro de téléphone doit contenir au minimum 8 caract&egrave;res.</p>
                                </div>
                            </div>
                            <span class="font-weight-light font-italic text-light-interface"><small>Tous les champs avec * sont obligatoires</small></span>
                        </form>
                    </div>
                    <div class="text-right" v-show="loading === true">
                        <v-btn class="btn-interface text-light mt-n5" @click="validationStepOne()">Continuer</v-btn>
                    </div>
                    <div v-show="loading === false">
                        <v-row class="text-center text-interface mt-10">
                            <v-col class="d-flex flex-column justify-center align-center">
                                <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                                <span class="mt-5">Chargement...</span>
                            </v-col>
                        </v-row>
                    </div>
                </v-stepper-content>
                <!-- Contenu partie 2 : Généralites (suite) -->
                <v-stepper-content step="2">
                    <div class="step-content mb-6">
                        <form>
                            <v-row>
                                <div class="col col-lg-12">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Commentaire(s)</span>
                                        </b-input-group-prepend>
                                        <b-form-textarea v-model="formInscrit.commentaire_inscrit" rows="5" class="form-control"
                                                         name="commentaire_inscrit" id="commentaire_inscrit" placeholder="Entrez  un commentaire...">
                                        </b-form-textarea>
                                        <has-error :form="formInscrit" field="commentaire_inscrit"></has-error>
                                    </b-input-group>
                                </div>
                            </v-row>
                        </form>
                    </div>
                    <div class="text-right">
                        <v-btn class="btn btn-danger text-light mt-n5" @click="step = 1">Retour</v-btn>
                        <v-btn class="btn-interface text-light mt-n5" @click="goToStepThree()">Continuer</v-btn>
                    </div>
                </v-stepper-content>
                <!-- Contenu partie 3 : Finaliser l'inscription -->
                <v-stepper-content step="3">
                    <div class="step-content mb-6">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <strong>{{ formInscrit.prenom }} {{ formInscrit.nom | upperCase }}</strong> participera au recrutement du {{ recrutement.date | newDate }} pour la formation "{{ recrutement.formation }}".
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <v-btn class="btn btn-danger text-light" @click="step = 2">Retour</v-btn>
                        <v-btn class="btn-success text-light" @click="validationCandidat()">Finaliser Candidat</v-btn>
                    </div>
                </v-stepper-content>
            </v-stepper-items>
        </v-stepper>
    </div>
</template>

<script>
    import {Form} from "vform";
    import moment from 'moment';

    export default {
        name: "CreateInscritWithFormation",
        data() {
            return {
                loading: false,
                step: 1,
                user: [],
                tel_national: '+32',
                gsm_national: '+32',
                formInscrit : new Form({
                    id: '',
                    nom: '',
                    prenom: '',
                    genre: null,
                    date_naissance: '',
                    rue: '',
                    numero: '',
                    boite: '',
                    ville_id: null,
                    email: '',
                    tel: null,
                    gsm: null,
                    commentaire_inscrit: '',
                    newsletter: false,
                    prospect: 0,
                    age: null,
                    num_national: null,
                    statut_legal: null,
                    diplome: null,
                    duree_chomage: null,
                    moyen_recrutement: null,
                    groupe_social: null,
                    tags: [],
                    recrutement: null,
                    formation: null,
                    current_user: null,
                    pmtic_module_1: false,
                    pmtic_module_2: false,
                    pmtic_module_3: false,
                }),
                champsObligatoires: {
                    'genre' : false,
                    'date_naissance' : false,
                    'ville_id' : false,
                    'email' : false,
                    'tel' : true,
                    'gsm' : false,
                },
                yearsAgo: moment().subtract(15, 'years').format('YYYY'),
                villes: [],
                recrutement: [],
                calculAge: null,
            }
        },
        watch: {
            checkNewsletter: function(newValue) {
                if(this.champsObligatoires.email === true) {
                    this.champsObligatoires.email = newValue;
                }
            }
        },
        mounted() {
            console.log('CreateInscritWithRecrutement component mounted');
            this.getDatas();
        },
        computed: {
            validationNom() {
                return this.formInscrit.nom.length > 1;
            },
            validationMaxNom() {
                return this.formInscrit.nom.length < 251;
            },
            validationPrenom() {
                return this.formInscrit.prenom.length > 1;
            },
            validationMaxPrenom() {
                return this.formInscrit.prenom.length < 251;
            },
            validationGenre() {
                if(this.champsObligatoires.genre === false) {
                    return this.formInscrit.genre != null;
                }
            },
            validationNaissance() {
                if(this.champsObligatoires.date_naissance === false) {
                    return this.formInscrit.date_naissance !== '';
                }
            },
            formattedDateNaissance () {
                return this.formInscrit.date_naissance ? moment(this.formInscrit.date_naissance).format('L') : ''
            },
            validDateNaissance() {
                if(this.formInscrit.date_naissance !== null && this.formInscrit.date_naissance.length > 0) {
                    return moment().diff(moment(this.formInscrit.date_naissance), 'years', true) < 15;
                }
            },
            validationMaxRue() {
                return this.formInscrit.rue.length < 251;
            },
            validationMaxNumero() {
                return Number(this.formInscrit.numero) > 0 && Number(this.formInscrit.numero) < 10000;
            },
            validationMaxBoite() {
                return this.formInscrit.boite.length < 11;
            },
            validationVille() {
                if(this.champsObligatoires.ville_id === false) {
                    return this.formInscrit.ville_id != null;
                }
            },
            validationEmail() {
                if(this.champsObligatoires.email === false) {
                    return this.formInscrit.email !== '';
                }
            },
            checkEmail() {
                if(this.formInscrit.email !== '') {
                    return this.validEmail(this.formInscrit.email);
                }
            },
            validationMaxEmail() {
                return this.formInscrit.email.length < 251;
            },
            checkNewsletter() {
                if(this.formInscrit.email.length === 0) {
                    return this.formInscrit.newsletter !== true;
                }
            },
            validationTel_national() {
                if(this.champsObligatoires.tel === false && this.formInscrit.tel_national !== null) {
                    return this.tel_national.length > 2 && this.tel_national.length < 5;
                }
            },
            checkTel() {
                if(this.champsObligatoires.tel === false) {
                    return this.formInscrit.tel !== null;
                }
            },
            validationTel() {
                if(this.champsObligatoires.tel === false && this.formInscrit.tel !== null) {
                    return this.formInscrit.tel.length > 7 && this.formInscrit.tel.length < 16;
                }
            },
            validationGsm_national() {
                if(this.champsObligatoires.gsm === false && this.formInscrit.gsm_national !== null) {
                    return this.gsm_national.length > 2 && this.gsm_national.length < 5;
                }
            },
            checkGsm() {
                if(this.champsObligatoires.gsm === false) {
                    return this.formInscrit.gsm !== null;
                }
            },
            validationGsm() {
                if(this.champsObligatoires.gsm === false && this.formInscrit.gsm !== null) {
                    return this.formInscrit.gsm.length > 8 && this.formInscrit.gsm.length < 17;
                }
            },
        },
        methods: {
            goBack() {
                Suppression.fire({
                    text: "Si vous continuez, vous retournez à la fiche du recrutement!",
                }).then((result) => {
                    if (result.value) {
                        return this.$router.go(-1);
                    }
                });
            },
            validEmail: function (email) {
                let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            validation(test, message){
                if(test) {
                    Snackbar.fire(message);
                    return true;
                }
                return false;
            },
            onContext(ctx) {
                // The date formatted in the locale, or the `label-no-date-selected` string
                this.formatted = ctx.selectedFormatted
                // The following will be an empty string until a valid date is entered
                this.selected = ctx.selectedYMD
            },
            getDatas() {
                this.getRecrutement();
                this.getVilles();
                this.currentUser();
            },
            getRecrutement() {
                this.loading = false;
                this.$Progress.start();
                axios.get('api/recrutements/show/' + this.$route.params.recrutement_id)
                    .then(response => {
                        this.recrutement = response.data.recrutement;
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération des infos du recrutement',
                            timer: undefined,
                        })
                    })
            },
            getVilles() {
                axios.get('api/villes/')
                    .then(response => {
                        this.villes = response.data;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des villes',
                            timer: undefined,
                        })
                    })
            },
            currentUser() {
                this.user = this.$store.getters.currentUser;
                this.formInscrit.current_user = this.user.id;
                this.loading = true;
            },
            checkDateNaissance() {
                if(this.champsObligatoires.date_naissance === false){
                    this.champsObligatoires.date_naissance = true;
                } else {
                    this.champsObligatoires.date_naissance = false;
                }
            },
            validationStepOne() {
                if(this.validation(this.formInscrit.nom === '', "Vous n'avez pas rempli le nom !")) {
                } else if(this.validation(this.formInscrit.prenom === '', "Vous n'avez pas rempli le prénom !")) {
                } else if(this.validation(this.champsObligatoires.nom === true && this.champsObligatoires.prenom === true, "Il faut au moins le nom ou le prénom !")) {
                } else if(this.validation(this.formInscrit.nom.length < 2, "Le nom doit être composé d'au moins 2 lettres !")) {
                } else if(this.validation(this.formInscrit.prenom.length < 2, "Le prénom doit être composé d'au moins 2 lettres !")) {
                } else if(this.validation(this.formInscrit.nom !== '' && !this.validationMaxNom, "Le champ nom ne peut contenir plus de 190 caractères !")) {
                } else if(this.validation(this.formInscrit.prenom !== '' && !this.validationMaxPrenom, "Le champ prénom ne peut contenir plus de 190 caractères !")) {
                } else if(this.validation(this.formInscrit.genre == null && this.champsObligatoires.genre === false, "Vous n'avez pas rempli le genre ou cochez la case 'non disponible' (ND) correspondante !")) {
                } else if(this.validation(this.formInscrit.date_naissance === '' && this.champsObligatoires.date_naissance === false, "Vous n'avez pas rempli la date de naissance ou cochez la case 'non disponible' (ND) correspondante !")) {
                } else if(this.validation(this.formInscrit.date_naissance !== '' && this.validDateNaissance, "La date de naissance ne peut être une date future ou datant après " + this.yearsAgo + " !")) {
                } else if(this.validation(this.formInscrit.rue !== '' && !this.validationMaxRue, "Le champ rue ne peut contenir plus de 190 caractères !")) {
                } else if(this.validation(this.formInscrit.numero !== '' && Number(this.formInscrit.numero) <= 0, 'Le numéro doit être positif et non nul !')) {
                } else if(this.validation(this.formInscrit.numero !== '' && Number(this.formInscrit.numero) > 9999, 'Le numéro doit être inférieur à 10000 !')) {
                } else if(this.validation(this.formInscrit.boite !== '' && !this.validationMaxBoite, "Le champ boite ne peut contenir plus de 10 caractères !")) {
                } else if(this.validation(this.formInscrit.ville_id == null && this.champsObligatoires.ville_id === false, "Vous n'avez pas rempli la ville ou cochez la case 'non disponible' (ND) correspondante !")) {
                } else if(this.validation(this.formInscrit.email === '' && this.champsObligatoires.email === false && this.checkNewsletter === true, "Vous n'avez pas rempli l'email ou cochez la case 'non disponible' (ND) correspondante !")) {
                } else if(this.validation(this.formInscrit.email !== '' && this.validEmail(this.formInscrit.email) === false, "Vous n'avez pas renseigné un email valide !")) {
                } else if(this.validation(this.formInscrit.email !== '' && !this.validationMaxEmail, "Le champ email ne peut contenir plus de 190 caractères !")) {
                } else if(this.validation(this.checkNewsletter === false, "L'email est requis pour la newsletter !")) {
                } else if(this.validation(this.champsObligatoires.gsm === false && (this.gsm_national === '' || this.formInscrit.gsm === '' || this.gsm_national === null || this.formInscrit.gsm === null), "Vous n'avez pas rempli tous les champs du n° de gsm ou cochez la case 'non disponible' (ND) correspondante !")) {
                } else if(this.validation(this.champsObligatoires.gsm === false && (this.gsm_national.length < 3 || this.formInscrit.gsm.length < 9 ), "Il n'y pas le minimum de caractères requis dans un des champs du n° de gsm !")) {
                } else if(this.validation(this.champsObligatoires.tel === false && (this.tel_national === '' || this.formInscrit.tel === '' || this.tel_national === null || this.formInscrit.tel === null), "Vous n'avez pas rempli le tél. fix ou cochez la case 'non disponible' (ND) correspondante !")) {
                } else if(this.validation(this.champsObligatoires.tel === false && (this.tel_national.length < 3 || this.formInscrit.tel.length < 8 ), "Il n'y pas le minimum de caractères requis dans un des champs du n° de tél. fix !")) {
                } else {
                    if(this.formInscrit.ville_id == null) {
                        this.formInscrit.ville_id = 2766
                    }
                    this.step = 2;
                }
            },
            goToStepThree() {
                this.getAge();
                this.step = 3;
            },
            getAge() {
                if(this.formInscrit.date_naissance !== null) {
                    this.calculAge = moment().diff(moment(this.formInscrit.date_naissance), 'years');
                    this.formInscrit.age = this.calculAge;
                }
            },
            validationCandidat() {
                this.formInscrit.recrutement = this.recrutement.id;
                this.storeInscrit();
            },
            storeInscrit() {
                if(this.formInscrit.tel !== null) {
                    let telTab = this.formInscrit.tel.split('');
                    if(telTab[0] == 0) {
                        telTab.splice(0, 0, '(');
                        telTab.splice(2, 0, ')');
                        this.formInscrit.tel = telTab.join('');
                    } else if(telTab[0] !== '(' && telTab[1] !== '0' && telTab[2] !== ')') {
                        telTab.splice(0, 0, '(0)');
                        this.formInscrit.tel = telTab.join('');
                    }
                    this.formInscrit.tel = this.tel_national + this.formInscrit.tel;
                }
                if(this.formInscrit.gsm !== null) {
                    let gsmTab = this.formInscrit.gsm.split('');
                    if(gsmTab[0] == 0) {
                        gsmTab.splice(0, 0, '(');
                        gsmTab.splice(2, 0, ')');
                        this.formInscrit.gsm = gsmTab.join('');
                    } else if(gsmTab[0] !== '(' && gsmTab[1] !== '0' && gsmTab[2] !== ')') {
                        gsmTab.splice(0, 0, '(0)');
                        this.formInscrit.gsm = gsmTab.join('');
                    }
                    this.formInscrit.gsm = this.gsm_national + this.formInscrit.gsm;
                }
                this.$Progress.start();
                this.formInscrit
                    .post('api/inscrits/create')
                    .then(response => {
                        if(this.formInscrit.successful) {
                            this.$Progress.finish();
                            if(response.data.message && response.data.message === 'Personne existante !') {
                                Snackbar.fire({
                                    title: response.data.message + "\nVeuillez mettre à jour sa fiche...",
                                    timer: undefined
                                });
                                this.$router.push('/inscrits/show/' + response.data.inscrit.id);
                            } else {
                                Toast.fire('Inscription effectuée');
                                this.$router.push('/recrutements/show/' + this.recrutement.id);
                            }
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la création du candidat')
                    })
            },
        }
    }
</script>

<style scoped>
    #sous-titre {
        font-size: medium;
    }

    .container-checkbox > .container{
        padding: 0 !important;
        margin-top: 10px;
    }
    .checkbox-pmtic {
        padding: 0 !important;
        display: flex;
        justify-content: center;
    }

    .v-input--selection-controls {
        margin-top: 0 !important;
    }
</style>
