<template>
    <v-container fluid>
        <!-- Header -->
        <v-row align="center" class="mb-4">
            <v-col cols="auto">
                <v-btn icon variant="text" @click="retour">
                    <v-icon>mdi-arrow-left</v-icon>
                </v-btn>
            </v-col>
            <v-col>
                <h1 class="text-h4">Nouvelle inscription <span class="text-subtitle-1 ml-2">pour le recrutement du {{ formatDate(recrutement.date) }}</span></h1>
            </v-col>
        </v-row>

        <!-- Stepper -->
        <v-stepper v-model="step" :items="['Généralités', 'Généralités (suite)']" alt-labels>
            <!-- Step 1: Généralités -->
            <template v-slot:item.1>
                <v-card flat v-if="loading">
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="formInscrit.nom"
                                    label="Nom*"
                                    variant="outlined"
                                    :error="!validationMinNom || !validationMaxNom"
                                    :error-messages="getNomErrors()"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="formInscrit.prenom"
                                    label="Prénom*"
                                    variant="outlined"
                                    :error="!validationMinPrenom || !validationMaxPrenom"
                                    :error-messages="getPrenomErrors()"
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="6">
                                <v-row align="center">
                                    <v-col>
                                        <v-select
                                            v-model="formInscrit.genre"
                                            :items="genreOptions"
                                            item-title="text"
                                            item-value="value"
                                            label="Genre*"
                                            variant="outlined"
                                            :error="!validationGenre && !champsObligatoires.genre"
                                            :error-messages="formInscrit.genre === null && !champsObligatoires.genre ? 'Le genre est requis (ou cocher la case ND).' : ''"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-checkbox v-model="champsObligatoires.genre" label="ND" hide-details></v-checkbox>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-row align="center">
                                    <v-col>
                                        <v-text-field
                                            v-model="formInscrit.date_naissance"
                                            label="Date de naissance*"
                                            type="date"
                                            variant="outlined"
                                            min="1950-01-01"
                                            :max="new Date().toISOString().substr(0, 10)"
                                            :error="(!validationNaissance && !champsObligatoires.date_naissance) || validDateNaissance"
                                            :error-messages="getDateNaissanceErrors()"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-checkbox v-model="champsObligatoires.date_naissance" label="ND" hide-details></v-checkbox>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="9">
                                <v-text-field
                                    v-model="formInscrit.rue"
                                    label="Rue"
                                    variant="outlined"
                                    :error="!validationMaxRue"
                                    :error-messages="!validationMaxRue ? 'La rue ne peut contenir plus de 190 caractères.' : ''"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field
                                    v-model.number="formInscrit.numero"
                                    label="N°"
                                    type="number"
                                    min="0"
                                    variant="outlined"
                                    :error="formInscrit.numero !== '' && !validationMaxNumero"
                                    :error-messages="formInscrit.numero !== '' && !validationMaxNumero ? 'Le numéro doit être compris entre 1 et 9999.' : ''"
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="3">
                                <v-text-field
                                    v-model="formInscrit.boite"
                                    label="Boite"
                                    variant="outlined"
                                    :error="formInscrit.boite !== '' && !validationMaxBoite"
                                    :error-messages="formInscrit.boite !== '' && !validationMaxBoite ? 'La boite ne peut contenir plus de 10 caractères.' : ''"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="9">
                                <v-row align="center">
                                    <v-col>
                                        <v-select
                                            v-model="formInscrit.ville_id"
                                            :items="villes.villes"
                                            :item-title="ville => ville.code_postal + ' ' + ville.ville"
                                            item-value="id"
                                            label="CP & Ville*"
                                            variant="outlined"
                                            :error="!validationVille && !champsObligatoires.ville_id"
                                            :error-messages="formInscrit.ville_id === null && !champsObligatoires.ville_id ? 'Le code postal et la ville sont requis (ou cocher la case ND).' : ''"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-checkbox v-model="champsObligatoires.ville_id" label="ND" hide-details></v-checkbox>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12" md="8">
                                <v-row align="center">
                                    <v-col>
                                        <v-text-field
                                            v-model="formInscrit.email"
                                            label="Email*"
                                            type="email"
                                            variant="outlined"
                                            :error="getEmailError()"
                                            :error-messages="getEmailErrors()"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="auto" v-show="formInscrit.newsletter !== true">
                                        <v-checkbox v-model="champsObligatoires.email" label="ND" hide-details></v-checkbox>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-select
                                    v-model="formInscrit.newsletter"
                                    :items="[{ text: 'Non', value: false }, { text: 'Oui', value: true }]"
                                    item-title="text"
                                    item-value="value"
                                    label="Newsletter"
                                    variant="outlined"
                                ></v-select>
                            </v-col>
                        </v-row>

                        <v-row class="mt-4">
                            <v-col>
                                <span class="text-caption text-grey font-italic">Tous les champs avec * sont obligatoires</span>
                            </v-col>
                            <v-col cols="auto">
                                <v-btn color="primary" @click="validationStepOne()">Continuer</v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>

                <!-- Spinner -->
                <div v-else class="text-center py-16">
                    <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
                    <div class="mt-4">Chargement...</div>
                </div>
            </template>

            <!-- Step 2: Généralités (suite) -->
            <template v-slot:item.2>
                <v-card flat>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-row align="center">
                                    <v-col cols="4">
                                        <v-text-field
                                            v-model="gsm_national"
                                            label="Préfixe GSM*"
                                            variant="outlined"
                                            placeholder="+32"
                                            maxlength="4"
                                            :error="validationGsm_national === false"
                                            :error-messages="validationGsm_national === false ? 'Le préfixe doit contenir au minimum 3 caractères.' : ''"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col>
                                        <v-text-field
                                            v-model="formInscrit.gsm"
                                            label="GSM*"
                                            variant="outlined"
                                            placeholder="0xxx/xxx.xxx"
                                            maxlength="16"
                                            :error="getGsmError()"
                                            :error-messages="getGsmErrors()"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-checkbox v-model="champsObligatoires.gsm" label="ND" hide-details></v-checkbox>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-row align="center">
                                    <v-col cols="4">
                                        <v-text-field
                                            v-model="tel_national"
                                            label="Préfixe Tél."
                                            variant="outlined"
                                            placeholder="+32"
                                            maxlength="4"
                                            :error="validationTel_national === false"
                                            :error-messages="validationTel_national === false ? 'Le préfixe doit contenir au minimum 3 caractères.' : ''"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col>
                                        <v-text-field
                                            v-model="formInscrit.tel"
                                            label="Tél. fix"
                                            variant="outlined"
                                            placeholder="0xx/xxx.xxx"
                                            maxlength="15"
                                            :error="getTelError()"
                                            :error-messages="getTelErrors()"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-checkbox v-model="champsObligatoires.tel" label="ND" hide-details></v-checkbox>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="12">
                                <v-textarea
                                    v-model="formInscrit.commentaire_inscrit"
                                    label="Commentaire(s)"
                                    variant="outlined"
                                    rows="5"
                                    placeholder="Entrez un commentaire..."
                                ></v-textarea>
                            </v-col>
                        </v-row>

                        <v-row class="mt-4">
                            <v-col>
                                <v-btn color="error" @click="step = 1">Retour</v-btn>
                            </v-col>
                            <v-col class="text-center">
                                <span class="text-caption text-grey font-italic">Tous les champs avec * sont obligatoires</span>
                            </v-col>
                            <v-col class="text-right">
                                <v-btn color="success" @click="storeCandidat()">Créer Candidat</v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </template>
        </v-stepper>
    </v-container>
</template>

<script>
import { Form } from "vform";
import moment from 'moment';

export default {
    name: "CreateInscritWithRecrutement",
    data() {
        return {
            loading: false,
            step: 1,
            tel_national: '+32',
            gsm_national: '+32',
            formInscrit: new Form({
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
                tel: '',
                gsm: '',
                commentaire_inscrit: '',
                newsletter: false,
                prospect: 0,
                tags: [],
                recrutement: null,
                formation: null,
                current_user: null,
            }),
            champsObligatoires: {
                'genre': false,
                'date_naissance': false,
                'ville_id': false,
                'email': false,
                'tel': true,
                'gsm': false,
            },
            yearsAgo: moment().subtract(15, 'years').format('YYYY'),
            villes: { villes: [] },
            recrutement: [],
            genreOptions: [
                { text: 'Choisissez', value: null },
                { text: 'Homme', value: 'homme' },
                { text: 'Femme', value: 'femme' },
                { text: '3e genre', value: '3e genre' }
            ],
        }
    },
    watch: {
        checkNewsletter: function(newValue) {
            if (this.champsObligatoires.email === true) {
                this.champsObligatoires.email = newValue;
            }
        }
    },
    mounted() {
        console.log('CreateInscritWithRecrutement component mounted');
        this.getDatas();
    },
    computed: {
        validationMinNom() {
            return this.formInscrit.nom.length > 1;
        },
        validationMaxNom() {
            return this.formInscrit.nom.length < 190;
        },
        validationMinPrenom() {
            return this.formInscrit.prenom.length > 1;
        },
        validationMaxPrenom() {
            return this.formInscrit.prenom.length < 190;
        },
        validationGenre() {
            if (this.champsObligatoires.genre === false) {
                return this.formInscrit.genre != null;
            }
            return true;
        },
        validationNaissance() {
            if (this.champsObligatoires.date_naissance === false) {
                return this.formInscrit.date_naissance !== '';
            }
            return true;
        },
        formattedDateNaissance() {
            return this.formInscrit.date_naissance ? moment(this.formInscrit.date_naissance).format('L') : '';
        },
        validDateNaissance() {
            if (this.formInscrit.date_naissance !== null && this.formInscrit.date_naissance.length > 0) {
                return moment().diff(moment(this.formInscrit.date_naissance), 'years', true) < 15;
            }
            return false;
        },
        validationMaxRue() {
            return this.formInscrit.rue.length < 190;
        },
        validationMaxNumero() {
            return Number(this.formInscrit.numero) > 0 && Number(this.formInscrit.numero) < 10000;
        },
        validationMaxBoite() {
            return this.formInscrit.boite.length <= 10;
        },
        validationVille() {
            if (this.champsObligatoires.ville_id === false) {
                return this.formInscrit.ville_id != null;
            }
            return true;
        },
        validationEmail() {
            if (this.champsObligatoires.email === false) {
                return this.formInscrit.email !== '';
            }
            return true;
        },
        checkEmail() {
            if (this.formInscrit.email !== '') {
                return this.validEmail(this.formInscrit.email);
            }
            return true;
        },
        validationMaxEmail() {
            return this.formInscrit.email.length < 190;
        },
        checkNewsletter() {
            if (this.formInscrit.newsletter === true) {
                return this.formInscrit.email !== '';
            }
            return true;
        },
        validationGsm_national() {
            if (this.champsObligatoires.gsm === false && this.gsm_national !== '') {
                return this.gsm_national.length > 2 && this.gsm_national.length < 5;
            }
            return true;
        },
        checkGsm() {
            if (this.champsObligatoires.gsm === false) {
                return this.formInscrit.gsm !== '';
            }
            return true;
        },
        validationMinGsm() {
            if (this.champsObligatoires.gsm === false && this.formInscrit.gsm !== '') {
                return this.formInscrit.gsm.length > 8;
            }
            return true;
        },
        validationMaxGsm() {
            if (this.champsObligatoires.gsm === false && this.formInscrit.gsm !== '') {
                return this.formInscrit.gsm.length < 17;
            }
            return true;
        },
        validationTel_national() {
            if (this.champsObligatoires.tel === false && this.tel_national !== '') {
                return this.tel_national.length > 2 && this.tel_national.length < 5;
            }
            return true;
        },
        checkTel() {
            if (this.champsObligatoires.tel === false) {
                return this.formInscrit.tel !== '';
            }
            return true;
        },
        validationMinTel() {
            if (this.champsObligatoires.tel === false && this.formInscrit.tel !== '') {
                return this.formInscrit.tel.length > 7;
            }
            return true;
        },
        validationMaxTel() {
            if (this.champsObligatoires.tel === false && this.formInscrit.tel !== '') {
                return this.formInscrit.tel.length < 16;
            }
            return true;
        },
    },
    methods: {
        formatDate(date) {
            if (!date) return '';
            return moment(date).format('DD/MM/YYYY');
        },
        getNomErrors() {
            if (this.formInscrit.nom === '') return 'Le nom est requis.';
            if (!this.validationMinNom) return "Le nom doit être composé d'au moins 2 lettres.";
            if (!this.validationMaxNom) return 'Le nom ne peut contenir plus de 190 caractères.';
            return '';
        },
        getPrenomErrors() {
            if (this.formInscrit.prenom === '') return 'Le prénom est requis.';
            if (!this.validationMinPrenom) return "Le prénom doit être composé d'au moins 2 lettres.";
            if (!this.validationMaxPrenom) return 'Le prénom ne peut contenir plus de 190 caractères.';
            return '';
        },
        getDateNaissanceErrors() {
            if (this.formInscrit.date_naissance === '' && !this.champsObligatoires.date_naissance) {
                return 'La date de naissance est requise (ou cocher la case ND).';
            }
            if (this.validDateNaissance) {
                return `La date de naissance ne peut être une date future ou datant après ${this.yearsAgo}.`;
            }
            return '';
        },
        getEmailError() {
            return (!this.validationEmail && !this.champsObligatoires.email) ||
                   (!this.checkEmail && !this.champsObligatoires.email) ||
                   !this.validationMaxEmail ||
                   !this.checkNewsletter;
        },
        getEmailErrors() {
            if (this.formInscrit.email === '' && !this.champsObligatoires.email && this.checkNewsletter) {
                return "L'email est requis (ou cocher la case ND).";
            }
            if (!this.checkEmail && !this.champsObligatoires.email && this.formInscrit.email !== '') {
                return "L'email n'est pas valide.";
            }
            if (!this.validationMaxEmail) {
                return "L'email ne peut contenir plus de 190 caractères.";
            }
            if (!this.checkNewsletter) {
                return "L'email est requis pour la newsletter.";
            }
            return '';
        },
        getGsmError() {
            return (!this.checkGsm && !this.champsObligatoires.gsm) ||
                   (!this.validationMinGsm && !this.champsObligatoires.gsm) ||
                   !this.validationMaxGsm;
        },
        getGsmErrors() {
            if (!this.checkGsm && !this.champsObligatoires.gsm) {
                return 'Le numéro de gsm est requis (ou cocher la case ND).';
            }
            if (this.formInscrit.gsm !== '' && !this.validationMinGsm) {
                return 'Le numéro de gsm doit contenir au minimum 9 caractères.';
            }
            if (this.formInscrit.gsm !== '' && !this.validationMaxGsm) {
                return 'Le numéro de gsm doit contenir au maximum 16 caractères.';
            }
            return '';
        },
        getTelError() {
            return (!this.checkTel && !this.champsObligatoires.tel) ||
                   (!this.validationMinTel && !this.champsObligatoires.tel) ||
                   !this.validationMaxTel;
        },
        getTelErrors() {
            if (!this.checkTel && !this.champsObligatoires.tel) {
                return 'Le numéro de tél. fix est requis (ou cocher la case ND).';
            }
            if (this.formInscrit.tel !== '' && !this.validationMinTel) {
                return 'Le numéro de tél. fix doit contenir au minimum 9 caractères.';
            }
            if (this.formInscrit.tel !== '' && !this.validationMaxTel) {
                return 'Le numéro de tél. fix doit contenir au maximum 16 caractères.';
            }
            return '';
        },
        retour() {
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Si vous continuez, vous retournez à la fiche du recrutement!",
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
        validEmail(email) {
            let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        validation(test, message) {
            if (test) {
                Snackbar.fire(message);
                return true;
            }
            return false;
        },
        getDatas() {
            this.loading = false;
            this.getRecrutement();
            this.getVilles();
            this.currentUser();
        },
        getRecrutement() {
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
                    });
                });
        },
        getVilles() {
            axios.get('api/villes/')
                .then(response => {
                    this.villes = response.data;
                    this.loading = true;
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire({
                        title: 'Problème avec la récupération de la liste des villes',
                        timer: undefined,
                    });
                });
        },
        currentUser() {
            this.formInscrit.current_user = this.$store.getters.currentUser.id;
        },
        validationStepOne() {
            if (this.validation(this.formInscrit.nom === '', "Vous n'avez pas rempli le nom !")) {
            } else if (this.validation(this.formInscrit.prenom === '', "Vous n'avez pas rempli le prénom !")) {
            } else if (this.validation(this.champsObligatoires.nom === true && this.champsObligatoires.prenom === true, "Il faut au moins le nom ou le prénom !")) {
            } else if (this.validation(this.formInscrit.nom.length < 2, "Le nom doit être composé d'au moins 2 lettres !")) {
            } else if (this.validation(this.formInscrit.prenom.length < 2, "Le prénom doit être composé d'au moins 2 lettres !")) {
            } else if (this.validation(this.formInscrit.nom !== '' && !this.validationMaxNom, "Le champ nom ne peut contenir plus de 190 caractères !")) {
            } else if (this.validation(this.formInscrit.prenom !== '' && !this.validationMaxPrenom, "Le champ prénom ne peut contenir plus de 190 caractères !")) {
            } else if (this.validation(this.formInscrit.genre == null && this.champsObligatoires.genre === false, "Vous n'avez pas rempli le genre ou cochez la case 'non disponible' (ND) correspondante !")) {
            } else if (this.validation(this.formInscrit.date_naissance === '' && this.champsObligatoires.date_naissance === false, "Vous n'avez pas rempli la date de naissance ou cochez la case 'non disponible' (ND) correspondante !")) {
            } else if (this.validation(this.formInscrit.date_naissance !== '' && this.validDateNaissance, "La date de naissance ne peut être une date future ou datant après " + this.yearsAgo + " !")) {
            } else if (this.validation(this.formInscrit.rue !== '' && !this.validationMaxRue, "Le champ rue ne peut contenir plus de 190 caractères !")) {
            } else if (this.validation(this.formInscrit.numero !== '' && Number(this.formInscrit.numero) <= 0, 'Le numéro doit être positif et non nul !')) {
            } else if (this.validation(this.formInscrit.numero !== '' && Number(this.formInscrit.numero) > 9999, 'Le numéro doit être inférieur à 10000 !')) {
            } else if (this.validation(this.formInscrit.boite !== '' && !this.validationMaxBoite, "Le champ boite ne peut contenir plus de 10 caractères !")) {
            } else if (this.validation(this.formInscrit.ville_id == null && this.champsObligatoires.ville_id === false, "Vous n'avez pas rempli la ville ou cochez la case 'non disponible' (ND) correspondante !")) {
            } else if (this.validation(this.formInscrit.email === '' && this.champsObligatoires.email === false && this.checkNewsletter === true, "Vous n'avez pas rempli l'email ou cochez la case 'non disponible' (ND) correspondante !")) {
            } else if (this.validation(this.formInscrit.email !== '' && this.validEmail(this.formInscrit.email) === false, "Vous n'avez pas renseigné un email valide !")) {
            } else if (this.validation(this.formInscrit.email !== '' && !this.validationMaxEmail, "Le champ email ne peut contenir plus de 190 caractères !")) {
            } else if (this.validation(this.checkNewsletter === false, "L'email est requis pour la newsletter !")) {
            } else {
                if (this.formInscrit.ville_id == null) {
                    this.formInscrit.ville_id = 2766;
                }
                this.step = 2;
            }
        },
        storeCandidat() {
            if (this.validation(this.champsObligatoires.gsm === false && (this.gsm_national === '' || this.formInscrit.gsm === '' || this.gsm_national === null || this.formInscrit.gsm === null), "Vous n'avez pas rempli tous les champs du n° de gsm ou cochez la case 'non disponible' (ND) correspondante !")) {
            } else if (this.validation(this.champsObligatoires.gsm === false && (this.gsm_national.length < 3 || this.formInscrit.gsm.length < 9), "Il n'y pas le minimum de caractères requis dans un des champs du n° de gsm !")) {
            } else if (this.validation(this.champsObligatoires.tel === false && (this.tel_national === '' || this.formInscrit.tel === '' || this.tel_national === null || this.formInscrit.tel === null), "Vous n'avez pas rempli le tél. fix ou cochez la case 'non disponible' (ND) correspondante !")) {
            } else if (this.validation(this.champsObligatoires.tel === false && (this.tel_national.length < 3 || this.formInscrit.tel.length < 8), "Il n'y pas le minimum de caractères requis dans un des champs du n° de tél. fix !")) {
            } else {
                this.$Progress.start();
                this.formInscrit.recrutement = this.recrutement.id;
                if (this.formInscrit.tel !== null && this.formInscrit.tel !== '') {
                    let telTab = this.formInscrit.tel.split('');
                    if (telTab[0] == 0) {
                        telTab.splice(0, 0, '(');
                        telTab.splice(2, 0, ')');
                        this.formInscrit.tel = telTab.join('');
                    } else if (telTab[0] !== '(' && telTab[1] !== '0' && telTab[2] !== ')') {
                        telTab.splice(0, 0, '(0)');
                        this.formInscrit.tel = telTab.join('');
                    }
                    this.formInscrit.tel = this.tel_national + this.formInscrit.tel;
                }
                if (this.formInscrit.gsm !== null && this.formInscrit.gsm !== '') {
                    let gsmTab = this.formInscrit.gsm.split('');
                    if (gsmTab[0] == 0) {
                        gsmTab.splice(0, 0, '(');
                        gsmTab.splice(2, 0, ')');
                        this.formInscrit.gsm = gsmTab.join('');
                    } else if (gsmTab[0] !== '(' && gsmTab[1] !== '0' && gsmTab[2] !== ')') {
                        gsmTab.splice(0, 0, '(0)');
                        this.formInscrit.gsm = gsmTab.join('');
                    }
                    this.formInscrit.gsm = this.gsm_national + this.formInscrit.gsm;
                }
                this.formInscrit
                    .post('api/inscrits/create')
                    .then(response => {
                        if (this.formInscrit.successful) {
                            this.$Progress.finish();
                            if (response.data.message && response.data.message === 'Personne existante !') {
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
                        this.$Progress.fail();
                        console.error(error.response);
                        Snackbar.fire('Problème avec la création du candidat');
                    });
            }
        },
    }
}
</script>

<style scoped>
</style>
