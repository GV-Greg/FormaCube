<template>
    <v-container fluid>
        <h1 class="text-h4 mb-4">Liste des Prospects</h1>

        <div v-if="loadingPage">
            <v-row align="center" class="mb-4">
                <v-col cols="12" sm="6" md="2">
                    <v-btn color="success" :to="{ name: 'createInscritWithProspect' }">
                        <v-icon start>mdi-plus</v-icon>
                        Nouveau prospect
                    </v-btn>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <v-select
                        v-model="search"
                        :items="listTags"
                        item-title="tag"
                        item-value="tag"
                        label="Filtrer par tag(s)..."
                        variant="outlined"
                        density="compact"
                        multiple
                        chips
                        closable-chips
                        hide-details
                        clearable
                        prepend-inner-icon="mdi-tag-multiple"
                    ></v-select>
                </v-col>
                <v-col cols="auto">
                    <v-btn color="primary" @click="searchData()">
                        <v-icon start>mdi-magnify</v-icon>
                        Rechercher
                    </v-btn>
                </v-col>
                <v-col cols="auto" v-if="recherche && prospects">
                    <v-btn
                        color="secondary"
                        :href="'mailto:?bcc=' + emails"
                    >
                        <v-icon start>mdi-email-multiple</v-icon>
                        Mailto ({{ prospects.length }})
                    </v-btn>
                </v-col>
            </v-row>

            <v-card>
                <v-table hover>
                    <thead>
                        <tr>
                            <th class="text-left">N°</th>
                            <th class="text-left">NOM</th>
                            <th class="text-left">PRÉNOM</th>
                            <th v-if="recherche" class="text-left">EMAIL</th>
                            <th v-if="recherche" class="text-left">TÉL.</th>
                            <th v-if="recherche" class="text-left">GSM</th>
                            <th class="text-center">CENTRES D'INTÉRÊT</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="prospects && (loading || newLoading)" v-for="(prospect, index) in prospects" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>
                                <span v-if="prospect.nom">{{ prospect.nom.toUpperCase() }}</span>
                                <span v-else class="text-grey font-italic">Non renseigné</span>
                            </td>
                            <td>
                                <span v-if="prospect.prenom">{{ prospect.prenom }}</span>
                                <span v-else class="text-grey font-italic">Non renseigné</span>
                            </td>
                            <td v-if="recherche">
                                <span v-if="prospect.email">{{ prospect.email }}</span>
                                <span v-else class="text-grey font-italic">Non renseigné</span>
                            </td>
                            <td v-if="recherche">
                                <span v-if="prospect.tel">{{ prospect.tel }}</span>
                                <span v-else class="text-grey font-italic">Non renseigné</span>
                            </td>
                            <td v-if="recherche">
                                <span v-if="prospect.gsm">{{ prospect.gsm }}</span>
                                <span v-else class="text-grey font-italic">Non renseigné</span>
                            </td>
                            <td class="text-center">
                                <v-chip
                                    v-for="tag in prospect.tags"
                                    :key="tag.id"
                                    size="small"
                                    color="success"
                                    class="ma-1"
                                >
                                    <v-icon start size="small">mdi-tag</v-icon>
                                    {{ tag.tag }}
                                </v-chip>
                            </td>
                            <td class="text-center">
                                <v-btn icon size="small" variant="text" color="primary" :to="{ name: 'showInscrit', params: { id: prospect.id }}">
                                    <v-icon>mdi-eye</v-icon>
                                </v-btn>
                                <v-btn icon size="small" variant="text" color="success" :to="{ name: 'editInscrit', params: { id: prospect.id }}">
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon size="small" variant="text" color="error" @click="destroy(prospect.id)">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </td>
                        </tr>

                        <!-- Loading state -->
                        <tr v-if="(prospects && loadingPage && !loading) || (prospects && loadingPage && !newLoading)">
                            <td colspan="8" class="pt-4">
                                <div class="text-center py-8">
                                    <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
                                    <div class="mt-4">Chargement...</div>
                                </div>
                            </td>
                        </tr>

                        <!-- No results -->
                        <tr v-if="(!prospects || !prospects.length) && newLoading">
                            <td colspan="8">
                                <v-alert type="warning" variant="tonal" class="ma-4" icon="mdi-database-search">
                                    {{ recherche ? 'Aucune donnée ne correspond à votre recherche.' : 'Aucune donnée correspondante.' }}
                                </v-alert>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-card>

            <v-pagination
                v-if="pagination.last_page > 1 && prospects && !recherche"
                v-model="pagination.current_page"
                :length="pagination.last_page"
                class="mt-4"
                @update:model-value="getData()"
            ></v-pagination>
        </div>

        <!-- Page Loading -->
        <div v-else class="text-center py-16">
            <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
            <div class="mt-4">Chargement...</div>
        </div>
    </v-container>
</template>

<script>
import MailtoUI from 'mailtoui/dist/mailtoui-min.js';

export default {
    name: "ListProspects",
    data() {
        return {
            loadingPage: false,
            loading: false,
            newLoading: false,
            recherche: false,
            search: [],
            prospects: [],
            listTags: [],
            choiceTags: [],
            tags: [],
            pagination: {
                current_page: 1,
                first_page_url: "",
                from: null,
                last_page: null,
                last_page_url: "",
                links: [],
                next_page_url: "",
                path: "",
                per_page: null,
                prev_page_url: null,
                to: null,
                total: null,
            },
            error: '',
            emails: '',
            listEmails: [],
            json_meta: [
                [
                    {
                        key: "charset",
                        value: "utf-8",
                    },
                ],
            ],
            json_fields: {
                Prenom: "prenom",
                Nom: "nom",
                Telephone: "tel",
                GSM: "gsm",
                Email: "email",
            },
            json_data: [],
        }
    },
    mounted() {
        console.log('List Prospects component mounted');
        this.loadingPage = false;
        this.getData();
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        },
        mailtoHref() {
            return 'mailto:?bcc=' + this.emails;
        }
    },
    methods: {
        getData() {
            this.$Progress.start();
            axios.get('api/inscrits/prospects?page=' + this.pagination.current_page)
                .then(response => {
                    this.prospects = response.data.prospects.data;
                    this.pagination = response.data.prospects;
                    this.getTags();
                    this.$Progress.finish();
                    this.loadingPage = true;
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération des prospects !');
                });
        },
        getTags() {
            axios.get('api/tags/all')
                .then(response => {
                    this.listTags = response.data.tags;
                    this.loading = true;
                    this.newLoading = true;
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération des tags !');
                });
        },
        searchData() {
            if (this.search.length === 0) {
                Snackbar.fire("Vous n'avez pas sélectionné de tags !");
                this.getData();
            } else {
                this.$Progress.start();
                this.loadingPage = true;
                this.loading = true;
                this.newLoading = false;
                this.prospects = [];
                axios.get('api/search/inscrits/prospects/tags/' + this.search)
                    .then(response => {
                        if (response.data.prospects != null) {
                            this.prospects = response.data.prospects;
                            this.json_data = this.prospects;
                            this.exportEmails();
                        } else {
                            this.prospects = response.data.prospects;
                            this.pagination = [];
                        }
                        this.newLoading = true;
                        this.recherche = true;
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la récupération des prospects avec les tags sélectionnés !');
                    });
            }
        },
        exportEmails() {
            this.emails = '';
            for (let i = 0; i < this.prospects.length; i++) {
                if (this.emails === '' && this.prospects[i].email != null) {
                    this.emails = this.prospects[i].email;
                } else {
                    if (this.prospects[i].email != null) {
                        this.emails = this.emails + ',' + this.prospects[i].email;
                    }
                }
            }
        },
        destroy(prospect) {
            this.$Progress.start();
            Suppression.fire().then((result) => {
                if (result.value) {
                    axios.delete('api/inscrits/' + prospect)
                        .then(response => {
                            this.getData();
                            this.$Progress.finish();
                            Confirm.fire('Prospect supprimé!');
                            Fire.$emit('RefreshPage');
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            Snackbar.fire('Problème avec la suppression du prospect !');
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
