<template>
    <div class="container">
        <h1 class="d-flex align-content-center">
            <button class="btn btn-light pb-2 mr-2" @click="goBack">
                <i class="fas fa-reply fa-lg text-primary-dark"></i>
            </button>
            Fiche d'inscrit<span v-show="inscrit.genre === 'femme'">e</span>
        </h1>
        <div class="row mt-2" v-if="loading === true">
            <v-card class="mx-auto w-100" shaped>
                    <v-card-text class="p-3 ml-2">
                        <v-row class="mt-n2">
                            <div class="col-11 d-flex align-items-start">
                                <v-avatar class="mt-4 ml-3 mr-1">
                                    <img v-show="inscrit.genre === 'homme'" :src="'storage/images/avatars/man.png'" class="img-circle elevation-2" alt="avatar">
                                    <img v-show="inscrit.genre === 'femme'" :src="'storage/images/avatars/woman.png'" class="img-circle elevation-2" alt="avatar">
                                    <img v-show="inscrit.genre === '3e genre' || inscrit.genre === null" :src="'storage/images/avatars/user.png'" class="img-circle elevation-2" alt="avatar">
                                </v-avatar>
                                <div class="col-lg-10">
                                    <span class="display-1 text--primary font-weight-bold">
                                        {{ inscrit.prenom }} {{ inscrit.nom | upperCase }}<br>
                                    </span>
                                    <p><i>Fiche créée depuis le {{ inscrit.created_at | newDate }}.</i></p>
                                </div>
                            </div>
                            <v-speed-dial class="ml-3 mt-2 mb-6"
                                v-model="fab"
                                :top="top"
                                :bottom="bottom"
                                :right="right"
                                :left="left"
                                :direction="direction"
                                :open-on-hover="hover"
                                :transition="transition"
                            >
                                <template v-slot:activator>
                                    <v-btn v-model="fab" color="darken-2" class="bg-interface" dark fab>
                                        <v-icon v-if="fab">
                                            mdi-close
                                        </v-icon>
                                        <v-icon v-else>
                                            mdi-settings
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <router-link :to="{ name: 'editInscrit', params: { id: inscrit.id }}" class="mt-n12">
                                    <v-btn fab dark small color="green">
                                        <v-icon>mdi-pencil</v-icon>
                                    </v-btn>
                                </router-link>
                                <v-btn fab dark small color="red" @click.prevent="destroy()">
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </v-speed-dial>
                        </v-row>
                        <v-row class="mb-n7 mt-n7">
                            <v-col cols="1" class="pb-0">

                            </v-col>
                            <v-col class="mb-0 pb-0">
                                <strong>DATE DE NAISSANCE : </strong><span v-if="inscrit.date_naissance !== null"> {{ inscrit.date_naissance | newDate }}</span><span v-else class="no-information">Non renseigné</span><br>
                                <strong>ADRESSE : </strong><span v-if="inscrit.rue !== null"> {{ inscrit.rue }}, {{ inscrit.numero }} <span v-if="inscrit.boite !== null">bte</span> {{ inscrit.boite }} - </span>
                                <span v-if="inscrit.cp !== 0">{{ inscrit.cp }} {{ inscrit.ville }}</span> <span v-if="inscrit.cp !== 0"></span><span v-else class="no-information">Non renseigné</span><br>
                                <strong>EMAIL : </strong><span v-if="inscrit.email !== null"><a class="mailtoui link-interface" :href="'mailto:' + inscrit.email">{{ inscrit.email }}</a></span><span v-else class="no-information">Non renseigné</span><br>
                                <strong>T&Eacute;L. : </strong><span v-if="inscrit.tel !== null">{{ inscrit.tel }}</span><span v-else class="no-information">Non renseigné</span><br>
                                <strong>GSM. : </strong><span v-if="inscrit.gsm !== null">{{ inscrit.gsm }}</span><span v-else class="no-information">Non renseigné</span><br>
                                <strong>NEWSLETTER : </strong><span v-if="inscrit.newsletter === 1">oui</span><span v-else>non</span><br>
                                <strong>COMMENTAIRE : </strong><br>
                                    <p v-if="inscrit.commentaire_inscrit !== null" class="ml-2 mb-0">{{ inscrit.commentaire_inscrit }}</p><p v-else class="ml-2 mb-0 no-information">Aucun</p><br>
                            </v-col>
                            <v-col cols="1" class="pb-0">
                                <span id="vertical-line"></span>
                            </v-col>
                            <v-col class="pb-0">
                                <h6>Centre(s) d'int&eacute;r&ecirc;t :</h6>
                                <div v-if="tags.length === 0" class="ml-2 mt-n2 no-information">Aucun</div>
                                <div v-else class="mt-n2 ml-n4">
                                    <v-chip v-for="item in tags" :key="item.id" class="ma-1" color="success" text-color="white"
                                            close @click:close="deleteTag(item.id)">
                                        <v-icon left class="myIconTable">mdi-tag</v-icon>
                                        <div class="pr-1">
                                            {{ item.tag }}
                                        </div>
                                    </v-chip>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <hr>
                            </v-col>
                        </v-row>
                        <v-row class="ml-2 mt-n3 mb-n2">
                            <v-col>
                                <strong>FORMATION(S) :</strong>
                                <ul>
                                    <li v-if="formations.length === 0" class="no-information">Aucune</li>
                                    <li v-else v-for="formation in formations" :key="formation.id">
                                        <span class="font-weight-bold">{{ formation.nom }}</span> <span v-show="formation.session !== null">- {{ formation.session }}</span> du {{ formation.date_debut | newDate}} au {{ formation.date_fin | newDate}}
                                    </li>
                                </ul>
                            </v-col>
                            <v-col>
                                <strong>RECRUTEMENT(S) :</strong>
                                <ul>
                                    <li v-if="recrutements.length === 0" class="no-information">Aucun</li>
                                    <li v-else v-for="(item, index) in recrutements" :key="index">
                                        <span class="font-weight-bold">Recrutement du {{ item.date | newDate }}</span> pour la formation <span class="font-weight-bold">{{ item.formation_nom }}</span>
                                    </li>
                                </ul>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            <v-tabs class="w-100 elevation-2 mt-2 mb-5" dark>
                <v-tabs-slider></v-tabs-slider>
                <v-tab href="#historique">Historique</v-tab>
                <v-tab href="#ajoutStagiaire">Inscrire à une formation</v-tab>
                <v-tab href="#ajoutCandidat">Inscrire à un recrutement</v-tab>
                <v-tab href="#tags">Tags</v-tab>
                <v-tab-item value="historique">
                    <v-card flat tile>
                        <v-card-text>
                            <v-row class="ml-3 mt-n3" v-if="loadingData === true">
                                <v-col cols="12">
                                    <ul>
                                        <li v-for="log in logs" :key="log.id">
                                            <span v-if="log.creation === 1"><strong>{{ log.date | newDate }} : {{ log.informations }} par {{ log.user }}</strong></span>
                                            <span v-else>{{ log.date | newDate }} : {{ log.informations }} par {{ log.user }}</span>
                                        </li>
                                    </ul>
                                </v-col>
                            </v-row>
                            <v-row v-else class="text-center text-interface">
                                <v-col class="d-flex flex-column justify-center align-center">
                                    <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                                    <span class="mt-5">Chargement...</span>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item value="ajoutStagiaire">
                    <v-card flat tile>
                        <v-card-text class="mb-n3">
                            <v-row class="mt-n3 mb-n5" v-if="listFormations != null">
                                <v-col cols="10">
                                    <b-form-group>
                                        <b-form-select v-model="choix_formation" id="choix_formation" :state="validationFormation">
                                            <b-form-select-option :value="null">Choisissez une formation...</b-form-select-option>
                                            <b-form-select-option v-for="(formation, index) in listFormations" :key="formation.id" :value="formation.id" :disabled="nbreStagiaires[index][0] === nbreStagiaires[index][1]">
                                                {{ formation.nom }}
                                                <span v-show="formation.session !== null"> - {{ formation.session }} </span>
                                                <span> débutant le {{ formation.date_debut | newDate }} - {{ nbreStagiaires[index][0] }} stagiaires</span>
                                                <span v-if="nbreStagiaires[index][0] === nbreStagiaires[index][1]"> - complet</span>
                                            </b-form-select-option>
                                        </b-form-select>
                                    </b-form-group>
                                </v-col>
                                <v-col cols="2">
                                    <button type="submit" class="btn btn-success" @click.prevent="checkFormation()">Inscrire</button>
                                </v-col>
                            </v-row>
                            <v-row v-else>
                                <v-col cols="10">
                                    <div class="alert alert-danger" role="alert">
                                        Aucune future formation prévue.
                                    </div>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item value="ajoutCandidat">
                    <v-card flat tile>
                        <v-card-text class="mb-n3">
                            <v-row class="mt-n3 mb-n5" v-if="listRecrutements != null">
                                <v-col cols="10">
                                    <b-form-group>
                                        <b-form-select v-model="choix_recrutement" id="choix_recrutement" :state="validationRecrutement">
                                            <b-form-select-option :value="null">Choisissez un recrutement...</b-form-select-option>
                                            <b-form-select-option v-for="recrutement in listRecrutements" :key="recrutement.id" :value="recrutement.id">
                                                {{ recrutement.date | newDate}} - pour la formation : {{ recrutement.formation}}
                                            </b-form-select-option>
                                        </b-form-select>
                                    </b-form-group>
                                </v-col>
                                <v-col cols="2">
                                    <button type="submit" class="btn btn-success" @click.prevent="checkRecrutement()">Inscrire</button>
                                </v-col>
                            </v-row>
                            <v-row v-else>
                                <v-col cols="10">
                                    <div class="alert alert-danger" role="alert">
                                        Aucune futur recrutement prévu.
                                    </div>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item value="tags">
                    <div class="container p-4">
                        <div class="row">
                            <div class="col">
                                <b-form-group label="Sélectionnez les centres d'intérêt dans la liste déroulante">
                                    <b-form-tags input-id="tags" v-model="inscritTags" size="lg" add-on-change no-outer-focus>
                                        <template v-slot="{ tags, inputAttrs, inputHandlers, disabled, removeTag }">
                                            <ul v-if="tags.length > 0" class="list-inline d-inline-block">
                                                <li v-for="tag in tags" :key="tag" class="list-inline-item">
                                                    <b-form-tag
                                                        @remove="removeTag(tag)"
                                                        :title="tag"
                                                        :disabled="disabled"
                                                        variant="success"
                                                    >{{ tag }}</b-form-tag>
                                                </li>
                                            </ul>
                                            <b-form-select v-bind="inputAttrs" v-on="inputHandlers" class="mt-3">
                                                <template v-slot:first>
                                                    <!-- This is required to prevent bugs with Safari -->
                                                    <option disabled value="">Choisissez un tag...</option>
                                                    <option v-for="tag in listTags" :key="tag.id" :value="tag.tag">{{ tag.tag }}</option>
                                                </template>
                                            </b-form-select>
                                        </template>
                                    </b-form-tags>
                                </b-form-group>
                                <v-btn class="btn-success text-light" @click="addTags()">Ajouter les tags</v-btn>
                            </div>
                            <div class="col">
                                <b-form-group label="Si vous ne trouvez pas le centre d'intérêt dans la liste déroulante, créez-le :">
                                    <b-form-tags
                                        input-id="tags"
                                        v-model="newTags"
                                        :tag-validator="validator"
                                        tag-variant="success"
                                        tag-pills
                                        size="md"
                                        separator=",."
                                        @tag-state="onTagState"
                                        placeholder="Entrez un new tag séparé d'une virgule ou d'un point"
                                    ></b-form-tags>
                                    <template #description>
                                        <div id="tags-validation-help">
                                            Un nouveau tag doit être compris entre 3 et 50 caractères.<br>Séparez les nouveaux tags par une virgule ou un point ou appuyez sur Add.
                                        </div>
                                    </template>
                                </b-form-group>
                                <v-btn class="btn-primary" @click="createTags()">Créer les tags</v-btn>
                            </div>
                        </div>
                    </div>
                </v-tab-item>
            </v-tabs>
        </div>
        <Spinner v-else />
    </div>
</template>

<script>
    import moment from 'moment';
    import {Form} from "vform";
    import MailtoUI from 'mailtoui/dist/mailtoui-min.js';
    import Spinner from "../../elements/Spinner";

    export default {
        name: "ShowInscrit",
        components: {
            Spinner,
        },
        data() {
            return {
                loading: false,
                loadingData: false,
                inscrit: [],
                formations: [],
                recrutements: [],
                today: null,
                listFormations: [],
                nbreStagiaires: [],
                choix_formation: null,
                listRecrutements: [],
                choix_recrutement: null,
                numRecrutement: null,
                compteur: 1,
                dates: {},
                noms: {},
                tmpInfos: {},
                Infos: [],
                tags: [],
                direction: 'bottom',
                fab: false,
                fling: false,
                hover: true,
                tabs: null,
                top: true,
                right: true,
                bottom: false,
                left: false,
                transition: 'slide-y-reverse-transition',
                listTags: [],
                newTags: [],
                inscritTags : [],
                formTag : new Form({
                    tag: null,
                }),
                test_tag: 0,
                tabTags: [],
                formInscrit : new Form({
                    tags: null,
                    current_user: null,
                }),
                exist: false,
                logs: [],
            }
        },
        watch: {
            choix_tag: function(newTag) {
                this.newTags.push(newTag);
                this.choix_tag = null;
            },
        },
        mounted() {
            console.log('Show Inscrit component mounted');
            this.getDatas();
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
            validationRecrutement() {
                return this.choix_recrutement != null;
            },
            validationFormation() {
                return this.choix_formation != null;
            },
            currentUser() {
                return this.$store.getters.currentUser;
            }
        },
        methods: {
            goBack() {
                return this.$router.go(-1);
            },
            getDatas() {
                this.getInscrit();
                this.getNow();
                this.getFormations();
                this.getRecrutements();
                this.getListTags();
            },
            getInscrit() {
                this.loading = false;
                this.$Progress.start();
                axios.get(`/api/inscrits/show/${this.$route.params.id}`)
                    .then((response) => {
                        this.inscrit = response.data.inscrit;
                        this.tags = response.data.tags;
                        this.recrutements = response.data.recrutements;
                        this.formations = response.data.formations;
                        this.getLogs()
                        this.loading = true;
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.log(error.response);
                        Snackbar.fire('Problème avec la récupération des infos de la personne');
                    })
            },
            getLogs() {
                axios.get(`/api/logs/show/${this.$route.params.id}`)
                    .then((response) => {
                        this.logs = response.data.logs;
                        this.loadingData = true;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire('Problème avec la récupération des logs concernant la personne');
                    })
            },
            getNow() {
                this.today = moment().format("YYYY-MM-DD");
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
                    })
            },
            getRecrutements() {
                axios.get('api/recrutements/futurs')
                    .then(response => {
                        this.listRecrutements = response.data.recrutements;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire('Problème avec la récupération de la liste des recrutements');
                    })
            },
            checkFormation() {
                if(this.choix_formation !== null) {
                    this.inscriptionFormation();
                } else {
                    Snackbar.fire('Vous n\'avez pas choisi de formation !');
                }
            },
            inscriptionFormation() {
                axios.post('api/formations/addInscrit/' + this.choix_formation +'/'+ this.inscrit.id)
                    .then(response => {
                        this.$Progress.finish();
                        if(response.data.message != null) {
                            Toast.fire({
                                title: 'Inscription effectuée',
                                timer: 2000,
                            });
                        } else if (response.data.error != null) {
                            Snackbar.fire('Déjà inscrit à cette formation');
                        }
                        this.choix_formation = null;
                        this.getInscrit();
                        Fire.$emit('RefreshPage'); // Rafraichit la page

                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$Progress.fail();
                        Snackbar.fire('Problème avec l\'inscription à la formation');
                    })
            },
            checkRecrutement() {
                if(this.choix_recrutement !== null) {
                    this.inscriptionRecrutement();
                } else {
                    Snackbar.fire('Vous n\'avez pas choisi de recrutement !');
                }
            },
            inscriptionRecrutement() {
                axios.post('api/recrutements/addInscrit/' + this.choix_recrutement +'/'+ this.inscrit.id)
                    .then(response => {
                        this.$Progress.finish();
                        if(response.data.message != null) {
                            Toast.fire({
                                title: 'Inscription effectuée',
                                timer: 2000,
                            });
                        } else if (response.data.error != null) {
                            Snackbar.fire('Déjà inscrit à ce recrutement');
                        }
                        this.choix_recrutement = null;
                        this.getInscrit();
                        Fire.$emit('RefreshPage'); // Rafraichit la page
                    })
                    .catch(error => {
                        console.log(error.response);
                        this.$Progress.fail();
                        Snackbar.fire('Problème avec l\'inscription au recrutement');
                    })
            },
            getListTags() {
                axios.get('api/tags/all')
                    .then(response => {
                        this.listTags = response.data.tags;
                    })
                    .catch(error => {
                        console.log(error.response);
                        Snackbar.fire('Problème avec la récupération de la liste de tags');
                    })
            },
            onTagState(valid, invalid, duplicate) {
                this.validTags = valid
                this.invalidTags = invalid
                this.duplicateTags = duplicate
            },
            validator(tag) {
                return tag.length > 2 && tag.length < 50
            },
            createTags() {
                if (this.newTags.length < 1) {
                    Snackbar.fire('Pas de nouveau tag à créer');
                } else {
                    for(let x = 0; x < this.newTags.length; x++) {
                        this.test_tag = 0;
                        for(let y = 0; y < this.listTags.length; y++) {
                            if(this.newTags[x] !== this.listTags[y].tag) {
                                this.test_tag++;
                            } else {
                                Snackbar.fire('Tag déjà existant');
                                this.newTags.splice([x],0);
                            }
                        }
                        if(this.test_tag === this.listTags.length) {
                            this.formTag = new Form({
                                tag: this.newTags[x],
                            });
                            this.storeTag();
                        }
                    }
                }

            },
            storeTag() {
                this.$Progress.start();
                this.formTag.tag = this.formTag.tag.toLowerCase();
                this.formTag
                    .post('api/tags/create')
                    .then(response => {
                        if (this.formTag.successful) {
                            Toast.fire('Tag(s) créé(s)');
                            this.getListTags();
                            this.inscritTags.push(this.formTag.tag);
                            this.newTags = [];
                            this.$Progress.finish();
                        }
                    })
                    .catch(error => {
                        this.$Progress.fail();
                        console.error(error.response);
                        Snackbar.fire('Problème avec la création de tags');
                    })
            },
            addTags() {
                this.$Progress.start();
                for(let x = 0; x < this.inscritTags.length; x++) {
                    for(let y = 0; y < this.listTags.length; y++) {
                        if(this.inscritTags[x] === this.listTags[y].tag) {
                            this.tabTags.push(this.listTags[y].id);
                        }
                    }
                }
                if (this.inscritTags.length < 1) {
                    Snackbar.fire('Aucun tag sélectionné');
                } else {
                    this.formInscrit.tags = this.inscritTags;
                    this.formInscrit.current_user = this.currentUser.id;
                    this.formInscrit
                        .post('api/inscrits/addTags/' + this.inscrit.id)
                        .then(response => {
                            if(this.formInscrit.successful) {
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
                            Snackbar.fire('Problème avec l\'ajout de tags');
                        })
                }

            },
            deleteTag($id) {
                this.$Progress.start();
                Suppression.fire().then((result) => {
                    if (result.value) {
                        axios.delete('api/inscrits/deleteTags/' + this.inscrit.id + '/' + $id + '/' + this.currentUser.id)
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
                            })
                    }
                });
            },
            destroy() {
                this.$Progress.start();
                Suppression.fire().then((result) => {
                    if (result.value) {
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
                            })
                    }
                });
            }
        }
    }
</script>

<style scoped>

    .button-link {
        color: white !important;
    }

    .myLink {
        color: #317283 !important;
    }

    .link-interface {
        color: #254F5D !important;
    }

    .myLink:hover, .link-interface:hover {
        color: #1976d2 !important;
    }

    .link:hover, .myLink:hover, .button-link:hover {
        color: #ffd05c !important;
    }

    #vertical-line {
        border-left: 2px solid #000;
        display: inline-block;
        height: 160px;
        margin-left: -17px;
    }

    hr {
        margin: 0;
        border: none;
        border-top: 3px double #333;
        color: #333;
        overflow: visible;
        text-align: center;
        height: 5px;
    }

    hr:after {
        background: #fff;
        content: '§';
        padding: 0 4px;
        position: relative;
        top: -13px;
    }

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
</style>
