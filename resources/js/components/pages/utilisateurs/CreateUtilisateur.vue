<template>
    <div class="container">
        <h1 class="d-flex align-content-center">
            <button class="btn btn-light pb-2 mr-2" @click="retour">
                <i class="fas fa-reply fa-lg text-primary-dark"></i>
            </button>
            Création d'un nouvel utilisateur
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
                            <i class="fas fa-key text-light-interface"></i> <span class="text-light-interface pl-1">*</span>
                        </b-input-group-prepend>
                        <b-form-input v-model="form.password" type="password"
                                      :state="checkPassword && validationMinPassword && validationMaxPassword"
                                      class="form-control rounded-r-lg" :class="{ 'is-invalid': form.errors.has('password') }"
                                      name="password" id="password" placeholder="Mot de passe">
                        </b-form-input>
                        <b-form-invalid-feedback id="password-feedback" class="pl-5" v-show="checkPassword === false">
                            Le mot de passe est requis.
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback id="password-max-feedback" class="pl-5" v-show="validationMinPassword === false">
                            Le mot de passe doit &ecirc;tre compos&eacute; d'au moins 8 caractères.
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback id="password-max-feedback" class="pl-5" v-show="validationMaxPassword === false">
                            Le mot de passe doit &ecirc;tre compos&eacute; de moins 100 caract&egrave;res.
                        </b-form-invalid-feedback>
                        <has-error :form="form" field="password"></has-error>
                    </b-input-group>
                    <b-input-group class="mt-5 mb-2 mr-sm-2 mb-sm-0">
                        <b-input-group-prepend is-text class="rounded-l-lg">
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
                                    <b-form-select-option value="admin">Admin</b-form-select-option>
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
                                               class="form-control rounded-r-lg" :class="{ 'is-invalid': form.errors.has('avatar') }"
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
                <div class="mt-1 d-flex justify-content-between">
                    <span class="text-left font-weight-light font-italic text-primary-dark mt-2"><small>Tous les champs avec * sont obligatoires</small></span>
                    <v-btn class="btn-success" @click="storeUser()">Cr&eacute;er</v-btn>
                </div>
            </div>
        </div>
        <Spinner v-else />
    </div>
</template>

<script>
    import {Form} from "vform";
    import Spinner from "../../elements/SpinnerStepper";

    export default {
        name: "NewUtilisateur",
        components: {
            Spinner,
        },
        data() {
            return {
                loading: false,
                form: new Form({
                    firstname: '',
                    lastname: '',
                    email: '',
                    password: '',
                    fonction: '',
                    role: null,
                    avatar: null,
                }),
                error: {},
            }
        },
        mounted() {
            this.loading = false;
            console.log('New component mounted');
            this.loading = true;
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
            checkPassword() {
                return this.form.password !== '';
            },
            validationMinPassword() {
                if(this.form.password !== '') {
                    return this.form.password.length > 7;
                }
            },
            validationMaxPassword() {
                if(this.form.password !== '') {
                    return this.form.password.length < 100;
                }
            },
            checkRole() {
                return this.form.role != null;
            },
            validationMaxFonction() {
                if(this.form.fonction !== '') {
                    return this.form.fonction.length < 100;
                }
            }
        },
        methods: {
            retour() {
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Si vous continuez, vous retournez à la liste des utilisateurs !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3CB521',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<strong>RETOUR</strong>'
                }).then((result) => {
                    if (result.value) {
                        return this.$router.go(-1);
                    }
                });
            },
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

            storeUser() {
                if(this.validation(!this.checkFirstname || !this.checkLastname || !this.checkEmail || !this.checkPassword || !this.checkRole, 'Vous n\'avez pas rempli tous les champs obligatoires !')) {
                } else if(this.validation( !this.validationMinFirstname, "Le prénom doit être composés d'au moins 3 caractères !")) {
                } else if(this.validation( !this.validationMaxFirstname, "Le prénom doit être composé de moins de 100 caractères !")) {
                } else if(this.validation( !this.validationMinLastname, "Le nom doit être composés d'au moins 3 caractères !")) {
                } else if(this.validation( !this.validationMaxFirstname, "Le nom doit être composé de moins de 100 caractères !")) {
                } else if(this.validation( !this.checkEmail && !this.checkValidEmail, "Vous n'avez pas renseigné un email valide !")) {
                } else if(this.validation(!this.validationMaxEmail, "L'email doit doit être composé de moins de 100 caractères !")) {
                } else if(this.validation(!this.validationMinPassword, 'Le mot de passe est trop court')) {
                } else if(this.validation(!this.validationMaxPassword, 'Le mot de passe doit être composé de moins de 100 caractères !')) {
                } else if(this.validation(this.form.fonction !== '' && !this.validationMaxFonction, 'La fonction doit être composée de moins de 100 caractères !')) {
                } else {
                    this.$Progress.start();
                    this.form
                        .post('/api/users/create')
                        .then((response) => {
                            if(this.form.successful) {
                                this.$Progress.finish();
                                Toast.fire('Utilisateur créé');
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
                                Snackbar.fire('Problème avec la création de l\'utilisateur !');
                            }
                        });
                }
            },
        }
    }
</script>

<style scoped>

</style>
