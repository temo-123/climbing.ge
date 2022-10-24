<template>
    <div class="container">
        <articlPage :article="tech_tips" />

        <metaData 
            :title = "tech_tips[0].title"
            :description = "tech_tips[0].description"
            :image = "'../../../../public/images/security_img/'+tech_tips.image"
        />
    </div>
</template>

<script>
    import articlPage from '../items/ArticleComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                tech_tips: [],
            }
        },
        components: {
            articlPage,
            metaData
        },
        mounted() {
            this.get_tech_tips()
        },
        watch: {
            '$route' (to, from) {
                this.get_tech_tips(),
                window.scrollTo(0,0)
            }
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