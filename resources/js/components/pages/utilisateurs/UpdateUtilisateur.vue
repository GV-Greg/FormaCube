<template>
    <div>
        <h1 class="ml-5 mb-n2">
            <router-link to="/users">
                <button class="btn btn-light mt-n2">
                    <i class="fas fa-reply fa-lg text-interface"></i>
                </button>
            </router-link>
            Modification de la fiche de {{ user.firstname }} {{ user.lastname | upperCase }}
        </h1>
        <div class="row justify-content-center mb-n3 mt-5" v-if="loading === true">
            <div class="col-lg-7 bg-white rounded-lg p-3">
                <form class="p-2">
                    <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                        <b-input-group-prepend is-text>
                            <span class="text-light-interface font-weight-bold">Prénom*</span>
                        </b-input-group-prepend>
                        <b-form-input v-model="form.firstname" type="text"
                                      :state="checkFirstname && validationMinFirstname && validationMaxFirstname"
                                      class="form-control rounded-r-lg" :class="{ 'is-invalid': form.errors.has('firstname') }"
                                      name="firstname" id="firstname" placeholder="Prénom">
                        </b-form-input>
                        <b-form-invalid-feedback id="firstname-feedback" class="pl-5" v-show="checkFirstname === false">
                            Le pr&eacute;nom est requis.
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback id="firstname-min-feedback" class="pl-5" v-show="validationMinFirstname === false">
                            Le pr&eacute;nom doit &ecirc;tre compos&eacute; d'au moins 3 caract&egrave;res.
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback id="firstname-max-feedback" class="pl-5" v-show="validationMaxFirstname === false">
                            Le pr&eacute;nom doit &ecirc;tre compos&eacute; de moins 100 caract&egrave;res.
                        </b-form-invalid-feedback>
                        <has-error :form="form" field="firstname" class="pl-5"></has-error>
                    </b-input-group>
                    <b-input-group class="mt-4 mb-2 mr-sm-2 mb-sm-0">
                        <b-input-group-prepend is-text>
                            <span class="text-light-interface font-weight-bold">Nom*</span>
                        </b-input-group-prepend>
                        <b-form-input v-model="form.lastname" type="text"
                                      :state="checkLastname && validationMinLastname && validationMaxLastname"
                                      class="form-control rounded-r-lg" :class="{ 'is-invalid': form.errors.has('lastname') }"
                                      name="lastname" id="lastname" placeholder="Nom">
                        </b-form-input>
                        <b-form-invalid-feedback id="lastname-feedback" class="pl-5" v-show="checkLastname === false">
                            Le nom est requis.
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback id="lastname-min-feedback" class="pl-5" v-show="validationMinLastname === false">
                            Le nom doit &ecirc;tre compos&eacute; d'au moins 3 caract&egrave;res.
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback id="lastname-max-feedback" class="pl-5" v-show="validationMaxLastname === false">
                            Le nom doit &ecirc;tre compos&eacute; de moins 100 caract&egrave;res.
                        </b-form-invalid-feedback>
                        <has-error :form="form" field="lastname" class="pl-5"></has-error>
                    </b-input-group>
                    <b-input-group class="mt-4 mb-2 mr-sm-2 mb-sm-0">
                        <b-input-group-prepend is-text>
                            <span class="text-light-interface font-weight-bold">Email*</span>
                        </b-input-group-prepend>
                        <b-form-input v-model="form.email" type="email"
                                      :state="checkEmail && checkValidEmail && validationMaxEmail"
                                       class="form-control rounded-r-lg" :class="{ 'is-invalid': form.errors.has('email') }"
                                       name="email" id="email" placeholder="Email">
                        </b-form-input>
                        <b-form-invalid-feedback id="lastname-feedback" class="pl-5" v-show="checkEmail === false">
                            L'email est requis.
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback id="lastname-max-feedback" class="pl-5" v-show="checkValidEmail === false">
                            L'email n'est pas valide.
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback id="lastname-max-feedback" class="pl-5" v-show="validationMaxEmail === false">
                            L'email doit &ecirc;tre compos&eacute; de moins 100 caract&egrave;res.
                        </b-form-invalid-feedback>
                        <has-error :form="form" field="email" class="pl-5"></has-error>
                    </b-input-group>
                        <b-input-group class="mt-5 mb-2 mr-sm-2 mb-sm-0">
                        <b-input-group-prepend is-text>
                            <i class="fas fa-key text-light-interface"></i>
                        </b-input-group-prepend>
                        <b-form-input v-model="form.password" type="password"
                                      :state="validationMinPassword && validationMaxPassword"
                                      class="form-control rounded-r-lg" :class="{ 'is-invalid': form.errors.has('password') }"
                                      name="password" id="password" placeholder="Mot de passe">
                        </b-form-input>
                            <b-form-invalid-feedback id="password-max-feedback" class="pl-5" v-show="validationMinPassword === false">
                                Le mot de passe doit &ecirc;tre compos&eacute; d'au moins 8 caractères.
                            </b-form-invalid-feedback>
                            <b-form-invalid-feedback id="password-max-feedback" class="pl-5" v-show="validationMaxPassword === false">
                                Le mot de passe doit &ecirc;tre compos&eacute; de moins 100 caract&egrave;res.
                            </b-form-invalid-feedback>
                            <has-error :form="form" field="password"></has-error>
                    </b-input-group>
                    <b-input-group class="mt-5 mb-2 mr-sm-2 mb-sm-0">
                        <b-input-group-prepend is-text>
                            <span class="text-light-interface font-weight-bold">Fonction</span>
                        </b-input-group-prepend>
                        <b-form-input v-model="form.fonction" type="text" :state="validationMaxFonction"
                                      class="form-control rounded-r-lg" :class="{ 'is-invalid': form.errors.has('fonction') }"
                                      name="fonction" id="fonction" placeholder="Fonction">
                        </b-form-input>
                        <b-form-invalid-feedback id="password-max-feedback" class="pl-5" v-show="validationMaxFonction === false">
                            La fonction doit &ecirc;tre compos&eacute;e de moins 100 caract&egrave;res.
                        </b-form-invalid-feedback>
                        <has-error :form="form" field="fonction" class="pl-5"></has-error>
                    </b-input-group>
                    <div class="row">
                        <div class="col">
                            <b-input-group class="mt-5 mb-5 mr-sm-2 mb-sm-0">
                                <b-input-group-prepend is-text>
                                    <span class="text-light-interface font-weight-bold">R&ocirc;le*</span>
                                </b-input-group-prepend>
                                <b-form-select v-model="form.role" :state="checkRole"
                                               class="form-control rounded-r-lg" :class="{ 'is-invalid': form.errors.has('role') }"
                                               name="role" id="role">
                                    <b-form-select-option :value="null">S&eacute;lectionner un r&ocirc;le</b-form-select-option>
                                    <b-form-select-option value="formateur">Formateur</b-form-select-option>
                                    <b-form-select-option value="commu">Commu</b-form-select-option>
                                    <b-form-select-option value="admin">Admin</b-form-select-option>
                                    <b-form-select-option value="superAdmin">SuperAdmin</b-form-select-option>
                                    <b-form-select-option value="master">Master</b-form-select-option>
                                </b-form-select>
                                <b-form-invalid-feedback id="password-max-feedback" class="pl-5" v-show="checkRole === false">
                                    Le rôle est requis.
                                </b-form-invalid-feedback>
                                <has-error :form="form" field="role" class="pl-5"></has-error>
                            </b-input-group>
                        </div>
                        <div class="col">
                            <b-input-group class="mt-5 mb-5 mr-sm-2 mb-sm-0">
                                <b-input-group-prepend is-text>
                                    <span class="text-light-interface font-weight-bold">Avatar</span>
                                </b-input-group-prepend>
                                <b-form-select v-model="form.avatar"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('avatar') }"
                                               name="avatar" id="avatar">
                                    <b-form-select-option :value="null">Choisissez</b-form-select-option>
                                    <b-form-select-option value="Homme">Homme</b-form-select-option>
                                    <b-form-select-option value="Femme">Femme</b-form-select-option>
                                </b-form-select>
                                <has-error :form="form" field="avatar" class="pl-5"></has-error>
                            </b-input-group>
                        </div>
                    </div>
                </form>
                <span class="font-weight-light font-italic text-light-interface mt-3 ml-2"><small>Tous les champs avec * sont obligatoires</small></span>
                <div class="mt-4 text-right">
                    <v-btn class="btn-success text-light mt-n5" @click="updateUser()">Modifier</v-btn>
                </div>
            </div>
        </div>
        <div v-else class="d-flex flex-column justify-center align-center mt-5">
            <v-progress-circular :size="70" :width="10" color="blue-grey" indeterminate></v-progress-circular>
            <span class="mt-5 text-interface">Chargement...</span>
        </div>
    </div>
</template>

<script>
import {Form} from "vform";

export default {
    name: "UpdateUtilisateur",
    data() {
        return {
            loading: false,
            form: new Form({
                firstname: '',
                lastname: '',
                email: '',
                password: null,
                fonction: '',
                role: null,
                avatar: null,
            }),
            error: {},
            user: [],
        }
    },
    mounted() {
        this.loading = false;
        this.getUser();
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        },
        checkFirstname() {
            return this.form.firstname !== ''
        },
        validationMinFirstname() {
            if(this.form.firstname !== '') {
                return this.form.firstname.length > 2;
            }
        },
        validationMaxFirstname() {
            if(this.form.firstname !== '')
            return this.form.firstname.length < 100;
        },
        checkLastname() {
            return this.form.lastname !== ''
        },
        validationMinLastname() {
            if(this.form.lastname !== '') {
                return this.form.lastname.length > 2;
            }
        },
        validationMaxLastname() {
            return this.form.lastname.length < 100;
        },
        checkEmail() {
            return this.form.email !== '';
        },
        checkValidEmail() {
            if(this.form.email !== '') {
                return this.validEmail(this.form.email);
            }
        },
        validationMaxEmail() {
            return this.form.email.length < 100;
        },
        validationMinPassword() {
            if(this.form.password !== '' && this.form.password !== undefined && this.form.password !== null) {
                return this.form.password.length > 7;
            }
        },
        validationMaxPassword() {
            if(this.form.password !== '' && this.form.password !== undefined && this.form.password !== null) {
                return this.form.password.length < 100;
            }
        },
        checkRole() {
            return this.form.role != null;
        },
        validationMaxFonction() {
            if(this.form.fonction !== '' && this.form.fonction !== null) {
                return this.form.fonction.length < 100;
            }
        }
    },
    methods: {
        validEmail: function (email) {
            let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        validation(test, message){
            if(test) {
                Snackbar.fire(message);
                return true;
            }
            return false;
        },
        getUser() {
            this.$Progress.start();
            axios.get(`/api/users/${this.$route.params.id}`)
                .then((response) => {
                    this.user = response.data.user;
                    this.getFormUser(this.user);
                    this.$Progress.finish();
                })
                .catch((error) => {
                    console.log(error.response);
                    this.$Progress.fail();
                    Snackbar.fire('Problème avec la récupération des infos de l\'utilisateur !');
                });
        },
        getFormUser(user) {
            this.form.firstname = this.user.firstname;
            this.form.lastname = this.user.lastname;
            this.form.email = this.user.email;
            this.form.fonction = this.user.fonction;
            this.form.role = this.user.role;
            if(user.avatar === 'man.png') {
                this.form.avatar = 'Homme';
            } else if(user.avatar === 'woman.png') {
                this.form.avatar = 'Femme';
            } else {
                this.form.avatar = null;
            }
            this.loading = true;
        },

        updateUser() {
            if(this.form.password === '' || this.form.password === null){
                this.form.password = undefined;
            }
            if(this.validation(!this.checkFirstname || !this.checkLastname || !this.checkEmail || !this.checkRole, 'Vous n\'avez pas rempli tous les champs obligatoires !')) {
            } else if(this.validation( !this.validationMinFirstname, "Le prénom doit être composés d'au moins 3 caractères !")) {
            } else if(this.validation( !this.validationMaxFirstname, "Le prénom doit être composé de 100 caractères !")) {
            } else if(this.validation( !this.validationMinLastname, "Le nom doit être composés d'au moins 3 caractères !")) {
            } else if(this.validation( !this.validationMaxFirstname, "Le nom  doit être composé de 100 caractères !")) {
            } else if(this.validation( !this.checkEmail && !this.checkValidEmail, "Vous n'avez pas renseigné un email valide !")) {
            } else if(this.validation(!this.validationMaxEmail, "L'email doit doit être composé de 100 caractères !")) {
            } else if(this.validation(this.form.password !== undefined && !this.validationMinPassword, 'Le mot de passe est trop court')) {
            } else if(this.validation(this.form.password !== undefined && !this.validationMaxPassword, 'Le mot de passe doit être composé de moins de 100 caractères !')) {
            } else if(this.validation(this.form.fonction !== null && this.form.fonction !== '' && !this.validationMaxFonction, 'La fonction doit être composée de moins de 100 caractères !')) {
            } else {
                this.$Progress.start();
                this.form
                    .put('/api/users/edit/' + this.$data.user.id)
                    .then((response) => {
                        if(this.form.successful) {
                            this.$Progress.finish();
                            Toast.fire('Utilisateur modifié');
                            this.$store.dispatch('getUsers');
                            this.$router.push('/users')
                        }
                    })
                    .catch((error) => {
                        console.log(error.response);
                        this.$Progress.fail();
                        if (error.response.status === 422) {
                            Snackbar.fire('Utilisateur avec cet email déjà existant');
                        } else {
                            Snackbar.fire('Problème avec la modification de l\'utilisateur !');
                        }
                    });
            }
        },
    },
}
</script>

<style scoped>

</style>
