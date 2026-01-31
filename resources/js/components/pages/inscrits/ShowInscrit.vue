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
                <h1 class="text-h4">Fiche d'inscrit<span v-if="inscrit.genre === 'femme'">e</span></h1>
            </v-col>
        </v-row>

        <!-- Main Content -->
        <div v-if="loading">
            <!-- Inscrit Info Card -->
            <v-card class="mb-4">
                <v-card-text>
                    <v-row align="center">
                        <v-col cols="11">
                            <v-row align="center">
                                <v-col cols="auto">
                                    <v-avatar size="64">
                                        <v-img v-if="inscrit.genre === 'homme'" src="/storage/images/avatars/man.png"></v-img>
                                        <v-img v-else-if="inscrit.genre === 'femme'" src="/storage/images/avatars/woman.png"></v-img>
                                        <v-img v-else src="/storage/images/avatars/user.png"></v-img>
                                    </v-avatar>
                                </v-col>
                                <v-col>
                                    <h2 class="text-h5 font-weight-bold">{{ inscrit.prenom }} {{ inscrit.nom?.toUpperCase() }}</h2>
                                    <p class="text-caption text-grey mb-0"><em>Fiche créée depuis le {{ formatDate(inscrit.created_at) }}.</em></p>
                                </v-col>
                            </v-row>
                        </v-col>
                        <v-col cols="1" class="text-right">
                            <v-speed-dial location="bottom end" transition="fade-transition">
                                <template v-slot:activator="{ props: activatorProps }">
                                    <v-btn v-bind="activatorProps" icon color="primary" variant="elevated">
                                        <v-icon>mdi-cog</v-icon>
                                    </v-btn>
                                </template>
                                <v-btn icon color="success" size="small" :to="{ name: 'editInscrit', params: { id: inscrit.id }}">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon color="error" size="small" @click="destroy">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </v-speed-dial>
                        </v-col>
                    </v-row>

                    <v-divider class="my-4"></v-divider>

                    <v-row>
                        <v-col cols="12" md="6">
                            <p><strong>DATE DE NAISSANCE :</strong>
                                <span v-if="inscrit.date_naissance">{{ formatDate(inscrit.date_naissance) }}</span>
                                <span v-else class="text-grey">Non renseigné</span>
                            </p>
                            <p><strong>ADRESSE :</strong>
                                <span v-if="inscrit.rue">{{ inscrit.rue }}, {{ inscrit.numero }} <span v-if="inscrit.boite">bte</span> {{ inscrit.boite }} - </span>
                                <span v-if="inscrit.cp !== 0">{{ inscrit.cp }} {{ inscrit.ville }}</span>
                                <span v-else class="text-grey">Non renseigné</span>
                            </p>
                            <p><strong>EMAIL :</strong>
                                <a v-if="inscrit.email" :href="'mailto:' + inscrit.email" class="text-primary">{{ inscrit.email }}</a>
                                <span v-else class="text-grey">Non renseigné</span>
                            </p>
                            <p><strong>TÉL. :</strong>
                                <span v-if="inscrit.tel">{{ inscrit.tel }}</span>
                                <span v-else class="text-grey">Non renseigné</span>
                            </p>
                            <p><strong>GSM :</strong>
                                <span v-if="inscrit.gsm">{{ inscrit.gsm }}</span>
                                <span v-else class="text-grey">Non renseigné</span>
                            </p>
                            <p><strong>NEWSLETTER :</strong> {{ inscrit.newsletter === 1 ? 'oui' : 'non' }}</p>
                            <p><strong>COMMENTAIRE :</strong></p>
                            <p class="ml-4" v-if="inscrit.commentaire_inscrit">{{ inscrit.commentaire_inscrit }}</p>
                            <p class="ml-4 text-grey" v-else>Aucun</p>
                        </v-col>
                        <v-col cols="12" md="6">
                            <h6 class="font-weight-bold">Centre(s) d'intérêt :</h6>
                            <div v-if="tags.length === 0" class="text-grey">Aucun</div>
                            <div v-else>
                                <v-chip
                                    v-for="item in tags"
                                    :key="item.id"
                                    class="ma-1"
                                    color="success"
                                    closable
                                    @click:close="deleteTag(item.id)"
                                >
                                    <v-icon start>mdi-tag</v-icon>
                                    {{ item.tag }}
                                </v-chip>
                            </div>
                        </v-col>
                    </v-row>

                    <v-divider class="my-4"></v-divider>

                    <v-row>
                        <v-col cols="12" md="6">
                            <strong>FORMATION(S) :</strong>
                            <ul v-if="formations.length > 0">
                                <li v-for="formation in formations" :key="formation.id">
                                    <strong>{{ formation.nom }}</strong>
                                    <span v-if="formation.session"> - {{ formation.session }}</span>
                                    du {{ formatDate(formation.date_debut) }} au {{ formatDate(formation.date_fin) }}
                                </li>
                            </ul>
                            <p v-else class="text-grey ml-4">Aucune</p>
                        </v-col>
                        <v-col cols="12" md="6">
                            <strong>RECRUTEMENT(S) :</strong>
                            <ul v-if="recrutements.length > 0">
                                <li v-for="(item, index) in recrutements" :key="index">
                                    <strong>Recrutement du {{ formatDate(item.date) }}</strong> pour la formation <strong>{{ item.formation_nom }}</strong>
                                </li>
                            </ul>
                            <p v-else class="text-grey ml-4">Aucun</p>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

            <!-- Tabs -->
            <v-card>
                <v-tabs v-model="tab" bg-color="primary">
                    <v-tab value="historique">Historique</v-tab>
                    <v-tab value="ajoutStagiaire">Inscrire à une formation</v-tab>
                    <v-tab value="ajoutCandidat">Inscrire à un recrutement</v-tab>
                    <v-tab value="tags">Tags</v-tab>
                </v-tabs>

                <v-tabs-window v-model="tab">
                    <!-- Tab: Historique -->
                    <v-tabs-window-item value="historique">
                        <v-card-text>
                            <div v-if="loadingData">
                                <ul v-if="logs.length > 0">
                                    <li v-for="log in logs" :key="log.id">
                                        <strong v-if="log.creation === 1">{{ formatDate(log.date) }} : {{ log.informations }} par {{ log.user }}</strong>
                                        <span v-else>{{ formatDate(log.date) }} : {{ log.informations }} par {{ log.user }}</span>
                                    </li>
                                </ul>
                                <p v-else class="text-grey">Aucun historique</p>
                            </div>
                            <div v-else class="text-center py-8">
                                <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
                                <div class="mt-4">Chargement...</div>
                            </div>
                        </v-card-text>
                    </v-tabs-window-item>

                    <!-- Tab: Inscrire à une formation -->
                    <v-tabs-window-item value="ajoutStagiaire">
                        <v-card-text>
                            <v-row v-if="listFormations && listFormations.length > 0" align="center">
                                <v-col cols="12" md="8">
                                    <v-select
                                        v-model="choix_formation"
                                        :items="listFormations"
                                        :item-title="item => item.nom + (item.session ? ' - ' + item.session : '') + ' débutant le ' + formatDate(item.date_debut) + (nbreStagiaires[listFormations.indexOf(item)][0] === nbreStagiaires[listFormations.indexOf(item)][1] ? ' - complet' : '')"
                                        item-value="id"
                                        :item-props="(item) => ({ disabled: nbreStagiaires[listFormations.indexOf(item)][0] === nbreStagiaires[listFormations.indexOf(item)][1] })"
                                        label="Choisissez une formation"
                                        variant="outlined"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-btn color="success" @click="checkFormation">Inscrire</v-btn>
                                </v-col>
                            </v-row>
                            <v-alert v-else type="error" variant="tonal">
                                Aucune future formation prévue.
                            </v-alert>
                        </v-card-text>
                    </v-tabs-window-item>

                    <!-- Tab: Inscrire à un recrutement -->
                    <v-tabs-window-item value="ajoutCandidat">
                        <v-card-text>
                            <v-row v-if="listRecrutements && listRecrutements.length > 0" align="center">
                                <v-col cols="12" md="8">
                                    <v-select
                                        v-model="choix_recrutement"
                                        :items="listRecrutements"
                                        :item-title="item => formatDate(item.date) + ' - pour la formation : ' + item.formation"
                                        item-value="id"
                                        label="Choisissez un recrutement"
                                        variant="outlined"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-btn color="success" @click="checkRecrutement">Inscrire</v-btn>
                                </v-col>
                            </v-row>
                            <v-alert v-else type="error" variant="tonal">
                                Aucun futur recrutement prévu.
                            </v-alert>
                        </v-card-text>
                    </v-tabs-window-item>

                    <!-- Tab: Tags -->
                    <v-tabs-window-item value="tags">
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-select
                                        v-model="inscritTags"
                                        :items="listTags"
                                        item-title="tag"
                                        item-value="tag"
                                        label="Sélectionner les centres d'intérêts"
                                        variant="outlined"
                                        multiple
                                        chips
                                        closable-chips
                                    ></v-select>
                                    <v-btn color="success" @click="addTags">Ajouter les tags</v-btn>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="newTagInput"
                                        label="Créer un nouveau tag (min 3 caractères)"
                                        variant="outlined"
                                        hint="Appuyez sur le bouton pour créer le tag"
                                    ></v-text-field>
                                    <v-btn color="primary" @click="createTag" :disabled="!newTagInput || newTagInput.length < 3">
                                        Créer le tag
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
    </v-container>
</template>

<script>
import moment from 'moment';
import { Form } from "vform";

export default {
    name: "ShowInscrit",
    data() {
        return {
            loading: false,
            loadingData: false,
            tab: 'historique',
            inscrit: {},
            formations: [],
            recrutements: [],
            tags: [],
            logs: [],
            listFormations: [],
            nbreStagiaires: [],
            choix_formation: null,
            listRecrutements: [],
            choix_recrutement: null,
            listTags: [],
            inscritTags: [],
            newTagInput: '',
            formInscrit: new Form({ tags: null, current_user: null }),
        }
    },
    mounted() {
        console.log('Show Inscrit component mounted');
        this.getDatas();
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
        goBack() {
            this.$router.go(-1);
        },
        getDatas() {
            this.getInscrit();
            this.getFormations();
            this.getRecrutements();
            this.getListTags();
        },
        getInscrit() {
            this.loading = false;
            this.$Progress.start();
            axios.get(`/api/inscrits/show/${this.$route.params.id}`)
                .then(response => {
                    this.inscrit = response.data.inscrit;
                    this.tags = response.data.tags;
                    this.recrutements = response.data.recrutements;
                    this.formations = response.data.formations;
                    this.getLogs();
                    this.loading = true;
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération des infos de la personne');
                });
        },
        getLogs() {
            axios.get(`/api/logs/show/${this.$route.params.id}`)
                .then(response => {
                    this.logs = response.data.logs;
                    this.loadingData = true;
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération des logs');
                });
        },
        getFormations() {
            axios.get('api/formations/all')
                .then(response => {
                    this.listFormations = response.data.formations;
                    this.nbreStagiaires = response.data.nbreStagiaires;
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération de la liste des formations');
                });
        },
        getRecrutements() {
            axios.get('api/recrutements/futurs')
                .then(response => {
                    this.listRecrutements = response.data.recrutements;
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération de la liste des recrutements');
                });
        },
        checkFormation() {
            if (this.choix_formation) {
                this.inscriptionFormation();
            } else {
                Snackbar.fire("Vous n'avez pas choisi de formation !");
            }
        },
        inscriptionFormation() {
            axios.post('api/formations/addInscrit/' + this.choix_formation + '/' + this.inscrit.id)
                .then(response => {
                    this.$Progress.finish();
                    if (response.data.message) {
                        Toast.fire({ title: 'Inscription effectuée', timer: 2000 });
                    } else if (response.data.error) {
                        Snackbar.fire('Déjà inscrit à cette formation');
                    }
                    this.choix_formation = null;
                    this.getInscrit();
                    Fire.$emit('RefreshPage');
                })
                .catch(error => {
                    console.log(error.response);
                    this.$Progress.fail();
                    Snackbar.fire("Problème avec l'inscription à la formation");
                });
        },
        checkRecrutement() {
            if (this.choix_recrutement) {
                this.inscriptionRecrutement();
            } else {
                Snackbar.fire("Vous n'avez pas choisi de recrutement !");
            }
        },
        inscriptionRecrutement() {
            axios.post('api/recrutements/addInscrit/' + this.choix_recrutement + '/' + this.inscrit.id)
                .then(response => {
                    this.$Progress.finish();
                    if (response.data.message) {
                        Toast.fire({ title: 'Inscription effectuée', timer: 2000 });
                    } else if (response.data.error) {
                        Snackbar.fire('Déjà inscrit à ce recrutement');
                    }
                    this.choix_recrutement = null;
                    this.getInscrit();
                    Fire.$emit('RefreshPage');
                })
                .catch(error => {
                    console.log(error.response);
                    this.$Progress.fail();
                    Snackbar.fire("Problème avec l'inscription au recrutement");
                });
        },
        getListTags() {
            axios.get('api/tags/all')
                .then(response => { this.listTags = response.data.tags; })
                .catch(error => { console.log(error.response); Snackbar.fire('Problème avec la récupération de la liste de tags'); });
        },
        createTag() {
            if (!this.newTagInput || this.newTagInput.length < 3) {
                Snackbar.fire('Le tag doit avoir au moins 3 caractères');
                return;
            }
            const tag = this.newTagInput.toLowerCase();
            if (this.listTags.find(t => t.tag === tag)) {
                Snackbar.fire('Tag déjà existant');
                this.newTagInput = '';
                return;
            }
            this.$Progress.start();
            axios.post('api/tags/create', { tag })
                .then(response => {
                    Toast.fire('Tag créé');
                    this.getListTags();
                    this.inscritTags.push(tag);
                    this.newTagInput = '';
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.error(error.response);
                    Snackbar.fire('Problème avec la création de tag');
                });
        },
        addTags() {
            if (this.inscritTags.length < 1) {
                Snackbar.fire('Aucun tag sélectionné');
                return;
            }
            this.$Progress.start();
            this.formInscrit.tags = this.inscritTags;
            this.formInscrit.current_user = this.currentUser.id;
            this.formInscrit.post('api/inscrits/addTags/' + this.inscrit.id)
                .then(response => {
                    if (this.formInscrit.successful) {
                        this.$Progress.finish();
                        Toast.fire(response.data);
                        this.formInscrit.tags = [];
                        this.inscritTags = [];
                        this.getInscrit();
                    }
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire("Problème avec l'ajout de tags");
                });
        },
        deleteTag(id) {
            Suppression.fire().then(result => {
                if (result.value) {
                    this.$Progress.start();
                    axios.delete('api/inscrits/deleteTags/' + this.inscrit.id + '/' + id + '/' + this.currentUser.id)
                        .then(response => {
                            this.$Progress.finish();
                            Confirm.fire('Tag Supprimé!');
                            this.getInscrit();
                            Fire.$emit('RefreshPage');
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            Snackbar.fire('Problème avec la suppression du tag');
                        });
                }
            });
        },
        destroy() {
            Suppression.fire().then(result => {
                if (result.value) {
                    this.$Progress.start();
                    axios.delete('api/inscrits/' + this.inscrit.id)
                        .then(response => {
                            this.$Progress.finish();
                            Confirm.fire('Personne Supprimée!');
                            this.$router.push('/inscrits');
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            Snackbar.fire('Problème avec la suppression de la personne');
                        });
                }
            });
        }
    }
}
</script>

<style scoped>
.v-table th {
    background-color: rgb(var(--v-theme-primary)) !important;
    color: white !important;
}
</style>
