<template>
    <div class="mx-5 d-flex flex-column justify-content-center">
        <div class="mx-5">
            <div class="row justify-content-end mt-3">
                <div class="col col-lg-11">
                    <div class="row justify-content-center">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MainPage",
    }
</script>

<style scoped>

</style>
