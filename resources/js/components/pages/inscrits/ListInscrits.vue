<template>
    <v-container fluid>
        <h1 class="text-h4 mb-4">Liste des Inscrits</h1>

        <!-- Actions & Search -->
        <v-row align="center" class="mb-4">
            <v-col cols="12" md="3">
                <v-btn color="success" :to="'/inscrits/create'" prepend-icon="mdi-plus">
                    Nouveau
                </v-btn>
            </v-col>
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

        <v-alert v-if="colonne === 'date_naissance'" type="warning" variant="tonal" class="mb-4">
            Le format de date doit être : YYYY-MM-JJ
        </v-alert>

        <!-- Table -->
        <v-card>
            <v-table fixed-header>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>NOM</th>
                        <th>PRÉNOM</th>
                        <th>NÉ·E LE</th>
                        <th>ADRESSE</th>
                        <th>TÉL/GSM</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="inscrits.length" v-for="inscrit in inscrits" :key="inscrit.id">
                        <td>{{ inscrit.id }}</td>
                        <td>{{ inscrit.nom?.toUpperCase() }}</td>
                        <td>{{ inscrit.prenom }}</td>
                        <td>
                            <span v-if="inscrit.date_naissance">{{ formatDate(inscrit.date_naissance) }}</span>
                        </td>
                        <td>
                            <span v-if="inscrit.rue">{{ inscrit.rue }}, {{ inscrit.numero }} <span v-if="inscrit.boite">bte</span> {{ inscrit.boite }} - </span>
                            {{ inscrit.cp }} {{ inscrit.ville }}
                        </td>
                        <td>
                            <span v-if="inscrit.tel">{{ inscrit.tel }}</span>
                            <br v-if="inscrit.tel && inscrit.gsm">
                            <span v-if="inscrit.gsm">{{ inscrit.gsm }}</span>
                        </td>
                        <td>
                            <v-btn icon variant="text" size="small" color="primary" :to="{ name: 'showInscrit', params: { id: inscrit.id }}">
                                <v-icon>mdi-eye</v-icon>
                            </v-btn>
                            <v-btn icon variant="text" size="small" color="success" :to="{ name: 'editInscrit', params: { id: inscrit.id }}">
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                            <v-btn icon variant="text" size="small" color="error" @click="destroy(inscrit)">
                                <v-icon>mdi-delete</v-icon>
                            </v-btn>
                        </td>
                    </tr>
                    <tr v-if="!inscrits.length && !loading">
                        <td colspan="7">
                            <div class="text-center py-8">
                                <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
                                <div class="mt-4">Chargement...</div>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!inscrits.length && loading">
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
    </v-container>
</template>

<script>
import moment from 'moment';

export default {
    name: "ListInscrits",
    data() {
        return {
            loading: false,
            searchColonnes: ['nom', 'prenom', 'rue', 'tel', 'date_naissance'],
            colonne: 'nom',
            search: '',
            inscrits: [],
            pagination: { current_page: 1, last_page: 1 },
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
        console.log('List Inscrits component mounted');
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
            this.$Progress.start();
            this.loading = false;
            axios.get('api/inscrits?page=' + this.pagination.current_page)
                .then(response => {
                    this.inscrits = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                    this.loading = true;
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération de la liste des personnes !');
                });
        },
        searchData() {
            this.$Progress.start();
            axios.get('api/search/inscrits/' + this.colonne + '/' + this.search + '?page=' + this.pagination.current_page)
                .then(response => {
                    this.inscrits = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération de la liste des personnes après la recherche !');
                });
        },
        destroy(inscrit) {
            Suppression.fire().then(result => {
                if (result.value) {
                    this.$Progress.start();
                    axios.delete('api/inscrits/' + inscrit.id)
                        .then(response => {
                            this.$Progress.finish();
                            Confirm.fire('Personne Supprimée!');
                            this.getData();
                            Fire.$emit('RefreshPage');
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            Snackbar.fire('Problème avec la suppression de la personne !');
                        });
                }
            });
        }
    },
}
</script>

<style scoped>
.v-table th {
    background-color: rgb(var(--v-theme-primary)) !important;
    color: white !important;
}
</style>
