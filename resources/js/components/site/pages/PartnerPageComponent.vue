<template>
    <div class="container">
        <articlPage :article="news" />
    </div>
</template>

<script>
    import articlPage from '../items/ArticleComponent'
    export default {
        data: function () {
            return {
                news: [],
            }
        },
        components: {
            articlPage,
        },
        mounted() {
            this.get_news()
        },
        methods: {
            get_news(){
                axios
                .get('../api/article/tech_tip/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.news = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>