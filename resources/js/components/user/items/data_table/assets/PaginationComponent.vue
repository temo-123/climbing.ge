<template>
    <div class="col-md-12">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" @click="next_page_pagination()">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    </a>
                </li>

                <li class="page-item">
                    <a class="page-link" href="#" @click="nomber_page_pagination(1)">
                        1
                    </a>
                </li>
                <li class="page-item active">
                    <a class="page-link" href="#" @click="nomber_page_pagination(2)">
                        2
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#" @click="nomber_page_pagination(3)">
                        3
                    </a>
                </li>

                <li class="page-item">
                    <a class="page-link" href="#" @click="privies_page_pagination()">
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>

export default {
    props: [
        "pagination_data_prop"
    ],

    methods: {
        next_page_pagination(){
            this.$emit('next_page_pagination')
        },
        privies_page_pagination(){
            this.$emit('privies_page_pagination')
        },
        nomber_page_pagination(page_id){
            this.$emit('nomber_page_pagination', page_id)
        }
    },
}
</script>