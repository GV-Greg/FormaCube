<template>
    <nav aria-label="...">
        <ul class="pagination justify-content-center">
            <li :class="{ disabled: pagination.current_page <= 1 }">
                <button class="page-navigation page-navigation-externe" @click.prevent="changePage(1)"  >
                    <i class="fas fa-chevron-left"></i>
                    <i class="fas fa-chevron-left"></i>
                    <i class="fas fa-chevron-left"></i>
                </button>
            </li>
            <li :class="{ disabled: pagination.current_page <= 1 }">
                <button class="page-navigation page-navigation-interne" @click.prevent="changePage(pagination.current_page - 1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </li>
            <li v-for="page in pages"  :key="page" :class="isCurrentPage(page) ? 'active' : ''">
                <button class="page-item" @click.prevent="changePage(page)"><strong>{{ page }}</strong>
                    <span v-if="isCurrentPage(page)" class="sr-only">(current)</span>
                </button>
            </li>
            <li :class="{ disabled: pagination.current_page >= pagination.last_page }">
                <button class="page-navigation page-navigation-interne" @click.prevent="changePage(pagination.current_page + 1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </li>
            <li :class="{ disabled: pagination.current_page >= pagination.last_page }">
                <button class="page-navigation page-navigation-externe" @click.prevent="changePage(pagination.last_page)">
                    <i class="fas fa-chevron-right"></i>
                    <i class="fas fa-chevron-right"></i>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: "PaginationComponent",
        props:['pagination', 'offset'],
        methods: {
            isCurrentPage(page) {
                return this.pagination.current_page === page
            },
            changePage(page) {
                if (page > this.pagination.last_page) {
                    page = this.pagination.last_page
                }
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        },
        computed: {
            pages() {
                let pages = [];
                let from = this.pagination.current_page - Math.floor(this.offset / 2);
                if (from < 1) {
                    from = 1
                }
                let to = from + this.offset -1
                if (to > this.pagination.last_page) {
                    to = this.pagination.last_page
                }
                while (from <= to) {
                    pages.push(from)
                    from++
                }
                return pages;
            }
        }
    }
</script>

<style scoped>
    .pagination {
        align-items: center;
        display: inline-flex;
        list-style-type: none;
        justify-content: center;
        margin: 0;
        max-width: 100%;
        width: 100%;
    }

    .pagination > li {
        align-items: center;
        display: flex;
    }

    .pagination > li > button {
        background-color: #fff;
        margin-left: 0.3rem;
        margin-right: 0.3rem;
        box-shadow: 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
        border-radius: 4px;
    }

    .pagination > li.active > button {
        width: 36px;
        height: 36px;
        background-color: #317283;
        color: #fff;
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.2), 0 4px 5px 0 rgba(0, 0, 0, 0.14), 0 1px 10px 0 rgba(0, 0, 0, 0.12);
    }

    .disabled {
        opacity: 0.2;
        pointer-events: none;
    }

    .page-navigation-interne {
        width: 32px;
        height: 32px;
        opacity: 0.7;
    }

    .page-navigation-externe {
        width: 54px;
        height: 32px;
        opacity: 0.5;
    }

    .page-item {
        font-size: 1rem;
        width: 34px;
        height: 34px;
    }
</style>
