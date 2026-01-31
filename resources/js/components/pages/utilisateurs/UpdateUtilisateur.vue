<template>
    <div class="container">
        <h1 class="d-flex align-content-center">
            <v-btn variant="flat" color="grey-lighten-3" class="pb-2 mr-2" @click="retour">
                <v-icon color="primary" size="large">mdi-reply</v-icon>
            </v-btn>
            Modification de la fiche de {{ user.firstname }} {{ upperCase(user.lastname) }}
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

                    <v-row class="mt-5 mb-5">
                        <v-col>
                            <v-select
                                v-model="form.role"
                                :items="roleItems"
                                item-title="text"
                                item-value="value"
                                label="Rôle*"
                                :error="!checkRole || form.errors.has('role')"
                                :error-messages="!checkRole ? 'Le rôle est requis.' : ''"
                                name="role"
                                id="role"
                                variant="outlined"
                                density="compact"
                            ></v-select>
                            <has-error :form="form" field="role" class="pl-5"></has-error>
                        </v-col>
                        <v-col>
                            <v-select
                                v-model="form.avatar"
                                :items="avatarItems"
                                item-title="text"
                                item-value="value"
                                label="Avatar"
                                :error="form.errors.has('avatar')"
                                name="avatar"
                                id="avatar"
                                variant="outlined"
                                density="compact"
                            ></v-select>
                            <has-error :form="form" field="avatar" class="pl-5"></has-error>
                        </v-col>
                    </v-row>
                </v-form>
                <div class="mt-1 d-flex justify-space-between">
                    <span class="text-left font-weight-light font-italic text-primary-dark mt-2"><small>Tous les champs avec * sont obligatoires</small></span>
                    <v-btn color="success" @click="updateUser()">Modifier</v-btn>
                </div>
            </v-col>
        </v-row>
        <Spinner v-else />
    </div>
</template>

<script>
import {Form} from "vform";
import Spinner from "../../elements/SpinnerStepper.vue";

export default {
    name: "UpdateUtilisateur",
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
                password: null,
                fonction: '',
                role: null,
                avatar: null,
            }),
            roleItems: [
                { text: 'Sélectionner un rôle', value: null },
                { text: 'Formateur', value: 'formateur' },
                { text: 'Commu', value: 'commu' },
                { text: 'Admin', value: 'admin' },
                { text: 'SuperAdmin', value: 'superAdmin' },
                { text: 'Master', value: 'master' },
            ],
            avatarItems: [
                { text: 'Choisissez', value: null },
                { text: 'Homme', value: 'Homme' },
                { text: 'Femme', value: 'Femme' },
            ],
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
        checkRole() {
            return this.form.role != null;
        },
        validationMaxFonction() {
            if(this.form.fonction !== '' && this.form.fonction !== null) {
                return this.form.fonction.length < 100;
            }
            return true;
        }
    },
    methods: {
        upperCase(value) {
            return value ? value.toUpperCase() : '';
        },
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
