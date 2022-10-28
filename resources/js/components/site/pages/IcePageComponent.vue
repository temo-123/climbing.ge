<template>
    <div class="container">
        <articlPage :article="ice" />

        <metaData 
            :title = "ice[0].title"
            :description = "ice[0].description"
            :image = "'../../../../public/images/event_img/'+ice.image"
        />
    </div>
</template>

<script>
    import articlPage from '../items/ArticleComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                ice: [],
            }
        },
        components: {
            articlPage,
            metaData,
        },
        mounted() {
            this.get_ice()
        },
        methods: {
            get_ice(){
                axios
                .get('../api/article/ice/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.ice = response.data
                })
                .catch(error =>{
                })
            },
        }
    }
</script>