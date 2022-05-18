<template>
    <div>
        <h1 class="ml-5 mb-n2">
            <router-link to="/profil">
                <button class="btn btn-light mt-n2">
                    <i class="fas fa-reply fa-lg text-interface"></i>
                </button>
            </router-link>
            Modification de votre compte
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
                </form>
                <span class="font-weight-light font-italic text-primary-dark mt-3 ml-2"><small>Tous les champs avec * sont obligatoires.</small></span>
                <div class="mt-4 d-flex justify-content-between">
                    <div class="ml-2 d-flex align-items-center font-weight-bold bg-danger rounded-lg px-3 text-light"><small>Ce compte ne peut &ecirc;tre modifi&eacute;</small></div>
                    <v-btn class="btn-success" @click="updateProfile()" v-if="currentUser.id !== 2">&Eacute;diter</v-btn>
                    <v-btn class="btn-success" disabled v-else>&Eacute;diter</v-btn>
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
    import Form from "vform";

    export default {
        name: "ProfitEdit",
        data() {
            return {
                loading: false,
                form: new Form({
                    firstname: '',
                    lastname: '',
                    email: '',
                    password: '',
                    fonction: '',
                }),
                user: [],
                error: {},
            }
        },
        mounted() {
            this.loading = false;
            this.getData();
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
            validationMaxFonction() {
                if(this.form.fonction !== '' && this.form.fonction !== null) {
                    return this.form.fonction.length < 100;
                }
            }
        },
        methods: {
            getData() {
                this.form.firstname = this.currentUser.firstname;
                this.form.lastname = this.currentUser.lastname;
                this.form.email = this.currentUser.email;
                this.form.fonction = this.currentUser.fonction;
                this.loading = true;
            },
            validEmail: function (email) {
                let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            updateStorageUser() {
                let user = JSON.parse(localStorage.user);
                if(this.user.firstname !== user.firstname ) {
                    user.firstname = this.user.firstname;
                }
                if(this.user.lastname !== user.lastname ) {
                    user.lastname = this.user.lastname;
                }
                if(this.user.email !== user.email ) {
                    user.email = this.user.email;
                }
                if(this.user.fonction !== user.fonction ) {
                    user.fonction = this.user.fonction;
                }
                localStorage.setItem('user', JSON.stringify(user));
            },

            validation(test, message){
                  if(test) {
                      Snackbar.fire(message);
                      return true;
                  }
                  return false;
            },

            updateProfile(){
                if(this.form.password === "" || this.form.password === null){
                    this.form.password = undefined;
                }
                if(this.validation(!this.checkFirstname || !this.checkLastname || !this.checkEmail, 'Vous n\'avez pas rempli tous les champs obligatoires !')) {
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
                        .put('api/profil/edit/' + this.currentUser.id)
                        .then(response => {
                            if(this.form.successful) {
                                this.$Progress.finish();
                                Toast.fire('Profil édité !');
                                this.user = response.data.user;
                                this.updateStorageUser();
                                this.$store.commit('updateUser', this.user);
                                this.$router.push('/profil');
                            }
                        })
                        .catch((error)=>{
                            console.log(error.response);
                            this.$Progress.fail();
                            if (error.response.status === 422) {
                                Snackbar.fire('Il existe déjà un autre utilisateur avec cet email !');
                            } else {
                                Snackbar.fire('Problème avec la modification de votre profil !');
                            }
                        });
                }
            },
        },
    }
</script>

<style scoped>

</style>
