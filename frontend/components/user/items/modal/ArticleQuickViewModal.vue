<template>
    <stack-modal
        :show="showModal"
        title="Article Quick View"
        @close="closeModal"
        :modal-class="{ [modalClass]: true }"
        :cancelButton="{ visible: true, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        :saveButton="{ visible: true, title: 'Go to Article', btnClass: { 'btn btn-primary': true } }"
        @save="goToArticle"
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
    </stack-modal>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'
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
                showModal: false,
                article: null,
                category: null
            }
        },

        mounted() {
            
        },

        methods: {
            show_quick_view_modal(article, category){
                this.article = article
                this.category = category
                this.showModal = true
            },
            closeModal(){
                this.showModal = false
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
