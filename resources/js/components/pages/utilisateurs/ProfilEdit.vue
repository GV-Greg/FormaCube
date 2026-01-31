<template>
    <div>
        <h1 class="ml-5 mb-n2">
            <router-link to="/profil">
                <v-btn variant="flat" color="grey-lighten-3" class="mt-n2">
                    <v-icon color="primary" size="large">mdi-reply</v-icon>
                </v-btn>
            </router-link>
            Modification de votre compte
        </h1>
        <v-row justify="center" class="mb-n3 mt-5" v-if="loading === true">
            <v-col cols="12" lg="7" class="bg-white rounded-lg pa-3">
                <v-form class="pa-2">
                    <v-text-field
                        v-model="form.firstname"
                        type="text"
                        label="Prénom*"
                        :error="!checkFirstname || !validationMinFirstname || !validationMaxFirstname || form.errors.has('firstname')"
                        :error-messages="getFirstnameErrors()"
                        name="firstname"
                        id="firstname"
                        placeholder="Prénom"
                        variant="outlined"
                        density="compact"
                        class="mb-2"
                    ></v-text-field>
                    <has-error :form="form" field="firstname" class="pl-5"></has-error>

                    <v-text-field
                        v-model="form.lastname"
                        type="text"
                        label="Nom*"
                        :error="!checkLastname || !validationMinLastname || !validationMaxLastname || form.errors.has('lastname')"
                        :error-messages="getLastnameErrors()"
                        name="lastname"
                        id="lastname"
                        placeholder="Nom"
                        variant="outlined"
                        density="compact"
                        class="mt-4 mb-2"
                    ></v-text-field>
                    <has-error :form="form" field="lastname" class="pl-5"></has-error>

                    <v-text-field
                        v-model="form.email"
                        type="email"
                        label="Email*"
                        :error="!checkEmail || !checkValidEmail || !validationMaxEmail || form.errors.has('email')"
                        :error-messages="getEmailErrors()"
                        name="email"
                        id="email"
                        placeholder="Email"
                        variant="outlined"
                        density="compact"
                        class="mt-4 mb-2"
                    ></v-text-field>
                    <has-error :form="form" field="email" class="pl-5"></has-error>

                    <v-text-field
                        v-model="form.password"
                        type="password"
                        label="Mot de passe"
                        :error="validationMinPassword === false || validationMaxPassword === false || form.errors.has('password')"
                        :error-messages="getPasswordErrors()"
                        name="password"
                        id="password"
                        placeholder="Mot de passe"
                        variant="outlined"
                        density="compact"
                        class="mt-5 mb-2"
                        prepend-inner-icon="mdi-key"
                    ></v-text-field>
                    <has-error :form="form" field="password"></has-error>

                    <v-text-field
                        v-model="form.fonction"
                        type="text"
                        label="Fonction"
                        :error="validationMaxFonction === false || form.errors.has('fonction')"
                        :error-messages="getFonctionErrors()"
                        name="fonction"
                        id="fonction"
                        placeholder="Fonction"
                        variant="outlined"
                        density="compact"
                        class="mt-5 mb-2"
                    ></v-text-field>
                    <has-error :form="form" field="fonction" class="pl-5"></has-error>
                </v-form>
                <span class="font-weight-light font-italic text-primary-dark mt-3 ml-2"><small>Tous les champs avec * sont obligatoires.</small></span>
                <div class="mt-4 d-flex justify-space-between">
                    <div class="ml-2 d-flex align-items-center font-weight-bold bg-danger rounded-lg px-3 text-light"><small>Ce compte ne peut &ecirc;tre modifi&eacute;</small></div>
                    <v-btn color="success" @click="updateProfile()" v-if="currentUser.id !== 2">&Eacute;diter</v-btn>
                    <v-btn color="success" disabled v-else>&Eacute;diter</v-btn>
                </div>
            </v-col>
        </v-row>
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
                return true;
            },
            validationMaxFirstname() {
                if(this.form.firstname !== '')
                    return this.form.firstname.length < 100;
                return true;
            },
            checkLastname() {
                return this.form.lastname !== ''
            },
            validationMinLastname() {
                if(this.form.lastname !== '') {
                    return this.form.lastname.length > 2;
                }
                return true;
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
                return true;
            },
            validationMaxEmail() {
                return this.form.email.length < 100;
            },
            validationMinPassword() {
                if(this.form.password !== '' && this.form.password !== undefined && this.form.password !== null) {
                    return this.form.password.length > 7;
                }
                return true;
            },
            validationMaxPassword() {
                if(this.form.password !== '' && this.form.password !== undefined && this.form.password !== null) {
                    return this.form.password.length < 100;
                }
                return true;
            },
            validationMaxFonction() {
                if(this.form.fonction !== '' && this.form.fonction !== null) {
                    return this.form.fonction.length < 100;
                }
                return true;
            }
        },
        methods: {
            getFirstnameErrors() {
                const errors = [];
                if (!this.checkFirstname) errors.push('Le prénom est requis.');
                else if (!this.validationMinFirstname) errors.push('Le prénom doit être composé d\'au moins 3 caractères.');
                else if (!this.validationMaxFirstname) errors.push('Le prénom doit être composé de moins 100 caractères.');
                return errors;
            },
            getLastnameErrors() {
                const errors = [];
                if (!this.checkLastname) errors.push('Le nom est requis.');
                else if (!this.validationMinLastname) errors.push('Le nom doit être composé d\'au moins 3 caractères.');
                else if (!this.validationMaxLastname) errors.push('Le nom doit être composé de moins 100 caractères.');
                return errors;
            },
            getEmailErrors() {
                const errors = [];
                if (!this.checkEmail) errors.push('L\'email est requis.');
                else if (!this.checkValidEmail) errors.push('L\'email n\'est pas valide.');
                else if (!this.validationMaxEmail) errors.push('L\'email doit être composé de moins 100 caractères.');
                return errors;
            },
            getPasswordErrors() {
                const errors = [];
                if (this.validationMinPassword === false) errors.push('Le mot de passe doit être composé d\'au moins 8 caractères.');
                else if (this.validationMaxPassword === false) errors.push('Le mot de passe doit être composé de moins 100 caractères.');
                return errors;
            },
            getFonctionErrors() {
                const errors = [];
                if (this.validationMaxFonction === false) errors.push('La fonction doit être composée de moins 100 caractères.');
                return errors;
            },
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
