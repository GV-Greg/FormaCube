<template>
    <div class="container">
        <h1 class="d-flex align-content-center">
            <router-link to="/users">
                <button class="btn btn-light pb-2 mr-2">
                    <i class="fas fa-reply fa-lg text-primary-dark"></i>
                </button>
            </router-link>
            Liste des Utilisateurs archivés
        </h1>
        <div class="row align-items-center mt-n2">
            <div class="col-lg-4" ></div>
            <div class="col-lg-2 text-right text-light">
                <span>Recherche par :</span>
            </div>
            <div class="col-lg-2">
                <v-select :items="searchColonnes" v-model="colonne" color="blue-grey darken-4" class="mySelect bg-light text-dark" outlined dense hide-details="auto"></v-select>
            </div>
            <div class="col-lg-4">
                <v-text-field v-model="search" label="Recherche" color="blue-grey darken-4" class="mySearch bg-light" outlined dense hide-details="auto" append-icon="fas fa-search"></v-text-field>
            </div>
        </div>
        <v-simple-table fixed-header class="mt-2">
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
                    {{ user.lastname | UpperCase }}
                </td>
                <td>
                    {{ user.email }}
                </td>
                <td>
                    {{ user.role | Capitalize }}
                </td>
                <td>
                    {{ user.deleted_at  | newDate }}
                </td>
                <td>
                    <button type="button" class="ml-3" @click="restore(user)">
                        <i class="fas fa-user-check fa-lg text-green"></i>
                    </button>
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
                    <div class="alert alert-danger" role="alert">
                        Oups ! Rien ne correspond à votre recherche.
                    </div>
                </td>
            </tr>
            </tbody>
        </v-simple-table>
        <PaginationComponent class="mt-3" v-if="pagination.last_page > 1"
                             :pagination="pagination" :offset="5"
                             @paginate="search === '' ? getData() : searchData()" />
    </div>
</template>

<script>
import PaginationComponent from "../../elements/PaginationComponent";

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
