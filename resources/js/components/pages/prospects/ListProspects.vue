<template>
    <div>
        <h1>Liste des Prospects</h1>
        <v-row v-if="loadingPage === true" class="ml-1">
            <v-row class="justify-center mt-2">
                <v-col cols="2">
                    <div class="btn-wrapper">
                        <router-link :to="{ name: 'createInscritWithProspect'}" class="btn btn-success px-3">
                            <i class="fas fa-plus"></i>
                            <span class="ml-1">Nouveau</span>
                        </router-link>
                    </div>
                </v-col>
                <v-col cols="2" class="text-right text-light">
                    <span>S&eacute;lection :</span>
                </v-col>
                <v-col cols="3">
                    <b-form-tags input-id="tags" v-model="search" size="lg" add-on-change no-outer-focus>
                        <template v-slot="{ tags, inputAttrs, inputHandlers, disabled, removeTag }">
                            <b-form-select v-bind="inputAttrs" v-on="inputHandlers">
                                <template v-slot:first>
                                    <option disabled value="">Choisir un ou des tag(s)...</option>
                                    <option v-for="tag in listTags" :key="tag.id" :value="tag.tag">{{ tag.tag }}</option>
                                </template>
                            </b-form-select>
                            <ul v-if="tags.length > 0" class="list-inline d-inline-block mt-2">
                                <li v-for="tag in tags" :key="tag" class="list-inline-item">
                                    <b-form-tag
                                        @remove="removeTag(tag)"
                                        :title="tag"
                                        :disabled="disabled"
                                        variant="success"
                                    >{{ tag }}</b-form-tag>
                                </li>
                            </ul>
                        </template>
                    </b-form-tags>
                </v-col>
                <v-col cols="2">
                    <v-btn @click="searchData()">Rechercher</v-btn>
                </v-col>
                <v-col cols="3">
                    <a class="mailtoui btn interface text-light" :href="'mailto:?bcc=' + emails" :class="recherche === false || prospects === null ? 'disabled' : ''">MAILTO</a>
                    <v-btn color="interface text-light" :disabled="recherche === false || prospects === null">
                        <download-excel :data="json_data" :fields="json_fields" worksheet="Prospects"
                                        name="prospects.xls">Excel</download-excel>
                    </v-btn>
                </v-col>
            </v-row>
            <v-row class="justify-center mt-1">
                <v-col cols="12">
                    <v-simple-table fixed-header>
                        <thead class>
                        <tr>
                            <th>N°</th>
                            <th class="text-left">NOM</th>
                            <th class="text-left">PRÉNOM</th>
                            <th class="text-left" v-show="recherche === true">EMAIL</th>
                            <th class="text-left" v-show="recherche === true">T&Egrave;L.</th>
                            <th class="text-left" v-show="recherche === true">GSM</th>
                            <th class="text-center">CENTRES D'INT&Eacute;R&Ecirc;TS</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-show="prospects != null && (loading === true || newLoading === true)" v-for="(prospect, index) in prospects" :key="index">
                            <th scope="row">
                                {{ index + 1 }}
                            </th>
                            <td>
                                <span v-if="prospect.nom != null">{{ prospect.nom | upperCase }}</span><span v-else class="no-information">Non renseigné</span>
                            </td>
                            <td>
                                <span v-if="prospect.prenom != null">{{ prospect.prenom }}</span><span v-else class="no-information">Non renseigné</span>
                            </td>
                            <td v-show="recherche === true">
                                <span v-if="prospect.email != null">{{ prospect.email }}</span><span v-else class="no-information">Non renseigné</span>
                            </td>
                            <td v-show="recherche === true">
                                <span v-if="prospect.tel != null">{{ prospect.tel }}</span><span v-else class="no-information">Non renseigné</span>
                            </td>
                            <td v-show="recherche === true">
                                <span v-if="prospect.gsm != null">{{ prospect.gsm }}</span><span v-else class="no-information">Non renseigné</span>
                            </td>
                            <td>
                                <v-chip small v-for="tag in prospect.tags" :key="tag.id"
                                        class="ma-2" color="green" text-color="white">
                                    <v-icon left class="myIconTable">mdi-tag</v-icon>
                                    {{ tag.tag }}
                                </v-chip>
                            </td>
                            <td>
                                <div class="d-flex flex-row justify-content-center">
                                    <router-link :to="{ name: 'showInscrit', params: { id: prospect.id }}">
                                        <i class="fas fa-eye fa-lg text-blue mr-1"></i>
                                    </router-link>
                                    <router-link :to="{ name: 'editInscrit', params: { id: prospect.id }}">
                                        <i class="fas fa-edit fa-lg text-green mx-1"></i>
                                    </router-link>
                                    |
                                    <button type="button" class="ml-1" @click="destroy(prospect.id)">
                                        <i class="fas fa-trash fa-lg text-red"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-show="(prospects != null && loadingPage === true && loading === false) || (prospects != null && loadingPage === true && newLoading === false)">
                            <td colspan="12" class="pt-4">
                                <v-row class="text-center text-interface mt-10">
                                    <v-col class="d-flex flex-column justify-center align-center">
                                        <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                                        <span class="mt-5">Chargement...</span>
                                    </v-col>
                                </v-row>
                            </td>
                        </tr>
                        <tr v-show="prospects === null && newLoading === true" class="justify-content-center">
                            <td colspan="12" class="pt-4">
                                <div class="alert alert-danger" role="alert">
                                    Oups ! Rien ne correspond à votre recherche.
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </v-simple-table>
                    <PaginationComponent class="mt-3"
                                         v-if="pagination.last_page > 1 && prospects != null && this.recherche === false"
                                         :pagination="pagination"
                                         :offset="5"
                                         @paginate="getData()"
                    ></PaginationComponent>
                </v-col>
            </v-row>
        </v-row>
        <v-row v-else class="text-center text-light mt-10">
            <v-col class="d-flex flex-column justify-center align-center">
                <v-progress-circular :size="70" :width="10" color="white" indeterminate></v-progress-circular>
                <span class="mt-5">Chargement...</span>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    import PaginationComponent from "../../elements/PaginationComponent";
    import MailtoUI from 'mailtoui/dist/mailtoui-min.js';

    export default {
        name: "ListProspects",
        components: {
            PaginationComponent,
        },
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
            // MailtoUI.run({
            //     title:"Envoyer l'email avec",
            //     buttonText1:"Gmail via navigateur",
            //     buttonText2:"Outlook via navigateur",
            //     buttonText3:"Yahoo via navigateur",
            //     buttonText4:"Via votre programme de messagerie par défaut",
            //     buttonTextCopy:"Copier",
            //     buttonTextCopyAction:"Copié"
            // });
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            mailtoHref: function() {
                return 'mailto:?bcc=' + this.emails;
            }
        },
        methods: {
            getData(){
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
                    })
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
                    })
            },
            searchData() {
                if(this.search.length === 0) {
                    Snackbar.fire('Vous n\'avez pas sélectionné de tags !');
                    this.getData();
                } else {
                    this.$Progress.start();
                    this.loadingPage = true;
                    this.loading = true;
                    this.newLoading = false;
                    this.prospects = [];
                    axios.get('api/search/inscrits/prospects/tags/' + this.search)
                        .then(response => {
                            if(response.data.prospects != null) {
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

                        })
                }
            },
            exportEmails() {
                this.emails = '';
                for(let i=0; i < this.prospects.length; i++) {
                    if(this.emails === '' && this.prospects[i].email != null) {
                        this.emails = this.prospects[i].email;
                    } else {
                        if(this.prospects[i].email != null) {
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
                                Fire.$emit('RefreshPage'); // Rafraichit la page
                            })
                            .catch(error => {
                                this.$Progress.fail();
                                console.log(error.response);
                                Snackbar.fire('Problème avec la suppression du prospect !');
                            })
                    }
                });
            }
        },
    }
</script>

<style scoped>
    .myIconTable {
        font-size: 15px !important;
    }

    .v-application--is-ltr .v-chip .v-avatar--left, .v-application--is-ltr .v-chip .v-icon--left {
        margin-left: -2px !important;
        margin-right: 5px !important;
    }

    .v-application ul {
        padding-left: 0 !important;
        margin-bottom: 0 !important;
    }

    .interface {
        background-color: #32909e !important;
    }
</style>
