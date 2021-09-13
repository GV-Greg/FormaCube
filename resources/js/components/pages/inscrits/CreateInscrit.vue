<template>
    <div>
        <h1>
            <button class="btn btn-light" @click="retour">
                <i class="fas fa-reply fa-lg text-interface"></i>
            </button>
            Nouvelle inscription
        </h1>
        <v-stepper v-model="step">
            <v-stepper-header>
                <v-stepper-step :complete="step > 1" step="1">Généralités</v-stepper-step>

                <v-divider class="light"></v-divider>

                <v-stepper-step :complete="step > 2" step="2">Généralités (suite)</v-stepper-step>

                <v-divider class="light"></v-divider>

                <v-stepper-step :complete="step > 3" step="3">Choix Inscription</v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step step="4">Finalisation</v-stepper-step>
            </v-stepper-header>
            <v-stepper-items class="pb-1">
                <!-- Contenu partie 1 : les généralités -->
                <v-stepper-content step="1">
                    <div class="step-content mb-3" v-if="loading === true">
                        <form>
                            <div class="row row-cols-2">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Nom*</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model="formInscrit.nom" type="text" :state="validationNom"
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
                                        <b-form-input v-model="formInscrit.prenom" type="text" :state="validationPrenom"
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
                                        <b-form-select v-model="formInscrit.genre" :state="validationGenre"
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
                                                button-only left
                                                aria-controls="date_naissance"
                                                @context="onContext"
                                            ></b-form-datepicker>
                                        </b-input-group-prepend>
                                        <b-form-input
                                            id="date_naissance" type="date" v-model="formInscrit.date_naissance"
                                            :state="validationNaissance" placeholder="YYYY-MM-DD" autocomplete="off"
                                        ></b-form-input>
                                        <has-error :form="formInscrit" field="date_naissance"></has-error>
                                        <b-input-group-append is-text>
                                            <b-form-checkbox v-model="champsObligatoires.date_naissance" class="mb-2 mr-sm-2 mb-sm-0">ND</b-form-checkbox>
                                        </b-input-group-append>
                                    </b-input-group>
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
                        </form>
                        <span class="font-weight-light font-italic text-light-interface mt-2"><small>Tous les champs avec * sont obligatoires</small></span>
                        <div class="text-right">
                            <v-btn class="btn-interface text-light" @click="validationStepOne()">Continuer</v-btn>
                        </div>
                    </div>

                    <div v-else>
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
                    <div class="text-right">
                        <v-btn class="btn btn-danger text-light mt-n5" @click="step = 1">Retour</v-btn>
                        <v-btn class="btn-interface text-light mt-n5" @click="goToStepThree()">Continuer</v-btn>
                    </div>
                </v-stepper-content>
                <!-- Contenu partie 3 : Choix de l'inscription -->
                <v-stepper-content step="3">
                    <div class="step-content mb-6">
                        <div class="row">
                            <div class="col">
                                <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                    <b-input-group-prepend is-text>
                                        <span class="text-light-interface font-weight-bold">&Agrave; inscrire comme :</span>
                                    </b-input-group-prepend>
                                    <b-form-select v-model="choix_inscription" id="choix_inscription">
                                        <b-form-select-option :value="null">Choisissez</b-form-select-option>
                                        <b-form-select-option value="prospect">Prospect</b-form-select-option>
                                        <b-form-select-option value="candidat">Candidat à un recrutement</b-form-select-option>
                                        <b-form-select-option value="stagiaire">Stagiaire à une formation</b-form-select-option>
                                    </b-form-select>
                                </b-input-group>
                            </div>
                        </div>
                        <div class="container" v-if="choix_inscription === 'prospect'">
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
                                    <v-btn class="btn-interface text-light" @click="createTags()">Créer les tags</v-btn>
                                </div>
                            </div>
                        </div>
                        <div class="container" v-if="choix_inscription === 'candidat'">
                            <div class="row" v-if="recrutements != null">
                                <div class="col">
                                    <b-form-group>
                                        <b-form-select v-model="choix_recrutement" id="choix_recrutement" :state="validationRecrutement">
                                            <b-form-select-option :value="null">Choisissez un recrutement...</b-form-select-option>
                                            <b-form-select-option v-for="recrutement in recrutements" :key="recrutement.id" :value="recrutement.id">
                                                {{ recrutement.date | newDate}} - pour la formation : {{ recrutement.formation}}
                                            </b-form-select-option>
                                        </b-form-select>
                                    </b-form-group>
                                </div>
                            </div>
                            <div class="row" v-else>
                                <div class="col">
                                    <div class="alert alert-danger" role="alert">
                                        Aucune futur recrutement prévu.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container" v-if="choix_inscription === 'stagiaire'">
                            <div class="row" v-if="formations != null">
                                <div class="col">
                                    <b-form-group>
                                        <b-form-select v-model="choix_formation" id="choix_formation" :state="validationFormation">
                                            <b-form-select-option :value="null">Choisissez une formation...</b-form-select-option>
                                            <b-form-select-option v-for="(formation, index) in formations" :key="formation.id" :value="formation.id" :disabled="nbreStagiaires[index][0] === nbreStagiaires[index][1]">
                                                {{ formation.nom }}
                                                <span v-show="formation.session !== null"> - {{ formation.session }} </span>
                                                <span> débutant le {{ formation.date_debut | newDate }} - {{ nbreStagiaires[index][0] }} stagiaires</span>
                                                <span v-if="nbreStagiaires[index][0] === nbreStagiaires[index][1]"> - complet</span>
                                            </b-form-select-option>
                                        </b-form-select>
                                    </b-form-group>
                                </div>
                            </div>
                            <div class="row" v-else>
                                <div class="col">
                                    <div class="alert alert-danger" role="alert">
                                        Aucune future formation prévue.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <v-btn class="btn btn-danger text-light" @click="step = 2">Retour</v-btn>
                        <v-btn v-show="choix_inscription === null" class="btn-interface text-light" disabled>Continuer</v-btn>
                        <v-btn v-show="choix_inscription === 'prospect'" class="btn-success text-light" @click="validationStepTag()">Continuer</v-btn>
                        <v-btn v-show="choix_inscription === 'candidat'" class="btn-interface text-light" @click="validationStepRecrutement()">Continuer</v-btn>
                        <v-btn v-show="choix_inscription === 'stagiaire'" class="btn-interface text-light" @click="validationStepFormation()">Continuer</v-btn>
                    </div>
                </v-stepper-content>
                <!-- Contenu partie 4 : Finaliser l'inscription -->
                <v-stepper-content step="4">
                    <div class="step-content mb-6">
                        <div class="container" v-if="choix_inscription === 'prospect'">
                            <div class="row">
                                <div class="col">
                                    <h6>Liste des tags liés au prospect {{ this.formInscrit.prenom + ' ' + this.formInscrit.nom }}</h6>
                                    <v-chip v-for="item in inscritTags" :key="item" class="ma-2" color="success" text-color="white">
                                        <v-avatar left>
                                            <v-icon>mdi-account-circle</v-icon>
                                        </v-avatar>
                                        <div class="pr-1">
                                            {{ item | LowerCase }}
                                        </div>
                                    </v-chip>
                                </div>
                            </div>
                        </div>
                        <div class="container" v-if="choix_inscription === 'candidat'">
                            <div class="row">
                                <div class="col">
                                    <strong>{{ inscrit.prenom}} {{inscrit.nom | upperCase }}</strong> participera au recrutement du {{ recrutementInscrit.date | newDate }} pour la formation "{{ recrutementInscrit.formation }}".
                                </div>
                            </div>
                        </div>
                        <div class="container" v-if="choix_inscription === 'stagiaire'">
                            <h6>Informations supplémentaires pour la formation {{ formation.nom  }}</h6>
                            <form>
                                <div class="row row-cols-3">
                                    <div class="col-3">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                            <b-input-group-prepend is-text>
                                                <span class="text-light-interface font-weight-bold">&Acirc;ge</span>
                                            </b-input-group-prepend>
                                            <b-form-input v-if="formInscrit.date_naissance !== ''" v-model.number="formInscrit.age" type="number"
                                                          class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('age') }"
                                                          name="age" id="age" disabled>
                                            </b-form-input>
                                            <b-form-input v-else v-model.number="formInscrit.age" type="number" min="1" max="999"
                                                          class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('age') || (checkAge === false && validationAge === false)  }"
                                                          name="age" id="age" :maxlength="maxLengthAge"
                                                          oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                            </b-form-input>
                                            <b-input-group-append is-text v-show="pouvsub_infos.age === 1">
                                                <i v-if="formInscrit.age === null || formInscrit.age === '' || (checkAge === false && validationAge === false)"
                                                   class="fas fa-exclamation-triangle fa-lg text-orange font-weight-bold"></i>
                                                <i v-else
                                                   class="fas fa-check fa-lg text-green font-weight-bold"></i>
                                            </b-input-group-append>
                                            <has-error :form="formInscrit" field="age"></has-error>
                                        </b-input-group>
                                        <p v-show="checkAge === false && validationAge === false" class="text-danger small mt-1 mb-n1">Le champ âge doit être sup&eacute;rieur à 0 et inférieur à 999.</p>
                                    </div>
                                    <div class="col-4">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0" v-show="user.role === 'admin' || user.role === 'master'">
                                            <b-input-group-prepend is-text>
                                                <span class="text-light-interface font-weight-bold">N° national</span>
                                            </b-input-group-prepend>
                                            <b-form-input v-model="formInscrit.num_national" type="text"
                                                          class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('num_national') }"
                                                          name="num_national" id="num_national" placeholder="xxxxxx-xxx.xx" maxlength="13">
                                            </b-form-input>
                                            <b-input-group-append is-text v-show="pouvsub_infos.num_national === 1">
                                                <i v-if="formInscrit.num_national === null"
                                                   class="fas fa-exclamation-triangle fa-lg text-orange font-weight-bold"></i>
                                                <i v-else
                                                   class="fas fa-check fa-lg text-green font-weight-bold"></i>
                                            </b-input-group-append>
                                            <has-error :form="formInscrit" field="num_national"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col-5">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                            <b-input-group-prepend is-text>
                                                <span class="text-light-interface font-weight-bold">Statut l&eacute;gal</span>
                                            </b-input-group-prepend>
                                            <b-form-select v-model="formInscrit.statut_legal"
                                                           class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('statut_legal') }"
                                                           name="statut_legal" id="statut_legal">
                                                <b-form-select-option :value="null">Choisissez...</b-form-select-option>
                                                <b-form-select-option value="stage d'attente">Stage d'attente</b-form-select-option>
                                                <b-form-select-option value="Dem. Emploi indemnisé">Dem. Emploi indemnis&eacute;</b-form-select-option>
                                                <b-form-select-option value="Dem. Emploi libre">Dem. Emploi libre</b-form-select-option>
                                                <b-form-select-option value="Rev. d'intégra. sociale">Rev. int&eacute;gra. soc.</b-form-select-option>
                                                <b-form-select-option value="Mutuelle">Mutuelle</b-form-select-option>
                                                <b-form-select-option value="Pensionné">Pensionn&eacute;</b-form-select-option>
                                                <b-form-select-option value="Autres">Autres</b-form-select-option>
                                            </b-form-select>
                                            <b-input-group-append is-text v-show="pouvsub_infos.statut_legal === 1">
                                                <i v-if="formInscrit.statut_legal === null"
                                                   class="fas fa-exclamation-triangle fa-lg text-orange font-weight-bold"></i>
                                                <i v-else
                                                   class="fas fa-check fa-lg text-green font-weight-bold"></i>
                                            </b-input-group-append>
                                            <has-error :form="formInscrit" field="statut_legal"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row row-cols-2" v-show="user.role === 'admin' || user.role === 'master'">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                            <b-input-group-prepend is-text>
                                                <span class="text-light-interface font-weight-bold">Dur&eacute;e de ch&ocirc;mage</span>
                                            </b-input-group-prepend>
                                            <b-form-select v-model="formInscrit.duree_chomage"
                                                           class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('duree_chomage') }"
                                                           name="duree_chomage" id="duree_chomage">
                                                <b-form-select-option :value="null">Choisissez...</b-form-select-option>
                                                <b-form-select-option value="aucun">Pas de ch&ocirc;mage</b-form-select-option>
                                                <b-form-select-option value="-1an">Moins d'1 an</b-form-select-option>
                                                <b-form-select-option value="1-2ans">Entre 1 et 2 ans</b-form-select-option>
                                                <b-form-select-option value="+2ans">Plus de 2 ans</b-form-select-option>
                                            </b-form-select>
                                            <b-input-group-append is-text>
                                                <i v-if="formInscrit.duree_chomage === null"
                                                   class="fas fa-exclamation-triangle fa-lg text-orange font-weight-bold"></i>
                                                <i v-else
                                                   class="fas fa-check fa-lg text-green font-weight-bold"></i>
                                            </b-input-group-append>
                                            <has-error :form="formInscrit" field="duree_chomage"></has-error>
                                        </b-input-group>
                                    </div>
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                            <b-input-group-prepend is-text>
                                                <span class="text-light-interface font-weight-bold">Groupe social</span>
                                            </b-input-group-prepend>
                                            <b-form-select v-model="formInscrit.groupe_social"
                                                           class="form-control" :class="{ 'is-invalid': formInscrit.errors.has('groupe_social') }"
                                                           name="groupe_social" id="groupe_social">
                                                <b-form-select-option :value="null">Choisissez...</b-form-select-option>
                                                <b-form-select-option value="réfugié">Probl&eacute;matique des r&eacute;fugi&eacute;s</b-form-select-option>
                                                <b-form-select-option value="sociale">Probl&eacute;matique sociale</b-form-select-option>
                                                <b-form-select-option value="médicale">Probl&eacute;matique m&eacute;dicale</b-form-select-option>
                                            </b-form-select>
                                            <b-input-group-append is-text v-show="pouvsub_infos.groupe_social === 1">
                                                <i v-if="formInscrit.groupe_social === null"
                                                   class="fas fa-exclamation-triangle fa-lg text-orange font-weight-bold"></i>
                                                <i v-else
                                                   class="fas fa-check fa-lg text-green font-weight-bold"></i>
                                            </b-input-group-append>
                                            <has-error :form="formInscrit" field="groupe_social"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                            <b-input-group-prepend is-text>
                                                <span class="text-light-interface font-weight-bold">Dipl&ocirc;me</span>
                                            </b-input-group-prepend>
                                            <b-form-select v-model="listDiplomes"
                                                           class="form-control col-5" :class="{ 'is-invalid': formInscrit.errors.has('diplome') }"
                                                           name="list_diplomes" id="list_diplomes" >
                                                <b-form-select-option :value="null">Choisissez...</b-form-select-option>
                                                <b-form-select-option value="Aucun">Pas de dipl&ocirc;me</b-form-select-option>
                                                <b-form-select-option value="CEB">CEB</b-form-select-option>
                                                <b-form-select-option value="CESI">CESI</b-form-select-option>
                                                <b-form-select-option value="CESS">CESS</b-form-select-option>
                                                <b-form-select-option value="Non Universitaire Type Court">Non universitaire type court</b-form-select-option>
                                                <b-form-select-option value="Non Universitaire Type Long">Non universitaire type long</b-form-select-option>
                                                <b-form-select-option value="Universitaire">Universitaire</b-form-select-option>
                                                <b-form-select-option value="Étranger sans équivalence">Dipl&ocirc;me &eacute;tranger (sans équivalence)</b-form-select-option>
                                                <b-form-select-option value="Étranger avec équivalence">Dipl&ocirc;me &eacute;tranger (avec équivalence)</b-form-select-option>
                                                <b-form-select-option value="autre">Autre</b-form-select-option>
                                            </b-form-select>
                                            <b-form-input v-model="formInscrit.diplome" type="text" v-show="listDiplomes === 'autre'"
                                                          class="form-control col-7" :class="{ 'is-invalid': formInscrit.errors.has('diplome') }"
                                                          name="diplome" id="diplome" placeholder="Précisez..." maxlength="90">
                                            </b-form-input>
                                            <b-input-group-append is-text v-show="pouvsub_infos.diplome === 1">
                                                <i v-if="listDiplomes === null || (listDiplomes === 'autre' && formInscrit.diplome === null)"
                                                   class="fas fa-exclamation-triangle fa-lg text-orange font-weight-bold"></i>
                                                <i v-else
                                                   class="fas fa-check fa-lg text-green font-weight-bold"></i>
                                            </b-input-group-append>
                                            <has-error :form="formInscrit" field="diplome"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                            <b-input-group-prepend is-text>
                                                <span class="text-light-interface font-weight-bold">Source ?</span>
                                            </b-input-group-prepend>
                                            <b-form-select v-model="listSources"
                                                           class="form-control col-5" :class="{ 'is-invalid': formInscrit.errors.has('listSources') }"
                                                           name="list_sources" id="list_sources" >
                                                <b-form-select-option :value="null">Choisissez où la personne a eu l'info...</b-form-select-option>
                                                <b-form-select-option value="Facebook">Facebook</b-form-select-option>
                                                <b-form-select-option value="Forem/Adressage forem">Forem/Adressage forem</b-form-select-option>
                                                <b-form-select-option value="Site internet">Site internet</b-form-select-option>
                                                <b-form-select-option value="Affiches/flyers">Affiches/flyers</b-form-select-option>
                                                <b-form-select-option value="Ancien stagiaire">Ancien stagiaire</b-form-select-option>
                                                <b-form-select-option value="Autre formation">Autre formation</b-form-select-option>
                                                <b-form-select-option value="Bouche à oreille">Bouche à oreille</b-form-select-option>
                                                <b-form-select-option value="CEFO">CEFO</b-form-select-option>
                                                <b-form-select-option value="CPAS/Synergie emploi">CPAS/Synergie emploi</b-form-select-option>
                                                <b-form-select-option value="Croix rouge">Croix rouge</b-form-select-option>
                                                <b-form-select-option value="Instagram">Instagram</b-form-select-option>
                                                <b-form-select-option value="La Charnière">La Charnière</b-form-select-option>
                                                <b-form-select-option value="Mailing">Mailing</b-form-select-option>
                                                <b-form-select-option value="Maison de l'emploi">Maison de l'emploi</b-form-select-option>
                                                <b-form-select-option value="Mirena">Mirena</b-form-select-option>
                                                <b-form-select-option value="Partenaires">Partenaires</b-form-select-option>
                                                <b-form-select-option value="Proximag">Proximag</b-form-select-option>
                                                <b-form-select-option value="Vlan">Vlan</b-form-select-option>
                                                <b-form-select-option value="autre">Autre</b-form-select-option>
                                            </b-form-select>
                                            <b-form-input v-model="formInscrit.source_info" type="text" maxlength="190"  v-show="listSources === 'autre'"
                                                          class="form-control col-7" :class="{ 'is-invalid': formInscrit.errors.has('source_info') }"
                                                          name="source_info" id="source_info" placeholder="Précisez...">
                                            </b-form-input>
                                            <b-input-group-append is-text v-show="pouvsub_infos.source_info === 1">
                                                <i v-if="listSources === null || (listSources === 'autre' && formInscrit.source_info === null)"
                                                   class="fas fa-exclamation-triangle fa-lg text-orange font-weight-bold"></i>
                                                <i v-else
                                                   class="fas fa-check fa-lg text-green font-weight-bold"></i>
                                            </b-input-group-append>
                                            <has-error :form="formInscrit" field="source_info"></has-error>
                                        </b-input-group>
                                    </div>
                                </div>
                                <v-item-group multiple v-if="lowerFormation === 'pmtic'" class="elevation-3 rounded container-checkbox">
                                    <v-container>
                                        <v-subheader>Modules de PMTIC</v-subheader>
                                        <v-row>
                                            <v-col d-flex justify-center cols="4" style="height: 40px" class="checkbox-pmtic">
                                                <v-checkbox color="success"
                                                    v-model="formInscrit.pmtic_module_1"
                                                    :label="`Module 1`"
                                                ></v-checkbox>
                                            </v-col>
                                            <v-col cols="4" style="height: 40px" class="checkbox-pmtic">
                                                <v-checkbox color="success"
                                                    v-model="formInscrit.pmtic_module_2"
                                                    :label="`Module 2`"
                                                ></v-checkbox>
                                            </v-col>
                                            <v-col cols="4" style="height: 40px" class="checkbox-pmtic">
                                                <v-checkbox color="success"
                                                    v-model="formInscrit.pmtic_module_3"
                                                    :label="`Module 3`"
                                                ></v-checkbox>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-item-group>
                            </form>
                        </div>
                    </div>
                    <div class="text-right">
                        <v-btn class="btn btn-danger text-light" @click="step = 3">Retour</v-btn>
                        <v-btn v-show="choix_inscription === 'prospect'" class="btn-success text-light" @click="validationStepProspect()">Finaliser Prospect</v-btn>
                        <v-btn v-show="choix_inscription === 'candidat'" class="btn-success text-light" @click="validationStepCandidat()">Finaliser Candidat</v-btn>
                        <v-btn v-show="choix_inscription === 'stagiaire'" class="btn-success text-light" @click="validationStepStagiaire()">Finaliser Stagiaire</v-btn>
                    </div>
                </v-stepper-content>
            </v-stepper-items>
        </v-stepper>
        <!-- Modal de prise de rendez-vous si inscription à une formation PMTIC -->
        <v-dialog v-model="dialog_fixer_rdv" persistent max-width="800px" height="400px">
            <v-card>
                <v-card-title>
                    <span class="headline">Fixer un rendez-vous pour {{ formInscrit.prenom }} {{ formInscrit.nom }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col>
                                <v-date-picker
                                    name="date_rdv" id="date_rdv"
                                    v-model="formRdv.date_rdv"
                                    locale="fr"
                                    :min="min"
                                    :max="max"
                                    :allowed-dates="allowedDays()"
                                    :first-day-of-week="weekday">
                                </v-date-picker>
                                <has-error :form="formRdv" field="date_rdv"></has-error>
                            </v-col>
                            <v-col>
                                <b-input-group class="mb-5 mr-sm-2">
                                    <b-input-group-prepend is-text>
                                        <span class="text-light-interface font-weight-bold">Par*</span>
                                    </b-input-group-prepend>
                                    <b-form-select v-model="formRdv.rdv_user_id"
                                                   :state="validationRdvUser"
                                                   class="form-control" :class="{ 'is-invalid': formRdv.errors.has('rdv_user_id') }"
                                                   name="rdv_user_id" id="rdv_user_id">
                                        <b-form-select-option :value="null">Choisissez</b-form-select-option>
                                        <b-form-select-option v-for="user in users" :key="user.id" :value="user.id">{{ user.firstname }} {{ user.lastname }}</b-form-select-option>
                                    </b-form-select>
                                    <b-form-invalid-feedback id="input-rdv_user_id-live-feedback" class="text-center">
                                        La personne qui recevra le·la stagiaire doit être sélectionnée
                                    </b-form-invalid-feedback>
                                    <has-error :form="formRdv" field="rdv_user_id"></has-error>
                                </b-input-group>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="dialog_fixer_rdv = false">Close</v-btn>
                    <v-btn color="success" @click="storeRdv(inscrit.id)">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import {Form} from "vform";
    import moment from 'moment';

    export default {
        name: "CreateInscrit",
        data() {
            const now = new Date();
            const minDate = new Date(now).toISOString().substr(0, 10);
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
                    source_info: null,
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
                villes: [],
                choix_inscription: null,
                inscrit: [],
                listTags: [],
                newTags: [],
                inscritTags : [],
                formTag : new Form({
                    tag: null,
                }),
                test_tag: 0,
                tabTags: [],
                finStoreProspect: false,
                recrutements: [],
                choix_recrutement: null,
                recrutementInscrit: [],
                formations: [],
                nbreStagiaires: [],
                choix_formation: null,
                pouvsub_infos: [],
                formation: [],
                lowerFormation: '',
                calculAge: null,
                maxLengthAge: 3,
                listDiplomes: null,
                listSources: null,
                users: {},
                dialog_fixer_rdv: false,
                weekday: 1,
                min: minDate,
                dayBeforeFinFormation: null,
                max: null,
                formRdv : new Form({
                    date_rdv: null,
                    rdv_user_id: null,
                }),
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
            validationNom() {
                return this.formInscrit.nom.length > 1;
            },
            validationMaxNom() {
                return this.formInscrit.nom.length < 190;
            },
            validationPrenom() {
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
                if(this.formInscrit.email.length === 0) {
                    return this.formInscrit.newsletter !== true;
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
            validationRecrutement() {
                return this.choix_recrutement != null;
            },
            validationFormation() {
                return this.choix_formation != null;
            },
            checkAge() {
                return this.formInscrit.age === null || this.formInscrit.age === '';
            },
            validationAge() {
                return Number(this.formInscrit.age) > 0 && Number(this.formInscrit.age) < 1000;
            },
            validationRdvUser() {
                return this.formRdv.rdv_user_id != null
            },
        },
        methods: {
            retour() {
                Suppression.fire({
                    text: "Si vous continuez, vous retournez à la liste des inscrits !",
                }).then((result) => {
                    if (result.value) {
                        this.$router.push('/inscrits')
                    }
                });
            },
            getDatas() {
                this.getVilles();
                this.getListTags();
                this.getRecrutements();
                this.getFormations();
                this.currentUser();
            },
            checkDateNaissance() {
                if(this.champsObligatoires.date_naissance === false){
                    this.champsObligatoires.date_naissance = true;
                } else {
                    this.champsObligatoires.date_naissance = false;
                }
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
            getVilles() {
                this.loading = false;
                axios.get('api/villes/')
                    .then(response => {
                        this.villes = response.data;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des villes !',
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
                            title: 'Problème avec la récupération de la liste des tags !',
                            timer: undefined,
                        })
                    })
            },
            getRecrutements() {
                axios.get('api/recrutements/futurs')
                    .then(response => {
                        this.recrutements = response.data.recrutements;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des recrutements !',
                            timer: undefined,
                        })
                    })
            },
            getFormations() {
                axios.get('api/formations/all')
                    .then(response => {
                        this.formations = response.data.formations;
                        this.nbreStagiaires = response.data.nbreStagiaires;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des formations !',
                            timer: undefined,
                        })
                    })
            },
            currentUser() {
                this.user = this.$store.getters.currentUser;
                this.formInscrit.current_user = this.user.id;
                this.loading = true;
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
                if(this.formInscrit.date_naissance !== '') {
                    this.calculAge = moment().diff(moment(this.formInscrit.date_naissance), 'years');
                    this.formInscrit.age = this.calculAge;
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
                            this.$Progress.finish();
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la création d\'un tag !');
                    })
            },
            validationStepTag() {
                for(let x = 0; x < this.inscritTags.length; x++) {
                    for(let y = 0; y < this.listTags.length; y++) {
                        if(this.inscritTags[x] === this.listTags[y].tag) {
                            this.tabTags.push(this.listTags[y].id);
                        }
                    }
                }
                this.formInscrit.prospect = 1;
                this.formInscrit.tags = this.inscritTags;
                this.step = 4;
            },
            validationStepRecrutement() {
                if(this.choix_recrutement !== null) {
                    this.getRecrutement();
                } else {
                    Snackbar.fire('Vous n\'avez pas choisi de recrutement !');
                }
            },
            validationStepFormation() {
                if(this.choix_formation !== null) {
                    this.getPouvsubInfos();
                    this.getFormation();
                    this.getAge();
                } else {
                    Snackbar.fire('Vous n\'avez pas choisi de formation !');
                }
            },
            getRecrutement() {
                axios.get('api/recrutements/show/' + this.choix_recrutement)
                    .then(response => {
                        this.recrutementInscrit = response.data.recrutement;
                        this.step = 4;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération des infos du recrutement !',
                            timer: undefined,
                        })
                    })
            },
            getPouvsubInfos() {
                axios.get('api/pouvsub-infos/infos/' + this.choix_formation)
                    .then(response => {
                        this.pouvsub_infos = response.data.data;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération des infos du pouvoir subsidiant !',
                            timer: undefined,
                        })
                    })
            },
            getFormation() {
                axios.get('api/formations/show/' + this.choix_formation)
                    .then(response => {
                        this.formation = response.data.formation;
                        this.lowerFormation = this.formation.nom.toLowerCase();
                        if(this.lowerFormation === 'pmtic') {
                            this.formInscrit.pmtic_module_1 = true;
                            this.formInscrit.pmtic_module_2 = true;
                            this.formInscrit.pmtic_module_3 = true;
                        }
                        this.step = 4;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération des infos de la formation sélectionnée !',
                            timer: undefined,
                        })
                    })
            },
            validationStepProspect() {
                this.storeInscrit();
            },
            validationStepCandidat() {
                this.formInscrit.recrutement = this.choix_recrutement;
                this.storeInscrit();
            },
            validationStepStagiaire() {
                if(this.validation(this.formInscrit.age !== null && this.formInscrit.age !== '' && this.formInscrit.age < 1, "L'âge ne peut être négatif ou valoir 0 !")) {
                } else {
                    if(this.formInscrit.diplome === '') {
                        if(this.listDiplomes !== null) {
                            this.formInscrit.diplome = this.listDiplomes;
                        } else {
                            this.formInscrit.diplome = null;
                        }
                    }
                    if(this.formInscrit.diplome === '') {
                        if(this.listDiplomes !== null) {
                            this.formInscrit.diplome = this.listDiplomes;
                        } else {
                            this.formInscrit.diplome = null;
                        }
                    }
                    this.formInscrit.formation = this.choix_formation;
                    this.storeInscrit();
                }
            },
            storeInscrit() {
                this.$Progress.start();
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
                if(this.listDiplomes !== null && this.listDiplomes !== 'autre') {
                    this.formInscrit.diplome = this.listDiplomes
                }
                if(this.listSources !== null && this.listSources !== 'autre') {
                    this.formInscrit.source_info = this.listSources
                }
                this.formInscrit
                    .post('api/inscrits/create')
                    .then(response => {
                        if(this.formInscrit.successful) {
                            this.$Progress.finish();
                            if(this.formInscrit.formation !== null && this.formation.nom.toLowerCase() === 'pmtic') {
                                Toast.fire('Inscription effectuée');
                                this.inscrit = response.data.data;
                                this.rdvPMTIC(this.inscrit);
                            } else if(response.data.message && response.data.message === 'Personne existante !') {
                                Snackbar.fire({
                                    title: response.data.message + "\nVeuillez mettre à jour sa fiche...",
                                    timer: undefined
                                });
                                this.$router.push('/inscrits/show/' + response.data.inscrit.id);
                            } else {
                                Toast.fire('Inscription effectuée');
                                this.$router.push('/inscrits');
                            }
                        }
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$Progress.fail();
                        Snackbar.fire('Problème avec la création de la personne inscrite !');
                    })
            },
            rdvPMTIC() {
                this.formRdv.reset();
                this.formRdv.clear();
                this.getUsers();
                this.dialog_fixer_rdv = true;
            },
            allowedDays: function(a){
                return val => [a].indexOf(val) === -1 && ![0,6].includes(new Date(val).getDay());
            },
            getUsers() {
                axios.get('api/users/')
                    .then(response => {
                        this.users = response.data.users
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des utilisateurs !',
                            timer: undefined,
                        })
                    })
            },
            storeRdv(id) {
                if(this.validation(this.formRdv.date_rdv === null, "Vous n'avez pas choisi de date !")) {
                } else if(this.validation(this.formRdv.rdv_user_id === null, "Vous n'avez pas sélectionné la personne qui recevra le·la stagiaire")) {
                } else {
                    this.$Progress.start();
                    this.formRdv
                        .put('api/inscrits/rdv/' + id +'/'+ this.formation.id)
                        .then(response => {
                            this.$Progress.finish();
                            Toast.fire(response.data.message);
                            this.dialog_fixer_rdv = false;
                            this.$router.push('/formations/show/' + this.formation.id);
                        })
                        .catch(error => {
                            console.log(error.response);
                            this.$Progress.fail();
                            Snackbar.fire('Problème avec l\'enregistrement du rdv !');
                        })
                }
            },
        }
    }
</script>

<style scoped>
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
