<template>
    <div class="container w-100">
        <h1 class="ml-5 mb-n2">
            Page test
        </h1>
        <v-row class="justify-center mt-1">
            <v-col cols="12">
                <v-simple-table fixed-header>
                    <thead class>
                    <tr>
                        <th>N°</th>
                        <th class="text-left">NOM</th>
                        <th class="text-left">PRÉNOM</th>
                        <th class="text-left">EMAIL</th>
<!--                        <th class="text-center">ACTIONS</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(contact, index) in contacts" :key="index">
                        <th scope="row">
                            {{ index + 1 }}
                        </th>
                        <td>
                            {{ contact.attributes.NOM | upperCase }}
                        </td>
                        <td>
                            {{ contact.attributes.PRENOM }}
                        </td>
                        <td>
                            {{ contact.email }}
                        </td>
<!--                        <td>-->
<!--                            <div class="d-flex flex-row justify-content-center">-->
<!--                                <router-link :to="{ name: 'showInscrit', params: { id: prospect.id }}">-->
<!--                                    <i class="fas fa-eye fa-lg text-blue mr-1"></i>-->
<!--                                </router-link>-->
<!--                                <router-link :to="{ name: 'editInscrit', params: { id: prospect.id }}">-->
<!--                                    <i class="fas fa-edit fa-lg text-green mx-1"></i>-->
<!--                                </router-link>-->
<!--                                |-->
<!--                                <button type="button" class="ml-1" @click="destroy(prospect.id)">-->
<!--                                    <i class="fas fa-trash fa-lg text-red"></i>-->
<!--                                </button>-->
<!--                            </div>-->
<!--                        </td>-->
                    </tr>
<!--                    <tr v-show="(prospects != null && loadingPage === true && loading === false) || (prospects != null && loadingPage === true && newLoading === false)">-->
<!--                        <td colspan="12" class="pt-4">-->
<!--                            <v-row class="text-center text-interface mt-10">-->
<!--                                <v-col class="d-flex flex-column justify-center align-center">-->
<!--                                    <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>-->
<!--                                    <span class="mt-5">Chargement...</span>-->
<!--                                </v-col>-->
<!--                            </v-row>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr v-show="prospects === null && newLoading === true" class="justify-content-center">-->
<!--                        <td colspan="12" class="pt-4">-->
<!--                            <div class="alert alert-danger" role="alert">-->
<!--                                Oups ! Rien ne correspond à votre recherche.-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
                    </tbody>
                </v-simple-table>
<!--                <PaginationComponent class="mt-3"-->
<!--                                     v-if="pagination.last_page > 1 && prospects != null && this.recherche === false"-->
<!--                                     :pagination="pagination"-->
<!--                                     :offset="5"-->
<!--                                     @paginate="getData()"-->
<!--                ></PaginationComponent>-->
            </v-col>
        </v-row>

    </div>
</template>

<script>
export default {
    name: "testPage",

    data() {
        return {
            contacts: [],
        }
    },
    mounted() {
        this.getContacts();
    },
    methods: {
        getContacts() {
            axios.get('api/contacts')
                .then(response => {
                    console.log(response);
                    this.contacts = response.data.contacts;
                })
                .catch(error => {
                    console.log(error.response);
                    Snackbar.fire('Problème avec la récupération des contacts !');
                })
        },
    }

}
</script>

<style scoped>

</style>
