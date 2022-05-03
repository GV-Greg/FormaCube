require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue';
import Vuex from 'vuex';
import router from './router';
import VueRouter from 'vue-router';
import storeData from './store/index.js'; // pour Vuex (le fichier qui comprent les states, getters, mutations, actions)
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'; // Framewok CSS Bootstrap pour Vue
import vuetify from '../plugins/vuetify'; // Framework CSS Vuetify pour Vue
import { initialize } from './helpers/general.js'; // initialisation des fonctions de 'retour en arrière' et interception d'erreur 401
import { Form, HasError, AlertError } from 'vform'; // utilisation de formulaires avec gestion d'erreurs
import moment from 'moment'; // formatage de dates
import Swal from 'sweetalert2'; // affichage de belles alertes
import VueProgressBar from 'vue-progressbar'; // Barre de progression affiché au top de la page
import JsonExcel from "vue-json-excel"; // Exportation de fichier excel

window.form = Form;
window.Swal = Swal;

window.Fire = new Vue(); // Global listen event pour rafraichir la page

Vue.component(HasError.name, HasError); // Les composants d'erreurs de vform
Vue.component(AlertError.name, AlertError); // Les composants d'alertes d'erreurs de vform
Vue.component('app-container', require('./components/AppContainer.vue').default);
Vue.component("downloadExcel", JsonExcel); // Composants pour l'exportation de fichier excel

// Formatage en Capitales
Vue.filter('UpperCase', function(text){
    if (!text) return '';
    text = text.toString();
    return text.toUpperCase();
});

// Formatage en Minuscules
Vue.filter('LowerCase', function(text){
    if (!text) return '';
    text = text.toString();
    return text.toLowerCase();
});

// Formatage Première lettre en capitale
Vue.filter('Capitalize', function(text){
    if (!text) return '';
    text = text.toString();
    return text.charAt(0).toUpperCase() + text.slice(1)
});

// Formatage de Date
Vue.filter('newDate', function(created){
    return moment(created).format('DD/MM/YYYY');
});

// Calcul age par rapport à la date de naissance
Vue.filter('newAge', function(created) {
    return moment().diff(moment(created), 'years');
})

// Mailto
const MailtoUI = require('mailtoui/dist/mailtoui-min.js');

// Affichage de belles alertes
const Toast = Swal.mixin({
    icon: 'success',
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// Affichage de snackbar
const Snackbar = Swal.mixin({
    icon: 'error',
    toast: true,
    position: 'top',
    showConfirmButton: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

// Notification de demande de suppression
const Suppression = Swal.mixin({
    title: 'Êtes-vous sûr?',
    text: "Si vous continuez, la suppression sera définitive!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Oui, supprimez!'
})

// Notification de confirmation de suppression
const Confirm = Swal.mixin({
    icon: 'success'
})

// window.MailtoUI= MailtoUI;
window.Toast = Toast;
window.Snackbar = Snackbar;
window.Suppression = Suppression;
window.Confirm = Confirm;

// Custom de la barre de progression
Vue.use(VueProgressBar, {
    color: '#50d38a',
    failedColor: '#f34646',
    height: '2px'
});

// Vue.prototype.$gate = new Gate(window.user);

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

const store = new Vuex.Store(storeData);
initialize(store, router); // fonction qui regroupe les fonctions dans le fichier general.js du dossier helpers

const app = new Vue({
    vuetify,
    store,
    router: router,
    el: '#app',
    mounted() {
        MailtoUI.run({
            title:"Envoyer l'email avec",
            buttonText1:"Gmail via navigateur",
            buttonText2:"Outlook via navigateur",
            buttonText3:"Yahoo via navigateur",
            buttonText4:"Via votre programme de messagerie par défaut",
            buttonTextCopy:"Copier",
            buttonTextCopyAction:"Copié"
        });
    },
});
