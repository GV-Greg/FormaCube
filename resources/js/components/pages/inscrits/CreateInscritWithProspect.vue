<template>
    <div class="container">
        <h1 class="d-flex align-content-center">
            <button class="btn btn-light pb-2 mr-2" @click="retour">
                <i class="fas fa-reply fa-lg text-primary-dark"></i>
            </button>
            <span class="d-flex align-baseline">Nouvelle inscription <span id="sous-titre" class="pl-2">en tant que prospect</span></span>
        </h1>
        <div class="row mt-2">
            <v-stepper v-model="step" class="w-100">
                <v-stepper-header>
                    <v-stepper-step :complete="step > 1" step="1">Généralités</v-stepper-step>

                    <v-divider class="light"></v-divider>

                    <v-stepper-step :complete="step > 2" step="2">Généralités (suite)</v-stepper-step>

                    <v-divider></v-divider>

                    <v-stepper-step step="3">Choix Tags</v-stepper-step>
                </v-stepper-header>
                <v-stepper-items class="pb-1">
                    <!-- Contenu partie 1 : les généralités -->
                    <v-stepper-content step="1">
                        <div class="step-content mb-3" v-if="loading === true">
                            <form>
                                <div class="row row-cols-2">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Nom*">
                                            <b-form-input v-model="formInscrit.nom" type="text" :state="validationMinNom"
                                                          class="rounded-right" :class="{ 'is-invalid': formInscrit.errors.has('nom') || validationMaxNom === false }"
                                                          name="nom" id="nom" placeholder="Nom">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="nom-feedback" v-show="formInscrit.nom === ''">
                                                Le nom est requis.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="nom-min-feedback" v-show="formInscrit.nom !== '' && validationMinNom === false">
                                                Le nom doit &ecirc;tre compos&eacute; d'au moins 2 lettres.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="nom-max-feedback" v-show="formInscrit.nom !== '' && validationMaxNom === false">
                                                Le nom ne peut contenir plus de 190 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formInscrit" field="nom"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Prénom*">
                                            <b-form-input v-model="formInscrit.prenom" type="text" :state="validationMinPrenom"
                                                          class="rounded-right" :class="{ 'is-invalid': formInscrit.errors.has('prenom') || validationMaxPrenom === false }"
                                                          name="prenom" id="prenom" placeholder="Prénom">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="prenom-feedback" v-show="formInscrit.prenom === ''">
                                                Le prénom est requis.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="prenom-min-feedback" v-show="formInscrit.prenom !== '' && validationMinPrenom === false">
                                                Le prénom doit &ecirc;tre compos&eacute; d'au moins 2 lettres.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="prenom-max-feedback" v-show="formInscrit.prenom !== '' && validationMaxPrenom === false">
                                                Le prénom ne peut contenir plus de 190 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formInscrit" field="prenom"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row row-cols-2 mt-n3">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Genre*">
                                            <b-form-select v-model="formInscrit.genre" :state="validationGenre"
                                                           :class="{ 'is-invalid': formInscrit.errors.has('genre') }"
                                                           name="genre" id="genre">
                                                <b-form-select-option :value="null">Choisissez</b-form-select-option>
                                                <b-form-select-option value="homme">Homme</b-form-select-option>
                                                <b-form-select-option value="femme">Femme</b-form-select-option>
                                                <b-form-select-option value="3e genre">3e genre</b-form-select-option>
                                            </b-form-select>
                                            <b-input-group-append is-text>
                                                <b-form-checkbox v-model="champsObligatoires.genre" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                            </b-input-group-append>
                                            <b-form-invalid-feedback id="genre-feedback" v-show="formInscrit.genre === null && champsObligatoires.genre === false">
                                                Le genre est requis (ou cocher la case "ND").
                                            </b-form-invalid-feedback>
                                            <has-error :form="formInscrit" field="genre"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                            <b-input-group-prepend>
                                                <b-input-group-text class="text-light-interface font-weight-bold">Date de naissance*</b-input-group-text>
                                                <b-form-datepicker
                                                    v-model="formInscrit.date_naissance" locale="fr"
                                                    min="1950-01-01" :max="new Date().toISOString().substr(0, 10)"
                                                    button-only left
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
                                            <b-input-group-append is-text>
                                                <b-form-checkbox v-model="champsObligatoires.date_naissance" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                            </b-input-group-append>
                                            <b-form-invalid-feedback id="date-naissance-feedback" v-show="formInscrit.date_naissance === '' && champsObligatoires.date_naissance === false">
                                                La date de naissance est requise (ou cocher la case "ND").
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="verif-date-naissance-feedback" v-show="!validDateNaissance === false">
                                                La date de naissance ne peut être une date future ou datant après {{ yearsAgo }}.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formInscrit" field="date_naissance"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row row-cols-2 mt-n4">
                                    <div class="col col-lg-9">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Rue">
                                            <b-form-input v-model="formInscrit.rue" type="text"
                                                          class="rounded-right" :class="{ 'is-invalid': formInscrit.errors.has('rue') || validationMaxRue === false }"
                                                          name="rue" id="rue" placeholder="Rue">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="rue-max-feedback" v-show="validationMaxRue === false">
                                                La rue ne peut contenir plus de 190 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formInscrit" field="rue"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col col-lg-3">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="N°">
                                            <b-form-input v-model.number="formInscrit.numero" type="number" min="0"
                                                          class="rounded-right" :class="{ 'is-invalid': formInscrit.errors.has('numero') || (formInscrit.numero !== '' && validationMaxNumero === false) }"
                                                          name="numero" id="numero" placeholder="Numéro">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="numero-feedback" v-show="formInscrit.numero !== '' && validationMaxNumero === false">
                                                Le num&eacute;ro doit être compris entre 1 et 9999.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formInscrit" field="numero"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row row-cols-2">
                                    <div class="col col-lg-3">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Boite">
                                            <b-form-input v-model="formInscrit.boite" type="text"
                                                          class="rounded-right" :class="{ 'is-invalid': formInscrit.errors.has('boite') || validationMaxBoite === false }"
                                                          name="boite" id="boite" placeholder="Boite">
                                            </b-form-input>
                                            <b-form-invalid-feedback id="boite-max-feedback" v-show="formInscrit.boite !== '' && validationMaxBoite === false">
                                                La boite ne peut contenir plus de 10 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formInscrit" field="boite"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col col-lg-9">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="CP & Ville*">
                                            <b-form-select v-model="formInscrit.ville_id" :state="validationVille"
                                                           :class="{ 'is-invalid': formInscrit.errors.has('ville_id') }"
                                                           name="ville_id" id="ville_id">
                                                <b-form-select-option :value="null">Choisissez</b-form-select-option>
                                                <b-form-select-option v-for="ville in villes.villes" :key="ville.id" :value="ville.id">{{ ville.code_postal + ' ' + ville.ville }}</b-form-select-option>
                                            </b-form-select>
                                            <b-input-group-append is-text>
                                                <b-form-checkbox v-model="champsObligatoires.ville_id" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                            </b-input-group-append>
                                            <b-form-invalid-feedback id="boite-max-feedback" v-show="formInscrit.ville_id === null && champsObligatoires.ville_id === false">
                                                Le code postal et la ville sont requis (ou cocher la case "ND").
                                            </b-form-invalid-feedback>
                                            <has-error :form="formInscrit" field="ville_id"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row row-cols-2">
                                    <div class="col col-lg-8">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Email*">
                                            <b-form-input v-model="formInscrit.email" :state="(validationEmail && checkEmail && validationMaxEmail) && (checkNewsletter && checkEmail && validationMaxEmail)" type="email"
                                                          :class="{ 'is-invalid': formInscrit.errors.has('email') || validationMaxEmail === false, 'rounded-r': formInscrit.newsletter === true }"
                                                          name="email" id="email" placeholder="Email">
                                            </b-form-input>
                                            <b-input-group-append is-text v-show="formInscrit.newsletter !== true">
                                                <b-form-checkbox v-model="champsObligatoires.email" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                            </b-input-group-append>
                                            <b-form-invalid-feedback id="email-feedback" v-show="formInscrit.email === '' && checkNewsletter !== false && champsObligatoires.email === false">
                                                L'email est requis (ou cocher la case "ND").
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="email-valide-feedback" v-show="champsObligatoires.email !== true && checkEmail === false">
                                                L'email n'est pas valide.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="email-max-feedback" v-show="champsObligatoires.email !== true && validationMaxEmail === false">
                                                L'email ne peut contenir plus de 190 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="email-news-feedback" v-show="checkNewsletter === false">
                                                L'email est requis pour la newsletter.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formInscrit" field="email"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col col-lg-4">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Newsletter">
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
                            </form>
                            <div class="mt-3 d-flex justify-content-between">
                                <span class="font-weight-light font-italic text-primary-dark mt-2"><small>Tous les champs avec * sont obligatoires</small></span>
                                <v-btn class="btn-primary" @click="validationStepOne()">Continuer</v-btn>
                            </div>
                        </div>
                        <Spinner v-else />
                    </v-stepper-content>
                    <!-- Contenu partie 2 : Généralites (suite) -->
                    <v-stepper-content step="2">
                        <div class="step-content mb-6">
                            <form>
                                <div class="row">
                                    <div class="col col-lg-6">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="GSM*">
                                            <b-form-input type="tel" v-model="gsm_national" :state="validationGsm_national"
                                                          name="gsm_national" id="gsm_national" placeholder="+32"
                                                          pattern="[\+]\d{2}" style="max-width:85px;" maxlength="4"
                                                          :class="{ 'is-invalid': validationGsm_national === false }">
                                            </b-form-input>
                                            <b-form-input type="tel" v-model="formInscrit.gsm" :state="checkGsm && validationMinGsm && validationMaxGsm"
                                                          name="gsm" id="gsm" placeholder="0xxx/xxx.xxx"
                                                          pattern="([\/\.\s\(\)]?[0-9]){9,16}" maxlength="16"
                                                          :class="{ 'is-invalid': formInscrit.errors.has('gsm') || validationMaxGsm === false }">
                                            </b-form-input>
                                            <b-input-group-append is-text>
                                                <b-form-checkbox v-model="champsObligatoires.gsm" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                            </b-input-group-append>
                                            <b-form-invalid-feedback id="prefix-gsm-feedback" v-show="validationGsm_national === false">
                                                Le préfixe doit contenir au minimum 3 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="gsm-feedback" v-show="checkGsm === false && champsObligatoires.gsm === false">
                                                Le numéro de gsm est requis (ou cocher la case "ND").
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="gsm-min-feedback" v-show="formInscrit.gsm !== '' && validationMinGsm === false">
                                                Le numéro de gsm doit contenir au minimum 9 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="gsm-max-feedback" v-show="formInscrit.gsm !== '' && validationMaxGsm === false">
                                                Le numéro de gsm doit contenir au maximum 16 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formInscrit" field="gsm_national"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col col-lg-6">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" prepend="Tél. fix">
                                            <b-form-input type="tel" v-model="tel_national" :state="validationTel_national"
                                                          name="tel_national" id="tel_national" placeholder="+32"
                                                          pattern="[\+]\d{2}" style="max-width:85px;" maxlength="4"
                                                          :class="{ 'is-invalid': validationTel_national === false }">
                                            </b-form-input>
                                            <b-form-input type="tel" v-model="formInscrit.tel" :state="checkTel && validationMinTel && validationMaxTel"
                                                          name="tel" id="tel" placeholder="0xx/xxx.xxx"
                                                          pattern="([\/\.\s\(\)]?[0-9]){8,15}" maxlength="15"
                                                          :class="{ 'is-invalid': formInscrit.errors.has('tel') || validationMaxTel === false }">
                                            </b-form-input>
                                            <b-input-group-append is-text>
                                                <b-form-checkbox v-model="champsObligatoires.tel" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                            </b-input-group-append>
                                            <b-form-invalid-feedback id="prefix-tel-feedback" v-show="validationTel_national === false">
                                                Le préfixe doit contenir au minimum 3 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="tel-feedback" v-show="checkTel === false && champsObligatoires.tel === false">
                                                Le numéro de tél. fix est requis (ou cocher la case "ND").
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="tel-min-feedback" v-show="formInscrit.tel !== '' && validationMinTel === false">
                                                Le numéro de tél. fix doit contenir au minimum 9 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <b-form-invalid-feedback id="tel-max-feedback" v-show="formInscrit.tel !== '' && validationMaxTel === false">
                                                Le numéro de tél. fix doit contenir au maximum 16 caract&egrave;res.
                                            </b-form-invalid-feedback>
                                            <has-error :form="formInscrit" field="gsm_national"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
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
                                </div>
                            </form>
                        </div>
                        <div class="mt-3 d-flex justify-content-between">
                            <v-btn class="btn-danger" @click="step = 1">Retour</v-btn>
                            <span class="font-weight-light font-italic text-primary-dark mt-2"><small>Tous les champs avec * sont obligatoires</small></span>
                            <v-btn class="btn-primary" @click="goToStepThree()">Continuer</v-btn>
                        </div>
                    </v-stepper-content>
                    <!-- Contenu partie 3 : Choix des tags -->
                    <v-stepper-content step="3">
                        <div class="step-content mb-6">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <b-form-group label="Sélectionner les centres d'intérêts dans la liste déroulante">
                                            <b-form-tags input-id="tags" v-model="inscritTags" size="lg" add-on-change no-outer-focus>
                                                <template v-slot="{ tags, inputAttrs, inputHandlers, disabled, removeTag }">
                                                    <ul v-if="tags.length > 0" class="list-inline d-inline-block mb-1">
                                                        <li v-for="tag in tags" :key="tag" class="list-inline-item">
                                                            <b-form-tag
                                                                @remove="removeTag(tag)"
                                                                :title="tag"
                                                                :disabled="disabled"
                                                                variant="success"
                                                                class="mb-1"
                                                            >{{ tag }}</b-form-tag>
                                                        </li>
                                                    </ul>
                                                    <b-form-select v-bind="inputAttrs" v-on="inputHandlers" class="mt-1">
                                                        <template v-slot:first>
                                                            <!-- This is required to prevent bugs with Safari -->
                                                            <option disabled value="">Choisissez un tag...</option>
                                                            <option v-for="tag in listTags" :key="tag.id" :value="tag.tag">{{ tag.tag }}</option>
                                                        </template>
                                                    </b-form-select>
                                                </template>
                                            </b-form-tags>
                                        </b-form-group>
                                    </div>
                                    <div class="col">
                                        <b-form-group label="Si vous ne trouvez pas le centre d'intérêt dans la liste déroulante, créez-le :">
                                            <b-form-tags
                                                input-id="tags"
                                                v-model="newTags"
                                                :tag-validator="validator"
                                                tag-variant="success"
                                                tag-pills
                                                size="md"
                                                separator=",."
                                                @tag-state="onTagState"
                                                placeholder="Entrez un new tag séparé d'une virgule ou d'un point"
                                            ></b-form-tags>
                                            <template #description>
                                                <div id="tags-validation-help">
                                                    Un nouveau tag doit être compris entre 3 et 50 caractères.<br>Séparer les nouveaux tags par une virgule ou un point ou appuyer sur Add.
                                                </div>
                                            </template>
                                        </b-form-group>
                                        <v-btn class="btn-primary" @click="createTags()">Créer les tags</v-btn>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-between">
                            <v-btn class="btn-danger" @click="step = 2">Retour</v-btn>
                            <v-btn class="btn-success" @click="storeInscrit()">Cr&eacute;er Prospect</v-btn>
                        </div>
                    </v-stepper-content>
                </v-stepper-items>
            </v-stepper>
        </div>
    </div>
</template>

<script>
    import {Form} from "vform";
    import moment from 'moment';
    import Spinner from "../../elements/SpinnerStepper";

    export default {
        name: "CreateInscrit",
        components: {
            Spinner,
        },
        data() {
            return {
                loading: false,
                step: 1,
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
                    tel: '',
                    gsm: '',
                    commentaire_inscrit: '',
                    newsletter: false,
                    prospect: 1,
                    tags: [],
                    recrutement: null,
                    formation: null,
                    current_user: null,
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
                inscrit: [],
                listTags: [],
                newTags: [],
                inscritTags : [],
                formTag : new Form({
                    tag: null,
                }),
                test_tag: 0,
                tabTags: [],
            }
        },
        watch: {
            choix_tag: function(newTag) {
                this.newTags.push(newTag);
                this.choix_tag = null;
            },
            checkNewsletter: function(newValue) {
                if(this.champsObligatoires.email === true) {
                    this.champsObligatoires.email = newValue;
                }
            }
        },
        mounted() {
            console.log('CreateInscrit component mounted');
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
                return this.formInscrit.rue.length < 190;
            },
            validationMaxNumero() {
                return Number(this.formInscrit.numero) > 0 && Number(this.formInscrit.numero) < 10000;
            },
            validationMaxBoite() {
                return this.formInscrit.boite.length <= 10;
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
                return this.formInscrit.email.length < 190;
            },
            checkNewsletter() {
                if(this.formInscrit.newsletter === true) {
                    return this.formInscrit.email !== '';
                } else {
                    return true;
                }
            },
            validationGsm_national() {
                if(this.champsObligatoires.gsm === false && this.gsm_national !== '') {
                    return this.gsm_national.length > 2 && this.gsm_national.length < 5;
                }
            },
            checkGsm() {
                if(this.champsObligatoires.gsm === false) {
                    return this.formInscrit.gsm !== '';
                }
            },
            validationMinGsm() {
                if(this.champsObligatoires.gsm === false && this.formInscrit.gsm !== '') {
                    return this.formInscrit.gsm.length > 8;
                }
            },
            validationMaxGsm() {
                if(this.champsObligatoires.gsm === false && this.formInscrit.gsm !== '') {
                    return this.formInscrit.gsm.length < 17;
                }
            },
            validationTel_national() {
                if(this.champsObligatoires.tel === false && this.tel_national !== '') {
                    return this.tel_national.length > 2 && this.tel_national.length < 5;
                }
            },
            checkTel() {
                if(this.champsObligatoires.tel === false) {
                    return this.formInscrit.tel !== '';
                }
            },
            validationMinTel() {
                if(this.champsObligatoires.tel === false && this.formInscrit.tel !== '') {
                    return this.formInscrit.tel.length > 7;
                }
            },
            validationMaxTel() {
                if(this.champsObligatoires.tel === false && this.formInscrit.tel !== '') {
                    return this.formInscrit.tel.length < 16;
                }
            },
        },
        methods: {
            retour() {
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Si vous continuez, vous retournez à la liste des inscrits !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3CB521',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<strong>RETOUR</strong>'
                }).then((result) => {
                    if (result.value) {
                        this.$router.push('/prospects')
                    }
                });
            },
            getDatas() {
                this.loading = false;
                this.getVilles();
                this.getListTags();
                this.currentUser();
                this.loading = true;
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
            currentUser() {
                this.formInscrit.current_user = this.$store.getters.currentUser.id;
            },
            onContext(ctx) {
                // The date formatted in the locale, or the `label-no-date-selected` string
                this.formatted = ctx.selectedFormatted
                // The following will be an empty string until a valid date is entered
                this.selected = ctx.selectedYMD
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
            getListTags() {
                axios.get('api/tags/all')
                    .then(response => {
                        this.listTags = response.data.tags;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des tags',
                            timer: undefined,
                        })
                    })
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
                } else {
                    if(this.formInscrit.ville_id == null) {
                        this.formInscrit.ville_id = 2766
                    }
                    this.step = 2;
                }
            },
            goToStepThree() {
                if(this.validation(this.champsObligatoires.gsm === false && (this.gsm_national === '' || this.formInscrit.gsm === '' || this.gsm_national === null || this.formInscrit.gsm === null), "Vous n'avez pas rempli tous les champs du n° de gsm ou cochez la case 'non disponible' (ND) correspondante !")) {
                } else if(this.validation(this.champsObligatoires.gsm === false && (this.gsm_national.length < 3 || this.formInscrit.gsm.length < 9 ), "Il n'y pas le minimum de caractères requis dans un des champs du n° de gsm !")) {
                } else if(this.validation(this.champsObligatoires.tel === false && (this.tel_national === '' || this.formInscrit.tel === '' || this.tel_national === null || this.formInscrit.tel === null), "Vous n'avez pas rempli le tél. fix ou cochez la case 'non disponible' (ND) correspondante !")) {
                } else if(this.validation(this.champsObligatoires.tel === false && (this.tel_national.length < 3 || this.formInscrit.tel.length < 8 ), "Il n'y pas le minimum de caractères requis dans un des champs du n° de tél. fix !")) {
                } else {
                    this.step = 3;
                }
            },
            onTagState(valid, invalid, duplicate) {
                this.validTags = valid
                this.invalidTags = invalid
                this.duplicateTags = duplicate
            },
            validator(tag) {
                return tag.length > 2 && tag.length < 50
            },
            createTags() {
                if (this.validation(this.newTags.length < 1, 'Pas de nouveau tag à créer !')) {
                } else {
                    for(let x = 0; x < this.newTags.length; x++) {
                        this.test_tag = 0;
                        for(let y = 0; y < this.listTags.length; y++) {
                            if(this.newTags[x] !== this.listTags[y].tag) {
                                this.test_tag++;
                            } else {
                                Snackbar.fire('Tag déjà existant !');
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
                            this.getListTags();
                            this.newTags = [];
                            this.inscritTags.push(this.formTag.tag);
                            this.$Progress.finish();
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.error(error.response);
                        Snackbar.fire('Problème avec la création du tag !');
                    })
            },
            storeInscrit() {
                this.$Progress.start();
                for(let x = 0; x < this.inscritTags.length; x++) {
                    for(let y = 0; y < this.listTags.length; y++) {
                        if(this.inscritTags[x] === this.listTags[y].tag) {
                            this.tabTags.push(this.listTags[y].id);
                        }
                    }
                }
                this.formInscrit.tags = this.inscritTags;
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
                                this.$router.push('/inscrits/show/' + response.data.data.id);
                            }
                        }
                    })
                    .catch(error => {
                        console.error(error.response);
                        this.$Progress.fail();
                        Snackbar.fire('Problème avec l\'inscription du prospect !');
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

    .v-input--selection-controls {
        margin-top: 0 !important;
    }
</style>
