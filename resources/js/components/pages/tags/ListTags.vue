<template>
    <v-container fluid>
        <h1 class="text-h4 mb-4">Liste des Tags</h1>

        <div v-if="loading">
            <v-row align="center" class="mb-4">
                <v-col cols="12" sm="6" md="4">
                    <v-btn color="success" @click="create">
                        <v-icon start>mdi-plus</v-icon>
                        Nouveau tag
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
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="tag in tags" :key="tag.id">
                            <td>{{ tag.id }}</td>
                            <td>{{ tag.tag?.toLowerCase() }}</td>
                            <td class="text-center">
                                <v-btn icon size="small" variant="text" color="success" @click="edit(tag)">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon size="small" variant="text" color="error" @click="destroy(tag)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                        <tr v-if="!tags.length">
                            <td colspan="3">
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
                    {{ editMode ? "Modification d'un" : "Création d'un nouveau" }} Tag
                </v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="form.tag"
                        label="Nom du tag"
                        variant="outlined"
                        prepend-inner-icon="mdi-tag"
                        :error="!validationMinTag || !validationMaxTag"
                        :error-messages="getTagErrors()"
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
import { Form } from 'vform';

export default {
    name: "ListTags",
    data() {
        return {
            loading: false,
            editMode: false,
            dialog: false,
            colonne: 'tag',
            search: '',
            tags: [],
            form: new Form({
                id: '',
                tag: '',
            }),
            pagination: {
                current_page: 1,
            },
            error: '',
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
        console.log('Tags component mounted');
        this.getData();
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        },
        validationMinTag() {
            return this.form.tag.length > 2;
        },
        validationMaxTag() {
            return this.form.tag.length < 100;
        },
    },
    methods: {
        getTagErrors() {
            if (!this.validationMinTag) return "Le tag doit être composé d'au moins 3 caractères.";
            if (!this.validationMaxTag) return "Le tag doit être composé de moins de 100 caractères.";
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
            axios.get('api/tags?page=' + this.pagination.current_page)
                .then(response => {
                    this.tags = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                    this.loading = true;
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire({
                        title: 'Problème avec la récupération de la liste des tags !',
                        timer: undefined,
                    });
                });
        },
        searchData() {
            this.$Progress.start();
            axios.get('api/search/tags/' + this.colonne + '/' + this.search + '?page=' + this.pagination.current_page)
                .then(response => {
                    this.tags = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la recherche de tag !');
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
            if (this.validation(!this.validationMinTag, "Le tag doit être composé d'au moins 3 caractères")) {
            } else if (this.validation(!this.validationMaxTag, "Le tag doit être composé de moins de 100 caractères")) {
            } else {
                this.form.tag = this.form.tag.toLowerCase();
                this.form.busy = true;
                this.form
                    .post('api/tags/create')
                    .then(response => {
                        this.getData();
                        if (this.form.successful) {
                            this.$Progress.finish();
                            Toast.fire('Tag créé');
                            this.dialog = false;
                            Fire.$emit('RefreshPage');
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        if (error.response.status === 422) {
                            this.error = error.response.data.errors.tag[0];
                            Snackbar.fire('Tag déjà existant');
                        } else {
                            Snackbar.fire('Problème avec la création du tag !');
                        }
                    });
            }
        },
        edit(tag) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(tag);
            this.dialog = true;
        },
        update() {
            if (this.validation(!this.validationMinTag, "Le tag doit être composé d'au moins 3 caractères")) {
            } else if (this.validation(!this.validationMaxTag, "Le tag doit être composé de moins de 100 caractères")) {
            } else {
                this.$Progress.start();
                this.form.tag = this.form.tag.toLowerCase();
                this.form.busy = true;
                this.form
                    .put('api/tags/edit/' + this.form.id)
                    .then(response => {
                        this.getData();
                        if (this.form.successful) {
                            this.$Progress.finish();
                            Toast.fire('Tag édité');
                            this.dialog = false;
                            Fire.$emit('RefreshPage');
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        if (error.response.status === 422) {
                            this.error = error.response.data.errors.tag[0];
                            Snackbar.fire('Tag déjà existant');
                        } else {
                            Snackbar.fire('Problème avec la modification du tag !');
                        }
                    });
            }
        },
        destroy(tag) {
            this.$Progress.start();
            Suppression.fire().then((result) => {
                if (result.value) {
                    axios.delete('api/tags/' + tag.id)
                        .then(response => {
                            this.getData();
                            this.$Progress.finish();
                            Confirm.fire('Tag supprimé!');
                            Fire.$emit('RefreshPage');
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            Snackbar.fire('Problème avec la suppression du tag !');
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
