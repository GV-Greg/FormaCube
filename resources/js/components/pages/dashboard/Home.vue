<template>
    <div class="home-container">
        <div v-if="loading">
            <!-- Barre de recherche -->
            <v-row justify="center" class="mb-4">
                <v-col cols="12" lg="8">
                    <v-text-field
                        v-model="query"
                        prepend-inner-icon="mdi-magnify"
                        label="Rechercher un·e inscrit·e"
                        variant="outlined"
                        density="comfortable"
                        bg-color="white"
                        hide-details
                        clearable
                    ></v-text-field>
                    <v-card v-if="results.length > 0 && query" class="search-results mt-1">
                        <v-list density="compact">
                            <v-list-item
                                v-for="result in results.slice(0,10)"
                                :key="result.id"
                                :href="result.url"
                                prepend-icon="mdi-account"
                            >
                                <v-list-item-title>{{ result.title }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-card>
                </v-col>
            </v-row>

            <!-- Boutons d'action -->
            <v-row justify="center" class="mb-4">
                <v-col cols="auto">
                    <router-link to="/inscrits/create" class="text-decoration-none">
                        <v-card class="action-card text-center pa-4" elevation="3" hover>
                            <v-icon size="48" color="primary">mdi-account-plus</v-icon>
                            <div class="mt-2 text-primary font-weight-bold">Faire une inscription</div>
                        </v-card>
                    </router-link>
                </v-col>
                <v-col cols="auto" v-if="currentUser?.role === 'admin' || currentUser?.role === 'master'">
                    <router-link to="/formations/create" class="text-decoration-none">
                        <v-card class="action-card text-center pa-4" elevation="3" hover>
                            <v-icon size="48" color="primary">mdi-school-outline</v-icon>
                            <div class="mt-2 text-primary font-weight-bold">Créer une formation</div>
                        </v-card>
                    </router-link>
                </v-col>
            </v-row>

            <!-- Tabs Formations / Recrutements -->
            <v-row justify="center">
                <v-col cols="12" lg="10">
                    <v-card elevation="4">
                        <v-tabs v-model="tabIndex" bg-color="primary" align-tabs="center" grow>
                            <v-tab value="formation">
                                <v-icon start>mdi-school</v-icon>
                                Formations
                            </v-tab>
                            <v-tab value="recrutement">
                                <v-icon start>mdi-calendar-plus</v-icon>
                                Recrutements
                            </v-tab>
                        </v-tabs>

                        <v-tabs-window v-model="tabIndex">
                            <!-- Tab Formations -->
                            <v-tabs-window-item value="formation">
                                <v-container v-if="loadingDatas" class="pa-4">
                                    <div v-if="formations.length > 0" class="d-flex flex-wrap justify-center ga-4">
                                        <v-card
                                            v-for="(formation, index) in formations"
                                            :key="formation.id"
                                            :to="{ name: 'showFormation', params: { id: formation.id }}"
                                            class="formation-card"
                                            elevation="3"
                                            hover
                                        >
                                            <v-card-title class="text-center text-primary">{{ formation.abreviation }}</v-card-title>
                                            <v-card-text class="text-center">
                                                <div class="text-caption">Du: {{ formatDate(formation.date_debut) }}</div>
                                                <div class="text-caption">Au: {{ formatDate(formation.date_fin) }}</div>
                                                <div class="text-h6 my-2">{{ stagiaires[index]?.[0] || 0 }} / {{ formation.max_stagiaires }}</div>
                                                <v-chip :color="getStatusColor(stagiaires[index]?.[1])" size="small">
                                                    {{ stagiaires[index]?.[1] || 'N/A' }}
                                                </v-chip>
                                                <div class="text-caption mt-2">
                                                    Tuteur: {{ formation.tuteur_prenom }}
                                                </div>
                                            </v-card-text>
                                        </v-card>
                                    </div>
                                    <div v-else class="text-center pa-4 text-grey">
                                        Aucune formation prévue
                                    </div>
                                </v-container>
                                <v-container v-else class="text-center pa-8">
                                    <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
                                    <div class="mt-4">Chargement...</div>
                                </v-container>
                            </v-tabs-window-item>

                            <!-- Tab Recrutements -->
                            <v-tabs-window-item value="recrutement">
                                <v-container v-if="loadingDatas" class="pa-4">
                                    <div v-if="recrutements.length > 0" class="d-flex flex-wrap justify-center ga-4">
                                        <v-card
                                            v-for="(recrutement, index) in recrutements"
                                            :key="recrutement.id"
                                            :to="{ name: 'showRecrutement', params: { id: recrutement.id }}"
                                            class="recrutement-card"
                                            elevation="3"
                                            hover
                                        >
                                            <v-card-title class="text-center text-primary">{{ recrutement.abreviation }}</v-card-title>
                                            <v-card-text class="text-center">
                                                <div class="text-caption">Date: {{ formatDate(recrutement.date) }}</div>
                                                <div class="text-h6 my-2 text-success">
                                                    {{ candidats[index] || 0 }} candidat{{ (candidats[index] || 0) > 1 ? 's' : '' }}
                                                </div>
                                                <div class="text-caption">
                                                    Tuteur: {{ recrutement.tuteur_prenom }}
                                                </div>
                                            </v-card-text>
                                        </v-card>
                                    </div>
                                    <div v-else class="text-center pa-4 text-grey">
                                        Aucun recrutement prévu
                                    </div>
                                </v-container>
                                <v-container v-else class="text-center pa-8">
                                    <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
                                    <div class="mt-4">Chargement...</div>
                                </v-container>
                            </v-tabs-window-item>
                        </v-tabs-window>
                    </v-card>
                </v-col>
            </v-row>
        </div>

        <!-- Loading initial -->
        <div v-else class="d-flex flex-column align-center justify-center" style="min-height: 50vh;">
            <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
            <div class="mt-4">Chargement...</div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';

export default {
    data() {
        return {
            loading: false,
            loadingDatas: false,
            tabIndex: 'formation',
            query: null,
            results: [],
            formations: [],
            stagiaires: [],
            recrutements: [],
            candidats: [],
        }
    },
    watch: {
        query(after, before) {
            if (after) {
                this.searchInscrits();
            } else {
                this.results = [];
            }
        }
    },
    mounted() {
        this.loading = false;
        this.loadingDatas = false;
        this.getFormations();
        this.getRecrutements();
        this.loading = true;
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        }
    },
    methods: {
        formatDate(date) {
            return moment(date).format('DD/MM/YYYY');
        },
        getStatusColor(status) {
            switch(status) {
                case 'Complet': return 'success';
                case 'Incomplet': return 'warning';
                case 'Insuffisant': return 'error';
                default: return 'grey';
            }
        },
        getFormations() {
            this.loadingDatas = false;
            axios.get('api/formations/all')
                .then(response => {
                    this.formations = response.data.formations;
                    this.stagiaires = response.data.stagiaires;
                })
                .catch(error => {
                    console.log(error);
                })
        },
        getRecrutements() {
            axios.get('api/recrutements/all')
                .then(response => {
                    this.recrutements = response.data.recrutements;
                    this.candidats = response.data.candidats;
                    this.loadingDatas = true;
                })
                .catch(error => {
                    console.log(error);
                })
        },
        searchInscrits() {
            axios.get('api/inscrits/search', { params: { query: this.query } })
                .then(response => this.results = response.data)
                .catch(error => { console.log(error) });
        },
    },
}
</script>

<style scoped>
.home-container {
    padding: 1rem;
}

.search-results {
    position: absolute;
    z-index: 100;
    width: 100%;
    max-height: 300px;
    overflow-y: auto;
}

.action-card {
    min-width: 180px;
    cursor: pointer;
    transition: transform 0.2s;
}

.action-card:hover {
    transform: translateY(-4px);
}

.formation-card,
.recrutement-card {
    width: 180px;
    min-height: 180px;
    cursor: pointer;
    transition: transform 0.2s;
}

.formation-card:hover,
.recrutement-card:hover {
    transform: translateY(-4px);
}
</style>
