<template>
    <div>
        <h1 class="ml-5 mb-n2">
            <a @click="goBack">
                <button class="btn btn-light mt-n2">
                    <i class="fas fa-reply fa-lg text-interface"></i>
                </button>
            </a>
            Fiche de Formation
        </h1>
        <v-row justify="center" class="mb-n3" v-if="loading === true">
            <v-col cols="12">
                <v-card class="mx-auto" shaped>
                    <v-card-text class="p-3 ml-2">
                        <v-row class="mt-n2">
                            <div class="col-11 d-inline-flex pb-0">
                                <h2 class="font-weight-bolder">{{ formation.nom | upperCase }}</h2>
                                <span class="mt-5 ml-5">{{ formation.session | upperCase }}</span>
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
                                <router-link :to="{ name: 'editFormation', params: { id: formation.id }}" class="mt-n2">
                                    <v-btn fab dark small color="green">
                                        <v-icon>mdi-pencil</v-icon>
                                    </v-btn>
                                </router-link>
                                <v-btn fab dark small color="red" @click.prevent="destroyFormation()">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </v-speed-dial>
                        </v-row>
                        <v-row>
                            <div class="col-11 pt-0">
                                <span><i>Création : le {{ formation.created_at | newDate }}</i></span><br/>
                                <span><i>Dernière modification : le {{ formation.updated_at | newDate }}</i></span>
                            </div>
                        </v-row>
                        <v-row>
                            <v-col class="text-start">
                                <i class="fas fa-calendar-day fa-lg text-interface mr-2"></i>Du <span class="font-weight-bolder text-interface">{{ formation.date_debut | newDate }}</span> au <span class="font-weight-bolder text-interface">{{ formation.date_fin | newDate }}</span>
                            </v-col>
                            <v-col class="text-start">
                                <i class="fas fa-users fa-lg text-interface mr-2"></i>De <span class="font-weight-bolder text-interface">{{ formation.min_stagiaires }}</span> à <span class="font-weight-bolder text-interface">{{ formation.max_stagiaires }}</span> stagiaires
                            </v-col>
                            <v-col class="text-start">
                                <i class="fas fa-clock fa-lg text-interface mr-2"></i>Nombres d'heures : <span class="font-weight-bolder text-interface">{{ formation.nbre_heures }} h</span>.
                            </v-col>
                            <v-col class="text-start">
                                <i class="fas fa-shopping-cart fa-lg text-interface mr-2"></i>Prix par stagiaire : <span class="font-weight-bolder text-interface">{{ formation.prix }}€</span>.
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col class="text-start">
                                <i class="fas fa-door-open fa-lg text-interface mr-2"></i>Salle :
                                <span class="font-weight-bolder text-interface" v-if="formation.salle != null">{{ formation.salle }}</span>
                                <span v-else class="font-italic text-sm">Non définie</span>
                            </v-col>
                            <v-col class="text-start">
                                <i class="fas fa-user fa-lg text-interface ml-1 mr-2"></i>
                                <span v-if="formation.tuteur_genre === 'man.png'">Tuteur : </span>
                                <span v-show="formation.tuteur_genre === 'woman.png'">Tutrice : </span>
                                <span v-show="formation.tuteur_genre === 'user.png'">Tuteur·rice : </span>
                                <span class="font-weight-bolder text-interface">{{ formation.tuteur_prenom }}</span>
                            </v-col>
                            <v-col class="text-start">
                                <a @click="show()" class="myLink">
                                    <i class="fas fa-hand-holding-usd fa-lg link-interface mr-2"></i>Pouvoir subsidiant : <span class="font-weight-bolder">{{ formation.pouvsub }}</span>
                                </a>
                            </v-col>
                            <v-col class="text-start">
                                <i class="fas fa-bell fa-lg text-interface mr-2"></i>Statut : <span class="font-weight-bolder text-interface">{{ formation.statut }}</span>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col class="text-start">
                                <i class="fas fa-clock fa-lg text-interface mr-2"></i>Dur&eacute;e durant laquelle garder les infos (RGPD) : <span class="font-weight-bolder text-interface">{{ formation.duree_rgpd }} années</span>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col class="text-start pb-0">
                                <h6>Commentaires :</h6>
                                <p v-if="formation.commentaire_formation !== null" class="ml-2 mb-0">{{ formation.commentaire_formation }}</p><p v-else class="ml-2 mb-0 no-information">Aucun</p>
                            </v-col>
                        </v-row>
                        <v-row v-if="stagiaires.length > 0">
                            <v-col>
                                <a class="mailtoui btn btn-interface button-link mt-1" :href="'mailto:?bcc=' + stagiairesEmails">Envoyer un mail aux stagiaires</a>
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
                    <v-tab href="#listStagiaires">Liste des stagiaires</v-tab>
                    <v-tab href="#ajoutStagiaires">Ajout de stagiaires</v-tab>
                    <v-tab href="#recrutements">Recrutement(s)</v-tab>
                    <v-tab href="#documents">Documents</v-tab>
                    <v-tab-item value="listStagiaires">
                        <v-card flat tile>
                            <v-card-text>
                                <div class="row d-flex justify-content-center mt-2" v-if="loadingDatas === true && lower_nom_formation  === 'pmtic' && stagiaires.length > 0">
                                    <v-simple-table fixed-header>
                                        <template v-slot:default>
                                            <thead>
                                            <tr>
                                                <th class="text-left">N°</th>
                                                <th class="text-center">Pr&eacute;nom</th>
                                                <th class="text-center">Nom</th>
                                                <th class="text-center">Infos</th>
                                                <th class="text-center">Modules</th>
                                                <th class="text-center">RDV</th>
                                                <th class="text-center">Rappel</th>
                                                <th class="text-left">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="myTr" v-for="(stagiaire, index) in stagiaires" :key="stagiaire.id">
                                                <td class="text-left"><strong>{{ index + 1 }}</strong></td>
                                                <td class="text-center"><strong>{{ stagiaire.prenom }}</strong></td>
                                                <td class="text-center"><strong>{{ stagiaire.nom | upperCase }}</strong></td>
                                                <td class="text-center">
                                                    <span v-if="stagiaire.tel !== null || stagiaire.gsm !== null || stagiaire.email !== null">
                                                         <button @click="afficherInfos(stagiaire.prenom, stagiaire.nom, stagiaire.tel, stagiaire.gsm, stagiaire.email)">
                                                             <i class="fas fa-user-graduate fa-lg mr-1"></i>
                                                             <span class="font-weight-bold" v-show="stagiaire.tel !== null">T&eacute;l. </span>
                                                             <span class="font-weight-bold" v-show="stagiaire.gsm !== null">GSM </span>
                                                             <span class="font-weight-bold"v-show="stagiaire.Email !== null">Email </span>
                                                         </button>
                                                    </span>
                                                    <span v-else class="no-information">Non renseigné</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="font-weight-bold mr-1">1</span>
                                                    <i v-if="stagiaire.pmtic_module_1 === true"class="fas fa-check fa-lg text-green mr-1"></i>
                                                    <i v-else class="fas fa-times fa-lg text-red mr-1"></i>
                                                    <span class="font-weight-bold mx-1">2</span>
                                                    <i v-if="stagiaire.pmtic_module_2 === true" class="fas fa-check fa-lg text-green mr-1"></i>
                                                    <i v-else class="fas fa-times fa-lg text-red mr-1"></i>
                                                    <span class="font-weight-bold mx-1">3</span>
                                                    <i v-if="stagiaire.pmtic_module_3 === true" class="fas fa-check fa-lg text-green mr-1"></i>
                                                    <i v-else class="fas fa-times fa-lg text-red mr-1"></i>
                                                </td>
                                                <td class="text-center" v-if="stagiaire.date_rdv === null">
                                                    <button @click="fixerRdv(stagiaire.id)">
                                                        <i class="fas fa-calendar-check fa-lg text-pink mr-1"></i><i class="fas fa-times fa-lg text-red ml-1"></i>
                                                    </button>
                                                </td>
                                                <td v-if="stagiaire.date_rdv != null && stagiaire.validation_rdv === 0" class="text-center">
                                                    <button id="bouton_rdv_pmtic" @click="fixerRdv(stagiaire.id)" class="d-flex flex-row">
                                                        <div class="mt-2"><i class="fas fa-calendar-check fa-lg text-orange mr-1"></i></div>
                                                        <div class="d-flex flex-column text-left">
                                                            <span>{{ stagiaire.date_rdv | newDate }}</span>
                                                            <span class="mt-n1">{{ stagiaire.rdv_user_prenom }} {{ stagiaire.rdv_user_nom }}</span>
                                                        </div>
                                                    </button>
                                                </td>
                                                <td v-if="stagiaire.date_rdv != null && stagiaire.validation_rdv === 1" class="text-left" >
                                                    <div class="d-flex flex-row">
                                                        <div class="align-self-center mr-1"><i class="fas fa-calendar-check fa-lg text-green"></i></div>
                                                        <div class="d-flex flex-column text-left">
                                                            <span>{{ stagiaire.date_rdv | newDate }}</span>
                                                            <span class="mt-n1">{{ stagiaire.rdv_user_prenom }} {{ stagiaire.rdv_user_nom }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-left" v-if="stagiaire.date_rappel === null">
                                                    <button @click="faireRappel(stagiaire.id)">
                                                        <i class="fas fa-phone fa-lg text-pink mr-1"></i><i class="fas fa-times fa-lg text-red ml-1"></i>
                                                    </button>
                                                </td>
                                                <td class="text-left" v-else>
                                                    <div class="d-flex flex-row">
                                                        <div class="align-self-center mr-1"><i class="fas fa-phone fa-lg text-green"></i></div>
                                                        <div class="d-flex flex-column text-left">
                                                            <span>{{ stagiaire.date_rappel | newDate }}</span>
                                                            <span class="mt-n1">{{ stagiaire.rappel_user_prenom }} {{ stagiaire.rappel_user_nom }}</span>
                                                            <span>{{ stagiaire.rappel_resultat }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <router-link :to="{ name: 'showInscrit', params: { id: stagiaire.id }}">
                                                        <i class="fas fa-eye fa-lg text-blue mr-1"></i>
                                                    </router-link>
                                                    <span>|</span>
                                                    <button type="button" @click="reportStagiaire(stagiaire.id)">
                                                        <i class="fas fa-paper-plane fa-lg text-orange mx-1"></i>
                                                    </button>
                                                    <span>|</span>
                                                    <button type="button" class="ml-1" @click="editStagiaire(stagiaire.id)">
                                                        <i class="fas fa-edit fa-lg text-green mx-1"></i>
                                                    </button>
                                                    <span>|</span>
                                                    <button type="button" class="ml-1" @click="startDeleteStagiaire(stagiaire.id, stagiaire.prenom, stagiaire.nom)">
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
                                <div class="row d-flex justify-content-center mt-2" v-if="loadingDatas === true && lower_nom_formation  !== 'pmtic' && stagiaires.length > 0">
                                    <v-simple-table fixed-header>
                                        <template v-slot:default>
                                            <thead class>
                                            <tr>
                                                <th class="text-left">N°</th>
                                                <th class="text-center">Pr&eacute;nom & Nom</th>
                                                <th class="text-center">Infos</th>
                                                <th class="text-center">RDV</th>
                                                <th class="text-center">Rappel</th>
                                                <th class="text-left">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="myTr" v-for="(stagiaire, index) in stagiaires" :key="stagiaire.id">
                                                <td class="text-left"><strong>{{ index + 1 }}</strong></td>
                                                <td class="text-left"><strong>{{ stagiaire.prenom }} {{ stagiaire.nom | upperCase }}</strong></td>
                                                <td class="text-center">
                                                    <span v-if="stagiaire.tel !== null || stagiaire.gsm !== null || stagiaire.email !== null">
                                                         <button @click="afficherInfos(stagiaire.prenom, stagiaire.nom, stagiaire.tel, stagiaire.gsm, stagiaire.email)">
                                                             <i class="fas fa-user-graduate fa-lg mr-1"></i>
                                                             <span class="font-weight-bold" v-show="stagiaire.tel !== null">T&eacute;l. </span>
                                                             <span class="font-weight-bold" v-show="stagiaire.gsm !== null">GSM </span>
                                                             <span class="font-weight-bold"v-show="stagiaire.Email !== null">Email </span>
                                                         </button>
                                                    </span>
                                                    <span v-else class="no-information">Non renseigné</span>
                                                </td>
                                                <td class="text-center" v-if="stagiaire.date_rdv === null">
                                                    <button @click="fixerRdv(stagiaire.id)">
                                                        <i class="fas fa-calendar-check fa-lg text-pink mr-1"></i><i class="fas fa-times fa-lg text-red ml-1"></i>
                                                    </button>
                                                </td>
                                                <td v-if="stagiaire.date_rdv != null && stagiaire.validation_rdv === 0" class="text-center">
                                                    <button id="bouton_rdv_general" @click="fixerRdv(stagiaire.id)" class="d-flex flex-row">
                                                        <div class="mt-2"><i class="fas fa-calendar-check fa-lg text-orange mr-1"></i></div>
                                                        <div class="d-flex flex-column text-left">
                                                            <span>{{ stagiaire.date_rdv | newDate }}</span>
                                                            <span class="mt-n1">{{ stagiaire.rdv_user_prenom }} {{ stagiaire.rdv_user_nom }}</span>
                                                        </div>
                                                    </button>
                                                </td>
                                                <td v-if="stagiaire.date_rdv != null && stagiaire.validation_rdv === 1" class="text-left" >
                                                    <div class="d-flex flex-row">
                                                        <div class="align-self-center mr-1"><i class="fas fa-calendar-check fa-lg text-green"></i></div>
                                                        <div class="d-flex flex-column text-left">
                                                            <span>{{ stagiaire.date_rdv | newDate }}</span>
                                                            <span class="mt-n1">{{ stagiaire.rdv_user_prenom }} {{ stagiaire.rdv_user_nom }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td v-if="stagiaire.date_rappel === null" class="text-center" >
                                                    <button @click="faireRappel(stagiaire.id)">
                                                        <i class="fas fa-phone fa-lg text-pink mr-1"></i><i class="fas fa-times fa-lg text-red ml-1"></i>
                                                    </button>
                                                </td>
                                                <td class="text-left" v-else>
                                                    <div class="d-flex flex-row">
                                                        <div class="align-self-center mr-1"><i class="fas fa-phone fa-lg text-green"></i></div>
                                                        <div class="d-flex flex-column text-left">
                                                            <span>{{ stagiaire.date_rappel | newDate }}</span>
                                                            <span class="mt-n1">{{ stagiaire.rappel_user_prenom }} {{ stagiaire.rappel_user_nom }}</span>
                                                            <span>{{ stagiaire.rappel_resultat }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <router-link :to="{ name: 'showInscrit', params: { id: stagiaire.id }}">
                                                        <i class="fas fa-eye fa-lg text-blue mr-1"></i>
                                                    </router-link>
                                                    <span>|</span>
                                                    <button type="button" @click="reportStagiaire(stagiaire.id)">
                                                        <i class="fas fa-paper-plane fa-lg text-orange mx-1"></i>
                                                    </button>
                                                    <span>|</span>
                                                    <button type="button" class="ml-1" @click="startDeleteStagiaire(stagiaire.id, stagiaire.prenom, stagiaire.nom)">
                                                        <i class="fas fa-trash-alt fa-lg text-red mx-1"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </template>
                                    </v-simple-table>
                                </div>
                                <div class="row d-flex justify-content-center" v-if="loadingDatas === true && stagiaires.length === 0">
                                    <div class="col text-center">
                                        Aucun stagiaire inscrit à la formation
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item value="ajoutStagiaires">
                        <v-card flat tile>
                            <v-card-text >
                                <v-row>
                                    <v-col cols="12">
                                        <h5 class="text-center font-weight-bold text-red">Il reste {{ stagiairesRestants }} places.</h5>
                                    </v-col>
                                </v-row>
                                <div class="row row-cols-4" v-if="recrutements.length === 0 || (recrutements.length > 0 && date_today >= first_recrutement)">
                                    <div class="col col-3 mb-5">
                                        <b-form-input type="text" placeholder="Rechercher un·e inscrit·e" v-model="query"></b-form-input>
                                        <div v-if="results.length > 0 && query" class="list_inscrits elevation-4">
                                            <ul>
                                                <li v-for="result in results.slice(0,10)" :key="result.id">
                                                    <div class="d-inline-flex">
                                                        <span class="inscrit" v-on:click="ajoutStagiaire(result.searchable.id, result.searchable.nom, result.searchable.prenom)">
                                                            <i class="fas fa-user-plus fa-lg myIcon"></i>
                                                            <span v-text="result.title" class="span_inscrit"></span>
                                                        </span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col col-3">
                                        <h6>Liste des stagiaires à ajouter :</h6>
                                        <div v-if="listStagiaires.length > 0" class="list_stagiaires">
                                            <ul>
                                                <li v-for="(personne, index) in listStagiaires" :key="personne.id">
                                                    <div class="d-inline-flex stagiaire">
                                                        <i class="fas fa-user fa-lg myIconStagiaire"></i>
                                                        {{ personne.nom | upperCase }} {{ personne.prenom }}
                                                        <i class="fas fa-times fa-lg myIconDelete" v-on:click="deleteListStagiaires(index)"></i>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div v-else>
                                            Aucun
                                        </div>
                                    </div>
                                    <div class="col col-3 text-right">
                                        <button v-if="nbreStagiaires === formation.max_stagiaires" class="btn btn-success text-light" disabled>Inscrire les stagiaires</button>
                                        <button v-else class="btn btn-success text-light" @click="startStoreInscriptionStagiaires()">Inscrire les stagiaires</button>
                                    </div>
                                    <div class="col col-3 text-left" v-if="nbreStagiaires < formation.max_stagiaires">
                                        <router-link :to="{ name: 'createInscritWithFormation', params: { formation_id: formation.id }}"
                                                     class="link default">
                                            <button class="btn btn-interface text-light">
                                                <b-img v-bind="images" center src="../images/BoutonAjoutPerso.png" alt="Center image"></b-img>
                                                Faire une inscription
                                            </button>
                                        </router-link>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center" v-else>
                                    <div class="col text-center">
                                        L'ajout de stagiaires se fait via les recrutements
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item value="recrutements">
                        <v-card flat tile>
                            <v-card-text style="height: auto;">
                                <v-row v-if="recrutements.length > 0">
                                    <v-col class="col-3 default bloc text-center mb-5 elevation-2 ml-4 mr-2 mt-2" v-for="item in recrutements" :key="item.id">
                                        <router-link :to="{ name: 'showRecrutement', params: { id: item.id }}"
                                                     class="link default">
                                                <i class="fas fa-calendar-plus fa-lg myIcon"></i>
                                                <strong>Recrutement du {{ item.date | newDate }}</strong><br/>
                                        </router-link>
                                        <i class="fas fa-times fa-lg myIconDeleteStagiaire"
                                           v-show="currentUser.role === 'admin' || currentUser.role === 'master'"
                                           v-on:click="verifRecrutement(item.id)">
                                        </i>
                                    </v-col>
                                </v-row>
                                <v-row v-else>
                                    <v-col>
                                        Aucun recrutement prévu
                                    </v-col>
                                </v-row>
                                <v-row v-show="currentUser.role === 'admin' || currentUser.role === 'master'">
                                    <v-col cols="12" sm="6" class="d-flex justify-content-center">
                                        <v-date-picker
                                            v-model="listNewDatesRecrutements"
                                            multiple no-title
                                            :min="min"
                                            :max="max"
                                            :allowed-dates="allowedDaysRecrutement(listDatesRecrutements)"
                                            locale="fr"
                                            :first-day-of-week="weekday"
                                            color="green lighten-1"
                                        >
                                        </v-date-picker>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-menu :close-on-content-click="false"
                                                :return-value.sync="listNewDatesRecrutements"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="290px" >
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-combobox
                                                    v-model="listNewDatesRecrutements"
                                                    multiple
                                                    chips
                                                    small-chips
                                                    label="Date(s) de recrutement"
                                                    prepend-icon="mdi-calendar"
                                                    readonly
                                                    v-bind="attrs" v-on="on" >
                                                </v-combobox>
                                            </template>
                                        </v-menu>
                                    </v-col>
                                </v-row>
                                <v-row v-show="currentUser.role === 'admin' || currentUser.role === 'master'">
                                    <v-col class="d-flex justify-content-center">
                                        <button class="btn btn-success" @click.prevent="startTraitementRecrutements()" :disabled="listNewDatesRecrutements.length === 0">
                                            Sauvegarder les dates
                                        </button>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item value="documents">
                        <v-card flat tile>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col class="px-1">
                                            <a :href="'api/excel/formation-participants/' + formation.id" class="btn btn-interface button-link">Liste des participants</a>
                                            <button class="btn btn-interface button-link" @click="uploadListePresence()">Liste des pr&eacute;sences</button>
                                            <button class="btn btn-interface button-link" @click="uploadAbsences()">Doc. Absences justifi&eacute;es</button>
                                            <button class="btn btn-interface button-link" @click="openModalAttestationParticipation()">Attestation de participation</button>
                                            <button class="btn btn-interface button-link" @click="uploadAttestationDeplacement()">Attestation d&eacute;placement</button>
                                        </v-col>
                                    </v-row>
                                    <v-row class="mt-1">
                                        <v-col class="px-1">
                                            <a v-show="formation.pouvsub === 'Cefora'" :href="'api/excel/suivi-stagiaires/' + formation.id" class="btn btn-interface button-link">Suivi des stagiaires</a>
                                            <button v-show="formation.prix > 0" class="btn btn-interface button-link" @click="uploadAttestationsPaiement()">Attestations de paiement</button>
                                            <button v-show="formation.nom === 'PMTIC'" class="btn btn-interface button-link" @click="uploadAttestationsPMTIC()">Attestations PMTIC</button>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </v-col>
        </v-row>
        <!-- Modal d'édition des modules PMTIC-->
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Modules PMTIC pour {{ editedStagiaire.prenom }} {{ editedStagiaire.nom }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-checkbox
                                v-model="editedStagiaire.pmtic_module_1"
                                :label="`Module 1`"
                            ></v-checkbox>
                        </v-row>
                        <v-row>
                            <v-checkbox class="checkbox_module"
                                        v-model="editedStagiaire.pmtic_module_2"
                                        :label="`Module 2`"
                            ></v-checkbox>
                        </v-row>
                        <v-row>
                            <v-checkbox
                                v-model="editedStagiaire.pmtic_module_3"
                                :label="`Module 3`"
                            ></v-checkbox>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="dialog = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="storeModules(stagiaire.id)">Modifier</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Modal d'affichage d'infos des stagiaires -->
        <div class="modal fade" id="showInfosStagiaireModal" tabindex="-1" role="dialog" aria-labelledby="showPouvsubModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showInfosStagiaireTitle">Infos du stagiaire {{ infosStagiaire.prenom }} {{ infosStagiaire.nom | upperCase}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <v-row>
                            <v-col>
                                <v-simple-table fixed-header>
                                    <template v-slot:default>
                                        <thead class>
                                        <tr>
                                            <th class="text-center">T&eacute;l.</th>
                                            <th class="text-center">GSM</th>
                                            <th class="text-center">Email</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="myTr">
                                            <td class="text-center">
                                                <span v-if="infosStagiaire.tel !== null">{{ infosStagiaire.tel }}</span>
                                                <span v-else class="no-information">Non renseigné</span>
                                            </td>
                                            <td class="text-center">
                                                <span v-if="infosStagiaire.gsm !== null">{{ infosStagiaire.gsm }}</span>
                                                <span v-else class="no-information">Non renseigné</span>
                                            </td>
                                            <td class="text-center">
                                                <span v-if="infosStagiaire.email !== null">
                                                    <a class="mailtoui link-interface" :href="'mailto:' + infosStagiaire.email" @click="hideModal()">
                                                        {{ infosStagiaire.email }}
                                                    </a>
                                                </span>
                                                <span v-else class="no-information">Non renseigné</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </v-col>
                        </v-row>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal d'édition pour rendez-vous -->
        <v-dialog v-model="dialog_fixer_rdv" persistent max-width="800px" height="400px">
            <v-card>
                <v-card-title>
                    <span class="headline">Fixer un rendez-vous pour {{ stagiaire.prenom }} {{ stagiaire.nom }}</span>
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
                            </v-col>
                            <v-col>
                                <b-form-group
                                    id="group_rdv"
                                    label="Personne qui recevra le·la futur·e stagiaire :"
                                    label-for="rdv_user_id"
                                    invalid-feedback="La personne qui recevra le·la stagiaire doit être sélectionnée"
                                    :state="validationRdvUser">
                                    <b-input-group class="mb-2">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold"><i class="fas fa-user fa-lg text-interface ml-1 mr-2"></i></span>
                                        </b-input-group-prepend>
                                        <b-form-select id="rdv_user_id" v-model="formRdv.rdv_user_id" :state="validationRdvUser" trim>
                                            <b-form-select-option :value="null">Choisissez une personne...</b-form-select-option>
                                            <b-form-select-option v-for="user in users" :key="user.id" :value="user.id">{{ user.firstname }} {{ user.lastname }}</b-form-select-option>
                                        </b-form-select>
                                    </b-input-group>
                                </b-form-group>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="dialog_fixer_rdv = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="deleteRdv(stagiaire.id)" v-if="stagiaire.date_rdv != null && stagiaire.validation_rdv === 0">Supprimer</v-btn>
                    <v-spacer v-if="stagiaire.date_rdv != null && stagiaire.validation_rdv === 0"></v-spacer>
                    <v-btn color="success" @click="validateRdv(stagiaire.id)" v-if="stagiaire.date_rdv != null && stagiaire.validation_rdv === 0">Valider</v-btn>
                    <v-spacer v-if="stagiaire.date_rdv != null && stagiaire.validation_rdv === 0"></v-spacer>
                    <v-btn color="primary" v-show="formRdv.date_rdv !== null && formRdv.rdv_user_id !== null && stagiaire.date_rdv === null && stagiaire.validation_rdv === null" @click="storeRdv(stagiaire.id)">Cr&eacute;er</v-btn>
                    <v-btn color="primary" v-show="formRdv.date_rdv !== null && formRdv.rdv_user_id !== null && stagiaire.date_rdv !== null && stagiaire.validation_rdv === 0" @click="storeRdv(stagiaire.id)">Modifier</v-btn>
                    <v-btn color="primary" v-show="formRdv.date_rdv === null || formRdv.rdv_user_id === null" disabled>Cr&eacute;er</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Modal d'édition pour rappel -->
        <v-dialog v-model="dialog_faire_rappel" persistent max-width="800px" max-height="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Rappel pour {{ stagiaire.prenom }} {{ stagiaire.nom }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col>
                                <v-date-picker
                                    name="date_rappel" id="date_rappel"
                                    v-model="formRappel.date_rappel"
                                    locale="fr"
                                    :min="min"
                                    :max="max"
                                    :allowed-dates="allowedDays"
                                    :first-day-of-week="weekday">
                                </v-date-picker>
                            </v-col>
                            <v-col>
                                <b-form-group
                                    id="group_rappel_user"
                                    label="Personne qui a téléphoné pour le rappel :"
                                    label-for="rappel_user_id"
                                    invalid-feedback="La personne qui a passé le coup de fil n'a pas été sélectionnée"
                                    :state="validationRappelUser">
                                    <b-input-group class="mb-2">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold"><i class="fas fa-user fa-lg text-interface mx-0"></i></span>
                                        </b-input-group-prepend>
                                        <b-form-select id="rappel_user_id" v-model="formRappel.rappel_user_id" :state="validationRappelUser" trim>
                                            <b-form-select-option :value="null">Choisissez une personne...</b-form-select-option>
                                            <b-form-select-option v-for="user in users" :key="user.id" :value="user.id">{{ user.firstname }} {{ user.lastname }}</b-form-select-option>
                                        </b-form-select>
                                    </b-input-group>
                                </b-form-group>
                                <b-form-group
                                    id="group_rappel_resultat"
                                    label="Résultat de l'appel :"
                                    label-for="rappel_resultat"
                                    invalid-feedback="Le résultat de l'appel n'a pas été renseigné"
                                    :state="validationRappelResultat">
                                    <b-input-group class="mb-2">
                                        <b-input-group-prepend is-text>
                                            <span class="text-light-interface font-weight-bold"><i class="fas fa-comments fa-lg text-interface mx-0"></i></span>
                                        </b-input-group-prepend>
                                        <b-form-input id="rappel_resultat" v-model="formRappel.rappel_resultat" :state="validationRappelResultat" placeholder="Inscrivez le résultat de l'appel"></b-form-input>
                                    </b-input-group>
                                </b-form-group>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="dialog_faire_rappel = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" v-if="formRappel.date_rappel !== null && formRappel.rappel_user_id !== null && formRappel.rappel_resultat !== ''" @click="storeRappel(stagiaire.id)">Cr&eacute;er</v-btn>
                    <v-btn color="primary" v-else disabled>Cr&eacute;er</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Modal de report de stagiaire-->
        <v-dialog v-model="dialog_report_stagiaire" persistent max-width="800px">
            <v-card>
                <v-card-title>
                    <span class="headline">Report à une autre formation de {{ stagiaire.prenom }} {{ stagiaire.nom }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container class="mb-n6">
                        <b-form-group
                            id="group_report_formation"
                            label="Liste des autres formations :"
                            label-for="report_formation"
                            invalid-feedback="Aucune autre formaton sélectionnée"
                            :state="validationReportFormation">
                            <b-input-group>
                                <b-input-group-prepend is-text>
                                    <span class="text-light-interface font-weight-bold"><i class="fas fa-chalkboard-teacher fa-lg text-interface mx-0"></i></span>
                                </b-input-group-prepend>
                                <b-form-select v-model="report_formation" :state="validationReportFormation" id="report_formation">
                                    <b-form-select-option :value="null">Choisissez une autre formation...</b-form-select-option>
                                    <b-form-select-option v-for="other_formation in others_formations" :key="other_formation.id" :value="other_formation.id" :disabled="other_formation.nombre_inscrits === other_formation.max_stagiaires">
                                        {{ other_formation.nom }} <span v-if="other_formation.session !== '' && other_formation.session !== null ">- {{ other_formation.session }}</span> <span v-if="other_formation.nombre_inscrits === other_formation.max_stagiaires"> - complet</span>
                                    </b-form-select-option>
                                </b-form-select>
                            </b-input-group>
                        </b-form-group>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="dialog_report_stagiaire = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" v-if="report_formation != null" @click="updateStagiaire(stagiaire.id)">Reporter</v-btn>
                    <v-btn color="primary" v-else disabled>Reporter</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Modal pour le choix de tags pour un stagiaire supprimé de la formation -->
        <v-dialog v-model="dialog_choice_tags" persistent max-width="700px">
            <v-card>
                <v-card-title>
                    <span class="headline">Choix de tags pour {{ this.formTags.prenom }} {{ this.formTags.nom | upperCase }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col>
                                <b-form-group label="Sélectionner les centres d'intérêts dans la liste déroulante">
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
                                <v-btn class="btn-interface text-light" @click="createTags()">Créer les tags</v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="interruptionDeleteStagiaire()" class="mb-2">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="storeTags()" class="mb-2">Sauvegarder & supprimer</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Modal de vérification du recrutement pour pouvoir transférer les candidats -->
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
                                                    :items="otherRecrutements"
                                                    v-model="candidat[5]"
                                                    label="Autre(s) date(s)"
                                                ></v-select>
                                            </td>
                                            <td v-show="candidat[4] === true">
                                                <button type="button" class="ml-1" @click="reportCandidat(candidat[0], candidat[1] + ' ' + candidat[2], candidat[3], candidat[5])">
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
                    <v-btn color="warning" @click="dialog_verif_recrutement = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="deleteRecrutement(recrutement.id)">Supprimer</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Show Modal Infos Pouvoir subsidiant -->
        <div class="modal fade" id="showPouvsubModal" tabindex="-1" role="dialog" aria-labelledby="showPouvsubModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showPouvsubModalTitle">Infos des stagiaires de cette formation pour le pouvoir subsidiant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <b-img :src="linkLogo" fluid alt="logo" v-show="pouvsub.logo !== null"></b-img>
                            </div>
                            <div class="col-9">
                                <v-chip class="ma-2" :color="choixCouleur(infospouvsub.adresse)" text-color="white">
                                    <v-avatar left><v-icon>mdi-account-circle</v-icon></v-avatar>
                                    <div class="pr-1">ADRESSE</div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(infospouvsub.date_naissance)" text-color="white">
                                    <v-avatar left><v-icon>mdi-account-circle</v-icon></v-avatar>
                                    <div class="pr-1">DATE DE NAISSANCE</div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(infospouvsub.age)" text-color="white">
                                    <v-avatar left><v-icon>mdi-account-circle</v-icon></v-avatar>
                                    <div class="pr-1">&Acirc;GE</div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(infospouvsub.email)" text-color="white">
                                    <v-avatar left><v-icon>mdi-account-circle</v-icon></v-avatar>
                                    <div class="pr-1">EMAIL</div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(infospouvsub.num_national)" text-color="white">
                                    <v-avatar left><v-icon>mdi-account-circle</v-icon></v-avatar>
                                    <div class="pr-1">NUM&Eacute;RO NATIONAL</div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(infospouvsub.statut_legal)" text-color="white">
                                    <v-avatar left><v-icon>mdi-account-circle</v-icon></v-avatar>
                                    <div class="pr-1">STATUT L&Eacute;GAL</div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(infospouvsub.diplome)" text-color="white">
                                    <v-avatar left><v-icon>mdi-account-circle</v-icon></v-avatar>
                                    <div class="pr-1">DIPL&Ocirc;ME</div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(infospouvsub.duree_chomage)" text-color="white">
                                    <v-avatar left><v-icon>mdi-account-circle</v-icon></v-avatar>
                                    <div class="pr-1">DUREE DE CH&Ocirc;MAGE</div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(infospouvsub.source_info)" text-color="white">
                                    <v-avatar left><v-icon>mdi-account-circle</v-icon></v-avatar>
                                    <div class="pr-1">SOURCE INFO</div>
                                </v-chip>
                                <v-chip class="ma-2" :color="choixCouleur(infospouvsub.groupe_social)" text-color="white">
                                    <v-avatar left><v-icon>mdi-account-circle</v-icon></v-avatar>
                                    <div class="pr-1">GROUPE SOCIAL</div>
                                </v-chip>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal d'export d'attestation de participation à la formation -->
        <v-dialog v-model="dialog_attestation_participation" persistent width="550">
            <v-card>
                <v-card-title>
                    <span class="headline">Attestations de participation</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <div v-if="stagiaires.length > 0">
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
                                    <tr v-for="(stagiaire, index) in stagiaires" :key="index">
                                        <td><strong>{{ stagiaire.prenom }}</strong></td>
                                        <td><strong>{{ stagiaire.nom | upperCase }}</strong></td>
                                        <td class="d-flex justify-center py-1">
                                            <v-btn depressed color="success" @click="uploadAttestationParticipation(stagiaire)">
                                                T&eacute;l&eacute;charger
                                            </v-btn>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </div>
                        <div v-else>
                            Pas de stagiaire
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
        name: "ShowFormation",
        data() {
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
            const minDate = new Date(now).toISOString().substr(0, 10);
            return {
                loading: false,
                loadingDatas: false,
                date_today: today,
                first_recrutement: null,
                green: 'green',
                default: 'default',
                menu: false,
                hideHeader: true,
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
                images: { blank: false, width: 80, height: 55, class: 'mb-1 ombre' },
                weekday: 1,
                min: minDate,
                dayBeforeFinFormation: null,
                max: null,
                formation: {},
                lower_nom_formation: '',
                infospouvsub: {},
                pouvsub: {},
                linkLogo: '',
                stagiaires: {},
                stagiairesEmails: '',
                dialog_afficher_infos: false,
                infosStagiaire: {},
                dialog_fixer_rdv: false,
                formRdv : new Form({
                    date_rdv: null,
                    rdv_user_id: null,
                }),
                dialog_faire_rappel: false,
                formRappel : new Form({
                    date_rappel: null,
                    rappel_user_id: null,
                    rappel_resultat: '',
                }),
                users: {},
                dialog_verif_recrutement: false,
                dialog_report_stagiaire: false,
                report_formation: null,
                formations: {},
                others_formations: [],
                recrutements: {},
                listDatesRecrutements: [],
                listCandidats: {},
                listCandidatsTemp: [],
                candidat: [],
                candidats: [],
                listNewDatesRecrutements: [],
                recrutement: [],
                transfertCandidat: false,
                otherRecrutements: [],
                checkNbreTransfertTrue: false,
                checkAllCandidatsOk: false,
                nbreTransfertFalse: 0,
                nbreTransfertTrue: 0,
                newRecrutementId: null,
                dialog_recrutements_traitement: false,
                traitements: [],
                vitesse: 1000,
                nbreStagiaires: null,
                stagiairesRestants: null,
                dialog: false,
                editedIndex: -1,
                editedStagiaire: {
                    id: null,
                    prenom: '',
                    nom: '',
                    pmtic_module_1: false,
                    pmtic_module_2: false,
                    pmtic_module_3: false,
                },
                formModulesPmtic : new Form({
                    pmtic_module_1: null,
                    pmtic_module_2: null,
                    pmtic_module_3: null,
                }),
                formRecrutement : new Form({
                    formation_id: null,
                    date: null,
                }),
                dateManquante: 0,
                query: null,
                results: [],
                stagiaire: {},
                listStagiaires: [],
                dialog_choice_tags: false,
                formTags: new Form({
                    inscrit_id: null,
                    current_user: null,
                    prenom: '',
                    nom: '',
                    tags: [],
                }),
                listTags: [],
                newTags: [],
                formTag : new Form({
                    tag: null,
                }),
                dialog_attestation_participation: false,
            }
        },
        watch: {
            query(after, before) {
                this.searchInscrits();
            },
            selected(newValue, oldValue) {
                if(newValue > oldValue) {
                    this.nbrePlaces--;
                } else {
                    this.nbrePlaces++;
                }
            }
        },
        mounted() {
            console.log('Show Formation component mounted');
            this.getDatas();
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            validationRdvUser() {
                return this.formRdv.rdv_user_id != null;
            },
            validationRappelUser() {
                return this.formRappel.rappel_user_id != null;
            },
            validationRappelResultat() {
                return this.formRappel.rappel_resultat !== '';
            },
            validationReportFormation() {
                return this.report_formation != null;
            }
        },
        methods: {
            goBack() {
                return this.$router.go(-1);
            },
            allowedDaysRecrutement: function(a){
                return val => a.indexOf(val) === -1 && ![0,6].includes(new Date(val).getDay());
            },
            allowedDays: function(a){
                return val => [a].indexOf(val) === -1 && ![0,6].includes(new Date(val).getDay());
            },
            dateDisabled(ymd, date) {
                const weekday = date.getDay();
                const day = date.getDay();
                const jours = [];
                if(this.recrutements.length > 0) {
                    for(let i=0; i < this.recrutements.length; i++) {
                        jours.push(new Date(this.recrutements[i].date).toISOString().substr(8, 2));
                    }
                }
                return weekday === 0 || weekday === 6 || day === jours;
            },
            choixCouleur(item) {
                if(item === 1) {
                    return this.green
                } else {
                    return this.default
                }
            },
            getDatas() {
                this.getFormation();
                this.getRecrutements();
            },
            getFormation() {
                this.$Progress.start();
                this.loading = false;
                this.loadingDatas = false;
                this.listDatesRecrutements = [];
                axios.get(`/api/formations/show/${this.$route.params.id}`)
                    .then((response) => {
                        this.formation = response.data.formation;
                        this.dayBeforeFormation = new Date(this.formation.date_debut);
                        this.dayBeforeFormation = this.dayBeforeFormation.setDate(this.dayBeforeFormation.getDate() -1);
                        this.max = new Date(this.dayBeforeFormation).toISOString().substr(0, 10)
                        this.lower_nom_formation = this.formation.nom.toLowerCase();
                        this.recrutements = response.data.recrutements;
                        this.infospouvsub = response.data.infospouvsub;
                        this.pouvsub = response.data.pouvsub;
                        this.getLogo();
                        this.loading= true;
                        this.stagiaires = response.data.stagiaires;
                        this.transformStagiaires();
                        this.nbreStagiaires = this.stagiaires.length;
                        this.stagiairesRestants = response.data.formation.max_stagiaires - this.nbreStagiaires;
                        this.getStagiairesEmails();
                        this.$Progress.finish();
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
            async getLogo(){
                this.linkLogo = "images/logos/" + this.pouvsub.logo;
            },
            transformStagiaires() {
                this.stagiaires.forEach(stagiaire => {
                    if(stagiaire.pmtic_module_1 === 0) {
                        stagiaire.pmtic_module_1 = false;
                    } else {
                        stagiaire.pmtic_module_1 = true;
                    }
                    if(stagiaire.pmtic_module_2 === 0) {
                        stagiaire.pmtic_module_2 = false;
                    } else {
                        stagiaire.pmtic_module_2 = true;
                    }
                    if(stagiaire.pmtic_module_3 === 0) {
                        stagiaire.pmtic_module_3 = false;
                    } else {
                        stagiaire.pmtic_module_3 = true;
                    }
                });
            },
            getStagiairesEmails() {
                this.stagiairesEmails = '';
                for(let i=0; i < this.stagiaires.length; i++) {
                    if(this.stagiairesEmails === '' && this.stagiaires[i].email != null) {
                        this.stagiairesEmails = this.stagiaires[i].email;
                    } else {
                        if(this.stagiaires[i].email != null) {
                            this.stagiairesEmails = this.stagiairesEmails + ',' + this.stagiaires[i].email;
                        }
                    }
                }
                this.loadingDatas = true;
            },
            getRecrutements() {
                axios.get(`/api/recrutements/recrutementsFormation/`+this.$route.params.id)
                    .then((response) => {
                        this.listDatesRecrutements = [];
                        this.recrutements = response.data.recrutements;
                        this.listCandidats = response.data.candidats;
                        for(let i=0; i < this.recrutements.length; i++) {
                            this.listDatesRecrutements.push(this.recrutements[i].date);
                        }
                        this.loading = true;
                        if(this.recrutements.length > 0) {
                            this.first_recrutement = new Date(this.recrutements[0].date)
                            this.first_recrutement.setHours(0);
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire({
                            title: 'Problème avec la récupération de la liste des recrutements de la formation !',
                            timer: undefined,
                        })
                    })
            },
            show() {
                this.$Progress.start();
                $('#showPouvsubModal').modal('show');
                this.$Progress.finish();
            },
            afficherInfos(prenom, nom, tel, gsm, email) {
                this.infosStagiaire = {};
                this.infosStagiaire.prenom = prenom;
                this.infosStagiaire.nom = nom;
                this.infosStagiaire.tel = tel;
                this.infosStagiaire.gsm = gsm;
                this.infosStagiaire.email = email;
                $('#showInfosStagiaireModal').modal('show');
            },
            hideModal() {
                $('#showInfosStagiaireModal').modal('hide');
            },
            fixerRdv(id) {
                for(let i=0; i < this.stagiaires.length; i++) {
                    if(this.stagiaires[i].id === id) {
                        this.stagiaire = this.stagiaires[i];
                    }
                }
                if(this.stagiaire.date_rdv !== null) {
                    this.formRdv.date_rdv = this.stagiaire.date_rdv
                    this.formRdv.rdv_user_id = this.stagiaire.rdv_user_id
                }
                this.getFormRdv(this.stagiaire);
                this.getUsers();
                this.dialog_fixer_rdv = true;
            },
            getFormRdv(stagiaire) {
                this.formRdv.reset();
                this.formRdv.clear();
                this.formRdv.fill(stagiaire);
            },
            faireRappel(id) {
                for(let i=0; i < this.stagiaires.length; i++) {
                    if(this.stagiaires[i].id === id) {
                        this.stagiaire = this.stagiaires[i];
                    }
                }
                this.getUsers();
                this.dialog_faire_rappel = true;
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
                this.$Progress.start();
                this.formRdv
                    .post('api/inscrits/rdv/' + id +'/'+ this.formation.id)
                    .then(response => {
                        this.$Progress.finish();
                        Toast.fire(response.data.message);
                        this.dialog_fixer_rdv = false;
                        this.getFormation();
                        Fire.$emit('RefreshPage');
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$Progress.fail();
                        Snackbar.fire('Problème : rendez-vous non sauvegardé !');
                    })
            },
            validateRdv(id) {
                this.$Progress.start();
                this.formRdv
                    .put('api/inscrits/rdv/' + id +'/'+ this.formation.id)
                    .then(response => {
                        this.$Progress.finish();
                        Toast.fire(response.data.message);
                        this.dialog_fixer_rdv = false;
                        this.getFormation();
                        Fire.$emit('RefreshPage');
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$Progress.fail();
                        Snackbar.fire('Problème : rendez-vous non validé !');
                    })
            },
            deleteRdv(id) {
                axios.delete('api/inscrits/rdv/' + id +'/'+ this.formation.id)
                    .then(response => {
                        this.$Progress.finish();
                        Toast.fire(response.data.message);
                        this.dialog_fixer_rdv = false;
                        this.getFormation();
                        Fire.$emit('RefreshPage');
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$Progress.fail();
                        Snackbar.fire('Problème : rendez-vous non supprimé !');
                    })
            },
            storeRappel(id) {
                this.$Progress.start();
                this.formRappel
                    .put('api/inscrits/rappel/' + id +'/'+ this.formation.id)
                    .then(response => {
                        this.$Progress.finish();
                        Toast.fire('Rappel sauvegardé');
                        this.dialog_faire_rappel = false;
                        this.getFormation();
                        Fire.$emit('RefreshPage');
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$Progress.fail();
                        Snackbar.fire('Problème : rappel non sauvegardé !');
                    })

            },
            editStagiaire(item) {
                for (let i=0; i < this.stagiaires.length; i++) {
                    if(this.stagiaires[i].id === item) {
                        this.editedIndex = [i];
                        this.editedStagiaire = Object.assign({}, this.stagiaires[i]);
                    }
                }
                this.dialog = true;
            },
            storeModules() {
                this.$Progress.start();
                this.formModulesPmtic.pmtic_module_1 = this.editedStagiaire.pmtic_module_1;
                this.formModulesPmtic.pmtic_module_2 = this.editedStagiaire.pmtic_module_2;
                this.formModulesPmtic.pmtic_module_3 = this.editedStagiaire.pmtic_module_3;
                this.formModulesPmtic
                    .post('api/formations/updateInscrit/' + this.formation.id +'/'+ this.editedStagiaire.id)
                    .then(response => {
                        if(this.formModulesPmtic.successful) {
                            this.$Progress.finish();
                            Toast.fire('Modules édités');
                            this.editedIndex = -1;
                            this.editedStagiaire = {
                                id: null,
                                prenom: '',
                                nom: '',
                                pmtic_module_1: false,
                                pmtic_module_2: false,
                                pmtic_module_3: false,
                            };
                            this.dialog = false;
                            this.getFormation();
                            Fire.$emit('RefreshPage');
                        }
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$Progress.fail();
                        Snackbar.fire('Problème : Modules non édités !');
                    })
            },
            reportStagiaire(id) {
                for(let i=0; i < this.stagiaires.length; i++) {
                    if(this.stagiaires[i].id === id) {
                        this.stagiaire = this.stagiaires[i];
                    }
                }
                this.getOtherFormations(this.formation.id);
                this.dialog_report_stagiaire = true;
            },
            getOtherFormations(id) {
                axios.get(`/api/formations/others/` + id)
                    .then((response) => {
                        this.others_formations = response.data.othersFormations;
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la récupartion de la liste des autres formations !');
                    })
            },
            updateStagiaire(id) {
                if(this.report_formation === null) {
                    Snackbar.fire('Vous n\'avez pas sélectionné une autre formation !');
                } else {
                    this.$Progress.start();
                    axios.put('api/inscrits/formation/' + id +'/'+ this.formation.id +'/'+ this.report_formation)
                        .then(response => {
                            this.$Progress.finish();
                            Toast.fire('Transfert du stagiaire effectué');
                            this.dialog_report_stagiaire = false;
                            this.getFormation();
                            Fire.$emit('RefreshPage');
                        })
                        .catch(error => {
                            console.log(error.response);
                            this.$Progress.fail();
                            Snackbar.fire('Transfert du stagiaire non effectué !');
                        })
                }
            },
            searchInscrits() {
                if(this.query != null) {
                    axios.get('api/inscrits/search', { params: { query: this.query } })
                        .then(response => this.results = response.data)
                        .catch(error => { console.log(error.response) } );
                }
            },
            ajoutStagiaire: function(id, nom, prenom) {
                this.stagiaire['id'] = id;
                this.stagiaire['nom'] = nom;
                this.stagiaire['prenom'] = prenom;
                this.ajoutListStagiaires(this.stagiaire);
                this.stagiaire= {};
            },
            ajoutListStagiaires(stagiaire) {
                if(this.stagiairesRestants === 0) {
                    Snackbar.fire('Nombre maximum de stagiaires atteint !');
                } else {
                    this.listStagiaires.push(stagiaire);
                    this.stagiairesRestants--;
                }
            },
            deleteListStagiaires: function (index) {
                this.listStagiaires.splice(index, 1);
                this.stagiairesRestants++;
            },

            startStoreInscriptionStagiaires() {
                this.traitements = [];
                this.dialog_recrutements_traitement = true;
                setTimeout(() => this.storeInscriptionStagiaires(this.listStagiaires[0].id, this.listStagiaires[0].prenom + ' ' + this.listStagiaires[0].nom, 0), this.vitesse);
            },

            storeInscriptionStagiaires(id, stagiaire, compteur) {
                this.$Progress.start();
                axios.post('api/formations/addInscrit/' + this.formation.id +'/'+ id)
                    .then(response => {
                        this.$Progress.finish();
                        if(response.data.message != null) {
                            this.traitements.push(stagiaire + ' inscrit·e.');
                        } else if (response.data.error != null) {
                            this.traitements.push(stagiaire + ' déjà inscrit·e.');
                        }
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.traitements.push(stagiaire + ' non inscrit·e.');
                    })
                compteur += 1;
                if(compteur < this.listStagiaires.length) {
                    setTimeout(() => this.storeInscriptionStagiaires(this.listStagiaires[compteur].id, this.listStagiaires[compteur].prenom + ' ' + this.listStagiaires[compteur].nom, compteur), this.vitesse);
                } else {
                    this.listStagiaires = [];
                    Toast.fire('Inscriptions effectuées !');
                    this.$Progress.finish();
                    this.dialog_recrutements_traitement = false;
                    this.query = null;
                    this.getFormation();
                }
            },

            getTags() {
                axios.get(`/api/tags/all`)
                    .then((response) => {
                        this.listTags = response.data.tags;
                        this.$Progress.finish();
                        this.loading= true;
                    })
                    .catch(error => {
                        console.log(error.response)
                        Snackbar.fire('Problème avec la récupération de la liste des tags!');
                    })
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
                if (this.newTags.length < 1) {
                    this.error = 'Pas de nouveau tag à créer';
                    this.snackbarError = true;
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
                            this.$Progress.finish();
                            Toast.fire('Tag(s) créé(s)');
                            this.getTags();
                            this.newTags = [];
                            this.formTags.tags.push(this.formTag.tag);
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la création de tag(s) !');
                    })
            },

            storeTags() {
                this.$Progress.start();
                if(this.formTags.tags.length > 0) {
                    this.formTags.current_user = this.currentUser.id;
                    this.formTags
                        .post('api/inscrits/addTags/' + this.formTags.inscrit_id)
                        .then(response => {
                            console.log(response);
                            this.$Progress.finish();
                            Toast.fire({
                                title: response.data,
                                timer: 1000
                            });
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            Snackbar.fire('Problème avec la sauvegarde de(s) tag(s) pour le stagiaire !');
                        })
                }
                this.deleteStagiaire(this.formTags.inscrit_id);
            },

            startDeleteStagiaire(id, prenom, nom) {
                this.$Progress.start();
                this.getTags();
                Suppression.fire({
                    text: "Si vous continuez, il·elle ne participera plus à la formation!",
                }).then((result) => {
                    if (result.value) {
                        this.choiceTags(id, prenom, nom);
                    }
                }).catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la suppression d\'un stagiaire !');
                });
            },

            choiceTags(id, prenom, nom) {
                this.dialog_choice_tags = true;
                this.formTags.inscrit_id = id;
                this.formTags.prenom = prenom;
                this.formTags.nom = nom;
                this.formTags.tags = [];
            },

            interruptionDeleteStagiaire() {
                this.dialog_choice_tags = false;
                Snackbar.fire('Stagiaire non supprimé de la formation !');
            },

            deleteStagiaire(id) {
                this.dialog_choice_tags = false;
                axios.delete('api/formations/deleteInscrit/' + this.formation.id +'/'+ id)
                    .then(response => {
                        this.$Progress.finish();
                        Toast.fire('Stagiaire supprimé de la formation');
                        this.getFormation();
                        Fire.$emit('RefreshPage');
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la suppression du stagiaire à la formation !');
                    })
            },

            startTraitementRecrutements() {
                this.traitements = [];
                this.dialog_recrutements_traitement = true;
                setTimeout(() => this.storeRecrutement(this.listNewDatesRecrutements[0], 0), this.vitesse);
            },

            storeRecrutement(date, compteur) {
                this.$Progress.start();
                this.formRecrutement = new Form({
                    formation_id: this.formation.id,
                    date: date,
                });
                this.formRecrutement
                    .post('api/recrutements')
                    .then(response => {
                        if(this.formRecrutement.successful) {
                            this.$Progress.finish();
                            this.traitements.push('Recrutement du ' + date + ' créé.');
                            this.recrutements.push(response.data.data);
                            this.listDatesRecrutements.push(response.data.data.date)
                        }
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$Progress.fail();
                        this.traitements.push('Recrutement du ' + date + ' non créé.');
                    })
                compteur += 1;
                if(compteur < this.listNewDatesRecrutements.length) {
                    setTimeout(() => this.storeRecrutement(this.listNewDatesRecrutements[compteur], compteur), this.vitesse);
                } else {
                    this.listNewDatesRecrutements = [];
                    Toast.fire('Recrutement(s) sauvergardé(s) !');
                    this.dialog_recrutements_traitement = false;
                }
            },

            verifRecrutement(recrutement) {
                this.listCandidatsTemp = [];
                this.candidats = [];
                this.candidat = [];
                this.otherRecrutements = [];
                for (let i=0; i < this.listCandidats.length; i++) {
                    this.listCandidatsTemp = this.listCandidats[i];
                    if(this.listCandidatsTemp.length > 1) {
                        for (let x=0; x < this.listCandidatsTemp.length; x++) {
                            if(this.listCandidatsTemp[x].recrutement_id === recrutement) {
                                this.candidat.push(this.listCandidatsTemp[x].inscrit_id);
                                this.candidat.push(this.listCandidatsTemp[x].prenom);
                                this.candidat.push(this.listCandidatsTemp[x].nom);
                                this.candidat.push(this.listCandidatsTemp[x].recrutement_id);
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
                            this.candidat.push(this.listCandidatsTemp[0].recrutement_id);
                            this.candidats.push(this.candidat);
                            this.candidat = [];
                        }
                    }
                    this.listCandidatsTemp = [];
                }
                for (let y=0; y < this.recrutements.length; y++) {
                    if (this.recrutements[y].id === recrutement) {
                        this.recrutement = this.recrutements[y];
                    } else {
                        this.otherRecrutements.push(this.recrutements[y].date);
                    }
                }
                this.dialog_verif_recrutement = true;
                this.transfertCandidat = false;
            },

            reportCandidat(candidat_id, candidat, id, recrutement) {
                if(recrutement === undefined) {
                    Snackbar.fire({
                        title: 'Vous n\'avez pas encodé de date pour ' + candidat,
                        timer: undefined,
                    })
                }
                for (let i=0; i < this.recrutements.length; i++) {
                    if(this.recrutements[i].date === recrutement) {
                        this.newRecrutementId = this.recrutements[i].id;
                    }
                }
                axios.put('api/inscrits/recrutement/' + candidat_id + '/' + id +'/'+ this.newRecrutementId)
                    .then(response => {
                        for (let x=0; x < this.candidats.length; x++) {
                            if(this.candidats[x][0] === candidat_id) {
                                this.candidats[x].push(true);
                            }
                        }
                        Toast.fire('Transfert du candidat effectué !');
                    })
                    .catch(error => {
                        console.log(error.response);
                        for (let x=0; x < this.candidats.length; x++) {
                            if(this.candidats[x][0] === candidat_id) {
                                this.candidats[x].push(false);
                            }
                        }
                        Snackbar.fire('Transfert du candidat non effectué !');
                    })
            },

            deleteRecrutement(recrutement_id) {
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
                        Snackbar.fire('Vous n\'avez pas transféré personne');
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
                                axios.delete('api/recrutements/' + recrutement_id)
                                    .then(response => {
                                        this.listNewDatesRecrutements = [];
                                        this.getRecrutements();
                                        Toast.fire('Recrutement Supprimé! \n Transfert du candidat effectué');
                                        this.$Progress.finish();
                                        this.dialog_verif_recrutement = false;
                                    })
                                    .catch(error => {
                                        console.log(error.response);
                                        this.$Progress.fail();
                                        Snackbar.fire('Problème avec la suppresion du recrutement !');
                                    });
                            } else if(this.transfertCandidat === false) {
                                axios.delete('api/recrutements/' + recrutement_id)
                                    .then(response => {
                                        this.listNewDatesRecrutements = [];
                                        this.getRecrutements();
                                        // Fire.$emit('RefreshPage');
                                        Toast.fire('Recrutement Supprimé!');
                                        this.$Progress.finish();
                                        this.dialog_verif_recrutement = false;
                                    })
                                    .catch(error => {
                                        this.$Progress.fail();
                                        console.log(error.response);
                                        Snackbar.fire('Problème avec la suppresion du recrutement !');
                                    });
                            }
                        }
                    });
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

            uploadListeParticipants() {
                axios.get('api/excel/formation-participants/' + this.formation.id)
                    .catch(error => {
                        console.log(error.response);
                        this.$Progress.fail();
                        this.traitements.push('Excel non exporté.');
                    })
            },

            uploadListePresence() {
                axios({
                    url: '/PDF/formation/presences/' + this.formation.id,
                    method: 'GET',
                    responseType: 'blob',
                })
                    .then(response => {
                        this.forceFileDownload(response, `${this.formation.abreviation}_liste-presence.pdf`);
                    })
                    .catch(error => {
                        console.log(error.response);
                    })
            },

            uploadAbsences() {
                axios({
                    url: '/PDF/formation/absences/' + this.formation.id,
                    method: 'GET',
                    responseType: 'blob',
                })
                    .then(response => {
                        this.forceFileDownload(response, `${this.formation.abreviation}_absences.pdf`);
                    })
                    .catch(error => {
                        console.log(error.response);
                    })
            },

            openModalAttestationParticipation() {
                this.dialog_attestation_participation = true;
            },

            uploadAttestationParticipation(stagiaire) {
                axios({
                    url: '/PDF/formation/participation/' + this.formation.id + '/' + stagiaire.id,
                    method: 'GET',
                    responseType: 'blob',
                })
                    .then(response => {
                        this.forceFileDownload(response, `attestation_participation_${stagiaire.nom}_${stagiaire.prenom}.pdf`);
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire("Problème avec l'attestation de participation à télécharger !");
                    })
            },

            uploadAttestationDeplacement() {
                axios({
                    url: '/PDF/formation/deplacement/' + this.formation.id,
                    method: 'GET',
                    responseType: 'blob',
                })
                    .then(response => {
                        this.forceFileDownload(response, `attestations_deplacement.pdf`);
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire("Problème avec les attestations de déplacement à télécharger !");
                    })
            },

            uploadSuiviStagiaires() {

            },

            uploadAttestationsPaiement() {
                axios({
                    url: '/PDF/formation/paiements/' + this.formation.id,
                    method: 'GET',
                    responseType: 'blob',
                })
                    .then(response => {
                        this.forceFileDownload(response, `attestations_paiement_${this.formation.abreviation}.pdf`);
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire("Problème avec les attestations de paiement à télécharger !");
                    })
            },

            uploadAttestationsPMTIC() {
                axios({
                    url: '/PDF/formation/pmtic/' + this.formation.id,
                    method: 'GET',
                    responseType: 'blob',
                })
                    .then(response => {
                        this.forceFileDownload(response, `attestations_pmtic.pdf`);
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire("Problème avec les attestations à télécharger de fréquentation et des capacités pour le PMTIC !");
                    })
            },

            destroyFormation() {
                this.$Progress.start();
                Suppression.fire().then((result) => {
                    if (result.value) {
                        axios.delete('api/formations/' + this.formation.id)
                            .then(response => {
                                this.$Progress.finish();
                                Confirm.fire('Formation supprimée !');
                                this.$router.push('/home');
                            })
                            .catch(error => {
                                console.log(error.response)
                                this.$Progress.fail();
                                Snackbar.fire('Problème avec la suppression de la formation !');
                            })
                    }
                });
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
        text-decoration: none !important;
    }

    .bloc {
        padding: 0.5em 0.5em;
        border-radius: 10px;
        background-color: #389fad;
    }

    .bloc:hover {
        color: #193340;
    }

    .theme--light.v-data-table tbody tr:not(:last-child) td:not(.v-data-table__mobile-row), .theme--light.v-data-table tbody tr:not(:last-child) th:not(.v-data-table__mobile-row) {
        border-bottom: none;
    }

    .theme--light.v-data-table.v-data-table--fixed-header thead th {
        background-color: #317283 !important;
    }

    .myTr {
        border-bottom: thin solid rgba(0, 0, 0, 0.12) !important;
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

    .list_stagiaires > ul > li {
        list-style-type: none;
        margin-bottom: 2px;
    }

    .stagiaire {
        background-color: #389fad;
        border-radius: 10px;
        padding: 5px 10px;
        color: #fff;
    }

    .myIconStagiaire {
        vertical-align: -0.2em !important;
        margin-right: 5px;
    }

    .myIconDelete {
        vertical-align: -0.2em !important;
        margin-left: 5px;
        cursor: pointer;
    }

    .myIconDeleteStagiaire {
        margin-left: 15px;
        cursor: pointer;
        color: #fff;
    }

    .myIconDeleteStagiaire:hover {
        color: #e3342f;
    }
</style>
