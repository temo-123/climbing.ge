<template>
    <div class="container">
        <articlPage :article="ice" />
    </div>
</template>

<script>
    import articlPage from '../items/ArticleComponent'

    export default {
        data: function () {
            return {
                ice: [],
            }
        },
        components: {
            articlPage,
        },
        mounted() {
            this.get_ice()
        },
        methods: {
            get_ice(){
                axios
                .get('../api/article/'+this.$route.params.url_title)
                .then(response => {
                    this.ice = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>