<template>
    <div>
        <h1>Liste des Utilisateurs archivés</h1>
        <div v-if="loading === true">
            <v-simple-table>
                <thead class="thead-dark">
                <tr>
                    <th scope="col" class="th">N°</th>
                    <th scope="col">Pr&eacute;nom</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Email</th>
                    <th scope="col">R&ocirc;le</th>
                    <th scope="col">Archivage</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <template v-if="archives.length > 0">
                    <tr v-for="user in archives" :key="user.id">
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
                </template>
                <template v-else>
                    <tr class="text-center">
                        Pas d'utilisateurs archiv&eacute;s
                    </tr>
                </template>
                <template v-if="loading !== true">
                    <v-progress-circular :size="70" :width="10" color="interface" indeterminate></v-progress-circular>
                    <span class="mt-5 text-interface">Chargement...</span>
                </template>
                </tbody>
            </v-simple-table>
        </div>
        <div v-else class="d-flex flex-column justify-center align-center mt-5">
            <v-progress-circular :size="70" :width="10" color="white" indeterminate></v-progress-circular>
            <span class="mt-5 text-white">Chargement...</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "utilisateurs-archives",
    data() {
        return {
            loading: false,
        }
    },
    mounted() {
        this.getArchives();
    },
    computed: {
        archives() {
            return this.$store.getters.archives;
        },
    },
    methods: {
        getArchives() {
            this.loading = false;
            this.$store.dispatch('getArchives');
            this.loading = true;
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
                            this.$store.dispatch('getArchives');
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
