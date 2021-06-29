import Vue from 'vue';
import VueRouter from "vue-router";

Vue.use(VueRouter);

import LoginForm from "../components/pages/auth/LoginForm.vue";

import Dashboard from "../components/pages/dashboard/Dashboard.vue";
import Home from "../components/pages/dashboard/Home.vue";

import UtilisateursMain from "../components/pages/utilisateurs/MainUtilisateurs.vue";
import UtilisateursList from "../components/pages/utilisateurs/ListUtilisateurs.vue";
import NewUtilisateur from "../components/pages/utilisateurs/CreateUtilisateur.vue";
import UpdateUtilisateur from "../components/pages/utilisateurs/UpdateUtilisateur.vue";
import ShowUtilisateur from "../components/pages/utilisateurs/ShowUtilisateur.vue";

import MainProfil from "../components/pages/utilisateurs/MainProfil.vue";
import Profil from "../components/pages/utilisateurs/Profil.vue";
import EditProfil from "../components/pages/utilisateurs/ProfilEdit.vue";

import PouvSubs from "../components/pages/PouvsubsComponent.vue";

import MainFormations from "../components/pages/formations/MainFormations.vue";
import ListFormations from "../components/pages/formations/ListFormations.vue";
import ShowFormation from "../components/pages/formations/ShowFormation.vue";
import CreateFormation from "../components/pages/formations/CreateFormation.vue";
import EditFormation from "../components/pages/formations/EditFormation.vue";

import MainRecrutements from "../components/pages/recrutements/MainRecrutements.vue";
import ListRecrutements from "../components/pages/recrutements/ListRecrutements.vue";
import ShowRecrutement from "../components/pages/recrutements/ShowRecrutement.vue";
import SelectionToFormation from "../components/pages/recrutements/SelectionToFormation.vue";

import MainInscrits from "../components/pages/inscrits/MainInscrits.vue";
import ListInscrits from "../components/pages/inscrits/ListInscrits.vue";
import ShowInscrit from "../components/pages/inscrits/ShowInscrit.vue";
import CreateInscrit from "../components/pages/inscrits/CreateInscrit.vue";
import CreateInscritWithFormation from "../components/pages/inscrits/CreateInscritWithFormation.vue";
import CreateInscritWithRecrutement from "../components/pages/inscrits/CreateInscritWithRecrutement.vue";
import CreateInscritWithProspect from "../components/pages/inscrits/CreateInscritWithProspect.vue";
import EditInscrit from "../components/pages/inscrits/EditInscrit.vue";

import MainSalles from "../components/pages/salles/MainSalles.vue";
import ListSalles from "../components/pages/salles/ListSalles.vue";

import MainTags from "../components/pages/tags/MainTags.vue";
import ListTags from "../components/pages/tags/ListTags.vue";

import MainProspects from "../components/pages/prospects/MainProspects.vue";
import ListProspects from "../components/pages/prospects/ListProspects.vue";

import Test from "../components/pages/test.vue";

import PageNotFound from "../components/elements/PageNotFound";

const routes = [
    {
        path: "/",
        name: "dashboard",
        component: Dashboard,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: "/home",
                component: Home,
            },
            {
                path: "/test",
                name: 'testPage',
                component: Test,
            },
            {
                path: "/users",
                component: UtilisateursMain,
                children: [
                    {
                        path: '/',
                        component: UtilisateursList,
                    },
                    {
                        path: 'create',
                        name: 'createUtilisateur',
                        component: NewUtilisateur,
                    },
                    {
                        path: 'edit/:id',
                        name: 'updateUtilisateur',
                        component: UpdateUtilisateur,
                    },
                    {
                        path: ':id',
                        name: 'showUtilisateur',
                        component: ShowUtilisateur,
                    },
                ]
            },
            {
                path: "/profil",
                component: MainProfil,
                children: [
                    {
                        path: '/',
                        component: Profil,
                    },
                    {
                        path: '/profil/edit/:id',
                        name: 'editProfil',
                        component: EditProfil,
                        props: true,
                    },
                ]
            },
            {
                path: "/pouvsubs",
                component: PouvSubs,
            },
            {
                path: "/formations",
                component: MainFormations,
                children: [
                    {
                        path: '/',
                        component: ListFormations,
                    },
                    {
                        path: '/formations/show/:id',
                        name: 'showFormation',
                        component: ShowFormation,
                        props: true,
                    },
                    {
                        path: 'create',
                        name: 'createFormation',
                        component: CreateFormation,
                    },
                    {
                        path: '/formations/edit/:id',
                        name: 'editFormation',
                        component: EditFormation,
                        props: true,
                    },
                ]
            },
            {
                path: "/recrutements",
                component: MainRecrutements,
                children: [
                    {
                        path: '/',
                        component: ListRecrutements,
                    },
                    {
                        path: '/recrutements/show/:id',
                        name: 'showRecrutement',
                        component: ShowRecrutement,
                        props: true,
                    },
                    {
                        path: 'toformation/:id',
                        name: 'selectionToFormation',
                        component: SelectionToFormation,
                    },
                ]
            },
            {
                path: "/inscrits",
                component: MainInscrits,
                children: [
                    {
                        path: '/',
                        component: ListInscrits,
                    },
                    {
                        path: '/inscrits/show/:id',
                        name: 'showInscrit',
                        component: ShowInscrit,
                        props: true,
                    },
                    {
                        path: 'create',
                        name: 'createInscrit',
                        component: CreateInscrit,
                    },
                    {
                        path: 'createwithformation/:formation_id',
                        name: 'createInscritWithFormation',
                        component: CreateInscritWithFormation,
                    },
                    {
                        path: 'createwithrecrutement/:recrutement_id',
                        name: 'createInscritWithRecrutement',
                        component: CreateInscritWithRecrutement,
                    },
                    {
                        path: 'createwithprospect',
                        name: 'createInscritWithProspect',
                        component: CreateInscritWithProspect,
                    },
                    {
                        path: '/inscrits/edit/:id',
                        name: 'editInscrit',
                        component: EditInscrit,
                        props: true,
                    },
                ]
            },
            {
                path: "/salles",
                component: MainSalles,
                children: [
                    {
                        path: '/',
                        component: ListSalles,
                    },
                ]
            },
            {
                path: "/tags",
                component: MainTags,
                children: [
                    {
                        path: '/',
                        component: ListTags,
                    },
                ]
            },
            {
                path: "/prospects",
                component: MainProspects,
                children: [
                    {
                        path: '/',
                        component: ListProspects,
                    },
                ]
            },


        ]
    },
    {
        path: "/login",
        component: LoginForm,
    },
    { path: '*', redirect: '/404' },
    { path: '/404', component: PageNotFound, },
];

const router = new VueRouter({
    routes: routes,
    // mode: 'history',
});

export default router;
