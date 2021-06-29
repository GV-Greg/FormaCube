<template>
    <div class="container w-100">
        <h1 class="ml-5 mb-n2">
            Page test
        </h1>
        <v-row class="mt-5 ml-2">
            <vue-google-autocomplete
                id="map"
                ref="address"
                classname="form-control"
                placeholder="Entrez le nom de la rue"
                v-on:placechanged="getAddressData"
                country="be"
            >
            </vue-google-autocomplete>
        </v-row>
    </div>
</template>

<script>
import MailtoUI from 'mailtoui/dist/mailtoui-min.js';
import VueGoogleAutocomplete from "vue-google-autocomplete";

export default {
    name: "testPage",
    components: {
        VueGoogleAutocomplete
    },

    data() {
        return {
            address: '',
            place: '',
            addressID: null,
        }
    },
    mounted() {
        this.$refs.address.focus();
    },
    methods: {
        getAddressData: function (addressData, placeResultData, id) {
            this.address = addressData;
            this.place = placeResultData;
            this.addressID = id;
        },
    }

}
</script>

<style scoped>

</style>
