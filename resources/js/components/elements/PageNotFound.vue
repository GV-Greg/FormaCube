<template>
    <div class="container-fluid">
        <div class="row justify-content-center text-center">
            <div class="col-lg-12 mt-5 text-dark">
                <h2>Page introuvable</h2>
                <p class="mt-5 text-light">Oups! Où tu vas ?</p>
                <p class="text-light">Cette page n'existe pas ou tu n'y as pas accès.</p>
                <router-link to="/home">
                    <button type="button" class="btn btn-primary mb-2 mt-3">
                        <i class="fas fa-undo-alt"></i>
                        <strong>retour Acceuil</strong>
                    </button>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style scoped>
    button {
        color: #fff;
    }
</style>
