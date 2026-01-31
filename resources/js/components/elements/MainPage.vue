<template>
    <v-container fluid class="px-5">
        <v-row justify="center" class="mt-3">
            <v-col cols="12" lg="11">
                <router-view></router-view>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "MainPage",
    }
</script>

<style scoped>

</style>
