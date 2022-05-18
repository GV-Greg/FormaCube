<template>
    <div class="container vh-100 d-flex flex-row align-items-center justify-content-center text-center">
        <div class="flex-col col-lg-6 bg-light p-5 rounded-xl" id="form__login">
            <div class="d-flex justify-content-center">
                <v-img :src="'./storage/images/logos/Logo_FormaCube-Noir-1000x200.png'" id="logo" alt="app logo"></v-img>
            </div>
            <v-container class="field my-3">
                <v-text-field
                    label="Email"
                    type="Email"
                    prepend-inner-icon="fas fa-house-user"
                    :min="0"
                    v-model="user.email"
                    :rules="emailRules"
                    outlined
                    color="green accent-4"
                    class="v-form v-form-email mb-3"
                >
                </v-text-field>
                <v-text-field
                    label="Mot de passe"
                    type="Submit"
                    :append-icon="TogglePass ? 'fas fa-eye' : 'fas fa-eye-slash'"
                    :type="TogglePass ? 'text' : 'password'"
                    :min="0"
                    prepend-inner-icon="fas fa-lock"
                    v-model="user.password"
                    :rules="passwordRules"
                    @click:append="TogglePass = !TogglePass"
                    outlined
                    color="green accent-4"
                    class="v-form v-form-password mb-3"
                >
                </v-text-field>
                <div v-if="authError">
                    <p class="error">
                        <strong>{{ authError }}</strong>
                    </p>
                </div>
                <button type="submit" class="btn btn-primary text-light px-3" @click="authenticate">Se loguer</button>
                <div class="mt-4 mb-n10 text-primary-dark font-weight-bold">
                    Compte de démo : <span class="text-primary-light">demo@creacube.be</span><br/>
                    Mot de passe : <span class="text-primary-light">demo.2022</span>
                </div>
            </v-container>
        </div>
    </div>
</template>

<script>
    import { login } from '../../../helpers/auth';

    export default {
        name: 'login',
        data: () => ({
            user: {
                email: '',
                password: '',
            },
            emailRules: [
                v => !!v || 'L\'email est requis',
                v => /.+@.+\..+/.test(v) || 'Le champ email n\'est pas valide',
            ],
            passwordRules: [
                v => !!v || 'Le mot de passe est requis',
                v => (v && v.length >= 8) || 'Le mot de passe doit contenir 8 caractères',
            ],
            TogglePass: false,
            error: null,
        }),
        methods: {
            authenticate() {
                this.$store.dispatch('login');

                login(this.$data.user)
                    .then((response) => {
                        this.$store.commit('loginSuccess', response);
                        this.$router.push({ path: '/home'});
                    })
                    .catch((error) => {
                        this.$store.commit('loginFailed', {error});
                    });
            }
        },
        computed: {
            authError() {
                return this.$store.getters.authError;
            }
        }
    }
</script>

<style scoped>
    .error {
        color: #e3342f !important;
    }
</style>
