<template>
    <div class="container mt-2">
        <div class="row justify-content-center text-center">
            <div class="col-lg-6 bg-light mt-5" style="border-radius: 40px;">
                <div id="app" class="p-3">
                    <div class="d-flex justify-content-center p-5">
                        <v-img src="./images/interface3-logo.png" id="logo" alt="Logo" style="width:80%"></v-img>
                    </div>
                    <div class="field px-5 pb-3">
                        <v-container class="text-center">
                            <v-text-field
                                label="Email"
                                type="Email"
                                prepend-inner-icon="fas fa-house-user"
                                :min="0"
                                v-model="user.email"
                                :rules="emailRules"
                                outlined
                                color="green accent-4"
                                class="myFormEmail"
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
                                class="myFormPassword"
                            >
                            </v-text-field>
                            <div v-if="authError">
                                <p class="error">
                                    <strong>{{ authError }}</strong>
                                </p>
                            </div>
                            <button type="submit" class="btn btn-interface text-light mt-4 px-3" @click="authenticate">Se loguer</button>
                        </v-container>
                    </div>
                </div>
            </div>
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
