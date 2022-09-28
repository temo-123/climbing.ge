<template>
    <div class="container">
        <articlPage :article="tech_tips" />
    </div>
</template>

<script>
    import articlPage from '../items/ArticleComponent'
    export default {
        data: function () {
            return {
                tech_tips: [],
            }
        },
        components: {
            articlPage,
        },
        mounted() {
            this.get_tech_tips()
        },
        methods: {
            get_tech_tips(){
                axios
                .get('../api/article/tech_tip/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.tech_tips = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>