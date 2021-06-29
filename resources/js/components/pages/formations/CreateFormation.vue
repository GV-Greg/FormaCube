<template>
    <div>
        <h1>
            <button class="btn btn-light" @click="retour">
                <i class="fas fa-reply fa-lg text-interface"></i>
            </button>
            Création d'une nouvelle formation
        </h1>
        <v-stepper v-model="step">
            <v-stepper-header>
                <v-stepper-step :complete="step > 1" step="1">Généralités partie 1</v-stepper-step>

                <v-divider class="light"></v-divider>

                <v-stepper-step :complete="step > 2" step="2">Généralités partie 2</v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step :complete="step > 3" step="3">Recrutement</v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step step="4">Infos Pouvoir Subsidiant</v-stepper-step>
            </v-stepper-header>
            <v-stepper-items class="pb-1">
                <!-- Contenu partie 1 : les généralités-->
                <v-stepper-content step="1">
                    <div class="step-content mb-3" v-if="loading === true">
                        <form>
                            <div class="row row-cols-2">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Nom*</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model="formFormation.nom" type="text"
                                                      :state="checkNom && validationMinNom && validationMaxNom"
                                                      class="form-control rounded-r" :class="{ 'is-invalid': formFormation.errors.has('nom') }"
                                                      name="nom" id="nom" placeholder="Nom de la formation">
                                        </b-form-input>
                                        <b-form-invalid-feedback id="nom-feedback" class="pl-5" v-show="checkNom === false">
                                            Le nom de la formation est requis.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="nom-min-feedback" class="pl-5" v-show="validationMinNom === false">
                                            Le nom doit &ecirc;tre compos&eacute; d'au moins 3 caract&egrave;res.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="nom-max-feedback" class="pl-5" v-show="validationMaxNom === false">
                                            Le nom doit &ecirc;tre compos&eacute; de moins 190 caract&egrave;res.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="nom" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Abr&eacute;viation*</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model="formFormation.abreviation" type="text"
                                                      :state="checkAbreviation && validationMinAbreviation && validationMaxAbreviation"
                                                      class="form-control rounded-r" :class="{ 'is-invalid': formFormation.errors.has('abreviation') }"
                                                      name="abreviation" id="abreviation" placeholder="Abréviation de la formation">
                                        </b-form-input>
                                        <b-form-invalid-feedback id="abreviation-feedback" class="pl-5" v-show="checkAbreviation === false">
                                            L'abréviation de la formation est requise.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="abreviation-min-feedback" class="pl-5" v-show="validationMinAbreviation === false">
                                            L'abréviation de la formation doit &ecirc;tre compos&eacute; d'au moins 3 caract&egrave;res.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="abreviation-max-feedback" class="pl-5" v-show="validationMaxAbreviation === false">
                                            L'abréviation de la formation doit &ecirc;tre compos&eacute; de moins 20 caract&egrave;res.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="abreviation" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                            </div>
                            <div class="row row-cols-2">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Session</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model="formFormation.session" type="text" :state="validationMaxSession"
                                                      class="form-control" :class="{ 'is-invalid': formFormation.errors.has('session') }"
                                                      name="session" id="session" placeholder="Session de la formation">
                                        </b-form-input>
                                        <b-form-invalid-feedback id="abreviation-max-feedback" class="pl-5" v-show="validationMaxSession === false">
                                            La session de la formation doit &ecirc;tre compos&eacute; de moins 190 caract&egrave;res.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="session" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Salle*</span>
                                        </b-input-group-prepend>
                                        <b-form-select v-model="formFormation.salle_id" :state="checkSalle"
                                                       class="form-control" :class="{ 'is-invalid': formFormation.errors.has('salle_id') }"
                                                       name="salle" id="salle" placeholder="Salle de la formation">
                                            <b-form-select-option :value="null">Choisissez</b-form-select-option>
                                            <b-form-select-option v-for="salle in salles" :key="salle.id" :value="salle.id">{{ salle.nom }}</b-form-select-option>
                                        </b-form-select>
                                        <b-form-invalid-feedback id="abreviation-max-feedback" class="pl-5" v-show="checkSalle === false">
                                            La salle est requise.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="salle" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                            </div>
                            <div class="row row-cols-2">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Début*</span>
                                        </b-input-group-prepend>
                                        <b-form-datepicker v-model="formFormation.date_debut" type="date" local="fr-BE" :hide-header="hideHeader"
                                                           :start-weekday="weekday" :date-disabled-fn="dateDisabled" :min="start_min"
                                                           :state="checkDateDebut && validationDatesDebutFin" no-flip
                                                           :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                                           class="form-control" :class="{ 'is-invalid': formFormation.errors.has('date_debut')}"
                                                           name="date_debut" id="date_debut" placeholder="Début de la formation">
                                        </b-form-datepicker>
                                        <b-form-invalid-feedback id="date-debut-feedback" class="pl-5" v-show="checkDateDebut === false">
                                            La date de début de formation est requise.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="dates-debut-fin-feedback" class="pl-5" v-show="validationDatesDebutFin === false">
                                            La date de fin de formation ne peut &ecirc;tre avant la date de d&eacute;but de la formation.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="date_debut" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Fin*</span>
                                        </b-input-group-prepend>
                                        <b-form-datepicker v-model="formFormation.date_fin" type="date" local="fr-BE" :hide-header="hideHeader"
                                                           :start-weekday="weekday" :date-disabled-fn="dateDisabled" :min="dateTodayOrDebut"
                                                           :state="checkDateFin && validationDatesDebutFin" no-flip
                                                           :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                                           class="form-control" :class="{ 'is-invalid': formFormation.errors.has('date_fin')}"
                                                           name="date_fin" id="date_fin" placeholder="Fin de la formation">
                                        </b-form-datepicker>
                                        <b-form-invalid-feedback id="date-fin-feedback" class="pl-5" v-show="checkDateFin === false">
                                            La date de fin de formation est requise.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="dates-fin-debut-feedback" class="pl-5" v-show="validationDatesDebutFin === false">
                                            La date de fin de formation ne peut &ecirc;tre avant la date de d&eacute;but de la formation.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="date_fin" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                            </div>
                            <div class="row row-cols-2">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Min. stagiaires*</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model.number="formFormation.min_stagiaires" type="number" min="0"
                                                      :state="checkMinStagiaires && validationMinStagiaires && validationMinMaxStagiaires"
                                                      class="form-control" :class="{ 'is-invalid': formFormation.errors.has('min_stagiaires')}"
                                                      name="min_stagiaires" id="min_stagiaires" placeholder="Minimum de stagiaires">
                                        </b-form-input>
                                        <b-form-invalid-feedback id="check-min-stagiaire-feedback" class="pl-5" v-show="checkMinStagiaires === false">
                                            Le nombre minimum de stagiaires est requis.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="min-stagiaire-feedback" class="pl-5" v-show="validationMinStagiaires === false">
                                            Le nombre minimum de stagiaires doit &ecirc;tre sup&eacute;rieur &agrave; 0.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="min-max-stagiaire-feedback" class="pl-5" v-show="validationMinMaxStagiaires  === false">
                                            Le nombre min. de stagiaires doit &ecirc;tre inf&eacute;rieur au nombre max. de stagiaires.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="min_stagiaires" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Max. stagiaires*</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model.number="formFormation.max_stagiaires" type="number" min="0"
                                                      :state="checkMaxStagiaires && validationMaxStagiaires && validationMinMaxStagiaires"
                                                      class="form-control" :class="{ 'is-invalid': formFormation.errors.has('max_stagiaires')}"
                                                      name="max_stagiaires" id="max_stagiaires" placeholder="Maximum de stagiaires">
                                        </b-form-input>
                                        <b-form-invalid-feedback id="check-max-stagiaire-feedback" class="pl-5" v-show="checkMaxStagiaires === false">
                                            Le nombre maximum de stagiaires est requis.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="max-stagiaire-feedback" class="pl-5" v-show="validationMaxStagiaires === false">
                                            Le nombre maximum de stagiaires doit &ecirc;tre sup&eacute;rieur &agrave; 0.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="max-min-stagiaire-feedback" class="pl-5" v-show="validationMinMaxStagiaires  === false">
                                            Le nombre min. de stagiaires doit &ecirc;tre inf&eacute;rieur au nombre max. de stagiaires.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="max_stagiaires" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                            </div>
                            <div class="row row-cols-2">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Nombre d'heures*</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model.number="formFormation.nbre_heures" type="number" min="0"
                                                      :state="checkNbreHeures && validationNbreHeures"
                                                      class="form-control" :class="{ 'is-invalid': formFormation.errors.has('nbre_heures')}"
                                                      name="nbre_heures" id="nbre_heures" placeholder="Nombre d'heures">
                                        </b-form-input>
                                        <b-form-invalid-feedback id="check-nbre-heures-feedback" class="pl-5" v-show="checkNbreHeures === false">
                                            Le nombre d'heures de formation est requis.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="nbre-heures-feedback" class="pl-5" v-show="validationNbreHeures === false">
                                            Le nombre d'heures doit &ecirc;tre sup&eacute;rieur &agrave; 0.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="nbre_heures" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Prix*</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model.number="formFormation.prix" type="number" min="0"
                                                      :state="checkPrix && validationPrix"
                                                      :class="{ 'is-invalid': formFormation.errors.has('prix')}"
                                                      name="prix" id="prix" placeholder="Prix">
                                        </b-form-input>
                                        <b-input-group-append is-text>
                                            <span class="text-light-interface font-weight-bold">€</span>
                                        </b-input-group-append>
                                        <b-form-invalid-feedback id="check-nbre-heures-feedback" class="pl-5" v-show="checkPrix === false">
                                            Le prix de la formation est requis.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="nbre-heures-feedback" class="pl-5" v-show="validationPrix === false">
                                            Le prix de la formation doit &ecirc;tre sup&eacute;rieur &agrave; 0.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="prix" class="pl-5"></has-error>
                                    </b-input-group>
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
                <!-- Contenu partie 2 : les généralités suite-->
                <v-stepper-content step="2">
                    <div class="step-content mb-6">
                        <form>
                            <div class="row row-cols-2">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Tuteur·rice*</span>
                                        </b-input-group-prepend>
                                        <b-form-select v-model="formFormation.user_id" :state="checkTuteur"
                                                       class="form-control" :class="{ 'is-invalid': formFormation.errors.has('user_id') }"
                                                       name="user_id" id="user_id">
                                            <b-form-select-option :value="null">Choisissez</b-form-select-option>
                                            <b-form-select-option v-for="user in users.users" :key="user.id" :value="user.id">{{ user.firstname + ' ' + user.lastname }}</b-form-select-option>
                                        </b-form-select>
                                        <b-form-invalid-feedback id="check-tuteur-feedback" class="pl-5" v-show="checkTuteur === false">
                                            Le tuteur·trice de la formation est requis·e.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="user_id" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Pouvoir Subsidiant*</span>
                                        </b-input-group-prepend>
                                        <b-form-select v-model="formFormation.pouvsub_id" :state="checkPouvsub"
                                                       class="form-control" :class="{ 'is-invalid': formFormation.errors.has('pouvsub_id') }"
                                                       name="pouvsub_id" id="pouvsub_id">
                                            <b-form-select-option :value="null">Choisissez</b-form-select-option>
                                            <b-form-select-option v-for="pouvsub in pouvsubs" :key="pouvsub.id" :value="pouvsub.id">{{ pouvsub.nom }}</b-form-select-option>
                                        </b-form-select>
                                        <b-form-invalid-feedback id="check-pouvsub-feedback" class="pl-5" v-show="checkPouvsub === false">
                                            Le pouvoir subsidiant de la formation est requis.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="pouvsub_id" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                            </div>
                            <div class="row row-cols-2">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Dur&eacute;e RGPD*</span>
                                        </b-input-group-prepend>
                                        <b-form-input v-model.number="formFormation.duree_rgpd" type="number" min="0" :state="checkDureeRgpd && validationDureeRgpd"
                                                      class="form-control" :class="{ 'is-invalid': formFormation.errors.has('duree_rgpd')}"
                                                      name="duree_rgpd" id="duree_rgpd" placeholder="Durée durant laquelle garder les infos (RGPD)">
                                        </b-form-input>
                                        <b-form-invalid-feedback id="check-rgpd-feedback" class="pl-5" v-show="checkDureeRgpd === false">
                                            Le durée pour le RGPD est requis.
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback id="min-rgpd-feedback" class="pl-5" v-show="validationDureeRgpd === false">
                                            La durée pour le RGPD doit &ecirc;tre sup&eacute;rieur &agrave; 0.
                                        </b-form-invalid-feedback>
                                        <has-error :form="formFormation" field="duree_rgpd" class="pl-5"></has-error>
                                    </b-input-group>
                                </div>
                                <div class="col">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold">Commentaire(s)</span>
                                        </b-input-group-prepend>
                                        <b-form-textarea v-model="formFormation.commentaire_formation" rows="5" class="form-control"
                                                         name="commentaire_formation" id="commentaire_formation" placeholder="Entrez  un commentaire...">
                                        </b-form-textarea>
                                        <has-error :form="formFormation" field="commentaire_formation"></has-error>
                                    </b-input-group>
                                </div>
                            </div>
                            <span class="font-weight-light font-italic text-light-interface"><small>Tous les champs avec * sont obligatoires</small></span>
                        </form>
                    </div>
                    <div class="text-right">
                        <v-btn class="btn btn-danger text-light" @click="step = 1">Retour</v-btn>
                        <v-btn class="btn-success text-light" @click="validationStepTwo()">
                                <span v-show="checkStoreFormation === false">Cr&eacute;er la formation</span>
                                <span v-show="checkStoreFormation === true">Modifier la formation</span>
                        </v-btn>
                    </div>
                </v-stepper-content>
                <!-- Contenu partie 3 : Le recrutement -->
                <v-stepper-content step="3">
                    <div class="step-content mb-6">
                        <v-row>
                            <v-col cols="8">
                                <span class="text-orange font-weight-bold">Cocher la case s'il y a des jours de recrutement :</span>
                                <v-checkbox v-model="checkRecrutement" label="Formation avec recrutement ?"
                                            class="myLabel ml-5" color="success" hide-details>
                                </v-checkbox>
                            </v-col>
                        </v-row>
                        <v-row v-if="checkRecrutement === true">
                            <v-col cols="12" sm="6" class="d-flex justify-content-center">
                                <v-date-picker
                                    v-model="listDatesRecrutements"
                                    multiple no-title
                                    :min="minRecrutement"
                                    :max="maxRecrutement"
                                    :allowed-dates="allowedDays()"
                                    locale="fr"
                                    :first-day-of-week="weekday"
                                    color="green lighten-1"
                                >
                                </v-date-picker>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-menu :close-on-content-click="false"
                                        :return-value.sync="listDatesRecrutements"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="290px" >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-combobox
                                            v-model="listDatesRecrutements"
                                            multiple
                                            chips
                                            small-chips
                                            label="Date(s) de recrutement"
                                            prepend-icon="mdi-calendar"
                                            v-bind="attrs" v-on="on" >
                                        </v-combobox>
                                    </template>
                                </v-menu>
                            </v-col>
                        </v-row>
                    </div>
                    <div class="text-right">
                        <v-btn class="btn btn-danger text-light" @click="step = 2">Retour</v-btn>
                        <v-btn class="btn-interface text-light" @click="validationStepThree()">Continuer</v-btn>
                    </div>
                </v-stepper-content>
                <!-- Contenu partie 4 : Les infos pour le pouvoir subsidiant  -->
                <v-stepper-content step="4">
                    <div class="step-content mb-6">
                        <form>
                            <div class="row row-cols-2">
                                <div class="col">
                                    <v-checkbox v-model="formPouvsub.adresse" label="ADRESSE" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="formPouvsub.age" label="ÂGE" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="formPouvsub.num_national" label="NUMÉRO NATIONAL" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="formPouvsub.diplome" label="DIPLOME" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="formPouvsub.source_info" label="SOURCE INFO" class="myLabel" color="success" hide-details></v-checkbox>
                                </div>
                                <div class="col">
                                    <v-checkbox v-model="formPouvsub.date_naissance" label="DATE DE NAISSANCE" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="formPouvsub.email" label="EMAIL" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="formPouvsub.statut_legal" label="STATUT LÉGAL" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="formPouvsub.duree_chomage" label="DURÉE DE CHÔMAGE" class="myLabel" color="success" hide-details></v-checkbox>
                                    <v-checkbox v-model="formPouvsub.groupe_social" label="GROUPE SOCIAL" class="myLabel" color="success" hide-details></v-checkbox>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="text-right">
                        <v-btn class="btn btn-danger text-light" @click="step = 3">Retour</v-btn>
                        <v-btn color="btn btn-success text-light" @click="storeInfosPouvSub()">Cr&eacute;er</v-btn>
                    </div>
                </v-stepper-content>
            </v-stepper-items>
        </v-stepper>
    </div>
</template>

<script>
    import { Form } from "vform";

    export default {
        name: "CreateFormation",
        data () {
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const minDate = new Date(today);
            const minDateR = new Date(now).toISOString().substr(0, 10);
            return {
                loading: false,
                step: 1,
                hideHeader: true,
                weekday: 1,
                start_min: minDate,
                end_min: minDate,
                min: minDate,
                minRecrutement: minDateR,
                dayBeforeFormation: null,
                maxRecrutement: null,
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
                    prix: 0,
                    commentaire_formation: '',
                    user_id: null,
                    pouvsub_id: null,
                    statut: 'futur',
                    abreviation: '',
                    duree_rgpd: '',
                }),
                users: [],
                pouvsubs: [],
                salles: [],
                PouvsubInfoOn: false,
                pouvsub: [],
                checkStoreFormation: false,
                formation: [],
                checkRecrutement: false,
                menuDatesRecrutement: false,
                listDatesRecrutements: [],
                nbreRecrutements: null,
                formRecrutement : new Form({
                    formation_id: null,
                    date: null,
                }),
                recrutements: [],
                formPouvsub : new Form({
                    id: '',
                    formation_id: null,
                    pouvsub_id: null,
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
                event: null,
                finStoreRecrutement: false,
            }
        },
        mounted() {
            console.log('CreateFormation component mounted');
            this.getUsers();
            this.getSalles();
        },
        watch: {
            checkRecrutement: function() {
                if(this.checkRecrutement === false) {
                    this.listDatesRecrutements = [];
                }
            },

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
                if(this.formFormation.session !== '') {
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
            checkPouvsub() {
                return this.formFormation.pouvsub_id != null
            },
            checkDureeRgpd() {
                return this.formFormation.duree_rgpd !== ''
            },
            validationDureeRgpd() {
                if(this.formFormation.duree_rgpd !== '') {
                    return Number(this.formFormation.duree_rgpd) > 0;
                }
            },
            // checkDateRecrutement() {
            //     return this.formRecrutement.date != null
            // },
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
                        this.$router.push('/formations')
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
            allowedDays: function(a){
                return val => [a].indexOf(val) === -1 && ![0,6].includes(new Date(val).getDay());
            },
            validation(test, message){
                if(test) {
                    Snackbar.fire(message);
                    return true;
                }
                return false;
            },

            getUsers() {
                axios.get('api/users/')
                    .then(response => {
                        this.users = response.data;
                        this.getPouvsubs();
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
            getPouvsubs() {
                axios.get('api/pouvsubs/')
                    .then(response => {
                        this.pouvsubs = response.data.data;
                        this.loading = true;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération des pouvoirs subsidiants !',
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
                        } else if(this.validation(this.formFormation.session !== '' && !this.validationMaxSession, "La session de formation doit être composé de moins de 190 caractères !")) {
                        } else {
                            if(this.twoDigit(this.formFormation.min_stagiaires) <= this.twoDigit(this.formFormation.max_stagiaires)) {
                                if(this.formFormation.date_fin >= this.formFormation.date_debut) {
                                    this.dayBeforeFormation = new Date(this.formFormation.date_debut);
                                    this.dayBeforeFormation = this.dayBeforeFormation.setDate(this.dayBeforeFormation.getDate() -1);
                                    this.maxRecrutement = new Date(this.dayBeforeFormation).toISOString().substr(0, 10)
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
                if(this.checkTuteur && this.checkPouvsub && this.checkDureeRgpd) {
                    if(this.validationDureeRgpd) {
                        this.getPouvsub(this.formFormation.pouvsub_id);
                        if(this.checkStoreFormation === false) {
                            this.storeFormation();
                        } else if(this.checkStoreFormation === true) {
                            this.updateFormation();
                        }
                    } else {
                        Snackbar.fire('La durée RGPD doit être supérieur à 0 !');
                    }
                } else {
                    Snackbar.fire('Vous n\'avez pas rempli tous les champs obligatoires !');
                }
            },
            getPouvsub(id) {
                axios.get('api/pouvsubs/'+ id)
                    .then(response => {
                        this.pouvsub = response.data
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération du pouvoir subsidiant !',
                            timer: undefined,
                        })
                    })
            },
            storeFormation() {
                this.$Progress.start();
                this.formFormation
                    .post('api/formations/create')
                    .then(response => {
                        if(this.formFormation.successful) {
                            this.$Progress.finish();
                            Toast.fire('Formation créée');
                            this.formation = response.data.data;
                            this.checkStoreFormation = true;
                            // this.getLatestFormation();
                            this.step = 3;
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        if (error.response.status === 422) {
                            Snackbar.fire('Formation déjà existante');
                        } else {
                            Snackbar.fire('Problème avec la création de la formation');
                        }
                    })
            },
            updateFormation() {
                this.$Progress.start();
                this.formFormation
                    .put('api/formations/edit/' + this.formation.id)
                    .then(response => {
                        if(this.formFormation.successful) {
                            this.$Progress.finish();
                            Toast.fire('Formation modifiée');
                            this.formation = response.data.data;
                            this.checkStoreFormation = true;
                            this.step = 3;
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la modification de la formation');
                    })
            },
            // getLatestFormation() {
            //     axios.get('api/formations/latest')
            //         .then(response => {
            //             this.formation = response.data.data;
            //         })
            //         .catch(error => {
            //             console.log(error);
            //             this.error = 'Oups, il y a un hic !';
            //             this.snackbar = true;
            //         })
            // },
            getPouvsubInfos() {
                this.formPouvsub.pouvsub_id = this.formation.pouvsub_id;
                this.formPouvsub.formation_id = this.formation.id;
                this.formPouvsub.adresse = this.pouvsub.data.adresse;
                this.formPouvsub.date_naissance = this.pouvsub.data.date_naissance;
                this.formPouvsub.age = this.pouvsub.data.age;
                this.formPouvsub.email = this.pouvsub.data.email;
                this.formPouvsub.num_national = this.pouvsub.data.num_national;
                this.formPouvsub.statut_legal = this.pouvsub.data.statut_legal;
                this.formPouvsub.diplome = this.pouvsub.data.diplome;
                this.formPouvsub.duree_chomage = this.pouvsub.data.duree_chomage;
                this.formPouvsub.source_info = this.pouvsub.data.source_info;
                this.formPouvsub.groupe_social = this.pouvsub.data.groupe_social;
                this.PouvsubInfoOn = true;
            },
            validationStepThree() {
                if(this.checkRecrutement === false) {
                    this.getPouvsubInfos()
                    this.step = 4;
                } else {
                    if(this.checkRecrutement === true && this.listDatesRecrutements.length === 0) {
                        Snackbar.fire('Vous n\'avez pas encodé de date(s) !');
                    } else {
                        this.getPouvsubInfos();
                        this.storeRecrutements();
                    }
                }
            },
            storeRecrutements() {
                this.finStoreRecrutement = false;
                for(let x = 0; x < this.listDatesRecrutements.length; x++) {
                    this.formRecrutement = new Form({
                        formation_id: this.formation.id,
                        date: this.listDatesRecrutements[x],
                    });
                    this.storeRecrutement();
                    if(x === this.listDatesRecrutements.length-1) {
                        this.finStoreRecrutement = true;
                    }
                }
            },
            storeRecrutement() {
                this.$Progress.start();
                this.formRecrutement
                    .post('api/recrutements')
                    .then(response => {
                        if(this.formRecrutement.successful) {
                            this.$Progress.finish();
                            Toast.fire('Recrutement créé');
                            if(this.finStoreRecrutement === true) {
                                this.step = 4;
                            }
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        if (error.response.status === 422) {
                            Snackbar.fire('Recrutement déjà existant');
                        } else {
                            Snackbar.fire('Problème avec la création d\'un recrutement');
                        }
                    })
            },
            storeInfosPouvSub() {
                this.$Progress.start();
                this.formPouvsub
                    .post('api/pouvsub-infos/create')
                    .then(response => {
                        if(this.formPouvsub.successful) {
                            this.$Progress.finish();
                            Toast.fire('Infos du pouvoir subsidiant créés');
                            this.$router.push('/formations')
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        if (error.response.status === 422) {
                            Snackbar.fire('Infos de pouvoir subsidant pour cette formation déjà existants');
                        } else {
                            Snackbar.fire('Problème avec l\'enregistrement des infos utiles pour le pouvoir subsidiant');
                        }
                    })
            }
        }
    }
</script>

<style scoped>

</style>
