<template>
    <div class="container vh-100 d-flex flex-row align-items-center justify-content-center text-center">
        <div class="flex-col col-lg-6 bg-light p-5 rounded-xl" id="form__login">
            <div class="d-flex justify-content-center mb-4">
                <img src="/storage/images/logos/Logo_FormaCube-1000x200.png" id="logo" alt="FormaCube logo" style="max-width: 400px; height: auto;" />
            </div>
            <v-container class="field my-3">
                <v-text-field
                    label="Email"
                    type="email"
                    prepend-inner-icon="mdi-account"
                    v-model="user.email"
                    :rules="emailRules"
                    variant="outlined"
                    color="primary"
                    base-color="secondary"
                    bg-color="white"
                    class="mb-3"
                >
                </v-text-field>
                <v-text-field
                    label="Mot de passe"
                    :append-inner-icon="TogglePass ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="TogglePass ? 'text' : 'password'"
                    prepend-inner-icon="mdi-lock"
                    v-model="user.password"
                    :rules="passwordRules"
                    @click:append-inner="TogglePass = !TogglePass"
                    variant="outlined"
                    color="primary"
                    base-color="secondary"
                    bg-color="white"
                    class="mb-3"
                >
                </v-text-field>
                <div v-if="authError">
                    <p class="error">
                        <strong>{{ authError }}</strong>
                    </p>
                </div>
                <v-btn color="primary" size="large" @click="authenticate" class="mt-2">Se connecter</v-btn>
                <div class="mt-4 text-secondary">
                    Compte de démo : <strong class="text-primary">demo@creacube.be</strong><br/>
                    Mot de passe : <strong class="text-primary">demo.2022</strong>
                </div>
            </v-container>
        </div>
    </div>
</template>

<script>
    import { login } from '../../../helpers/auth';
    import { useMainStore } from '../../../store';

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
        setup() {
            const store = useMainStore();
            return { store };
        },
        methods: {
            authenticate() {
                this.store.login();

                login(this.$data.user)
                    .then((response) => {
                        this.store.loginSuccess(response);
                        this.$router.push({ path: '/home'});
                    })
                    .catch((error) => {
                        this.store.loginFailed({error});
                    });
            }
        },
        computed: {
            authError() {
                return this.store.auth_error;
            }
        }
    }
</script>

<style scoped>
    .error {
        color: #e3342f !important;
    }
</style>
