<template>
    <div class="container">
        <h1 class="d-flex align-content-center">
            <router-link to="/users">
                <v-btn variant="flat" color="grey-lighten-3" class="pb-2 mr-2">
                    <v-icon color="primary" size="large">mdi-reply</v-icon>
                </v-btn>
            </router-link>
            Liste des Utilisateurs archivés
        </h1>
        <v-row align="center" class="mt-n2">
            <v-col cols="12" lg="4"></v-col>
            <v-col cols="12" lg="2" class="text-right text-light">
                <span>Recherche par :</span>
            </v-col>
            <v-col cols="12" lg="2">
                <v-select :items="searchColonnes" v-model="colonne" color="blue-grey-darken-4" class="mySelect bg-light text-dark" variant="outlined" density="compact" hide-details="auto"></v-select>
            </v-col>
            <v-col cols="12" lg="4">
                <v-text-field v-model="search" label="Recherche" color="blue-grey-darken-4" class="mySearch bg-light" variant="outlined" density="compact" hide-details="auto" append-inner-icon="mdi-magnify"></v-text-field>
            </v-col>
        </v-row>
        <v-table fixed-header class="mt-2">
            <thead>
            <tr>
                <th scope="col" class="th">N°</th>
                <th scope="col">Pr&eacute;nom</th>
                <th scope="col">Nom</th>
                <th scope="col">Email</th>
                <th scope="col">R&ocirc;le</th>
                <th scope="col">Archivage</th>
                <th scope="col">ACTIONS</th>
            </tr>
            </thead>
            <tbody>
            <tr v-show="archives.length > 0" v-for="user in archives" :key="user.id">
                <th scope="row">
                    {{ user.id }}
                </th>
                <td>
                    {{ user.firstname }}
                </td>
                <td>
                    {{ upperCase(user.lastname) }}
                </td>
                <td>
                    {{ user.email }}
                </td>
                <td>
                    {{ capitalize(user.role) }}
                </td>
                <td>
                    {{ formatDate(user.deleted_at) }}
                </td>
                <td>
                    <v-btn variant="text" size="small" class="ml-3" @click="restore(user)">
                        <v-icon color="green" size="large">mdi-account-check</v-icon>
                    </v-btn>
                </td>
            </tr>
            <tr v-show="!archives.length && loading === false">
                <td colspan="12" class="pt-4">
                    <v-row class="text-center text-interface mt-10">
                        <v-col class="d-flex flex-column justify-center align-center">
                            <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                            <span class="mt-5">Chargement...</span>
                        </v-col>
                    </v-row>
                </td>
            </tr>
            <tr v-show="!archives.length && loading === true" class="justify-content-center">
                <td colspan="12" class="pt-4">
                    <v-alert type="warning" variant="tonal" icon="mdi-database-search">
                        {{ search ? 'Aucune donnée ne correspond à votre recherche.' : 'Aucune donnée correspondante.' }}
                    </v-alert>
                </td>
            </tr>
            </tbody>
        </v-table>
        <PaginationComponent class="mt-3" v-if="pagination.last_page > 1"
                             :pagination="pagination" :offset="5"
                             @paginate="search === '' ? getData() : searchData()" />
    </div>
</template>

<script>
import PaginationComponent from "../../elements/PaginationComponent.vue";
import moment from 'moment';

export default {
    name: "utilisateurs-archives",
    components: {
        PaginationComponent,
    },
    data() {
        return {
            loading: false,
            searchColonnes: ['prenom', 'nom', 'email'],
            colonne: 'nom',
            search: '',
            archives: [],
            pagination: {
                current_page: 1,
            },
        }
    },
    watch: {
        search: function(newSearch) {
            if (newSearch === '') {
                this.getData();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        console.log('List Archives component mounted');
        this.getData();
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        }
    },
    methods: {
        formatDate(date) {
            return moment(date).format('DD/MM/YYYY');
        },
        upperCase(value) {
            return value ? value.toUpperCase() : '';
        },
        capitalize(value) {
            if (!value) return '';
            return value.charAt(0).toUpperCase() + value.slice(1);
        },
        getData(){
            this.$Progress.start();
            this.loading = false;
            axios.get('api/users/archives?page=' + this.pagination.current_page)
                .then(response => {
                    this.archives = response.data.archives.data;
                    this.pagination = response.data.archives;
                    this.$Progress.finish();
                    this.loading= true;
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.log(error.response);
                    Snackbar.fire({
                        title: 'Problème avec la récupération de la liste des utilisateurs archivés !',
                        timer: undefined,
                    })
                })
        },
        searchData() {
            this.$Progress.start();
            axios.get('api/search/users/archives/' + this.colonne +'/'+ this.search +'?page=' + this.pagination.current_page)
                .then(response => {
                    this.archives = response.data.archives.data;
                    this.pagination = response.data.archives;
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.$Progress.fail();
                    console.error(error);
                    Snackbar.fire('Problème avec la recherche de l\'utilisateur !');
                })
        },
        restore(user) {
            this.$Progress.start();
            Suppression.fire({
                text: "Si vous continuez, l'utilisateur sera restauré!",
                confirmButtonText: 'Oui, restaurez!'
            }).then((result) => {
                if (result.value) {
                    axios.get('api/users/restore/' + user.id)
                        .then(response => {
                            this.$Progress.finish();
                            this.getData();
                            Confirm.fire(response.data.message);
                            Fire.$emit('RefreshPage'); // Rafraichit la page
                        })
                        .catch(error => {
                            this.$Progress.fail();
                            console.log(error.response);
                            Snackbar.fire('Problème avec la restauration de l\'utilisateur !');
                        })
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
