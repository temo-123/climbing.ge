<template>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group float-right d-flex align-items-center input-group mb-3">
                <select id="itemsPerPage" v-model="localItemsPerPage" class="form-control" style="width: 100px;" @change="onItemsPerPageChange">
                    <option v-for="option in itemsPerPageOptions" :key="option" :value="option">{{ option }}</option>
                </select>
            </div>
        </div>

        <div class="col-md-10">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                        <a class="page-link action" href="#" tabindex="-1" @click="privies_page_pagination()">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in visiblePages" :key="page" :class="{ active: page === currentPage, disabled: page === '...' }">
                        <a class="page-link action" href="#" :class="{ 'disabled-link': page === '...' }" @click="page !== '...' && nomber_page_pagination(page)">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                        <a class="page-link action" href="#" @click="next_page_pagination()">
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>

export default {
    props: [
        "pagination_data_prop",
        "itemsPerPage",
        "itemsPerPageOptions",
        "currentPage",
        "totalPages"
    ],
    data() {
        return {
            localItemsPerPage: this.itemsPerPage
        }
    },
    watch: {
        itemsPerPage(newVal) {
            this.localItemsPerPage = newVal;
        }
    },
    computed: {
        visiblePages() {
            const pages = [];
            const maxVisible = 15; // Maximum 15 buttons
            const delta = 7; // Pages around current

            // Always include first page
            pages.push(1);

            // Calculate range around current
            let start = Math.max(2, this.currentPage - delta);
            let end = Math.min(this.totalPages - 1, this.currentPage + delta);

            // If start > 2, add dots
            if (start > 2) {
                pages.push('...');
            }

            // Add pages from start to end
            for (let i = start; i <= end; i++) {
                if (!pages.includes(i)) {
                    pages.push(i);
                }
            }

            // If end < totalPages - 1, add dots
            if (end < this.totalPages - 1) {
                pages.push('...');
            }

            // Always include last page if not already
            if (this.totalPages > 1 && !pages.includes(this.totalPages)) {
                pages.push(this.totalPages);
            }

            // Limit to maxVisible
            if (pages.length > maxVisible) {
                // If too many, simplify: 1, ..., current-delta to current+delta, ..., last
                const simplified = [1];
                if (this.currentPage - delta > 2) simplified.push('...');
                for (let i = Math.max(2, this.currentPage - delta); i <= Math.min(this.totalPages - 1, this.currentPage + delta); i++) {
                    simplified.push(i);
                }
                if (this.currentPage + delta < this.totalPages - 1) simplified.push('...');
                if (this.totalPages > 1) simplified.push(this.totalPages);
                return simplified.slice(0, maxVisible);
            }

            return pages;
        }
    },
    methods: {
        next_page_pagination(){
            this.$emit('next_page_pagination')
        },
        privies_page_pagination(){
            this.$emit('privies_page_pagination')
        },
        nomber_page_pagination(page_id){
            this.$emit('nomber_page_pagination', page_id)
        },
        onItemsPerPageChange() {
            this.$emit('update:itemsPerPage', this.localItemsPerPage);
        }
    },
}
</script>

<style>
.disabled-link {
    pointer-events: none;
    color: #6c757d;
}
</style>
