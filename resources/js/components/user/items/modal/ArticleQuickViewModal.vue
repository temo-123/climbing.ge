<template>
    <StackModal
        v-model="is_show_modal"
        :title="modalTitle"
        :modal-class="{ [modalClass]: true }"
        :cancelButton="{ visible: true, title: 'Close', btnClass: { 'btn btn-secondary': true } }"
        :saveButton="{ visible: true, title: 'Go to Article', btnClass: { 'btn btn-primary': true } }"
        @save="goToArticle"
        @close="close_modal"
    >
        <div class="container">
            <div class="clearfix row" v-if="article">
                <div class="col-md-12">
                    <h4>{{ article.locale_data?.title || 'No Title' }}</h4>
                </div>
                <div class="col-md-12">
                    <p><strong>ID:</strong> {{ article.id }}</p>
                </div>
                <div class="col-md-12">
                    <p><strong>Published:</strong> {{ article.published ? 'Yes' : 'No' }}</p>
                </div>
                <div class="col-md-12" v-if="article.locale_data?.description">
                    <p><strong>Description:</strong></p>
                    <p>{{ article.locale_data.description }}</p>
                </div>
            </div>
            <div class="clearfix row" v-else>
                <p>No article data available</p>
            </div>
        </div>
    </StackModal>
</template>

<script>
// import StackModal from '@innologica/vue-stackable-modal'  // Global now
    export default {
        components: {
            StackModal,
        },
        props: {
            modalClass: {
                type: String,
                default: 'modal-md'
            }
        },
        data(){
            return {
                is_show_modal: false,
                article: null,
                category: null
            }
        },
        computed: {
            modalTitle() {
                return this.article ? (this.article.locale_data?.title || 'Article Quick View') : 'Article Quick View';
            }
        },

        mounted() {
            
        },

        methods: {
            show_modal(article, category){
                this.article = article
                this.category = category
                this.is_show_modal = true
            },
            close_modal(){
                this.is_show_modal = false
            },
            goToArticle(){
                if(this.article && this.category && this.article.url_title){
                    window.location.href = process.env.MIX_APP_SSH + process.env.MIX_GUIDBOOK_URL + '/' + this.category + '/' + this.article.url_title
                }
            }
        }
    }
</script>

<style>

</style>
