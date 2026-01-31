<template>
    <v-container fluid>
        <v-row align="center" class="mb-4">
            <v-col cols="12" md="4">
                <h1 class="text-h4">Liste des Formations</h1>
            </v-col>
            <v-col cols="12" md="8">
                <v-row align="center" justify="end">
                    <v-col cols="auto" v-if="currentUser.role === 'admin' || currentUser.role === 'master'">
                        <v-btn color="success" :to="'/formations/create'" prepend-icon="mdi-plus">
                            Nouveau
                        </v-btn>
                    </v-col>
                    <v-col cols="12" md="3">
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
            </v-col>
        </v-row>

        <v-card>
            <v-table fixed-header>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th class="text-center">NOM</th>
                        <th style="width:95px;">SESSION</th>
                        <th>TUTEUR·RICE</th>
                        <th>DÉBUT</th>
                        <th>FIN</th>
                        <th style="width:115px;">SALLE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="formations.length" v-for="formation in formations" :key="formation.id">
                        <th scope="row">{{ formation.id }}</th>
                        <td>{{ formation.nom }}</td>
                        <td>{{ formation.session }}</td>
                        <td>{{ formation.tuteur_prenom }}</td>
                        <td>{{ formatDate(formation.date_debut) }}</td>
                        <td>{{ formatDate(formation.date_fin) }}</td>
                        <td>
                            <span v-if="formation.salle != null">{{ formation.salle }}</span>
                            <span v-else class="font-italic text-grey">Pas de salle</span>
                        </td>
                        <td>
                            <div class="d-flex align-center">
                                <v-btn
                                    icon
                                    variant="text"
                                    size="small"
                                    color="primary"
                                    :to="{ name: 'showFormation', params: { id: formation.id }}"
                                >
                                    <v-icon>mdi-eye</v-icon>
                                    <v-tooltip activator="parent" location="top">Voir</v-tooltip>
                                </v-btn>
                                <template v-if="currentUser.role === 'admin' || currentUser.role === 'master'">
                                    <v-btn
                                        icon
                                        variant="text"
                                        size="small"
                                        color="success"
                                        :to="{ name: 'editFormation', params: { id: formation.id }}"
                                    >
                                        <v-icon>mdi-pencil</v-icon>
                                        <v-tooltip activator="parent" location="top">Modifier</v-tooltip>
                                    </v-btn>
                                    <v-btn
                                        icon
                                        variant="text"
                                        size="small"
                                        color="error"
                                        @click="destroy(formation)"
                                    >
                                        <v-icon>mdi-delete</v-icon>
                                        <v-tooltip activator="parent" location="top">Supprimer</v-tooltip>
                                    </v-btn>
                                </template>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!formations.length && !loading">
                        <td colspan="8" class="text-center py-8">
                            <v-progress-circular
                                :size="70"
                                :width="7"
                                color="primary"
                                indeterminate
                            ></v-progress-circular>
                            <div class="mt-4 text-grey">Chargement...</div>
                        </td>
                    </tr>
                    <tr v-if="!formations.length && loading">
                        <td colspan="8">
                            <v-alert type="warning" variant="tonal" class="ma-4" icon="mdi-database-search">
                                {{ search ? 'Aucune donnée ne correspond à votre recherche.' : 'Aucune donnée correspondante.' }}
                            </v-alert>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </v-card>

        <div class="d-flex justify-center mt-4" v-if="pagination.last_page > 1">
            <v-pagination
                v-model="pagination.current_page"
                :length="pagination.last_page"
                :total-visible="5"
                @update:model-value="search === '' ? getData() : searchData()"
                rounded
            ></v-pagination>
        </div>
    </v-container>
</template>

<script>
import moment from 'moment';

export default {
    name: "ListFormations",
    data() {
        return {
            loading: false,
            searchColonnes: ['nom', 'salle'],
            colonne: 'nom',
            search: '',
            formations: [],
            pagination: {
                current_page: 1,
                last_page: 1,
            },
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
        console.log('List Formations component mounted');
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
            axios.get('api/formations?page=' + this.pagination.current_page)
                .then(response => {
                    this.formations = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                    this.loading = true;
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire({
                        title: 'Problème avec la récupération de la liste des formations !',
                        timer: undefined,
                    })
                })
        },
        searchData() {
            this.$Progress.start();
            axios.get('api/search/formations/' + this.colonne + '/' + this.search + '?page=' + this.pagination.current_page)
                .then(response => {
                    this.formations = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la recherche de formations !');
                })
        },
        destroy(formation) {
            this.$Progress.start();
            Suppression.fire().then((result) => {
                if (result.value) {
                    axios.delete('api/formations/' + formation.id)
                        .then(response => {
                            this.getData();
                            this.$Progress.finish();
                            Confirm.fire('Formation supprimée!');
                            Fire.$emit('RefreshPage');
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            Snackbar.fire('Problème avec la suppression de la formation !');
                        })
                }
            });
        }
    },
}
</script>

<style scoped>
.v-table {
    background: transparent;
}

.v-table th {
    background-color: rgb(var(--v-theme-primary)) !important;
    color: white !important;
    font-weight: bold !important;
}
</style>
