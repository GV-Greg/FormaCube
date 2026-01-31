import './bootstrap';

import { createApp, reactive } from 'vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import router from './router';
import vuetify from '../plugins/vuetify';
import { initialize } from './helpers/general.js';
import { useMainStore } from './store/index.js';
import moment from 'moment';
import Swal from 'sweetalert2';

import AppContainer from './components/AppContainer.vue';

// Pinia setup
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

// Simple Progress plugin for Vue 3
const progressState = reactive({ loading: false });
const ProgressPlugin = {
    install(app) {
        app.config.globalProperties.$Progress = {
            start() {
                progressState.loading = true;
            },
            finish() {
                progressState.loading = false;
            },
            fail() {
                progressState.loading = false;
            }
        };
        // Make progress state available globally
        app.config.globalProperties.$progressState = progressState;
    }
};

// Create Vue app
const app = createApp({
    components: {
        'app-container': AppContainer,
    },
    async mounted() {
        try {
            const MailtoUI = await import('mailtoui/dist/mailtoui-min.js');
            if (MailtoUI.default?.run) {
                MailtoUI.default.run({
                    title: "Envoyer l'email avec",
                    buttonText1: "Gmail via navigateur",
                    buttonText2: "Outlook via navigateur",
                    buttonText3: "Yahoo via navigateur",
                    buttonText4: "Via votre programme de messagerie par défaut",
                    buttonTextCopy: "Copier",
                    buttonTextCopyAction: "Copié"
                });
            } else if (MailtoUI.run) {
                MailtoUI.run({
                    title: "Envoyer l'email avec",
                    buttonText1: "Gmail via navigateur",
                    buttonText2: "Outlook via navigateur",
                    buttonText3: "Yahoo via navigateur",
                    buttonText4: "Via votre programme de messagerie par défaut",
                    buttonTextCopy: "Copier",
                    buttonTextCopyAction: "Copié"
                });
            }
        } catch (e) {
            console.warn('MailtoUI could not be loaded:', e);
        }
    },
});

// Use plugins
app.use(pinia);
app.use(router);
app.use(vuetify);
app.use(ProgressPlugin);

// Global components
app.component('app-container', AppContainer);

// Global properties
window.Swal = Swal;

// Affichage de belles alertes
const Toast = Swal.mixin({
    icon: 'success',
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

// Affichage de snackbar
const Snackbar = Swal.mixin({
    icon: 'error',
    toast: true,
    position: 'top',
    showConfirmButton: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

// Notification de demande de suppression
const Suppression = Swal.mixin({
    title: 'Êtes-vous sûr?',
    text: "Si vous continuez, la suppression sera définitive!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Oui, supprimez!'
});

// Notification de confirmation de suppression
const Confirm = Swal.mixin({
    icon: 'success'
});

window.Toast = Toast;
window.Snackbar = Snackbar;
window.Suppression = Suppression;
window.Confirm = Confirm;

// Global filters as app.config.globalProperties (Vue 3 way)
app.config.globalProperties.$filters = {
    UpperCase(text) {
        if (!text) return '';
        return text.toString().toUpperCase();
    },
    LowerCase(text) {
        if (!text) return '';
        return text.toString().toLowerCase();
    },
    Capitalize(text) {
        if (!text) return '';
        text = text.toString();
        return text.charAt(0).toUpperCase() + text.slice(1);
    },
    newDate(created) {
        return moment(created).format('DD/MM/YYYY');
    },
    newAge(created) {
        return moment().diff(moment(created), 'years');
    }
};

// Initialize store and router helpers
const mainStore = useMainStore();
initialize(mainStore, router);

// Compatibility layer for Vuex syntax (this.$store)
// This allows gradual migration from Vuex to Pinia
app.config.globalProperties.$store = {
    get state() { return mainStore.$state; },
    getters: {
        get currentUser() { return mainStore.currentUser; },
        get isLoggedIn() { return mainStore.isLoggedIn; },
        get isLoading() { return mainStore.loading; },
        get authError() { return mainStore.auth_error; },
        get users() { return mainStore.users; },
        get archives() { return mainStore.archives; },
    },
    commit(mutation, payload) {
        // Map Vuex mutations to Pinia actions
        switch(mutation) {
            case 'login': mainStore.login(); break;
            case 'loginSuccess': mainStore.loginSuccess(payload); break;
            case 'loginFailed': mainStore.loginFailed(payload); break;
            case 'logout': mainStore.logout(); break;
            case 'updateUser': mainStore.updateUser(payload); break;
            case 'updateUsers': mainStore.updateUsers(payload); break;
            case 'updateArchives': mainStore.updateArchives(payload); break;
            default: console.warn(`Unknown mutation: ${mutation}`);
        }
    },
    dispatch(action, payload) {
        // Map Vuex actions to Pinia actions
        switch(action) {
            case 'login': mainStore.login(); break;
            case 'getUsers':
            case 'fetchUsers': mainStore.fetchUsers(); break;
            case 'fetchArchives': mainStore.fetchArchives(); break;
            default: console.warn(`Unknown action: ${action}`);
        }
    }
};

// Mount the app
app.mount('#app');

