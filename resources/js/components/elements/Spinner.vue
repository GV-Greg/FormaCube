<template>
    <div class="d-flex flex-row text-light mt-10 justify-content-center">
        <div class="d-flex flex-column justify-content-center">
            <v-progress-circular :size="70" :width="10" indeterminate class="ml-2"></v-progress-circular>
            <span class="mt-3">Chargement...</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "Spinner"
}
</script>

<style scoped>

</style>
