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
                <h1 class="text-h4">Fiche de Formation</h1>
            </v-col>
        </v-row>

        <!-- Main Content -->
        <div v-if="loading">
            <!-- Formation Info Card -->
            <v-card class="mb-4">
                <v-card-text>
                    <v-row align="center">
                        <v-col cols="11" class="text-center">
                            <h2 class="text-h5 font-weight-bold text-uppercase">{{ formation.nom }}</h2>
                            <span class="text-subtitle-1 text-uppercase">{{ formation.session }}</span>
                        </v-col>
                        <v-col cols="1" class="text-right">
                            <v-speed-dial location="bottom end" transition="fade-transition">
                                <template v-slot:activator="{ props: activatorProps }">
                                    <v-btn v-bind="activatorProps" icon color="primary" variant="elevated">
                                        <v-icon>mdi-cog</v-icon>
                                    </v-btn>
                                </template>
                                <v-btn icon color="success" size="small" :to="{ name: 'editFormation', params: { id: formation.id }}">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon color="error" size="small" @click="destroyFormation">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </v-speed-dial>
                        </v-col>
                    </v-row>

                    <v-row class="mt-2">
                        <v-col cols="12">
                            <span class="text-caption text-grey">
                                Création : {{ formatDate(formation.created_at) }} |
                                Dernière modification : {{ formatDate(formation.updated_at) }}
                            </span>
                        </v-col>
                    </v-row>

                    <v-row class="mt-4">
                        <v-col cols="12" md="3">
                            <v-icon color="primary" class="mr-2">mdi-calendar</v-icon>
                            Du <strong>{{ formatDate(formation.date_debut) }}</strong> au <strong>{{ formatDate(formation.date_fin) }}</strong>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-icon color="primary" class="mr-2">mdi-account-group</v-icon>
                            De <strong>{{ formation.min_stagiaires }}</strong> à <strong>{{ formation.max_stagiaires }}</strong> stagiaires
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-icon color="primary" class="mr-2">mdi-clock</v-icon>
                            <strong>{{ formation.nbre_heures }} h</strong>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-icon color="primary" class="mr-2">mdi-currency-eur</v-icon>
                            <strong>{{ formation.prix }}€</strong>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="3">
                            <v-icon color="primary" class="mr-2">mdi-door</v-icon>
                            Salle : <strong>{{ formation.salle || 'Non définie' }}</strong>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-icon color="primary" class="mr-2">mdi-account</v-icon>
                            Tuteur : <strong>{{ formation.tuteur_prenom }}</strong>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-icon color="primary" class="mr-2">mdi-bell</v-icon>
                            Statut : <strong>{{ formation.statut }}</strong>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-icon color="primary" class="mr-2">mdi-shield-lock</v-icon>
                            RGPD : <strong>{{ formation.duree_rgpd }} ans</strong>
                        </v-col>
                    </v-row>

                    <v-row v-if="formation.commentaire_formation" class="mt-4">
                        <v-col cols="12">
                            <h6 class="font-weight-bold">Commentaires :</h6>
                            <p>{{ formation.commentaire_formation }}</p>
                        </v-col>
                    </v-row>

                    <v-row v-if="stagiaires.length > 0" class="mt-2">
                        <v-col>
                            <v-btn color="primary" :href="'mailto:?bcc=' + stagiairesEmails" prepend-icon="mdi-email">
                                Envoyer un mail aux stagiaires
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

            <!-- Tabs -->
            <v-card>
                <v-tabs v-model="tab" bg-color="primary">
                    <v-tab value="stagiaires">Liste des stagiaires</v-tab>
                    <v-tab value="ajout">Ajout de stagiaires</v-tab>
                    <v-tab value="recrutements">Recrutement(s)</v-tab>
                    <v-tab value="documents">Documents</v-tab>
                </v-tabs>

                <v-tabs-window v-model="tab">
                    <!-- Tab: Liste des stagiaires -->
                    <v-tabs-window-item value="stagiaires">
                        <v-card-text>
                            <div v-if="!loadingDatas" class="text-center py-8">
                                <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
                                <div class="mt-4">Chargement...</div>
                            </div>
                            <div v-else-if="stagiaires.length > 0">
                                <v-table fixed-header>
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th class="text-center">Prénom & Nom</th>
                                            <th class="text-center">Infos</th>
                                            <th class="text-center">RDV</th>
                                            <th class="text-center">Rappel</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(stagiaire, index) in stagiaires" :key="stagiaire.id">
                                            <td><strong>{{ index + 1 }}</strong></td>
                                            <td><strong>{{ stagiaire.prenom }} {{ stagiaire.nom?.toUpperCase() }}</strong></td>
                                            <td class="text-center">
                                                <v-btn v-if="stagiaire.tel || stagiaire.gsm || stagiaire.email"
                                                    icon variant="text" size="small"
                                                    @click="afficherInfos(stagiaire)">
                                                    <v-icon>mdi-information</v-icon>
                                                </v-btn>
                                                <span v-else class="text-grey">-</span>
                                            </td>
                                            <td class="text-center">
                                                <v-btn v-if="!stagiaire.date_rdv" icon variant="text" size="small" @click="fixerRdv(stagiaire.id)">
                                                    <v-icon color="grey">mdi-calendar-check</v-icon>
                                                </v-btn>
                                                <v-chip v-else-if="stagiaire.validation_rdv === 0" color="warning" size="small" @click="fixerRdv(stagiaire.id)">
                                                    {{ formatDate(stagiaire.date_rdv) }}
                                                </v-chip>
                                                <v-chip v-else color="success" size="small">
                                                    {{ formatDate(stagiaire.date_rdv) }}
                                                </v-chip>
                                            </td>
                                            <td class="text-center">
                                                <v-btn v-if="!stagiaire.date_rappel" icon variant="text" size="small" @click="faireRappel(stagiaire.id)">
                                                    <v-icon color="grey">mdi-phone</v-icon>
                                                </v-btn>
                                                <v-chip v-else color="success" size="small">
                                                    {{ formatDate(stagiaire.date_rappel) }}
                                                </v-chip>
                                            </td>
                                            <td>
                                                <v-btn icon variant="text" size="small" color="primary" :to="{ name: 'showInscrit', params: { id: stagiaire.id }}">
                                                    <v-icon>mdi-eye</v-icon>
                                                </v-btn>
                                                <v-btn icon variant="text" size="small" color="warning" @click="reportStagiaire(stagiaire.id)">
                                                    <v-icon>mdi-send</v-icon>
                                                </v-btn>
                                                <v-btn icon variant="text" size="small" color="error" @click="startDeleteStagiaire(stagiaire.id, stagiaire.prenom, stagiaire.nom)">
                                                    <v-icon>mdi-delete</v-icon>
                                                </v-btn>
                                            </td>
                                        </tr>
                                    </tbody>
                                </v-table>
                            </div>
                            <div v-else class="text-center py-8 text-grey">
                                Aucun stagiaire inscrit à la formation
                            </div>
                        </v-card-text>
                    </v-tabs-window-item>

                    <!-- Tab: Ajout de stagiaires -->
                    <v-tabs-window-item value="ajout">
                        <v-card-text>
                            <v-alert type="info" variant="tonal" class="mb-4">
                                Il reste <strong>{{ stagiairesRestants }}</strong> places.
                            </v-alert>

                            <v-row v-if="recrutements.length === 0 || (recrutements.length > 0 && date_today >= first_recrutement)">
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
                                            @click="ajoutStagiaire(result.searchable.id, result.searchable.nom, result.searchable.prenom)"
                                        >
                                            <template v-slot:prepend>
                                                <v-icon color="success">mdi-account-plus</v-icon>
                                            </template>
                                            <v-list-item-title>{{ result.title }}</v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <h6 class="mb-2">Liste des stagiaires à ajouter :</h6>
                                    <v-chip
                                        v-for="(personne, index) in listStagiaires"
                                        :key="personne.id"
                                        class="ma-1"
                                        closable
                                        color="primary"
                                        @click:close="deleteListStagiaires(index)"
                                    >
                                        {{ personne.nom?.toUpperCase() }} {{ personne.prenom }}
                                    </v-chip>
                                    <p v-if="!listStagiaires.length" class="text-grey">Aucun</p>
                                </v-col>
                                <v-col cols="12" md="4" class="d-flex flex-column ga-2">
                                    <v-btn
                                        color="success"
                                        :disabled="nbreStagiaires === formation.max_stagiaires || !listStagiaires.length"
                                        @click="startStoreInscriptionStagiaires"
                                        prepend-icon="mdi-account-check"
                                    >
                                        Inscrire les stagiaires
                                    </v-btn>
                                    <v-btn
                                        v-if="nbreStagiaires < formation.max_stagiaires"
                                        color="primary"
                                        :to="{ name: 'createInscritWithFormation', params: { formation_id: formation.id }}"
                                        prepend-icon="mdi-account-plus"
                                    >
                                        Faire une inscription
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <div v-else class="text-center py-8 text-grey">
                                L'ajout de stagiaires se fait via les recrutements
                            </div>
                        </v-card-text>
                    </v-tabs-window-item>

                    <!-- Tab: Recrutements -->
                    <v-tabs-window-item value="recrutements">
                        <v-card-text>
                            <v-row v-if="recrutements.length > 0" class="mb-4">
                                <v-col cols="12" md="3" v-for="item in recrutements" :key="item.id">
                                    <v-card color="primary" variant="elevated" class="text-center">
                                        <v-card-text>
                                            <router-link :to="{ name: 'showRecrutement', params: { id: item.id }}" class="text-white text-decoration-none">
                                                <v-icon size="large" class="mb-2">mdi-calendar-plus</v-icon>
                                                <div class="font-weight-bold">Recrutement du {{ formatDate(item.date) }}</div>
                                            </router-link>
                                        </v-card-text>
                                        <v-card-actions v-if="currentUser?.role === 'admin' || currentUser?.role === 'master'">
                                            <v-spacer></v-spacer>
                                            <v-btn icon variant="text" color="white" @click="verifRecrutement(item.id)">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-col>
                            </v-row>
                            <v-alert v-else type="info" variant="tonal" class="mb-4">
                                Aucun recrutement prévu
                            </v-alert>

                            <template v-if="currentUser?.role === 'admin' || currentUser?.role === 'master'">
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-date-picker
                                            v-model="listNewDatesRecrutements"
                                            multiple
                                            :min="min"
                                            :max="max"
                                            color="success"
                                            show-adjacent-months
                                        ></v-date-picker>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-card variant="outlined">
                                            <v-card-title>Nouvelles dates</v-card-title>
                                            <v-card-text>
                                                <v-chip
                                                    v-for="(date, index) in listNewDatesRecrutements"
                                                    :key="index"
                                                    class="ma-1"
                                                    closable
                                                    @click:close="listNewDatesRecrutements.splice(index, 1)"
                                                >
                                                    {{ formatDateDisplay(date) }}
                                                </v-chip>
                                                <p v-if="!listNewDatesRecrutements.length" class="text-grey">
                                                    Aucune date sélectionnée
                                                </p>
                                            </v-card-text>
                                        </v-card>
                                        <v-btn
                                            color="success"
                                            class="mt-4"
                                            :disabled="!listNewDatesRecrutements.length"
                                            @click="startTraitementRecrutements"
                                            block
                                        >
                                            Sauvegarder les dates
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </template>
                        </v-card-text>
                    </v-tabs-window-item>

                    <!-- Tab: Documents -->
                    <v-tabs-window-item value="documents">
                        <v-card-text>
                            <v-row>
                                <v-col cols="12">
                                    <v-btn color="primary" class="ma-1" :href="'api/excel/formation-participants/' + formation.id" prepend-icon="mdi-file-excel">
                                        Liste des participants
                                    </v-btn>
                                    <v-btn color="primary" class="ma-1" @click="uploadListePresence" prepend-icon="mdi-file-pdf-box">
                                        Liste des présences
                                    </v-btn>
                                    <v-btn color="primary" class="ma-1" @click="uploadAbsences" prepend-icon="mdi-file-pdf-box">
                                        Doc. Absences justifiées
                                    </v-btn>
                                    <v-btn color="primary" class="ma-1" @click="openModalAttestationParticipation" prepend-icon="mdi-file-pdf-box">
                                        Attestation de participation
                                    </v-btn>
                                    <v-btn color="primary" class="ma-1" @click="uploadAttestationDeplacement" prepend-icon="mdi-file-pdf-box">
                                        Attestation déplacement
                                    </v-btn>
                                    <v-btn v-if="formation.prix > 0" color="primary" class="ma-1" @click="uploadAttestationsPaiement" prepend-icon="mdi-file-pdf-box">
                                        Attestations de paiement
                                    </v-btn>
                                </v-col>
                            </v-row>
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

        <!-- Dialog: Infos Stagiaire -->
        <v-dialog v-model="dialog_afficher_infos" max-width="500">
            <v-card>
                <v-card-title>Infos de {{ infosStagiaire.prenom }} {{ infosStagiaire.nom?.toUpperCase() }}</v-card-title>
                <v-card-text>
                    <v-list>
                        <v-list-item prepend-icon="mdi-phone">
                            <v-list-item-title>Tél: {{ infosStagiaire.tel || 'Non renseigné' }}</v-list-item-title>
                        </v-list-item>
                        <v-list-item prepend-icon="mdi-cellphone">
                            <v-list-item-title>GSM: {{ infosStagiaire.gsm || 'Non renseigné' }}</v-list-item-title>
                        </v-list-item>
                        <v-list-item prepend-icon="mdi-email">
                            <v-list-item-title>
                                <a v-if="infosStagiaire.email" :href="'mailto:' + infosStagiaire.email">{{ infosStagiaire.email }}</a>
                                <span v-else>Non renseigné</span>
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="dialog_afficher_infos = false">Fermer</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Dialog: Fixer RDV -->
        <v-dialog v-model="dialog_fixer_rdv" persistent max-width="800">
            <v-card>
                <v-card-title>Fixer un rendez-vous pour {{ stagiaire.prenom }} {{ stagiaire.nom }}</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-date-picker v-model="formRdv.date_rdv" :min="min" :max="max" color="primary"></v-date-picker>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select
                                v-model="formRdv.rdv_user_id"
                                :items="users"
                                :item-title="item => item.firstname + ' ' + item.lastname"
                                item-value="id"
                                label="Personne qui recevra le stagiaire"
                                variant="outlined"
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="dialog_fixer_rdv = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn v-if="stagiaire.date_rdv && stagiaire.validation_rdv === 0" color="error" @click="deleteRdv(stagiaire.id)">Supprimer</v-btn>
                    <v-btn v-if="stagiaire.date_rdv && stagiaire.validation_rdv === 0" color="success" @click="validateRdv(stagiaire.id)">Valider</v-btn>
                    <v-btn color="primary" :disabled="!formRdv.date_rdv || !formRdv.rdv_user_id" @click="storeRdv(stagiaire.id)">
                        {{ stagiaire.date_rdv ? 'Modifier' : 'Créer' }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Dialog: Faire Rappel -->
        <v-dialog v-model="dialog_faire_rappel" persistent max-width="800">
            <v-card>
                <v-card-title>Rappel pour {{ stagiaire.prenom }} {{ stagiaire.nom }}</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-date-picker v-model="formRappel.date_rappel" :min="min" :max="max" color="primary"></v-date-picker>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select
                                v-model="formRappel.rappel_user_id"
                                :items="users"
                                :item-title="item => item.firstname + ' ' + item.lastname"
                                item-value="id"
                                label="Personne qui a téléphoné"
                                variant="outlined"
                                class="mb-4"
                            ></v-select>
                            <v-text-field
                                v-model="formRappel.rappel_resultat"
                                label="Résultat de l'appel"
                                variant="outlined"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="dialog_faire_rappel = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" :disabled="!formRappel.date_rappel || !formRappel.rappel_user_id || !formRappel.rappel_resultat" @click="storeRappel(stagiaire.id)">
                        Créer
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Dialog: Report Stagiaire -->
        <v-dialog v-model="dialog_report_stagiaire" persistent max-width="600">
            <v-card>
                <v-card-title>Report de {{ stagiaire.prenom }} {{ stagiaire.nom }}</v-card-title>
                <v-card-text>
                    <v-select
                        v-model="report_formation"
                        :items="others_formations"
                        :item-title="item => item.nom + (item.session ? ' - ' + item.session : '') + (item.nombre_inscrits === item.max_stagiaires ? ' - complet' : '')"
                        item-value="id"
                        :item-props="item => ({ disabled: item.nombre_inscrits === item.max_stagiaires })"
                        label="Sélectionner une autre formation"
                        variant="outlined"
                    ></v-select>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="dialog_report_stagiaire = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" :disabled="!report_formation" @click="updateStagiaire(stagiaire.id)">Reporter</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Dialog: Choice Tags -->
        <v-dialog v-model="dialog_choice_tags" persistent max-width="600">
            <v-card>
                <v-card-title>Tags pour {{ formTags.prenom }} {{ formTags.nom?.toUpperCase() }}</v-card-title>
                <v-card-text>
                    <v-select
                        v-model="formTags.tags"
                        :items="listTags"
                        item-title="tag"
                        item-value="tag"
                        label="Sélectionner les centres d'intérêts"
                        variant="outlined"
                        multiple
                        chips
                        closable-chips
                    ></v-select>
                    <v-text-field
                        v-model="newTagInput"
                        label="Créer un nouveau tag"
                        variant="outlined"
                        append-inner-icon="mdi-plus"
                        @click:append-inner="addNewTag"
                        @keyup.enter="addNewTag"
                    ></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="interruptionDeleteStagiaire">Annuler</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="storeTags">Confirmer et supprimer</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Dialog: Attestation Participation -->
        <v-dialog v-model="dialog_attestation_participation" max-width="550">
            <v-card>
                <v-card-title>Attestations de participation</v-card-title>
                <v-card-text>
                    <v-table v-if="stagiaires.length > 0" fixed-header>
                        <thead>
                            <tr>
                                <th>Prénom</th>
                                <th>Nom</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="stag in stagiaires" :key="stag.id">
                                <td>{{ stag.prenom }}</td>
                                <td>{{ stag.nom?.toUpperCase() }}</td>
                                <td class="text-center">
                                    <v-btn color="success" size="small" @click="uploadAttestationParticipation(stag)">
                                        Télécharger
                                    </v-btn>
                                </td>
                            </tr>
                        </tbody>
                    </v-table>
                    <p v-else class="text-grey">Pas de stagiaire</p>
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

        <!-- Dialog: Verif Recrutement -->
        <v-dialog v-model="dialog_verif_recrutement" persistent max-width="700">
            <v-card>
                <v-card-title>Supprimer le recrutement du {{ formatDate(recrutement.date) }}</v-card-title>
                <v-card-text>
                    <v-alert v-if="candidats.length > 0" type="warning" variant="tonal" class="mb-4">
                        Ce recrutement contient des candidats. Veuillez les transférer avant suppression.
                    </v-alert>
                    <v-checkbox v-if="candidats.length > 0" v-model="transfertCandidat" label="Je souhaite transférer les candidats"></v-checkbox>
                    <div v-if="transfertCandidat && candidats.length > 0">
                        <v-table>
                            <thead>
                                <tr>
                                    <th>Candidat</th>
                                    <th>Nouvelle date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="candidat in candidats" :key="candidat[0]">
                                    <td>{{ candidat[1] }} {{ candidat[2] }}</td>
                                    <td>
                                        <v-select
                                            v-model="candidat[5]"
                                            :items="otherRecrutements"
                                            label="Date"
                                            variant="outlined"
                                            density="compact"
                                            hide-details
                                        ></v-select>
                                    </td>
                                    <td>
                                        <v-btn size="small" color="primary" @click="reportCandidat(candidat[0], candidat[1] + ' ' + candidat[2], candidat[3], candidat[5])">
                                            Transférer
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </div>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="dialog_verif_recrutement = false">Annuler</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="deleteRecrutement(recrutement.id)">Supprimer</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import { Form } from "vform";
import moment from 'moment';

export default {
    name: "ShowFormation",
    data() {
        const now = new Date();
        const minDate = now.toISOString().substr(0, 10);
        return {
            loading: false,
            loadingDatas: false,
            tab: 'stagiaires',
            date_today: new Date(now.getFullYear(), now.getMonth(), now.getDate()),
            first_recrutement: null,
            weekday: 1,
            min: minDate,
            max: null,
            formation: {},
            stagiaires: [],
            stagiairesEmails: '',
            nbreStagiaires: 0,
            stagiairesRestants: 0,
            // Dialogs
            dialog_afficher_infos: false,
            infosStagiaire: {},
            dialog_fixer_rdv: false,
            dialog_faire_rappel: false,
            dialog_report_stagiaire: false,
            dialog_choice_tags: false,
            dialog_attestation_participation: false,
            dialog_recrutements_traitement: false,
            dialog_verif_recrutement: false,
            // Forms
            formRdv: new Form({ date_rdv: null, rdv_user_id: null }),
            formRappel: new Form({ date_rappel: null, rappel_user_id: null, rappel_resultat: '' }),
            formTags: new Form({ inscrit_id: null, current_user: null, prenom: '', nom: '', tags: [] }),
            formRecrutement: new Form({ formation_id: null, date: null }),
            // Data
            users: [],
            stagiaire: {},
            report_formation: null,
            others_formations: [],
            recrutements: [],
            listDatesRecrutements: [],
            listNewDatesRecrutements: [],
            listCandidats: [],
            candidats: [],
            recrutement: {},
            otherRecrutements: [],
            transfertCandidat: false,
            traitements: [],
            vitesse: 1000,
            query: null,
            results: [],
            listStagiaires: [],
            listTags: [],
            newTagInput: '',
            nbreTransfertFalse: 0,
            nbreTransfertTrue: 0,
            checkNbreTransfertTrue: false,
            checkAllCandidatsOk: false,
            newRecrutementId: null,
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
    },
    watch: {
        query() {
            this.searchInscrits();
        }
    },
    methods: {
        formatDate(date) {
            if (!date) return '';
            return moment(date).format('DD/MM/YYYY');
        },
        formatDateDisplay(date) {
            if (!date) return '';
            return moment(date).format('DD/MM/YYYY');
        },
        goBack() {
            this.$router.go(-1);
        },
        getDatas() {
            this.getFormation();
            this.getRecrutements();
        },
        getFormation() {
            this.$Progress.start();
            this.loading = false;
            this.loadingDatas = false;
            axios.get(`/api/formations/show/${this.$route.params.id}`)
                .then((response) => {
                    this.formation = response.data.formation;
                    const dayBefore = new Date(this.formation.date_debut);
                    dayBefore.setDate(dayBefore.getDate() - 1);
                    this.max = dayBefore.toISOString().substr(0, 10);
                    this.recrutements = response.data.recrutements;
                    this.stagiaires = response.data.stagiaires;
                    this.nbreStagiaires = this.stagiaires.length;
                    this.stagiairesRestants = this.formation.max_stagiaires - this.nbreStagiaires;
                    this.loading = true;
                    this.getStagiairesEmails();
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire({ title: 'Problème avec la récupération de la formation !', timer: undefined });
                })
        },
        getStagiairesEmails() {
            this.stagiairesEmails = this.stagiaires
                .filter(s => s.email)
                .map(s => s.email)
                .join(',');
            this.loadingDatas = true;
        },
        getRecrutements() {
            axios.get(`/api/recrutements/recrutementsFormation/${this.$route.params.id}`)
                .then((response) => {
                    this.recrutements = response.data.recrutements;
                    this.listCandidats = response.data.candidats;
                    this.listDatesRecrutements = this.recrutements.map(r => r.date.slice(0, 10));
                    if (this.recrutements.length > 0) {
                        this.first_recrutement = new Date(this.recrutements[0].date);
                        this.first_recrutement.setHours(0);
                    }
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire({ title: 'Problème avec les recrutements !', timer: undefined });
                })
        },
        afficherInfos(stagiaire) {
            this.infosStagiaire = { ...stagiaire };
            this.dialog_afficher_infos = true;
        },
        fixerRdv(id) {
            this.stagiaire = this.stagiaires.find(s => s.id === id) || {};
            if (this.stagiaire.date_rdv) {
                this.formRdv.date_rdv = this.stagiaire.date_rdv;
                this.formRdv.rdv_user_id = this.stagiaire.rdv_user_id;
            } else {
                this.formRdv.reset();
            }
            this.getUsers();
            this.dialog_fixer_rdv = true;
        },
        faireRappel(id) {
            this.stagiaire = this.stagiaires.find(s => s.id === id) || {};
            this.formRappel.reset();
            this.getUsers();
            this.dialog_faire_rappel = true;
        },
        getUsers() {
            axios.get('api/users/admins')
                .then(response => { this.users = response.data.users; })
                .catch(error => { console.log(error.response); });
        },
        storeRdv(id) {
            this.$Progress.start();
            this.formRdv.post('api/inscrits/rdv/' + id + '/' + this.formation.id)
                .then(response => {
                    this.$Progress.finish();
                    Toast.fire(response.data.message);
                    this.dialog_fixer_rdv = false;
                    this.getFormation();
                })
                .catch(error => {
                    this.$Progress.fail();
                    Snackbar.fire('Problème : rendez-vous non sauvegardé !');
                });
        },
        validateRdv(id) {
            this.$Progress.start();
            this.formRdv.put('api/inscrits/rdv/' + id + '/' + this.formation.id)
                .then(response => {
                    this.$Progress.finish();
                    Toast.fire(response.data.message);
                    this.dialog_fixer_rdv = false;
                    this.getFormation();
                })
                .catch(error => {
                    this.$Progress.fail();
                    Snackbar.fire('Problème : rendez-vous non validé !');
                });
        },
        deleteRdv(id) {
            axios.delete('api/inscrits/rdv/' + id + '/' + this.formation.id)
                .then(response => {
                    Toast.fire(response.data.message);
                    this.dialog_fixer_rdv = false;
                    this.getFormation();
                })
                .catch(error => {
                    Snackbar.fire('Problème : rendez-vous non supprimé !');
                });
        },
        storeRappel(id) {
            this.$Progress.start();
            this.formRappel.put('api/inscrits/rappel/' + id + '/' + this.formation.id)
                .then(response => {
                    this.$Progress.finish();
                    Toast.fire('Rappel sauvegardé');
                    this.dialog_faire_rappel = false;
                    this.getFormation();
                })
                .catch(error => {
                    this.$Progress.fail();
                    Snackbar.fire('Problème : rappel non sauvegardé !');
                });
        },
        reportStagiaire(id) {
            this.stagiaire = this.stagiaires.find(s => s.id === id) || {};
            this.getOtherFormations(this.formation.id);
            this.dialog_report_stagiaire = true;
        },
        getOtherFormations(id) {
            axios.get(`/api/formations/others/${id}`)
                .then(response => { this.others_formations = response.data.othersFormations; })
                .catch(error => { Snackbar.fire('Problème avec les autres formations !'); });
        },
        updateStagiaire(id) {
            if (!this.report_formation) {
                Snackbar.fire("Vous n'avez pas sélectionné une autre formation !");
                return;
            }
            this.$Progress.start();
            axios.put('api/inscrits/formation/' + id + '/' + this.formation.id + '/' + this.report_formation)
                .then(response => {
                    this.$Progress.finish();
                    Toast.fire('Transfert du stagiaire effectué');
                    this.dialog_report_stagiaire = false;
                    this.getFormation();
                })
                .catch(error => {
                    this.$Progress.fail();
                    Snackbar.fire('Transfert non effectué !');
                });
        },
        searchInscrits() {
            if (this.query) {
                axios.get('api/inscrits/search', { params: { query: this.query } })
                    .then(response => { this.results = response.data; })
                    .catch(error => { console.log(error.response); });
            }
        },
        ajoutStagiaire(id, nom, prenom) {
            if (this.stagiairesRestants === 0) {
                Snackbar.fire('Nombre maximum de stagiaires atteint !');
                return;
            }
            this.listStagiaires.push({ id, nom, prenom });
            this.stagiairesRestants--;
            this.query = null;
        },
        deleteListStagiaires(index) {
            this.listStagiaires.splice(index, 1);
            this.stagiairesRestants++;
        },
        startStoreInscriptionStagiaires() {
            this.traitements = [];
            this.dialog_recrutements_traitement = true;
            setTimeout(() => this.storeInscriptionStagiaires(0), this.vitesse);
        },
        storeInscriptionStagiaires(index) {
            const s = this.listStagiaires[index];
            this.$Progress.start();
            axios.post('api/formations/addInscrit/' + this.formation.id + '/' + s.id)
                .then(response => {
                    this.$Progress.finish();
                    this.traitements.push(s.prenom + ' ' + s.nom + (response.data.message ? ' inscrit·e.' : ' déjà inscrit·e.'));
                })
                .catch(error => {
                    this.traitements.push(s.prenom + ' ' + s.nom + ' non inscrit·e.');
                });
            if (index + 1 < this.listStagiaires.length) {
                setTimeout(() => this.storeInscriptionStagiaires(index + 1), this.vitesse);
            } else {
                setTimeout(() => {
                    this.listStagiaires = [];
                    Toast.fire('Inscriptions effectuées !');
                    this.dialog_recrutements_traitement = false;
                    this.query = null;
                    this.getFormation();
                }, this.vitesse);
            }
        },
        getTags() {
            axios.get('/api/tags/all')
                .then(response => { this.listTags = response.data.tags; })
                .catch(error => { Snackbar.fire('Problème avec les tags !'); });
        },
        startDeleteStagiaire(id, prenom, nom) {
            this.getTags();
            Suppression.fire({ text: "Il·elle ne participera plus à la formation!" })
                .then(result => {
                    if (result.value) {
                        this.formTags.inscrit_id = id;
                        this.formTags.prenom = prenom;
                        this.formTags.nom = nom;
                        this.formTags.tags = [];
                        this.dialog_choice_tags = true;
                    }
                });
        },
        addNewTag() {
            if (this.newTagInput && this.newTagInput.length > 2) {
                const tag = this.newTagInput.toLowerCase();
                if (!this.listTags.find(t => t.tag === tag)) {
                    axios.post('api/tags/create', { tag })
                        .then(response => {
                            Toast.fire('Tag créé');
                            this.getTags();
                            this.formTags.tags.push(tag);
                            this.newTagInput = '';
                        })
                        .catch(error => { Snackbar.fire('Problème avec le tag !'); });
                } else {
                    this.formTags.tags.push(tag);
                    this.newTagInput = '';
                }
            }
        },
        storeTags() {
            this.$Progress.start();
            if (this.formTags.tags.length > 0) {
                this.formTags.current_user = this.currentUser.id;
                this.formTags.post('api/inscrits/addTags/' + this.formTags.inscrit_id)
                    .then(response => { this.$Progress.finish(); Toast.fire('Tags sauvegardés'); })
                    .catch(error => { this.$Progress.fail(); });
            }
            this.deleteStagiaire(this.formTags.inscrit_id);
        },
        interruptionDeleteStagiaire() {
            this.dialog_choice_tags = false;
            Snackbar.fire('Stagiaire non supprimé !');
        },
        deleteStagiaire(id) {
            this.dialog_choice_tags = false;
            axios.delete('api/formations/deleteInscrit/' + this.formation.id + '/' + id)
                .then(response => {
                    Toast.fire('Stagiaire supprimé');
                    this.getFormation();
                })
                .catch(error => { Snackbar.fire('Problème avec la suppression !'); });
        },
        startTraitementRecrutements() {
            this.traitements = [];
            this.dialog_recrutements_traitement = true;
            setTimeout(() => this.storeRecrutement(0), this.vitesse);
        },
        storeRecrutement(index) {
            const dateVal = this.listNewDatesRecrutements[index];
            const dateStr = typeof dateVal === 'string' ? dateVal : dateVal.toISOString().substring(0, 10);
            this.$Progress.start();
            this.formRecrutement = new Form({ formation_id: this.formation.id, date: dateStr });
            this.formRecrutement.post('api/recrutements')
                .then(response => {
                    this.$Progress.finish();
                    this.traitements.push('Recrutement du ' + dateStr + ' créé.');
                    this.recrutements.push(response.data.data);
                    this.listDatesRecrutements.push(response.data.data.date.slice(0, 10));
                })
                .catch(error => {
                    this.$Progress.fail();
                    this.traitements.push('Recrutement du ' + dateStr + ' non créé.');
                });
            if (index + 1 < this.listNewDatesRecrutements.length) {
                setTimeout(() => this.storeRecrutement(index + 1), this.vitesse);
            } else {
                setTimeout(() => {
                    this.listNewDatesRecrutements = [];
                    Toast.fire('Recrutement(s) sauvegardé(s) !');
                    this.dialog_recrutements_traitement = false;
                }, this.vitesse);
            }
        },
        verifRecrutement(recrutementId) {
            this.candidats = [];
            this.otherRecrutements = [];
            for (const candidatList of this.listCandidats) {
                const arr = Array.isArray(candidatList) ? candidatList : [candidatList];
                for (const c of arr) {
                    if (c.recrutement_id === recrutementId) {
                        this.candidats.push([c.inscrit_id, c.prenom, c.nom, c.recrutement_id, false]);
                    }
                }
            }
            this.recrutement = this.recrutements.find(r => r.id === recrutementId) || {};
            this.otherRecrutements = this.recrutements.filter(r => r.id !== recrutementId).map(r => r.date);
            this.dialog_verif_recrutement = true;
            this.transfertCandidat = false;
        },
        reportCandidat(candidat_id, candidat, id, recrutement) {
            if (!recrutement) {
                Snackbar.fire({ title: "Pas de date pour " + candidat, timer: undefined });
                return;
            }
            const newRec = this.recrutements.find(r => r.date === recrutement);
            if (!newRec) return;
            axios.put('api/inscrits/recrutement/' + candidat_id + '/' + id + '/' + newRec.id)
                .then(response => {
                    const c = this.candidats.find(x => x[0] === candidat_id);
                    if (c) c.push(true);
                    Toast.fire('Transfert effectué !');
                })
                .catch(error => {
                    const c = this.candidats.find(x => x[0] === candidat_id);
                    if (c) c.push(false);
                    Snackbar.fire('Transfert non effectué !');
                });
        },
        deleteRecrutement(recrutement_id) {
            Suppression.fire().then(result => {
                if (result.value) {
                    axios.delete('api/recrutements/' + recrutement_id)
                        .then(response => {
                            this.listNewDatesRecrutements = [];
                            this.getRecrutements();
                            Toast.fire('Recrutement supprimé !');
                            this.dialog_verif_recrutement = false;
                        })
                        .catch(error => { Snackbar.fire('Problème avec la suppression !'); });
                }
            });
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
            axios({ url: '/PDF/formation/presences/' + this.formation.id, method: 'GET', responseType: 'blob' })
                .then(response => { this.forceFileDownload(response, `${this.formation.abreviation}_liste-presence.pdf`); })
                .catch(error => { console.log(error.response); });
        },
        uploadAbsences() {
            axios({ url: '/PDF/formation/absences/' + this.formation.id, method: 'GET', responseType: 'blob' })
                .then(response => { this.forceFileDownload(response, `${this.formation.abreviation}_absences.pdf`); })
                .catch(error => { console.log(error.response); });
        },
        openModalAttestationParticipation() {
            this.dialog_attestation_participation = true;
        },
        uploadAttestationParticipation(stagiaire) {
            axios({ url: '/PDF/formation/participation/' + this.formation.id + '/' + stagiaire.id, method: 'GET', responseType: 'blob' })
                .then(response => { this.forceFileDownload(response, `attestation_participation_${stagiaire.nom}_${stagiaire.prenom}.pdf`); })
                .catch(error => { Snackbar.fire("Problème avec l'attestation !"); });
        },
        uploadAttestationDeplacement() {
            axios({ url: '/PDF/formation/deplacement/' + this.formation.id, method: 'GET', responseType: 'blob' })
                .then(response => { this.forceFileDownload(response, `attestations_deplacement.pdf`); })
                .catch(error => { Snackbar.fire("Problème avec les attestations !"); });
        },
        uploadAttestationsPaiement() {
            axios({ url: '/PDF/formation/paiements/' + this.formation.id, method: 'GET', responseType: 'blob' })
                .then(response => { this.forceFileDownload(response, `attestations_paiement_${this.formation.abreviation}.pdf`); })
                .catch(error => { Snackbar.fire("Problème avec les attestations !"); });
        },
        destroyFormation() {
            Suppression.fire().then(result => {
                if (result.value) {
                    axios.delete('api/formations/' + this.formation.id)
                        .then(response => {
                            Confirm.fire('Formation supprimée !');
                            this.$router.push('/home');
                        })
                        .catch(error => { Snackbar.fire('Problème avec la suppression !'); });
                }
            });
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
