<template>
    <div class="ui container d-flex flex-column">
        <div v-if="loading === true">
            <div class="row row-cols-4 d-flex justify-content-center">
                <div class="col text-light">
                    <router-link :to="{ name: 'testPage' }" class="d-flex align-items-center">
                        <i class="fas fa-question-circle fa-lg mr-2"></i>
                        <span>Page test</span>
                    </router-link>
                </div>
                <div class="col mb-5">
                    <b-form-input type="text" placeholder="Rechercher un·e inscrit·e" v-model="query"></b-form-input>
                    <div v-if="results.length > 0 && query" class="list_inscrits elevation-4">
                        <ul>
                            <li v-for="result in results.slice(0,10)" :key="result.id">
                                <a :href="result.url">
                                    <div class="d-inline-flex">
                                        <i class="fas fa-user fa-lg"></i><div v-text="result.title" class="inscrit"></div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col">

                </div>
            </div>
            <div class="d-flex justify-content-center mb-5">
                <router-link to="/inscrits/create">
                    <div class="mr-5">
                        <b-img v-bind="images" center src="../images/BoutonAjoutPerso.png" alt="Center image"></b-img>
                        <span class="text-light text-uppercase">Faire une inscription</span>
                    </div>
                </router-link>
                <router-link to="/formations/create" v-show="currentUser.role === 'admin' || currentUser.role === 'master'">
                    <div class="ml-5">
                        <b-img v-bind="images" center src="../images/BoutonAjoutFormation.png" alt="Center image"></b-img>
                        <span class="text-light text-uppercase">Créer une formation</span>
                    </div>
                </router-link>
            </div>
            <v-tabs v-model="tabIndex" background-color="indigo accent-4"
                    class="elevation-2" dark centered grow icons-and-text>
                <v-tabs-slider class="slider"></v-tabs-slider>

                <v-tab :href="`#formation`">
                    Formations
                    <i class="fas fa-chalkboard-teacher fa-lg"></i>
                </v-tab>

                <v-tabs-slider class="slider"></v-tabs-slider>

                <v-tab :href="`#recrutement`">
                    Recrutements
                    <i class="fas fa-calendar-plus fa-lg"></i>
                </v-tab>

                <v-tab-item value="formation" class="tab-item">
                    <div class="p-2 container" >
                        <div class="row row-cols-12 default mt-n2 mr-2">
                            <div class="col" v-if="loadingFormationNotPmtic === true">
                                <div class="row row-cols-3"  v-if="formationsNotPmtic.length > 0">
                                    <div class="col" v-for="(formation, index) in formationsNotPmtic" :key="formation.id">
                                        <router-link  :to="{ name: 'showFormation', params: { id: formation.id }}"
                                                      class="link default">
                                            <div class="blocFormation elevation-5">
                                                <div class="titre text-center"><strong>{{ formation.abreviation }}</strong></div>
                                                <div>
                                                    <span class="small">Du : </span>{{ formation.date_debut | newDate }}<br>
                                                    <span class="small">Au : </span>{{ formation.date_fin | newDate }}
                                                </div>
                                                <div class="text-center"><strong>{{ stagiairesNotPmtic[index][0] }} sur {{ formation.max_stagiaires }}</strong></div>
                                                <div class="titre text-center" :class="stagiairesNotPmtic[index][1]"><strong>{{ stagiairesNotPmtic[index][1] }}</strong></div>
                                                <div class="small text-center">
                                                    <span v-if="formation.tuteur_genre === 'man.png'">Tuteur : </span>
                                                    <span v-show="formation.tuteur_genre === 'woman.png'">Tutrice : </span>
                                                    <span v-show="formation.tuteur_genre === 'user.png'">Tuteur·rice : </span>
                                                    <strong>{{ formation.tuteur_prenom }}</strong>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                                <div class="row row-cols-12 text-white d-flex justify-content-center mt-2" v-else>
                                    Aucune formation prévue
                                </div>
                            </div>
                            <div v-else class="col">
                                <v-row class="text-center text-white mt-10">
                                    <v-col class="d-flex flex-column justify-center align-center">
                                        <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                                        <span class="mt-5">Chargement...</span>
                                    </v-col>
                                </v-row>
                            </div>
                            <div class="col pmtic ml-2" v-if="loadingFormationPmtic === true">
                                <div class="row row-cols-3" v-if="formationsPmtic.length > 0">
                                    <div class="col" v-for="(formation, index) in formationsPmtic" :key="formation.id" >
                                        <router-link  :to="{ name: 'showFormation', params: { id: formation.id }}"
                                                      class="link default">
                                            <div class="blocFormation elevation-5">
                                                <div class="titre text-center"><strong>{{ formation.abreviation }}</strong></div>
                                                <div>
                                                    <span class="small">Du : </span>{{ formation.date_debut | newDate }}<br>
                                                    <span class="small">Au : </span>{{ formation.date_fin | newDate }}
                                                </div>
                                                <div class="text-center"><strong>{{ stagiairesPmtic[index][0] }} sur {{ formation.max_stagiaires }}</strong></div>
                                                <div class="titre text-center" :class="stagiairesPmtic[index][1]"><strong>{{ stagiairesPmtic[index][1] }}</strong></div>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                                <div class="row row-cols-12 text-white d-flex justify-content-center mt-2" v-else>
                                    Aucune formation PMTIC prévue
                                </div>
                            </div>
                            <div v-else class="col pmtic ml-2">
                                <v-row class="text-center text-white mt-10">
                                    <v-col class="d-flex flex-column justify-center align-center">
                                        <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                                        <span class="mt-5">Chargement...</span>
                                    </v-col>
                                </v-row>
                            </div>
                        </div>
                    </div>

                </v-tab-item>
                <v-tab-item value="recrutement" class="tab-item">
                    <div class="p-3 pt-5 container" v-if="loadingDatas === true">
                        <div class="row row-cols-12 ml-4 default" v-if="recrutements.length > 0">
                            <div class="col default" v-for="(recrutement, index) in recrutements" :key="recrutement.id">
                                <router-link :to="{ name: 'showRecrutement', params: { id: recrutement.id }}"
                                             class="link default">
                                    <div class="blocRecrutement mb-5 elevation-2">
                                        <div class="titre text-center"><strong>{{ recrutement.abreviation }}</strong></div>
                                        <div>
                                            <span class="small">Date : </span><strong>{{ recrutement.date | newDate }}</strong>
                                        </div>
                                        <div class="text-center titre candidats"><strong>{{ candidats[index] }} candidat<span v-if="candidats[index] > 0">(s)</span></strong></div>
                                        <div class="small text-center">
                                            <span v-if="recrutement.tuteur_genre === 'man.png'">Tuteur : </span>
                                            <span v-show="recrutement.tuteur_genre === 'woman.png'">Tutrice : </span>
                                            <span v-show="recrutement.tuteur_genre === 'user.png'">Tuteur·rice : </span>
                                            <strong>{{ recrutement.tuteur_prenom }}</strong>
                                        </div>
                                    </div>
                                </router-link>
                            </div>
                        </div>
                        <div class="row row-cols-12 text-white d-flex justify-content-center mt-2" v-else>
                            Aucun recrutement prévu
                        </div>
                    </div>
                    <div v-else class="p-2 container">
                        <v-row class="text-center text-white mt-10">
                            <v-col class="d-flex flex-column justify-center align-center">
                                <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                                <span class="mt-5">Chargement...</span>
                            </v-col>
                        </v-row>
                    </div>
                </v-tab-item>
            </v-tabs>
        </div>
        <div v-else>
            <v-row class="text-center text-white mt-10">
                <v-col class="d-flex flex-column justify-center align-center">
                    <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                    <span class="mt-5">Chargement...</span>
                </v-col>
            </v-row>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
                loadingDatas: false,
                loadingFormationNotPmtic: false,
                loadingFormationPmtic: false,
                tabIndex: 0,
                images: { blank: false, width: 80, height: 55, class: 'mb-1 ombre' },
                tabs: 2,
                query: null,
                results: [],
                formationsNotPmtic: [],
                stagiairesNotPmtic: [],
                formationsPmtic: [],
                stagiairesPmtic: [],
                recrutements: [],
                candidats: [],
            }
        },
        watch: {
           query(after, before) {
               this.searchInscrits();
           }
        },
        mounted() {
            console.log('Home component mounted');
            this.loading = false;
            this.loadingDatas = false;
            this.getFormationsPmtic();
            this.getFormationsNotPmtic();
            this.getRecrutements();
            this.loading = true;
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            }
        },
        methods: {
            getFormationsPmtic() {
                this.loadingFormationPmtic = false;
                axios.get('api/formations/all-pmtic')
                    .then(response => {
                        this.formationsPmtic = response.data.formations;
                        this.stagiairesPmtic = response.data.stagiaires;
                        this.loadingFormationPmtic = true;
                    })
                    .catch(error => {
                        console.log(error);
                        this.error = 'Oups, il y a un hic !';
                        this.snackbar = true;
                    })
            },
            getFormationsNotPmtic() {
                this.loadingFormationNotPmtic = false;
                axios.get('api/formations/all-not-pmtic')
                    .then(response => {
                        this.formationsNotPmtic = response.data.formations;
                        this.stagiairesNotPmtic = response.data.stagiaires;
                        this.loadingFormationNotPmtic = true;
                    })
                    .catch(error => {
                        console.log(error);
                        this.error = 'Oups, il y a un hic !';
                        this.snackbar = true;
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
                        this.error = 'Oups, il y a un hic !';
                        this.snackbar = true;
                    })
            },
            searchInscrits() {
                axios.get('api/inscrits/search', { params: { query: this.query } })
                .then(response => this.results = response.data)
                .catch(error => { console.log(error)});
            },
        },
    }
</script>

<style scoped>
    .list_inscrits {
        z-index: 100;
        background-color: #fff;
        color: #193340 !important;
        padding: 10px 0;
        border-radius: 5px;
    }

    .list_inscrits > ul > li > a {
        color: #193340 !important;
        padding: 3px 87px 7px 20px;
    }

    .list_inscrits > ul {
        margin: 0;
        padding: 0;
    }

    li {
        list-style-type: none;
        margin: 3px 0 2px 0;
        padding-top: 2px;
    }

    li:hover {
        background-color: #389fad !important;
    }

    .fa-lg {
        vertical-align: -0.2em !important;
    }

    .inscrit {
        margin-left: 5px;
        padding-bottom: 2px;
        font-size: large;
    }

    .ombre:hover {
        -webkit-filter: drop-shadow(5px 5px 5px #193340);
        filter: drop-shadow(5px 5px 5px #193340);
    }

    .tab-item {
        background-color: #307e8c !important;
    }

    .slider {
        background-color: #389fad !important;
    }

    .default {
        margin: 0 !important;
        padding: 0 !important;
    }

    .link {
        text-decoration: none;
        color: #0a0a17 !important;
    }

    .blocFormation, .blocRecrutement {
        padding: 0.3em;
        border-radius: 15px;
        background-color: #fff;
    }

    .blocFormation {
        height: 150px;
        width: 150px;
    }

    .blocRecrutement {
        height: 103px;
        width: 150px;
    }

    .blocFormation:hover, .blocRecrutement:hover{
        background-color: #389fad !important;
        color: #fff !important;
    }

    span {
        line-height: 1.0em;
    }

    .titre {
        font-size: large;
        font-weight: bold;
    }

    .small {
        font-size: smaller;
    }

    .Insuffisant {
        color: #e3342f;
    }

    .Incomplet {
        color: #f6993f;
    }

    .Complet {
        color: #38c172;
    }

    .candidats {
        color: #44d27f;
    }

    .pmtic {
        border-left: 2px solid #3f96a5;
    }
</style>
