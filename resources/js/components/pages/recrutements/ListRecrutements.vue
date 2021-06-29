<template>
    <div>
        <h1>Liste des Recrutements</h1>
        <div class="row align-items-center mt-2">
            <div class="col-lg-2 text-right text-light">
                <span>Recherche par :</span>
            </div>
            <div class="col-lg-2">
                <v-select :items="searchColonnes" v-model="colonne" color="blue-grey darken-4" class="mySelect bg-light text-dark" outlined dense hide-details="auto"></v-select>
            </div>
            <div class="col-lg-5">
                <v-text-field v-model="search" label="Recherche format date : yyyy-mm-dd" color="blue-grey darken-4" class="mySearch bg-light" outlined dense hide-details="auto" append-icon="fas fa-search"></v-text-field>
            </div>
        </div>
        <v-simple-table fixed-header class="mt-3">
            <thead class>
            <tr>
                <th>N°</th>
                <th class="text-center">FORMATION</th>
                <th>SESSION</th>
                <th>TUTEUR·RICE</th>
                <th class="text-center">DATE</th>
                <th class="text-left">NOMBRE DE CANDIDATS</th>
                <th>ACTIONS</th>
            </tr>
            </thead>
            <tbody>
            <tr v-show="recrutements.length" v-for="recrutement in recrutements" :key="recrutement.id">
                <th scope="row" class="text-center">
                    {{ recrutement.id }}
                </th>
                <td>
                    {{ recrutement.formation }}
                </td>
                <td>
                    {{ recrutement.session }}
                </td>
                <td>
                    {{ recrutement.tuteur_prenom }}
                </td>
                <td class="text-center">
                    {{ recrutement.date | newDate }}
                </td>
                <td>
                    {{ recrutement.nombreCandidats }} candidats
                </td>
                <td>
                    <div class="d-flex flex-row">
                        <router-link :to="{ name: 'showRecrutement', params: { id: recrutement.id }}">
                            <i class="fas fa-eye fa-lg text-blue mr-1"></i>
                        </router-link>
                        <span v-show="currentUser.role === 'admin' || currentUser.role === 'master'"> | </span>
                        <button type="button" class="mx-1" @click="editRecrutement(recrutement, recrutement.id, recrutement.formation_id)"
                            v-show="currentUser.role === 'admin' || currentUser.role === 'master'">
                            <i class="fas fa-edit fa-lg text-green"></i>
                        </button>
                        <span v-show="currentUser.role === 'admin' || currentUser.role === 'master'"> | </span>
                        <button type="button" class="ml-1" @click="verifRecrutement(recrutement.id)"
                            v-show="currentUser.role === 'admin' || currentUser.role === 'master'">
                            <i class="fas fa-trash fa-lg text-red"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr v-show="!recrutements.length && loading === false">
                <td colspan="12" class="pt-4">
                    <v-row class="text-center text-interface mt-10">
                        <v-col class="d-flex flex-column justify-center align-center">
                            <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                            <span class="mt-5">Chargement...</span>
                        </v-col>
                    </v-row>
                </td>
            </tr>
            <tr v-show="!recrutements.length && loading === true" class="justify-content-center">
                <td colspan="12" class="pt-4">
                    <div class="alert alert-danger" role="alert">
                        Oups ! Rien ne correspond à votre recherche.
                    </div>
                </td>
            </tr>
            </tbody>
        </v-simple-table>
        <PaginationComponent class="mt-3"
             v-if="pagination.last_page > 1"
             :pagination="pagination"
             :offset="5"
             @paginate="search === '' ? getData() : searchData()"
        ></PaginationComponent>
        <!-- Modal d'édition -->
        <v-dialog v-model="dialog_edition" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Modification de la date</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row justify="center">
                            <v-menu
                                ref="menu"
                                v-model="menu_date_recrutement"
                                :close-on-content-click="false"
                                :return-value.sync="form.date"
                                transition="scale-transition"
                                offset-y
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="form.date"
                                        label="Date du jour de recrutement"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="form.date"
                                    :allowed-dates="allowedDays(listDatesOthersRecrutements)"
                                    :min="min"
                                    :max="max"
                                    locale="be-fr"
                                    :first-day-of-week="1"
                                    @change="save"
                                >
                                    <v-spacer></v-spacer>
                                    <v-btn text color="primary" @click="menu_date_recrutement = false" class="mt-n12">
                                        Cancel
                                    </v-btn>
                                    <v-btn text color="primary" @click="$refs.menu.save(form.date)"  class="mt-n12">
                                        OK
                                    </v-btn>
                                </v-date-picker>
                            </v-menu>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="dialog_edition = false">Close</v-btn>
                    <v-btn color="success" @click="updateRecrutement(form.id)">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Modal de vérification pour pouvoir transférer les candidats lors de la suppression du recrutement -->
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
                            <div v-if="transfertCandidats.length > 0">
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
                                        <tr v-for="(candidat, index) in transfertCandidats" :key="index">
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
                                                    :items="listDatesOthersRecrutements"
                                                    v-model="candidat[5]"
                                                    label="Autre(s) date(s)"
                                                ></v-select>
                                            </td>
                                            <td v-show="candidat[4] === true">
                                                <button type="button" class="ml-1" @click="reportCandidat(candidat[0], candidat[1] + ' ' + candidat[2], candidat[5])">
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
                    <v-btn depressed color="warning" @click="dialog_verif_recrutement = false">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="destroyRecrutement(recrutement.id)">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import PaginationComponent from "../../elements/PaginationComponent";
    import {Form} from "vform";

    export default {
        name: "ListRecrutements",
        components: {
            PaginationComponent,
        },
        data() {
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const minDate = new Date(today).toISOString().substr(0, 10);
            return {
                loading: false,
                searchColonnes: ['date'],
                colonne: 'date',
                search: '',
                recrutements: [],
                pagination: {
                    current_page: 1,
                },
                form: new Form({
                    id: '',
                    formation_id: null,
                    formation: '',
                    session: '',
                    date: null,
                }),
                dialog_edition: false,
                menu_date_recrutement: false,
                listDatesOthersRecrutements: [],
                weekday: 1,
                min: minDate,
                max: null,
                recrutement: [],
                others_recrutements: [],
                listCandidats: {},
                candidat: [],
                candidats: [],
                transfertCandidats: [],
                numberOfListCandidats: null,
                dialog_verif_recrutement: false,
                transfertCandidat: false,
                newRecrutementId: null,
                listCandidatsTemp: [],
                checkNbreTransfertTrue: false,
                checkAllCandidatsOk: false,
                nbreTransfertFalse: 0,
                nbreTransfertTrue: 0,
            }
        },
        watch: {
            search: function(newSearch) {
                if (newSearch === '') {
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
            getData(){
                this.loading = false;
                axios.get('api/recrutements?page=' + this.pagination.current_page)
                    .then(response => {
                        this.recrutements = response.data.data
                        this.pagination = response.data.meta
                        this.loading= true;
                    })
                    .catch(error => {
                        console.log(error.response)
                        Snackbar.fire('Inscription non effectuée !');
                    })
            },
            searchData() {
                this.$Progress.start();
                axios.get('api/search/recrutements/' + this.colonne +'/'+ this.search +'?page=' + this.pagination.current_page)
                    .then(response => {
                        this.recrutements = response.data.data
                        this.pagination = response.data.meta
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response)
                        Snackbar.fire('Problème dans la récupération des infos suite à la recherche !');
                    })
            },
            save (date) {
                this.$refs.menu.save(date)
            },
            allowedDays: function(a){
                return val => a.indexOf(val) === -1 && ![0,6].includes(new Date(val).getDay());
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
                axios.get(`/api/recrutements/others/` + recrutement + '/' + formation)
                    .then((response) => {
                        this.others_recrutements = response.data.othersRecrutements;
                        for(let i=0; i < this.others_recrutements.length; i++) {
                            this.listDatesOthersRecrutements.push(this.others_recrutements[i].date);
                        }
                    }).catch(error => {
                    this.$Progress.fail();
                    console.log(error.response)
                    Snackbar.fire('Problème dans la récupération des infos des autres recrutements !');
                })
            },
            updateRecrutement() {
                this.$Progress.start();
                this.form.busy = true;
                this.form
                    .put('api/recrutements/' + this.form.id)
                    .then(response => {
                        if(this.form.successful) {
                            this.$Progress.finish();
                            Toast.fire('Recrutement édité');
                            this.dialog_edition = false;
                            this.getData()
                            Fire.$emit('RefreshPage'); // Rafraichit la page
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response)
                        Snackbar.fire('Recrutement non édité !');
                    })
            },
            verifRecrutement(recrutement) {
                this.getRecrutement(recrutement);
            },
            getRecrutement(recrutement) {
                axios.get(`/api/recrutements/show/` + recrutement)
                    .then((response) => {
                        this.recrutement = response.data.recrutement;
                        this.formation_date = response.data.formation;
                        this.candidats = response.data.candidats;
                        this.getOthersRecrutements(this.recrutement.id, this.recrutement.formation_id);
                        this.getListCandidatsAllRecrutements();
                    })
                    .catch(error => {
                        console.log(error.response)
                        Snackbar.fire('Problème de récupération des infos du recrutement !');
                    })
            },
            getListCandidatsAllRecrutements() {
                this.listCandidats = [];
                axios.get(`/api/recrutements/recrutementsFormation/` + this.recrutement.formation_id)
                    .then((response) => {
                        this.listCandidats = response.data.candidats;
                        this.listTransfertCandidats();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response)
                        Snackbar.fire('Problème de récupération des infos des candidats de tous recrutements de la même formation !');
                    })
            },
            listTransfertCandidats() {
                this.transfertCandidats = [];
                for (let x=0; x < this.listCandidats.length; x++) {
                    if(this.listCandidats[x].length > 0) {
                        if(this.listCandidats[x][0].recrutement_id === this.recrutement.id) {
                            for (let y=0; y < this.listCandidats[x].length; y++) {
                                this.candidat = [];
                                this.candidat.push(this.listCandidats[x][y].inscrit_id);
                                this.candidat.push(this.listCandidats[x][y].prenom);
                                this.candidat.push(this.listCandidats[x][y].nom);
                                this.candidat.push(this.listCandidats[x][y].inscrit_recrutement_id);
                                this.candidat.push(false);
                                this.transfertCandidats.push(this.candidat);
                            }
                        }
                    }
                }
                this.dialog_verif_recrutement = true;
                this.transfertCandidat = false;
            },
            reportCandidat(candidat_id, candidat, recrutement) {
                if(recrutement === undefined) {
                    Snackbar.fire('Vous n\'avez pas encodé de date pour ' + candidat);
                }
                for (let i=0; i < this.others_recrutements.length; i++) {
                    if(this.others_recrutements[i].date === recrutement) {
                        this.newRecrutementId = this.others_recrutements[i].id;
                    }
                }
                axios.put('api/inscrits/recrutement/' + candidat_id +'/'+ this.recrutement.id +'/'+ this.newRecrutementId)
                    .then(response => {
                        for (let x=0; x < this.transfertCandidats.length; x++) {
                            if(this.transfertCandidats[x][0] === candidat_id) {
                                this.transfertCandidats[x].push(true);
                            }
                        }
                        Toast.fire('Transfert du candidat effectué');
                    })
                    .catch(error => {
                        for (let x=0; x < this.transfertCandidats.length; x++) {
                            if(this.transfertCandidats[x][0] === candidat_id) {
                                this.transfertCandidats[x].push(false);
                            }
                        }
                        console.log(error.response)
                        Snackbar.fire('Transfert du candidat non effectué');
                    })
            },
            destroyRecrutement(recrutement) {
                this.$Progress.start();
                this.nbreTransfertFalse = 0;
                this.checkNbreTransfertTrue = false;
                this.checkAllCandidatsOk = false;
                if(this.transfertCandidat === true) {
                    for(let y=0; y < this.transfertCandidats.length; y++ ) {
                        if(!this.transfertCandidats[y][4]) {
                            this.nbreTransfertFalse = this.nbreTransfertFalse+1;
                        } else if(this.transfertCandidats[y][4] === true) {
                            if(!this.transfertCandidats[y][5]) {
                                this.$Progress.fail();
                                Snackbar.fire('Vous n\'avez pas encodé de date pour ' + this.transfertCandidats[y][1] + ' ' + this.transfertCandidats[y][2]);
                            } else if (this.transfertCandidats[y][6] === false) {
                                this.$Progress.fail();
                                Snackbar.fire('Le transfert de ' + this.transfertCandidats[y][1] + ' ' + this.transfertCandidats[y][2] + ' n\'a pas été effectué. Réessayez !');
                            } else if (!this.transfertCandidats[y][6]) {
                                this.$Progress.fail();
                                Snackbar.fire('Vous n\'avez pas effectué le transfert de ' + this.transfertCandidats[y][1] + ' ' + this.transfertCandidats[y][2]);
                            }
                        }
                    }

                    if (this.nbreTransfertFalse === this.transfertCandidats.length) {
                        this.$Progress.fail();
                        Snackbar.fire('Vous n\'avez pas transféré personne');
                        this.checkNbreTransfertTrue = false;
                    } else {
                        this.checkNbreTransfertTrue = true;
                    }

                    this.nbreTransfertTrue = 0;
                    for(let z=0; z < this.transfertCandidats.length; z++ ) {
                        if(this.transfertCandidats[z][4] === true && this.transfertCandidats[z][6] === true) {
                            this.nbreTransfertTrue = this.nbreTransfertTrue + 1;
                        }
                    }

                    if(this.nbreTransfertTrue === this.transfertCandidats.length - this.nbreTransfertFalse) {
                        this.checkAllCandidatsOk = true;
                    }
                }
                if((this.transfertCandidat === true && this.checkNbreTransfertTrue === true && this.checkAllCandidatsOk === true) || this.transfertCandidat === false) {
                    Suppression.fire().then((result) => {
                        if(result.value) {
                            if(this.transfertCandidat === true && this.checkNbreTransfertTrue === true && this.checkAllCandidatsOk === true) {
                                axios.delete('api/recrutements/' + recrutement)
                                    .then(response => {
                                        this.$Progress.finish();
                                        Toast.fire('Recrutement Supprimé! \n Transfert du candidat effectué');
                                        this.dialog_verif_recrutement = false;
                                        this.getData()
                                        Fire.$emit('RefreshPage');
                                    })
                                    .catch(error => {
                                        this.$Progress.fail();
                                        console.log(error.response);
                                        Snackbar.fire('Problème avec la suppression du recrutement !');
                                    });
                            } else if(this.transfertCandidat === false) {
                                axios.delete('api/recrutements/' + recrutement)
                                    .then(response => {
                                        this.$Progress.finish();
                                        Toast.fire('Recrutement Supprimé!');
                                        this.dialog_verif_recrutement = false;
                                        this.getData()
                                        Fire.$emit('RefreshPage');
                                    })
                                    .catch(error => {
                                        this.$Progress.fail();
                                        console.log(error.response);
                                        Snackbar.fire('Problème avec la suppression du recrutement sans transfert candidat !');
                                    });
                            }
                        }
                    });
                }
            },
        },
    }
</script>

<style scoped>

</style>
