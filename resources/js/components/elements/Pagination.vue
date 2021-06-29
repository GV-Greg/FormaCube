<template>
    <div class="text-center">
        <v-pagination
            v-model="currentPage"
            :length="lastPage"
            :total-visible="8"
            color="#317283"
        ></v-pagination>
    </div>
</template>

<script>
    export default {
        props: ['store', 'collection'],
        // Regarde si on appuie sur une autre page de la pagination, ça upload les infos
        watch: {
            currentPage ( newVal, oldVal ) {
                this.paginatePage( newVal );
            }
        },
        computed: {
            currentPage: {
                get() {
                    return this.$store.state[this.store][this.collection].current_page;
                },
                set( value ) {
                    this.$store.commit( this.store + '/setCurrentPage', value);
                }
            },
            lastPage: {
                get() {
                    return this.$store.state[this.store][this.collection].last_page;
                }
            }
        },
        methods: {
            paginatePage( pageNumber ) {
                this.$store.dispatch(this.store + '/getList', pageNumber);
            }
        }
    }
</script>

<style scoped>

</style>
