<template>
    <v-container fluid>
        <h1 class="text-h4 mb-4">Liste des Salles</h1>

        <div v-if="loading">
            <v-row align="center" class="mb-4">
                <v-col cols="12" sm="6" md="4">
                    <v-btn color="success" @click="create">
                        <v-icon start>mdi-plus</v-icon>
                        Nouvelle salle
                    </v-btn>
                </v-col>
                <v-col cols="12" sm="6" md="4" class="ml-auto">
                    <v-text-field
                        v-model="search"
                        label="Recherche"
                        variant="outlined"
                        density="compact"
                        hide-details
                        clearable
                        prepend-inner-icon="mdi-magnify"
                    ></v-text-field>
                </v-col>
            </v-row>

            <v-card>
                <v-table hover>
                    <thead>
                        <tr>
                            <th class="text-left">N°</th>
                            <th class="text-left">NOM</th>
                            <th class="text-left">CRÉATION</th>
                            <th class="text-left">DERNIÈRE MODIFICATION</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="salle in salles" :key="salle.id">
                            <td>{{ salle.id }}</td>
                            <td>{{ salle.nom?.toUpperCase() }}</td>
                            <td>{{ formatDate(salle.created_at) }}</td>
                            <td>{{ formatDate(salle.updated_at) }}</td>
                            <td class="text-center">
                                <v-btn icon size="small" variant="text" color="success" @click="edit(salle)">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon size="small" variant="text" color="error" @click="destroy(salle)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                        <tr v-if="!salles.length">
                            <td colspan="5">
                                <v-alert type="warning" variant="tonal" class="ma-4" icon="mdi-database-search">
                                    {{ search ? 'Aucune donnée ne correspond à votre recherche.' : 'Aucune donnée correspondante.' }}
                                </v-alert>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-card>

            <v-pagination
                v-if="pagination.last_page > 1"
                v-model="pagination.current_page"
                :length="pagination.last_page"
                class="mt-4"
                @update:model-value="search === '' ? getData() : searchData()"
            ></v-pagination>
        </div>

        <!-- Loading -->
        <div v-else class="text-center py-16">
            <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
            <div class="mt-4">Chargement...</div>
        </div>

        <!-- Create & Edit Dialog -->
        <v-dialog v-model="dialog" max-width="600" persistent>
            <v-card>
                <v-card-title>
                    {{ editMode ? "Modification d'une" : "Création d'une nouvelle" }} Salle
                </v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="form.nom"
                        label="Nom de la salle"
                        variant="outlined"
                        prepend-inner-icon="mdi-tag"
                        :error="!validationMinSalle || !validationMaxSalle"
                        :error-messages="getSalleErrors()"
                    ></v-text-field>
                </v-card-text>
                <v-card-actions class="justify-space-between pa-4">
                    <v-btn color="error" @click="dialog = false">Fermer</v-btn>
                    <v-btn color="success" :disabled="form.busy" @click="editMode ? update() : store()">
                        {{ editMode ? "Éditer" : "Créer" }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import { Form } from "vform";
import moment from 'moment';

export default {
    name: "ListSalles",
    data() {
        return {
            loading: false,
            editMode: false,
            dialog: false,
            colonne: 'nom',
            search: '',
            salles: [],
            pagination: {
                current_page: 1,
            },
            error: '',
            form: new Form({
                id: '',
                nom: '',
            }),
        }
    },
    watch: {
        search(newSearch) {
            if (newSearch === '') {
                this.getData();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        console.log('List Salles component mounted');
        this.getData();
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        },
        validationMinSalle() {
            return this.form.nom.length > 2;
        },
        validationMaxSalle() {
            return this.form.nom.length < 200;
        },
    },
    methods: {
        formatDate(date) {
            if (!date) return '';
            return moment(date).format('DD/MM/YYYY');
        },
        getSalleErrors() {
            if (!this.validationMinSalle) return "Le nom de la salle doit être composé d'au moins 3 caractères.";
            if (!this.validationMaxSalle) return "Le nom de la salle doit être composé de moins de 200 caractères.";
            return '';
        },
        validation(test, message) {
            if (test) {
                Snackbar.fire(message);
                return true;
            }
            return false;
        },
        getData() {
            this.$Progress.start();
            this.loading = false;
            axios.get('api/salles?page=' + this.pagination.current_page)
                .then(response => {
                    this.salles = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                    this.loading = true;
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error);
                    Snackbar.fire({
                        title: 'Problème avec la récupération de la liste des salles !',
                        timer: undefined,
                    });
                });
        },
        searchData() {
            this.$Progress.start();
            axios.get('api/search/salles/' + this.colonne + '/' + this.search + '?page=' + this.pagination.current_page)
                .then(response => {
                    this.salles = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error);
                    Snackbar.fire('Problème avec la recherche de salle !');
                });
        },
        create() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            this.dialog = true;
        },
        store() {
            this.$Progress.start();
            if (this.validation(!this.validationMinSalle, "Le nom de la salle doit être composé d'au moins 3 caractères")) {
            } else if (this.validation(!this.validationMaxSalle, "Le nom de la salle doit être composé de moins de 200 caractères")) {
            } else {
                this.form.nom = this.form.nom.toLowerCase();
                this.form.busy = true;
                this.form
                    .post('api/salles/create')
                    .then(response => {
                        this.getData();
                        if (this.form.successful) {
                            this.$Progress.finish();
                            Toast.fire('Salle créée');
                            this.dialog = false;
                            Fire.$emit('RefreshPage');
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        if (error.response.status === 422) {
                            this.error = error.response.data.errors.nom[0];
                            Snackbar.fire('Salle déjà existante');
                        } else {
                            Snackbar.fire('Problème avec la création de la salle !');
                        }
                    });
            }
        },
        edit(salle) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(salle);
            this.dialog = true;
        },
        update() {
            this.$Progress.start();
            if (this.validation(!this.validationMinSalle, "Le nom de la salle doit être composé d'au moins 3 caractères")) {
            } else if (this.validation(!this.validationMaxSalle, "Le nom de la salle doit être composé de moins de 200 caractères")) {
            } else {
                this.form.nom = this.form.nom.toLowerCase();
                this.form.busy = true;
                this.form
                    .put('api/salles/edit/' + this.form.id)
                    .then(response => {
                        this.getData();
                        if (this.form.successful) {
                            this.$Progress.finish();
                            Toast.fire('Salle éditée');
                            this.dialog = false;
                            Fire.$emit('RefreshPage');
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        if (error.response.status === 422) {
                            this.error = error.response.data.errors.nom[0];
                            Snackbar.fire('Salle déjà existante !');
                        } else {
                            Snackbar.fire('Problème avec la modification de salle !');
                        }
                    });
            }
        },
        destroy(salle) {
            this.$Progress.start();
            Suppression.fire().then((result) => {
                if (result.value) {
                    axios.delete('api/salles/' + salle.id)
                        .then(response => {
                            this.getData();
                            this.$Progress.finish();
                            Confirm.fire('Salle supprimée!');
                            Fire.$emit('RefreshPage');
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.error(error.response);
                            Snackbar.fire('Problème avec la suppression de la salle !');
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
