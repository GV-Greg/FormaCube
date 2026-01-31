<template>
    <v-container fluid>
        <v-row align="center" class="mb-4">
            <v-col cols="auto">
                <v-btn icon variant="text" @click="retour">
                    <v-icon>mdi-arrow-left</v-icon>
                </v-btn>
            </v-col>
            <v-col>
                <h1 class="text-h4">Modification de la formation</h1>
            </v-col>
        </v-row>

        <v-stepper v-model="step" :items="stepItems" alt-labels>
            <template v-slot:item.1>
                <v-card flat>
                    <v-card-text v-if="loading">
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="formFormation.nom"
                                    label="Nom *"
                                    variant="outlined"
                                    :error="!checkNom || !validationMinNom || !validationMaxNom"
                                    :error-messages="getNomErrors()"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="formFormation.abreviation"
                                    label="Abréviation *"
                                    variant="outlined"
                                    :error="!checkAbreviation || !validationMinAbreviation || !validationMaxAbreviation"
                                    :error-messages="getAbreviationErrors()"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="formFormation.session"
                                    label="Session"
                                    variant="outlined"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-select
                                    v-model="formFormation.salle_id"
                                    :items="salles"
                                    item-title="nom"
                                    item-value="id"
                                    label="Salle *"
                                    variant="outlined"
                                    :error="!checkSalle"
                                    :error-messages="!checkSalle ? 'La salle est requise.' : ''"
                                ></v-select>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="formFormation.date_debut"
                                    label="Date de début *"
                                    type="date"
                                    variant="outlined"
                                    :error="!checkDateDebut || !validationDatesDebutFin"
                                    :error-messages="getDateDebutErrors()"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="formFormation.date_fin"
                                    label="Date de fin *"
                                    type="date"
                                    variant="outlined"
                                    :error="!checkDateFin || !validationDatesDebutFin"
                                    :error-messages="getDateFinErrors()"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model.number="formFormation.min_stagiaires"
                                    label="Min. stagiaires *"
                                    type="number"
                                    min="0"
                                    variant="outlined"
                                    :error="!checkMinStagiaires || !validationMinStagiaires || !validationMinMaxStagiaires"
                                    :error-messages="getMinStagiairesErrors()"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model.number="formFormation.max_stagiaires"
                                    label="Max. stagiaires *"
                                    type="number"
                                    min="0"
                                    variant="outlined"
                                    :error="!checkMaxStagiaires || !validationMaxStagiaires || !validationMinMaxStagiaires"
                                    :error-messages="getMaxStagiairesErrors()"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model.number="formFormation.nbre_heures"
                                    label="Nombre d'heures *"
                                    type="number"
                                    min="0"
                                    variant="outlined"
                                    :error="!checkNbreHeures || !validationNbreHeures"
                                    :error-messages="getNbreHeuresErrors()"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model.number="formFormation.prix"
                                    label="Prix *"
                                    type="number"
                                    min="0"
                                    variant="outlined"
                                    suffix="€"
                                    :error="!checkPrix || !validationPrix"
                                    :error-messages="getPrixErrors()"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row class="mt-4">
                            <v-col class="d-flex justify-space-between align-center">
                                <span class="text-caption text-grey">* Champs obligatoires</span>
                                <v-btn color="primary" @click="validationStepOne()">
                                    Continuer
                                    <v-icon end>mdi-arrow-right</v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-text v-else class="text-center py-8">
                        <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
                        <div class="mt-4">Chargement...</div>
                    </v-card-text>
                </v-card>
            </template>

            <template v-slot:item.2>
                <v-card flat>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-select
                                    v-model="formFormation.user_id"
                                    :items="users.users || []"
                                    :item-title="item => item.firstname + ' ' + item.lastname"
                                    item-value="id"
                                    label="Tuteur·rice *"
                                    variant="outlined"
                                    :error="!checkTuteur"
                                    :error-messages="!checkTuteur ? 'Le tuteur·trice est requis·e.' : ''"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model.number="formFormation.duree_rgpd"
                                    label="Durée RGPD (années) *"
                                    type="number"
                                    min="0"
                                    variant="outlined"
                                    :error="!checkDureeRgpd || !validationDureeRgpd"
                                    :error-messages="getDureeRgpdErrors()"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <v-textarea
                                    v-model="formFormation.commentaire_formation"
                                    label="Commentaire(s)"
                                    variant="outlined"
                                    rows="4"
                                ></v-textarea>
                            </v-col>
                        </v-row>
                        <v-row class="mt-4">
                            <v-col class="d-flex justify-space-between align-center">
                                <v-btn variant="outlined" color="error" @click="step = 1">
                                    <v-icon start>mdi-arrow-left</v-icon>
                                    Retour
                                </v-btn>
                                <span class="text-caption text-grey">* Champs obligatoires</span>
                                <v-btn color="success" @click="validationStepTwo()">
                                    <v-icon start>mdi-check</v-icon>
                                    Éditer
                                </v-btn>
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

export default {
    name: "EditFormation",
    data() {
        return {
            step: 1,
            stepItems: [
                { title: 'Généralités', value: 1 },
                { title: 'Généralités (suite)', value: 2 },
            ],
            formation: {},
            loading: false,
            formFormation: new Form({
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
        }
    },
    mounted() {
        console.log('EditFormation component mounted');
        this.getFormation();
        this.getUsers();
        this.getSalles();
    },
    computed: {
        checkNom() {
            return this.formFormation.nom !== '';
        },
        validationMinNom() {
            if (this.checkNom) {
                return this.formFormation.nom.length > 2;
            }
            return true;
        },
        validationMaxNom() {
            return this.formFormation.nom.length < 190;
        },
        checkAbreviation() {
            return this.formFormation.abreviation !== '';
        },
        validationMinAbreviation() {
            if (this.checkAbreviation) {
                return this.formFormation.abreviation.length > 2;
            }
            return true;
        },
        validationMaxAbreviation() {
            return this.formFormation.abreviation.length < 20;
        },
        validationMaxSession() {
            if (this.formFormation.session !== '' && this.formFormation.session !== null) {
                return this.formFormation.session.length < 190;
            }
            return true;
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
        validationDatesDebutFin() {
            if (this.formFormation.date_debut && this.formFormation.date_fin) {
                return this.formFormation.date_debut <= this.formFormation.date_fin;
            }
            return true;
        },
        checkMinStagiaires() {
            return this.formFormation.min_stagiaires !== '';
        },
        validationMinStagiaires() {
            if (this.formFormation.min_stagiaires !== '') {
                return Number(this.formFormation.min_stagiaires) > 0;
            }
            return true;
        },
        checkMaxStagiaires() {
            return this.formFormation.max_stagiaires !== '';
        },
        validationMaxStagiaires() {
            if (this.formFormation.max_stagiaires !== '') {
                return Number(this.formFormation.max_stagiaires) > 0;
            }
            return true;
        },
        validationMinMaxStagiaires() {
            if (this.formFormation.min_stagiaires !== '' && this.formFormation.max_stagiaires !== '') {
                return Number(this.formFormation.max_stagiaires) >= Number(this.formFormation.min_stagiaires);
            }
            return true;
        },
        checkNbreHeures() {
            return this.formFormation.nbre_heures !== '';
        },
        validationNbreHeures() {
            if (this.formFormation.nbre_heures !== '') {
                return Number(this.formFormation.nbre_heures) > 0;
            }
            return true;
        },
        checkPrix() {
            return this.formFormation.prix !== '';
        },
        validationPrix() {
            if (this.formFormation.prix !== '') {
                return Number(this.formFormation.prix) >= 0;
            }
            return true;
        },
        checkTuteur() {
            return this.formFormation.user_id != null;
        },
        checkDureeRgpd() {
            return this.formFormation.duree_rgpd !== '';
        },
        validationDureeRgpd() {
            if (this.formFormation.duree_rgpd !== '') {
                return Number(this.formFormation.duree_rgpd) > 0;
            }
            return true;
        },
    },
    methods: {
        getNomErrors() {
            if (!this.checkNom) return 'Le nom est requis.';
            if (!this.validationMinNom) return 'Le nom doit comporter au moins 3 caractères.';
            if (!this.validationMaxNom) return 'Le nom doit comporter moins de 190 caractères.';
            return '';
        },
        getAbreviationErrors() {
            if (!this.checkAbreviation) return "L'abréviation est requise.";
            if (!this.validationMinAbreviation) return "L'abréviation doit comporter au moins 3 caractères.";
            if (!this.validationMaxAbreviation) return "L'abréviation doit comporter moins de 20 caractères.";
            return '';
        },
        getDateDebutErrors() {
            if (!this.checkDateDebut) return 'La date de début est requise.';
            if (!this.validationDatesDebutFin) return 'La date de fin ne peut être avant la date de début.';
            return '';
        },
        getDateFinErrors() {
            if (!this.checkDateFin) return 'La date de fin est requise.';
            if (!this.validationDatesDebutFin) return 'La date de fin ne peut être avant la date de début.';
            return '';
        },
        getMinStagiairesErrors() {
            if (!this.checkMinStagiaires) return 'Le nombre minimum est requis.';
            if (!this.validationMinStagiaires) return 'Le nombre doit être supérieur à 0.';
            if (!this.validationMinMaxStagiaires) return 'Le min doit être inférieur au max.';
            return '';
        },
        getMaxStagiairesErrors() {
            if (!this.checkMaxStagiaires) return 'Le nombre maximum est requis.';
            if (!this.validationMaxStagiaires) return 'Le nombre doit être supérieur à 0.';
            if (!this.validationMinMaxStagiaires) return 'Le min doit être inférieur au max.';
            return '';
        },
        getNbreHeuresErrors() {
            if (!this.checkNbreHeures) return "Le nombre d'heures est requis.";
            if (!this.validationNbreHeures) return 'Le nombre doit être supérieur à 0.';
            return '';
        },
        getPrixErrors() {
            if (!this.checkPrix) return 'Le prix est requis.';
            if (!this.validationPrix) return 'Le prix doit être supérieur ou égal à 0.';
            return '';
        },
        getDureeRgpdErrors() {
            if (!this.checkDureeRgpd) return 'La durée RGPD est requise.';
            if (!this.validationDureeRgpd) return 'La durée doit être supérieure à 0.';
            return '';
        },
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
                    this.$router.go(-1);
                }
            });
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
            if (this.checkNom && this.checkAbreviation && this.checkSalle && this.checkDateDebut && this.checkDateFin &&
                this.checkMinStagiaires && this.checkMaxStagiaires && this.checkNbreHeures && this.checkPrix) {
                if (this.validationMinStagiaires && this.validationMaxStagiaires && this.validationNbreHeures) {
                    if (!this.validationMaxNom) {
                        Snackbar.fire("Le nom doit être composé de moins de 100 caractères !");
                    } else if (!this.validationMaxAbreviation) {
                        Snackbar.fire("L'abréviation doit être composée de moins de 20 caractères !");
                    } else if (this.formFormation.session !== '' && this.formFormation.session !== null && !this.validationMaxSession) {
                        Snackbar.fire("La session doit être composée de moins de 190 caractères !");
                    } else if (Number(this.formFormation.min_stagiaires) > Number(this.formFormation.max_stagiaires)) {
                        Snackbar.fire('Le minimum de stagiaires doit être plus petit que le maximum !');
                    } else if (this.formFormation.date_fin < this.formFormation.date_debut) {
                        Snackbar.fire('La fin de formation ne peut être avant le début !');
                    } else {
                        this.step = 2;
                    }
                } else {
                    Snackbar.fire('Vous avez rempli des champs avec un nombre négatif et/ou nul !');
                }
            } else {
                Snackbar.fire("Vous n'avez pas rempli tous les champs obligatoires !");
            }
        },
        validationStepTwo() {
            if (this.checkTuteur && this.checkDureeRgpd) {
                if (this.validationDureeRgpd) {
                    this.editFormation();
                } else {
                    Snackbar.fire('La durée RGPD doit être supérieure à 0 !');
                }
            } else {
                Snackbar.fire("Vous n'avez pas rempli tous les champs obligatoires !");
            }
        },
        editFormation() {
            this.$Progress.start();
            this.formFormation
                .put(`/api/formations/edit/${this.$route.params.id}`)
                .then(response => {
                    if (this.formFormation.successful) {
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
.v-stepper {
    background: transparent;
}
</style>
