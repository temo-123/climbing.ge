<template>
    <div class="container">
        <articlPage :article="indoors" />

        <metaData 
            :title = "indoors[0].title"
            :description = "indoors[0].description"
            :image = "'../../../../public/images/indoor_img/'+indoors.image"
        />
    </div>
</template>

<script>
    import articlPage from '../items/cards/ArticleCardComponent'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                indoors: [],
            }
        },
        components: {
            articlPage,
            metaData,
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