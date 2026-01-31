<template>
    <v-container fluid>
        <h1 class="text-h4 mb-4">Liste des Recrutements</h1>

        <!-- Search -->
        <v-row align="center" class="mb-4">
            <v-col cols="12" md="2">
                <v-select
                    v-model="colonne"
                    :items="searchColonnes"
                    label="Recherche par"
                    variant="outlined"
                    density="compact"
                    hide-details
                ></v-select>
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field
                    v-model="search"
                    label="Recherche"
                    variant="outlined"
                    density="compact"
                    hide-details
                    prepend-inner-icon="mdi-magnify"
                    clearable
                ></v-text-field>
            </v-col>
        </v-row>

        <v-alert v-if="colonne === 'date'" type="warning" variant="tonal" class="mb-4">
            Le format de date doit être : YYYY-MM-JJ
        </v-alert>

        <!-- Table -->
        <v-card>
            <v-table fixed-header>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th class="text-center">FORMATION</th>
                        <th>SESSION</th>
                        <th>TUTEUR·RICE</th>
                        <th class="text-center">DATE</th>
                        <th>NOMBRE DE CANDIDATS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="recrutements.length" v-for="recrutement in recrutements" :key="recrutement.id">
                        <td class="text-center"><strong>{{ recrutement.id }}</strong></td>
                        <td>{{ recrutement.formation }}</td>
                        <td>{{ recrutement.session }}</td>
                        <td>{{ recrutement.tuteur_prenom }}</td>
                        <td class="text-center">{{ formatDate(recrutement.date) }}</td>
                        <td>{{ recrutement.nombreCandidats }} candidats</td>
                        <td>
                            <v-btn icon variant="text" size="small" color="primary" :to="{ name: 'showRecrutement', params: { id: recrutement.id }}">
                                <v-icon>mdi-eye</v-icon>
                            </v-btn>
                            <template v-if="currentUser?.role === 'admin' || currentUser?.role === 'master'">
                                <v-btn icon variant="text" size="small" color="success" @click="editRecrutement(recrutement, recrutement.id, recrutement.formation_id)">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon variant="text" size="small" color="error" @click="verifRecrutement(recrutement.id)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </template>
                        </td>
                    </tr>
                    <tr v-if="!recrutements.length && !loading">
                        <td colspan="7">
                            <div class="text-center py-8">
                                <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
                                <div class="mt-4">Chargement...</div>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!recrutements.length && loading">
                        <td colspan="7">
                            <v-alert type="warning" variant="tonal" class="ma-4" icon="mdi-database-search">
                                {{ search ? 'Aucune donnée ne correspond à votre recherche.' : 'Aucune donnée correspondante.' }}
                            </v-alert>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </v-card>

        <!-- Pagination -->
        <v-pagination
            v-if="pagination.last_page > 1"
            v-model="pagination.current_page"
            :length="pagination.last_page"
            @update:model-value="search === '' ? getData() : searchData()"
            class="mt-4"
        ></v-pagination>

        <!-- Dialog: Edition -->
        <v-dialog v-model="dialog_edition" persistent max-width="600">
            <v-card>
                <v-card-title>Modification de la date</v-card-title>
                <v-card-text>
                    <v-date-picker
                        v-model="form.date"
                        :allowed-dates="allowedDays(listDatesOthersRecrutements)"
                        :min="min"
                        :max="max"
                        color="primary"
                    ></v-date-picker>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="dialog_edition = false">Fermer</v-btn>
                    <v-btn color="success" @click="updateRecrutement(form.id)">Sauvegarder</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Dialog: Verif Recrutement -->
        <v-dialog v-model="dialog_verif_recrutement" persistent max-width="700">
            <v-card>
                <v-card-title>Vérification pour le recrutement du {{ formatDate(recrutement.date) }}</v-card-title>
                <v-card-text>
                    <v-checkbox v-model="transfertCandidat" label="Transfert de candidats"></v-checkbox>
                    <div v-if="transfertCandidat && transfertCandidats.length > 0">
                        <v-table fixed-header>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th class="text-center">Prénom</th>
                                    <th class="text-center">Nom</th>
                                    <th>Transfert ?</th>
                                    <th style="width:175px;">Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(candidat, index) in transfertCandidats" :key="index">
                                    <td><strong>{{ candidat[0] }}</strong></td>
                                    <td class="text-center"><strong>{{ candidat[1] }}</strong></td>
                                    <td class="text-center"><strong>{{ candidat[2]?.toUpperCase() }}</strong></td>
                                    <td>
                                        <v-checkbox v-model="candidat[4]" label="Transfert" hide-details density="compact"></v-checkbox>
                                    </td>
                                    <td v-if="candidat[4]">
                                        <v-select
                                            v-model="candidat[5]"
                                            :items="listDatesOthersRecrutements"
                                            label="Autre(s) date(s)"
                                            variant="outlined"
                                            density="compact"
                                            hide-details
                                        ></v-select>
                                    </td>
                                    <td v-if="candidat[4]">
                                        <v-btn icon variant="text" size="small" @click="reportCandidat(candidat[0], candidat[1] + ' ' + candidat[2], candidat[5])">
                                            <v-icon :color="candidat[6] ? 'success' : 'error'">mdi-send</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </div>
                    <div v-else-if="transfertCandidat" class="text-grey">Pas de candidat</div>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="warning" @click="dialog_verif_recrutement = false">Fermer</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="destroyRecrutement(recrutement.id)">Supprimer</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import { Form } from "vform";
import moment from 'moment';

export default {
    name: "ListRecrutements",
    data() {
        const now = new Date();
        const minDate = now.toISOString().substr(0, 10);
        return {
            loading: false,
            searchColonnes: ['formation', 'date'],
            colonne: 'formation',
            search: '',
            recrutements: [],
            pagination: { current_page: 1, last_page: 1 },
            form: new Form({ id: '', formation_id: null, formation: '', session: '', date: null }),
            dialog_edition: false,
            listDatesOthersRecrutements: [],
            min: minDate,
            max: null,
            recrutement: {},
            others_recrutements: [],
            listCandidats: {},
            candidat: [],
            candidats: [],
            transfertCandidats: [],
            dialog_verif_recrutement: false,
            transfertCandidat: false,
            newRecrutementId: null,
            checkNbreTransfertTrue: false,
            checkAllCandidatsOk: false,
            nbreTransfertFalse: 0,
            nbreTransfertTrue: 0,
        }
    },
    watch: {
        search(newSearch) {
            if (newSearch === '' || newSearch === null) {
                this.getData();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        console.log('List Recrutements component mounted');
        this.getData();
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        }
    },
    methods: {
        formatDate(date) {
            if (!date) return '';
            return moment(date).format('DD/MM/YYYY');
        },
        getData() {
            this.loading = false;
            axios.get('api/recrutements?page=' + this.pagination.current_page)
                .then(response => {
                    this.recrutements = response.data.data;
                    this.pagination = response.data.meta;
                    this.loading = true;
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème de récupération des recrutements !');
                });
        },
        searchData() {
            this.$Progress.start();
            axios.get('api/search/recrutements/' + this.colonne + '/' + this.search + '?page=' + this.pagination.current_page)
                .then(response => {
                    this.recrutements = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème dans la récupération des infos suite à la recherche !');
                });
        },
        allowedDays(a) {
            return val => a.indexOf(val) === -1 && ![0, 6].includes(new Date(val).getDay());
        },
        editRecrutement(recrutement, id, formation) {
            this.form.reset();
            this.form.clear();
            this.form.fill(recrutement);
            this.getOthersRecrutements(id, formation);
            this.dialog_edition = true;
        },
        getOthersRecrutements(recrutement, formation) {
            this.listDatesOthersRecrutements = [];
            axios.get(`/api/recrutements/others/${recrutement}/${formation}`)
                .then(response => {
                    this.others_recrutements = response.data.othersRecrutements;
                    this.listDatesOthersRecrutements = this.others_recrutements.map(r => r.date);
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème avec les autres recrutements !');
                });
        },
        updateRecrutement() {
            this.$Progress.start();
            this.form.busy = true;
            this.form.put('api/recrutements/' + this.form.id)
                .then(response => {
                    if (this.form.successful) {
                        this.$Progress.finish();
                        Toast.fire('Recrutement édité');
                        this.dialog_edition = false;
                        this.getData();
                        Fire.$emit('RefreshPage');
                    }
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Recrutement non édité !');
                });
        },
        verifRecrutement(recrutement) {
            this.getRecrutement(recrutement);
        },
        getRecrutement(recrutement) {
            axios.get(`/api/recrutements/show/${recrutement}`)
                .then(response => {
                    this.recrutement = response.data.recrutement;
                    this.candidats = response.data.candidats;
                    this.getOthersRecrutements(this.recrutement.id, this.recrutement.formation_id);
                    this.getListCandidatsAllRecrutements();
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème de récupération des infos du recrutement !');
                });
        },
        getListCandidatsAllRecrutements() {
            this.listCandidats = [];
            axios.get(`/api/recrutements/recrutementsFormation/${this.recrutement.formation_id}`)
                .then(response => {
                    this.listCandidats = response.data.candidats;
                    this.listTransfertCandidats();
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème de récupération des candidats !');
                });
        },
        listTransfertCandidats() {
            this.transfertCandidats = [];
            for (let x = 0; x < this.listCandidats.length; x++) {
                if (this.listCandidats[x].length > 0) {
                    if (this.listCandidats[x][0].recrutement_id === this.recrutement.id) {
                        for (let y = 0; y < this.listCandidats[x].length; y++) {
                            this.transfertCandidats.push([
                                this.listCandidats[x][y].inscrit_id,
                                this.listCandidats[x][y].prenom,
                                this.listCandidats[x][y].nom,
                                this.listCandidats[x][y].inscrit_recrutement_id,
                                false
                            ]);
                        }
                    }
                }
            }
            this.dialog_verif_recrutement = true;
            this.transfertCandidat = false;
        },
        reportCandidat(candidat_id, candidat, recrutement) {
            if (!recrutement) {
                Snackbar.fire("Vous n'avez pas encodé de date pour " + candidat);
                return;
            }
            const rec = this.others_recrutements.find(r => r.date === recrutement);
            if (rec) this.newRecrutementId = rec.id;
            axios.put('api/inscrits/recrutement/' + candidat_id + '/' + this.recrutement.id + '/' + this.newRecrutementId)
                .then(response => {
                    const c = this.transfertCandidats.find(x => x[0] === candidat_id);
                    if (c) c.push(true);
                    Toast.fire('Transfert du candidat effectué');
                })
                .catch(error => {
                    const c = this.transfertCandidats.find(x => x[0] === candidat_id);
                    if (c) c.push(false);
                    console.log(error.response);
                    Snackbar.fire('Transfert du candidat non effectué');
                });
        },
        destroyRecrutement(recrutement) {
            this.$Progress.start();
            this.nbreTransfertFalse = 0;
            this.checkNbreTransfertTrue = false;
            this.checkAllCandidatsOk = false;

            if (this.transfertCandidat) {
                for (let y = 0; y < this.transfertCandidats.length; y++) {
                    if (!this.transfertCandidats[y][4]) {
                        this.nbreTransfertFalse++;
                    } else if (this.transfertCandidats[y][4]) {
                        if (!this.transfertCandidats[y][5]) {
                            this.$Progress.fail();
                            Snackbar.fire("Vous n'avez pas encodé de date pour " + this.transfertCandidats[y][1] + ' ' + this.transfertCandidats[y][2]);
                            return;
                        } else if (this.transfertCandidats[y][6] === false) {
                            this.$Progress.fail();
                            Snackbar.fire('Le transfert de ' + this.transfertCandidats[y][1] + ' ' + this.transfertCandidats[y][2] + " n'a pas été effectué. Réessayez !");
                            return;
                        } else if (!this.transfertCandidats[y][6]) {
                            this.$Progress.fail();
                            Snackbar.fire("Vous n'avez pas effectué le transfert de " + this.transfertCandidats[y][1] + ' ' + this.transfertCandidats[y][2]);
                            return;
                        }
                    }
                }

                if (this.nbreTransfertFalse === this.transfertCandidats.length) {
                    this.$Progress.fail();
                    Snackbar.fire("Vous n'avez transféré personne");
                    return;
                } else {
                    this.checkNbreTransfertTrue = true;
                }

                this.nbreTransfertTrue = this.transfertCandidats.filter(c => c[4] && c[6]).length;
                this.checkAllCandidatsOk = this.nbreTransfertTrue === this.transfertCandidats.length - this.nbreTransfertFalse;
            }

            if ((this.transfertCandidat && this.checkNbreTransfertTrue && this.checkAllCandidatsOk) || !this.transfertCandidat) {
                Suppression.fire().then(result => {
                    if (result.value) {
                        axios.delete('api/recrutements/' + recrutement)
                            .then(response => {
                                this.$Progress.finish();
                                Toast.fire('Recrutement Supprimé!');
                                this.dialog_verif_recrutement = false;
                                this.getData();
                                Fire.$emit('RefreshPage');
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.log(error.response);
                                Snackbar.fire('Problème avec la suppression du recrutement !');
                            });
                    }
                });
            }
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
