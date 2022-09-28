<template>
    <div class="container">
        <articlPage :article="indoors" />
    </div>
</template>

<script>
    import articlPage from '../items/cards/ArticleCardComponent'
    export default {
        data: function () {
            return {
                indoors: [],
            }
        },
        components: {
            articlPage,
        },
        mounted() {
            this.get_indoors()
        },
        methods: {
            get_indoors(){
                axios
                .get('../api/article/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.indoors = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>